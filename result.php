<?php

/*
  Project name : BITuN
  Start Date : 6 Jul, 2015 12:50:24 AM
  Author: Adarsh
  Purpose :
 */
include_once 'result/classes/getResults.php';
$resObj = new getResults();

//Display the error message if any and then display the forms prompting the user for input 
function dispError($erm){
	echo "<h3>$erm</h3>";
	displayForms();
}

/*
 * Display the results of the requested class. 
 * The function first gets the semester, and then gets the details of all students from that semester and branch
 $myRow is a flag which is set when the current row being displayed is equal to the callers USN.
 * 
 */
function classResult($resObj){
	$classRes = $resObj->getClassResult($_REQUEST["usn"]);
	if($resObj->error){
		dispError("Sorry results are unavialable");
		return FALSE;
	}
	$myRow = FALSE;
	$requestUSN = strtolower($_REQUEST["usn"]);
	$sNames = ""; //Used to build a string for the graphs
	$sMarks ="";
	echo "<table>
		<thead>
			<tr>
			<th>Rank</th>
			<th>Name</th>
			<th>Total</th>
			</tr>
		</thead>";
	$i=1;
	while($row = $classRes->fetch_assoc()){
		$row["s_roll"]=  sprintf('%03d',$row["s_roll"]);
		$usn = $row["s_coll"].$row["s_year"].$row["s_branch"].$row["s_roll"];
		
		
		if($requestUSN == strtolower($usn))
			$myRow = TRUE;
		$s_name = ucwords(strtolower($row["s_name"]));
		
		if($myRow){
			echo "<tr class='myRow'>";
			$myRow = FALSE; //Make it flase so that subsequent rows remain unaffected
		}
		else 
			echo "<tr>";
		echo "
					<td>$i</td>
					<td><a href='result.php?usn=$usn'>$s_name</a></td>
					<td>".$row["s_total"]."</td>
				</tr>";
		
		$sNames.="'".$row["s_name"]."',";
		$sMarks.=$row["s_total"].",";
		$i++;
	}
	echo "</table>";
	
	$sMarks = rtrim($sMarks,",");
	$sNames = rtrim($sNames,",");
	
	
	//Draw the container for the chart
	echo "<div id='chart'></div>";
	//Draw the chart
	echo '<script type="text/javascript">';
	echo "
		$(function () {
    $('#chart').highcharts({
        chart: {
            type: 'bar'
        },
        title: {
            text: 'Class results'
        },
        xAxis: {
            categories: [$sNames],
            title: {
                text: null
            }
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Total Marks',
                align: 'middle'
            },
            labels: {
                overflow: 'justify'
            }
        },
        plotOptions: {
			series: {
				pointPadding: 1,
				groupPadding: 1,
			},
            bar: {
                dataLabels: {
                    enabled: true
                }
            }
        },
        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'top',
            x: -40,
            y: 80,
            floating: true,
            borderWidth: 1,
            backgroundColor: ((Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'),
            shadow: true
        },
        credits: {
            enabled: false
        },
        series: [{
            name: 'Marks Obtained',
            data: [$sMarks]
        }]
    });
});
	";
	echo "</script>";
}

/*
 * Get and display the results of one student.
 */
