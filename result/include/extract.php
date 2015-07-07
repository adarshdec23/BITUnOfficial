<?php
/* 
Project name : BITuN 
Start Date : 28 Mar, 2015 5:35:56 PM
Author: Adarsh
Purpose : An altered copy of basic.php to extract results from http://results.vtu.ac.in
*/

/*
 * IMPORTANT: Check what results.vtu.ac.in shows when a student is absent and update accordingly. Currently assuming 'A'
 */

set_time_limit(0);

$branchCodes=array(
    'cv'=>'Civil',
    'cs'=>'Computer Science',
    'ec'=>'Electronics and Communication',
    'ee'=>'Electical And Electronics',
    'is'=>'Info Science',
    'it'=>'Instrumentation Technology',
    'im'=>'Industrial Engineering and Management',
    'me'=>'Mechanical',
    'te'=>'Telecommunication Engineering'
);

class vtuleach{
    private $usn;
    private $extract=1; //1-> Calculate now 1, or Store then calulation for later 
    private $failCounter=5; //No of time there has been a re try in case of a connection failure to the remote server
	
    private $con;
	private $st_stu;
	private $st_sub;
	private $st_res;
	
	private $handle; //Handle for the error log file
    
    function vtuleach(){
        include '../../author/include/aCon.php';
        $this->con = $aLink;
		$this->handle = fopen('res_error_log.txt', 'a');
		if(!$this->handle)
			die("Could not open error log. USN = $this->usn");
		$this->st_stu = $this->con->prepare("INSERT INTO student (s_name,s_coll,s_year, s_sem,s_branch, s_roll, s_res, s_total) VALUES(?,?,?,?,?,?,?)")or die($this->con->error);
		$this->st_sub = $this->con->prepare("INSERT INTO subject (sub_year, sub_branch, sub_code, sub_name ) VALUES(?,?,?,?) ON DUPLICATE KEY UPDATE sub_id = LAST_INSERT_ID(sub_id)")or die($this->con->error);
		$this->st_res = $this->con->prepare("INSERT INTO result (s_id, sub_id, internals,externals, tot, pass_fail) VALUES(?,?,?,?,?,?)")or die($this->con->error);
    }
    
	function write_error_log(){
		if(!fwrite($this->handle, $this->usn."\n"))
			die("Could not write USN: $this->usn");
	}
	
    function myProcess($result){//Decides what to do with the data
        $isAvailRes= stripos($result,"$this->usn");
        if($isAvailRes===false)
            return 0;//If the result isnt available yet, say so
        else{
            if($this->extract==1)//Immidiate calculation,then store
                $this->extractor($result,$this->usn);
            else
			{
                //Store raw string
            }
            return 1;
        }
    }
	
	function extractBasicDetails($strResult,$table1){	
		preg_match("/b>(.*)\(.*>(.)<.*Result:(.*?)<\/b>/", $strResult, $matches);
		$stuDetails['name'] = $matches[1];
		$stuDetails['semester'] = $matches[2];
		$stuDetails['result'] = str_replace(chr(194),"",$matches[3]);
		preg_match("/(...)(..)(..)(.*)/", $this->usn, $matches);
		$stuDetails["coll"] = $matches[1];
		$stuDetails["year"] = $matches[2];
		$stuDetails["branch"] = $matches[3];
		$stuDetails["roll"] = $matches[4];
		
		preg_match("/(\d+)/", $table1->nodeValue, $matchesR);
		$stuDetails["total"] = $matchesR[1];
		return $stuDetails;
	}
	
	function extractSubjects($tableWithSubjects) {
		
		$tableWithSubjects->removeChild($tableWithSubjects->firstChild);
		$subjectCount = 0;
		foreach($tableWithSubjects->childNodes as $childTR)
		{
			if($childTR->nodeName == "tr"){
				$tdList = $childTR->childNodes;
				preg_match("/(.*?)\((.*)\)/", $tdList->item(0)->nodeValue, $matches); //Split subject name and code
				$subject[$subjectCount]["subjectName"] = $matches[1];
				$subject[$subjectCount]["subjectCode"] = $matches[2];
				$subject[$subjectCount]["externals"] = ($tdList->item(1)->nodeValue == 'A' ?0:$tdList->item(1)->nodeValue);
				$subject[$subjectCount]["internals"] = ($tdList->item(2)->nodeValue == 'A' ?0:$tdList->item(2)->nodeValue);
				$subject[$subjectCount]["total"] = ($tdList->item(3)->nodeValue == 'A' ?0:$tdList->item(3)->nodeValue);
				$subject[$subjectCount]["result"] = $tdList->item(4)->nodeValue;
				
				$subjectCount++;
			}
			
		}
		return $subject;
	}
	
