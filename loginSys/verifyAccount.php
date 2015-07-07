<?php

session_start();
if(isset($_SESSION["uid"]) || !isset($_GET["verifyFor"]) || !isset($_GET['rand'])){
        header("location:../index.php");
}
else{
    $suc=false;
    if(is_numeric($_GET['verifyFor'])){
        require_once('../include/newCon.php');	
        $id=$_GET["verifyFor"];
        
        $resInitial = mysqli_query($link,"SELECT * FROM new_mem WHERE member_id=$id");
        $rowInitial = mysqli_fetch_assoc($resInitial);
        if($rowInitial['rand'] == $_GET['rand']){
            mysqli_query($link,"DELETE FROM new_mem WHERE member_id=$id");
            $sqlQuery="SELECT emailVerif FROM membasic WHERE ID=$id";
            $result=mysqli_query($link,$sqlQuery);
            if(mysqli_num_rows($result)>0){
                $row=mysqli_fetch_array($result,MYSQLI_ASSOC);
                if($row["emailVerif"]){//Already verified
                        die("You are already a member.");
                }else{//update
                        $sqlQuery="UPDATE membasic SET emailVerif=1 WHERE ID=$id";
                        if(mysqli_query($link,$sqlQuery)){
                                $suc=true;
                        }
                }
            }
        }
    }
}
?>
<!DOCTYPE HTML>
<html>
<head>
  <title>BIT UnOfficial | Account Verification</title>
  <link rel="stylesheet" type="text/css" href="../Style_Folder/outline.css">
  <script src="http://ajax.aspnetcdn.com/ajax/jQuery/jquery-1.10.2.min.js"></script>
  <meta charset="UTF-8">	
</head>
<body>
        <?php 
		require_once('../outline.php');
		head();
		?>
	<section>
            <div id="signup">
		<div id="simpleMessage">
		<?php 
		if($suc){
			echo "<h2>Congratulation, you are now an official member of BIT UnOfficial</h2>\n\t\t<br> Your email account has been verified. This provides you with complete access to BIT UnOfficial.
			<br> Just login and win big!!\n";	
		}else{
			echo "\t<h2>Ooops...</h2>\n\t\t\t<br>Something seems to have gone wrong while verifying your account. Please try again. \n";
		}
		?>
		</div>	
            </div>
	</section>
	<?php footer(); ?>
</body>
</html>	