function oneResult($resObj){
	
	list($stuDetails, $resultDetails) = $resObj->getOneResultByUSN($_REQUEST["usn"]);
	if($resObj->error){
		dispError($resObj->error);
		return;
	}
	
	echo "<h3>".ucwords(strtolower($stuDetails["s_name"]))." (".strtoupper($_REQUEST["usn"]).")</h3>";
	
	//Print table with subject wise marks.
	echo "<table>
		<thead>
			<tr>
			<th>Subject</th>
			<th>Internals</th>
			<th>Externals</th>
			<th>Total</th>
			<th>Result</th>
			</tr>
		</thead>";
	while($row = $resultDetails->fetch_assoc()){
		$subCode = $row["sub_year"].$row["sub_branch"].$row["sub_code"];
		echo "<tr>
				<td><a href='result.php?subCode=$subCode'>".$row["sub_name"]."($subCode)</a></td>
				<td>".$row["internals"]."</td>
				<td>".$row["externals"]."</td>
				<td>".$row["tot"]."</td>
				<td>".$row["pass_fail"]."</td>
			  </tr>";
		$toChart[$row["sub_name"]] = $row["tot"];
	}
	echo "</table>";
	
	//Draw the container for the chart
	echo "<div id='chart'></div>";
	//Draw the chart
	echo '<script type="text/javascript">';
	echo "
		$(function () {
    $('#chart').highcharts({
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false,
            type: 'pie'
        },
        title: {
            text: 'Share of subjects'
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.y}</b>'
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true,
                    format: '<b>{point.name}</b>: {point.y}',
                    style: {
                        color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                    }
                }
            }
        },
        series: [{
            name: 'Marks',
            colorByPoint: true,
            data: [";
	$strToChart = "";		
	foreach ($toChart as $key => $value) {
		$strToChart.="{ name: '$key', y: $value },";
	}
	$strToChart = rtrim($strToChart,",");
			echo $strToChart;
			
		echo"]
        }]
    });
});
</script>	";	
	$temp = strtolower($stuDetails["s_res"]);
	echo "<h3>Total:".$stuDetails["s_total"]." ".ucwords($temp)."</h3>";
}

/*
 * Function to display forms for user input
 */
function displayForms(){
	echo <<<DOC
	<h3>Results announced:7th and 8th semesters </h3>
	<h3>Check class results</h3>
	<form class="form-group" method="POST" action="result.php">
		<input type="hidden" name="form" value="classResult">
		<input type="text" class="form-control" name ="usn" placeholder="Enter any USN from your class">
		<input type="submit" class="btn" value="Gimmie Them Results">
	</form>
	
	<h3>Compare your marks with your friends!</h3>
	<form class="form-group" method="POST" action="result.php">
		<input type="hidden" name="form" value="compareResult">
		<input type="text" class="form-control" name ="usn1" placeholder="Enter the first USN">
		<input type="text" class="form-control" name ="usn2" placeholder="Enter the second USN">
		<input type="submit" class="btn" value="Compare Them Results">
	</form>
	
	<h3>Check your results out!</h3>
	<form class="form-group center-block" method="POST" action="result.php">
		<input type="hidden" name="form" value="result">
		<input type="text" class="form-control" name ="usn" placeholder="Enter your USN">
		<input type="submit" class="btn" value="Gimmie My Results">
	</form>
DOC;
}

/*
 * Compare the results of two students. First get the common subjects and then build string required to 
 * display the graphs
 */
function compareResult($resObj){
	if(!isset($_POST["usn1"]) || !isset($_POST["usn2"])){
		dispError("Enter valid USN");
		return;
	}
	list($s1,$m1) = $resObj->getOneResultByUSN($_POST["usn1"]);
	if($resObj->error){
		dispError("Sorry, something went wrong, please try again later.");
		return;
	}
	list($s2,$m2) = $resObj->getOneResultByUSN($_POST["usn2"]);
	if($resObj->error){
		dispError("Sorry, something went wrong, please try again later.");
		return;
	}
	while($row = $m1->fetch_assoc()){
		$sub_code = $row["sub_year"].$row["sub_branch"].$row["sub_code"];
		$subCodeToName[$sub_code] = $row["sub_name"];
		$s1Marks[$sub_code] = $row["tot"];
	}
	
	while($row = $m2->fetch_assoc()){
		$sub_code = $row["sub_year"].$row["sub_branch"].$row["sub_code"];
		$subCodeToName[$sub_code] = $row["sub_name"];
		$s2Marks[$sub_code] = $row["tot"];
	}
	$commonSub = array_intersect_key($s1Marks, $s2Marks);
	if(count($commonSub) < 1){
		dispError("No common subjects. Please try a different combination.");
		return;
	}
	$subList =""; $s1List = ""; $s2List = "";
	//Build strings required for graphs
	foreach ($commonSub as $key => $value) {
		$subList.="'".$subCodeToName["$key"]."',";
		$s1List.=$s1Marks["$key"].",";
		$s2List.=$s2Marks["$key"].",";
	}
	$subList = rtrim($subList,",");
	$s1List = rtrim($s1List,",");
	$s2List = rtrim($s2List,",");
	//Draw the container for the chart
	echo "<div id='chart'></div>";
	//Draw the chart
	echo '<script type="text/javascript">';
	echo " 
		$(function () {
    $('#chart').highcharts({
        chart: {
            type: 'bar'
        },
        title: {
            text: 'Subject Wise Comparision'
        },
        xAxis: {
            categories: [$subList],
            title: {
                text: null
            }
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Marks',
                align: 'high'
            },
            labels: {
                overflow: 'justify'
            }
        },
        plotOptions: {
            bar: {
                dataLabels: {
                    enabled: true
                }
            }
        },
        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'top',
            x: -40,
            y: 80,
            floating: true,
            borderWidth: 1,
            backgroundColor: ((Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'),
            shadow: true
        },
        credits: {
            enabled: false
        },
        series: [{
            name: '".$s1["s_name"]."',
            data: [$s1List]
        }, {
            name: '".$s2["s_name"]."',
            data: [$s2List]
        }]
    });
});
</script>	";
}

