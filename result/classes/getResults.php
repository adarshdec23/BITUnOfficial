<?php
/*
  Project name : BITuN
  Start Date : 6 Jul, 2015 1:51:30 AM
  Author: Adarsh
  Purpose :
 */
class getResults {

	private $con;
	public $error;
	function getResults() {
		include 'author/include/aCon.php';
		$this->con = $aLink;
		$this->error = NULL;
	}
	
	function breakUSN($usn){
		preg_match("/(...)(..)(..)(...)/", $usn, $matches);
		$brokenUSN["college"] = strtolower($matches[1]); 
		$brokenUSN["year"] = $matches[2];
		$brokenUSN["branch"] = strtolower($matches[3]);
		$brokenUSN["roll"] = $matches[4];
		return $brokenUSN;
	}
	
	function santizeUSN(&$brokenUSN){
		if(!is_numeric($brokenUSN["year"]) || !is_numeric($brokenUSN["roll"])){
			$this->error = "Oops. Now thats not right. Why don't you try again?";
			return FALSE;
		}
		if($brokenUSN["college"] != "1bi"){
			$this->error = "Sorry, this is only available to students from BIT";
			return FALSE;
		}
		addslashes($brokenUSN["branch"]);
	}
	
	//!!!! This function return two arrays. One that holds student details (row that has been fecthed using fetch_assoc) and a result set for subjects
	function getOneResultByUSN($usn){
		$brokenUSN = $this->breakUSN($usn);
		$this->santizeUSN($brokenUSN);
		if($this->error)
			return FALSE;
		try{
			$stuResult = $this->con->query("SELECT * FROM student WHERE  s_year=".$brokenUSN["year"]." AND s_branch='".$brokenUSN["branch"]."' AND s_roll=".$brokenUSN["roll"]."");
		}
		catch (Exception $e){
			$this->error = "Database error. Try again later.";
			return FALSE;
		}
		if($stuResult->num_rows != 1){
			$this->error = "Your results are not curently available.";
			return FALSE;
		}
		$stuRow = $stuResult->fetch_assoc();
		$s_id = $stuRow["s_id"]; //Used to fetch student results
		
		try{
			$stuResult = $this->con->query("SELECT s.*,r.*
											FROM subject s, result r
											WHERE r.s_id = $s_id
											AND r.sub_id = s.sub_id");
		}
		catch (Exception $e){
			$this->error = "Database error. Try again later.1";
			return FALSE;
		}
		return [$stuRow, $stuResult];
	}
	
	function getClassResult($usn){
		$brokenUSN = $this->breakUSN($usn);
		$this->santizeUSN($brokenUSN);
		$branch = $brokenUSN["branch"];
		$roll = $brokenUSN["roll"];
		$year = $brokenUSN["year"];
		//Get the semester value
		$result = $this->con->query("SELECT s_sem 
							FROM student
							WHERE s_coll='1bi'
							AND s_branch='$branch'
							AND s_year = $year
							AND s_roll = $roll") or die($this->con->error);
		if($result->num_rows != 1){
			$this->error = "Sorry, results for your class are not available yet.";
			return FALSE;
		}
		$temp = $result->fetch_assoc();
		$sem = $temp["s_sem"];
		unset($result);
		$result = $this->con->query("SELECT * 
							FROM student
							WHERE s_coll='1bi'
							AND s_branch='$branch'
							AND s_sem = $sem
							ORDER BY s_total DESC");
		if($result->num_rows < 1){
			$this->error = "Sorry, results for your class are not available yet.";
			return FALSE;
		}
		return $result;
	}
	
	function breakSub($subCode){
		preg_match("/(..)(..[a-zA-Z]?)(\d*)/", $subCode, $matches);
		$brokenSub["year"] = $matches[1];
		$brokenSub["branch"] = $matches[2];
		$brokenSub["code"] = $matches[3];
		return $brokenSub;
	}
	
	function sanitizeSub(&$brokenSub){
		if(!is_numeric($brokenSub["year"]) || !is_numeric($brokenSub["code"])){
			$this->error = "Oops. Now thats not right. Why don't you try again?";
			return FALSE;
		}
		addslashes($brokenSub["branch"]);
	}
	
	function getSubjectResult($subCode, $limitRes = 10){
		$brokenSub = $this->breakSub($subCode);
		$this->sanitizeSub($brokenSub);
		if($this->error)
			return FALSE;
		$year = $brokenSub["year"];
		$branch = $brokenSub["branch"];
		$code = $brokenSub["code"];
		$result = $this->con->query("SELECT st.*,r.*,sub.sub_name
									FROM student st, result r, subject sub
									WHERE sub.sub_year = $year AND sub.sub_branch = '$branch' AND sub.sub_code = $code
									AND sub.sub_id = r.sub_id
									AND r.s_id = st.s_id
									ORDER BY r.tot DESC
									LIMIT $limitRes");
		if($result->num_rows < 1){
			$this->error = "Sorry, results for this subject are not available yet.";
			return FALSE;
		}
		
		return $result;
	}
	
	function getSubjectAnalysis($subCode) {
		$brokenSub = $this->breakSub($subCode);
		$this->sanitizeSub($brokenSub);
		if($this->error)
			return FALSE;
		$year = $brokenSub["year"];
		$branch = $brokenSub["branch"];
		$code = $brokenSub["code"];
		$result = $this->con->query("
									SELECT	s.sub_name,
											count(*) AS sCount,
											MIN(r.internals) AS iMin,
											MAX(r.internals) AS iMax,
											AVG(r.internals) AS iAvg,
											MIN(r.externals) AS eMin,
											MAX(r.externals) AS eMax,
											AVG(r.externals) AS eAvg,
											MIN(r.tot) AS tMin,
											MAX(r.tot) AS tMax,
											AVG(r.tot) AS tAvg
									FROM subject s, result r
									WHERE s.sub_year = $year AND s.sub_branch='$branch' AND s.sub_code = $code
									AND s.sub_id = r.sub_id
									");
		if($result->num_rows != 1){
			$this->error = "Sorry, results for this subject are not available yet.";
			return FALSE;
		}
		return $result->fetch_assoc();
	}
		
}
?>

