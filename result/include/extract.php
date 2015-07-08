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
		$this->st_stu = $this->con->prepare("INSERT INTO student (s_name,s_coll,s_year, s_sem,s_branch, s_roll, s_res, s_total) VALUES(?,?,?,?,?,?,?,?) ON DUPLICATE KEY UPDATE s_id = LAST_INSERT_ID(s_id)")or die($this->con->error."1");
		$this->st_sub = $this->con->prepare("INSERT INTO subject (sub_year, sub_branch, sub_code, sub_name ) VALUES(?,?,?,?) ON DUPLICATE KEY UPDATE sub_id = LAST_INSERT_ID(sub_id)")or die($this->con->error."2");
		$this->st_res = $this->con->prepare("INSERT INTO result (s_id, sub_id, internals,externals, tot, pass_fail) VALUES(?,?,?,?,?,?) ON DUPLICATE KEY UPDATE s_id = VALUES(s_id)")or die($this->con->error."3");
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
	
	function extractBasicDetails($res){
		
		$strResult = $res->C14N();
		preg_match("/b>(.*?)\(.*?>(.)<.*?Result:(.*?)<\/b>/", $strResult, $matches);
		$stuDetails['name'] = $matches[1];
		$stuDetails['semester'] = $matches[2];
		$stuDetails['result'] = str_replace(chr(194),"",$matches[3]);
		preg_match("/(...)(..)(..)(.*)/", $this->usn, $matches);
		$stuDetails["coll"] = $matches[1];
		$stuDetails["year"] = $matches[2];
		$stuDetails["branch"] = $matches[3];
		$stuDetails["roll"] = $matches[4];
		
		return $stuDetails;
	}
	
	function extractSubjects($td250s) {
		
		$subjectCount = 0;
		foreach($td250s as $childtd)
		{
				if($childtd->nodeValue == "Subject") //This is the table headind which read "Subject Internals Ex.... Skip this row"
					continue;
				preg_match("/(.*?)\((.*)\)/", $childtd->nodeValue, $matches); //Split subject name and code
				$subject[$subjectCount]["subjectName"] = $matches[1];
				$subject[$subjectCount]["subjectCode"] = $matches[2];
				$childtd = $childtd->nextSibling;
				$subject[$subjectCount]["externals"] = ($childtd->nodeValue == 'A' ? 0:$childtd->nodeValue);
				$childtd = $childtd->nextSibling;
				$subject[$subjectCount]["internals"] = ($childtd->nodeValue == 'A' ? 0:$childtd->nodeValue);
				$childtd = $childtd->nextSibling;
				$subject[$subjectCount]["total"] = ($childtd->nodeValue == 'A' ? 0:$childtd->nodeValue);
				$childtd = $childtd->nextSibling;
				$subject[$subjectCount]["result"] = $childtd->nodeValue;
				
				$subjectCount++;	
		}
		return $subject;
	}
	
	function extractor($result){//Function to extract required data
 
        $dom = new DOMDocument();
        @$dom->loadHTML($result);
        $allTd = $dom->getElementsByTagName("td");
        for($i=0 ; $i< $allTd->length ; $i++){
            $width = $allTd->item($i)->getAttribute("width");
            if($width == "513")
                break;
        }

        for($j=0 ; $j< $allTd->length ; $j++){
            $width = $allTd->item($j)->getAttribute("width");
            if($width == "250")
                $td250 [] = $allTd->item ($j);
        }
		
		$stuDetails = $this->extractBasicDetails($allTd->item($i));
		$this->getTotal($td250[count($td250) -1], $stuDetails); //The total marks is scrapped sepraretely. Pass the last td.
		$subjectDetails = $this->extractSubjects($td250);
		$this->write_to_db($stuDetails, $subjectDetails);
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
	
	//We take the last td in from all subject tds and then move along the DOM tree to reach the 
	//table with the result. The result table is always the next table after the table with the
	// last set of results
	function getTotal($td, &$stuDetails) {
		$td = $td->parentNode; //Points to the <tr>
		$td = $td->parentNode; //Points to the <table>
		$table = $td->nextSibling->nextSibling->nextSibling; //Points to the skip two <br> and point to required table
		preg_match("/(\d+)/", $table->nodeValue, $matches);
		$stuDetails["total"] = $matches[1];
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

	function getResForFail($year){
		$res = $this->con->query("SELECT s_branch,s_roll FROM student WHERE s_res LIKE '%fail%' AND s_year=$year") or die($this->con->error);
		while($row = $res->fetch_assoc()){
			$row['s_roll'] = sprintf('%03d',$row['s_roll']);
			$usn = '1bi'.$year.$row['s_branch'].$row['s_roll'];
			echo $usn."<br>";
			$this->getOneFromSite($usn);
		}
	}
}
//echo "Accidential usage of the script alters the database. Uncomment the necessary line(s) if that was your intention.";
$temp = new vtuleach();
//$temp->getOneFromSite("1bi11cs112");
//$temp->beginLeach("1bi11cs040", 200, 15);

?>