/*
 * Display the results of one subject identified by its unique subject code.
 */
function subjectResults($resObj) {
	$subRes = $resObj->getSubjectResult($_GET["subCode"], 6); //Get top 6 results
	if($resObj->error){
		dispError("This is weird, could you try again?");
		return FALSE;
	}
	
	//As the table with subject results is printed after the graph, we just build the string $tableToEcho
	$tableToEcho = "<table> 
						<thead>
							<tr>
								<th>Rank</th>
								<th>Name</th>
								<th>Internals</th>
								<th>Externals</th>
								<th>Total</th>
							</tr>
						</thead>
			";
	$i=1;
	$intMarks =""; $extMarks = ""; $totMarks = "" ; $names = ""; //String required for the graph
	
	while($row = $subRes->fetch_assoc()){
		$row["s_roll"]=  sprintf('%03d',$row["s_roll"]);//Pad 0 at the start,ie 43 becomes 043		
		$usn = $row["s_coll"].$row["s_year"].$row["s_branch"].$row["s_roll"];
		$s_name = ucwords(strtolower($row["s_name"]));	
		$tableToEcho.= "<tr>
							<td>$i</td>
							<td><a href='result.php?usn=$usn'>$s_name</a></td>
							<td>".$row["internals"]."</td>
							<td>".$row["externals"]."</td>
							<td>".$row["s_total"]."</td>
						</tr>";
		$names.="'".$row["s_name"]."',";
		$intMarks.=$row["internals"].",";
		$extMarks.=$row["externals"].",";
		$totMarks.=$row["tot"].",";
		$i++;
		$subName = $row["sub_name"];
	}
	
	//Remove the extra ', ' at the right end
	$names = rtrim($names,",");
	$intMarks = rtrim($intMarks,",");
	$extMarks = rtrim($extMarks,",");
	$totMarks = rtrim($totMarks,",");
	$tableToEcho.= "</table>";
	
	//Draw the container for the chart
	echo "<div id='chart'></div>";
	echo '<script type="text/javascript">';
	echo "
		$(function () {
    $('#chart').highcharts({
        chart: {
            type: 'bar'
        },
        title: {
            text: '$subName - Top 6'
        },
        xAxis: {
            categories: [$names],
            title: {
                text: null
            }
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Marks',
                align: 'high'
            },
            labels: {
                overflow: 'justify'
            }
        },
        plotOptions: {
            bar: {
                dataLabels: {
                    enabled: true
                }
            }
        },
        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'top',
            x: 0,
            y: 270,
            floating: true,
            borderWidth: 1,
            backgroundColor: ((Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'),
            shadow: true
        },
        credits: {
            enabled: false
        },
        series: [{
            name: 'Internals',
            data: [$intMarks]
        }, {
            name: 'Externals',
            data: [$extMarks]
        }, {
            name: 'Total',
            data: [$totMarks]
        }]
    });
});
		";
	echo "</script>";
	
	
	//Subject analysis
	echo "<h3>Subject Analysis</h3>";
	$aRow = $resObj->getSubjectAnalysis($_GET["subCode"]);
	if($resObj->error){
		dispError("This is weird, could you try again?");
		return FALSE;
	}
	echo "	<table>
				<thead>
					<tr>
						<th>Students = ".$aRow["sCount"]."</th>
						<th>Minimun</th>
						<th>Maximum</th>
						<th>Average</th>
					</tr>
				</thead>
				<tr>
					<td>Internals</td>
					<td>".$aRow["iMin"]."</td>
					<td>".$aRow["iMax"]."</td>
					<td>".$aRow["iAvg"]."</td>
				</tr>
				<tr>
					<td>Externals</td>
					<td>".$aRow["eMin"]."</td>
					<td>".$aRow["eMax"]."</td>
					<td>".$aRow["eAvg"]."</td>
				</tr>
				<tr>
					<td>Total</td>
					<td>".$aRow["tMin"]."</td>
					<td>".$aRow["tMax"]."</td>
					<td>".$aRow["tAvg"]."</td>
				</tr>
			</table>
		";
	
	//Toppers table. Finally print the string $tableToEcho string
	echo "<h3>Toppers List</h3>";
	echo $tableToEcho;
}

