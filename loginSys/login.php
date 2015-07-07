<?php
	session_start();
	if(isset($_SESSION["id"]))
		header("location: ../index.php");
	include_once("../include/newCon.php");	
	$erm=array();
	$er=false;
	if(!isset($_REQUEST['retAddr']) || $_REQUEST['retAddr']==NULL){
		$retAddr="../index.php";
	}
        else{ 
            $retAddr=$_GET['retAddr'];
            $retAddr= urldecode($retAddr);
        }
	if(isset($_POST["username"])){
		$username=@$_POST["username"];
		$password=@$_POST["password"];
		$retUrl=@$_POST['url'];
                $username=  mysqli_real_escape_string($link,$username);
		$username=trim($username);
		$retUrl=trim($retUrl);
		if(empty($username)){
			$erm="Username empty";
			$er=true;
		}else if(empty($password)){
			$erm="Password is empty ";
			$er=true;
		}
		if(!$er){
                    $sqlQuery='SELECT * FROM membasic WHERE username=\''.$username.'\'';
                    $result=mysqli_query($link,$sqlQuery);
                    $row=mysqli_fetch_array($result,MYSQLI_ASSOC);
                    if(mysqli_num_rows($result)>0){
                        if($row['emailVerif'] ==0){
                            $er=TRUE;
                            $erm="Please check you email to verify your account.";
                        }
                        else{
                            include_once '../include/PasswordHash.php'; // Related comments in signup.php
                            $checker = new PasswordHash(8, FALSE);
                            if($checker->CheckPassword($password, $row['password'])){
                                $_SESSION["id"]=$row["ID"];
                                $id=$row["ID"];
                                $_SESSION["username"]=$row["username"];
                                $_SESSION["imgExist"]=$row["profilePic"];
                                $_SESSION["imgExt"]=$row["profilePicExt"];
                                date_default_timezone_set("Asia/Kolkata");
                                $curTime = date("Y-m-d H:i:s");
                                mysqli_query($link,"UPDATE membasic SET lastLogin='$curTime' WHERE ID=$id") or die("dont die");
                                require_once '../author/classes/authorClass.php';
                                $newAuthor = new author();
                                $authentic = $newAuthor->authenticate();
                                if($authentic){
                                    $_SESSION['author_id'] = $authentic;
                                }
                                unset($newAuthor);
                                unset($authentic);
                                session_regenerate_id();
                                session_write_close();
                                header("location: $retUrl");
                                exit();
                            }
                            else{
                                    $erm="Invalid password";
                                    $er=true;
                            }	
                        }
                    }
                    else{
                            $erm="Invalid username, try again ";
                            $er=true;
                    }
		}
	}	
?>
<!DOCTYPE HTML>
<html>
<head>
  <title>BIT UnOfficial | Login</title>
  <meta charset="utf-8">
  <meta name="Keywords" content="Login,Membership,BIT,Bangalore Institute Of Technology,VTU,KR Road,K.R Road,KIMS">
  <meta name="Description" content="Everything you need to know about Bangalore Institute Of Technology - The Unofficial Website: The better alternative">
  <meta name="viewport" content="width=device-width" initial-scale=1>
  <link rel="stylesheet" type="text/css" href="../Style_Folder/outline.css">
  <link rel="stylesheet" type="text/css" href="../Style_Folder/login.css">
  <script src="http://ajax.aspnetcdn.com/ajax/jQuery/jquery-1.10.2.min.js"></script>
  <meta charset="UTF-8">	
</head>
<body>
        <?php
		require_once('../outline.php');
		head();
		?> 		
	<section>
            <div id="login">
		<div id="lbox-holder">
			<h2>BIT UnOfficial Login</h2>
			<?php if($er) echo "<div id=\"login-page-error-msg\">$erm</div>"; ?>
			<form id="lForm" method="post" action="login.php">
				<label for="enterUsername">Username</label>
				<input type="text" id="enterUsername" class="l-input" name="username"><br>
				<label for="enterPwd">Password</label>
				<input type="password" name="password" id="enterPwd" class="l-input" maxlength=20>
				<br>
				<input type="hidden" name="url" value="<?php echo "$retAddr" ;?>">
				<input type="submit" value="Login" id="lSubmit">
			</form>
                        <a href='recover.php'>Forgot Password?</a>
			<a href="signup.php">New to the BIT UnOfficial? Sign Up!</a> 
		</div>
            </div>
        </section>
	<?php footer(); ?>
</body>
</html>	