	function write_to_db($stuDetails, $subjectDetails){
		
		$this->st_stu->bind_param("ssiisisi", $stuDetails["name"], $stuDetails["coll"], $stuDetails["year"], $stuDetails["semester"], $stuDetails["branch"], $stuDetails["roll"], $stuDetails["result"],$stuDetails["total"]) or die($this->con->error);
		$this->st_stu->execute()or die($this->con->error);
		$studentId = $this->st_stu->insert_id;
		$subCount = count($subjectDetails);
		for($i=0 ; $i<$subCount ; $i++){
			preg_match("/(..)(..[a-zA-Z]?)(\d*)/", $subjectDetails[$i]["subjectCode"], $matches);
			$this->st_sub->bind_param("isis", $matches[1], $matches[2], $matches[3], $subjectDetails[$i]["subjectName"])or die($this->con->error);
			$this->st_sub->execute()or die($this->con->error);
			$subjectId = $this->st_sub->insert_id;
			$this->st_res->bind_param("iiiiis",$studentId,$subjectId, $subjectDetails[$i]["internals"], $subjectDetails[$i]["externals"], $subjectDetails[$i]["total"], $subjectDetails[$i]["result"])or die($this->con->error);
			$this->st_res->execute()or die($this->con->error);
		}
		
	}
	
	function getTotal($table1) {
		preg_match("/(\d+)/", $table1->nodeValue, $matches);
		$total = $matches[1];
		preg_match("/(...)(\d\d)(..)(\d\d\d)/", $this->usn, $bUSN);
		$s_year = $bUSN[2];
		$s_branch = $bUSN[3];
		$s_roll = $bUSN[4];
		$this->con->query("UPDATE student SET s_total = $total
							WHERE s_coll='1bi' 
							AND s_year=".$s_year."
							AND s_branch='".$s_branch."'
							AND s_roll=".$s_roll."") or die($this->con->error);
	}
            
    function extractor($result){//Function to extract required data
        $tableInResult = array(); //An array for all tables within a result column
        $dom = new DOMDocument();
        @$dom->loadHTML($result);
        $allTd = $dom->getElementsByTagName("td");
        for($i=0 ; $i< $allTd->length ; $i++){
            $width = $allTd->item($i)->getAttribute("width");
            if($width == "513")
                break;
        }
		$strResult = $allTd->item($i)->C14N();
        foreach($allTd->item($i)->childNodes as $cNode){
            if($cNode->nodeName == "table"){
                $tableInResult[] = $cNode;
            }
        }
		$stuDetails = $this->extractBasicDetails($strResult,$tableInResult[2]);
		$subjectDetails = $this->extractSubjects($tableInResult[1]);
		$this->write_to_db($stuDetails, $subjectDetails);
    }
    
    function getOneFromSite($usn){//Obtain the results of the student from results.vtu.ac.in, via a php5 post 
        $this->usn=$usn;
        $url = 'http://results.vtu.ac.in/vitavi.php';
        $data = array('rid' => "$usn", 'submit' => 'SUBMIT');
            $options = array(
                    'http' => array(
                    'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                    'method'  => 'POST',
                    'content' => http_build_query($data),
                )
            );
        
		$context  = stream_context_create($options);
		$counter=0;//In case of connection error, no of times the fn tries a reconnect
		$result = FALSE;
		while(TRUE){
			$result = file_get_contents($url, false, $context);
			if($result)
			{
				$retVal=$this->myProcess($result);//After obtaining the result, process it
				return $retVal;
			}
			else
			{
				$counter++;
				if($counter >= $this->failCounter)
				{
					$this->write_error_log();
					break;
				}
			}
		}
    }
    
    function beginLeach($startUSN,$qty=150,$nonExist=5){
        preg_match('/^(...)(....)(...)$/',$startUSN,$newUSN);
        $recordsLeached=0;//Total number of records leached by the function
        $recordsWO=0;//consecutive number of recods without a result
        echo "The following USN's have been written:<br>";
        for($i=1;$i<=$qty;$i++){
            $tempCounter=1;
			$tempUSN=array("$newUSN[1]","$newUSN[2]","$newUSN[3]");
			$strUSN=implode('',$tempUSN);
			$success=  $this->getOneFromSite($strUSN);
			if($success){
				$recordsLeached++;
				$recordsWO=0;
				echo "$strUSN<br>";
			}else
				$recordsWO++;
            if($recordsWO==$nonExist)
                return $recordsLeached;
            $newUSN[3]++;
            $newUSN[3]=  sprintf('%03d',$newUSN[3]);//Pad 0 at the start,ie 43 becomes 043
        }
        return $recordsLeached;
    }
	
}
echo "Accidential usage of the script alters the database. Uncomment the necessary line(s) if that was your intention.";
//$temp = new vtuleach();
//$temp->getOneFromSite("1bi11cv001");
//$temp->beginLeach("1bi12te400", 40, 15);

?>