?>
<!DOCTYPE html>
<html>
    <head>
        <title> BIT Results - VTU results for Bangalore Institute Of Technology </title>
        <meta name="Keywords" content="VTU Results, BIT results, Class ranks, Subject Analysis,Subject wise ranking, subject rank, BIT Bangalore ranking">
        <meta name="Description" content="Obtain class ranks, subject wise analysis of VTU results for BIT Bangalore students with graphs and charts">
        <meta name="viewport" content="width=device-width" initial-scale=1>
		<script src="http://ajax.aspnetcdn.com/ajax/jQuery/jquery-1.10.2.min.js"></script>
        <script type="text/javascript" src="js/basic.js"></script>
		<link rel="shortcut icon" href="http://bitunofficial.com/Images/Logo.ico">
        <link rel="stylesheet" type="text/css" href="Style_Folder/outline.css">
		<style>
			form{
				width:60%;
				margin:0 auto;
			}
			input{
				margin:20px auto 0 auto;
				display:block;
				width:100%;
				border:0;
				padding-left: 5px;
				border-radius: 5px;
				height: 30px;
			}
			.btn{
				background-color: #D1FCD5;
				cursor: pointer;
				padding:5px;
			}
			.btn:hover{
				background-color: #AEFDB4;
			}
			table{
				width: 90%;
				margin: 20px auto;
				border:1px;
				text-align: center;
				border-collapse: collapse;
			}
			td{
				padding: 1.5%;
			}
			thead{
				background-color: #303030;
			}
			th{
				padding: 2% 2% 2% 2%;
			}
			#chart{
				width:95%;
				height: auto;
				margin:10px auto;
			}
			.myRow{
				background-color: #BC3131;
			}
		</style>
		<?php
        require_once 'outline.php';
		ga();
		?>
    </head>
    <body>
        <?php
        head();
        ?>
        <section>			
            <div id="content">
				<h2>BIT Results</h2>
				<?php 
				if(isset($_POST["form"])){
					switch($_POST["form"]){
						case "result":	oneResult($resObj);
										break;
						case "classResult" :	classResult($resObj);
												break;
						case "compareResult":	compareResult($resObj);
												break;
					}
				}
				else if(isset ($_GET['usn']))
					oneResult ($resObj);
				else if(isset ($_GET['subCode']))
					subjectResults($resObj);
				else
					displayForms(); ?>
            </div> <!-- End of content div -->
            <?php right(); ?>	
            </section>
        <?php footer(); ?>
		<script type="text/javascript" src="http://cdnjs.cloudflare.com/ajax/libs/highcharts/4.1.7/highcharts.js"></script>
    </body>
</html>