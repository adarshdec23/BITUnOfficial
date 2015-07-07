<?php
session_start();
if(isset($_SESSION["id"])) 
	header("location:../index.php");
include_once("../include/newCon.php");	
$erm=array();
$er=false;	
$emailSent=false;
if(isset($_POST["username"])){	
    $username=@$_POST["username"];
    $email=@$_POST["email"];
    $pwd=@$_POST["password"];
    $rpwd=@$_POST["rpassword"];
    $username=trim($username);
    $email=trim($email);
    function verifyLenght($str,$lenght){
        if(strlen($str)>$lenght)
            return false;
        else 
            return true;
    }
    if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
            $er=true;
            $erm="Invalid Email address";
    }
    else if(strlen($pwd)<8 && !verifyLenght($pwd,80)){
            $er=true;
            $erm="Password must be over 7 characters";	
    }
    else if(!($pwd===$rpwd)){
            $er=true;
            $erm="Password mis-match";
    }else{
        $email=  mysqli_real_escape_string($link,$email);
        $username=  mysqli_real_escape_string($link,$username);
        $result=mysqli_query($link,'SELECT * FROM membasic WHERE email=\''.$email.'\'')or die('die die die');;
        if(mysqli_num_rows($result)>0){
                $erm="Email ID already in use";
                $er=true;
        }
        else{
            $result=  mysqli_query($link,'SELECT * FROM membasic WHERE username=\''.$username.'\'') or die('me two');
            if(mysqli_num_rows($result)>0){
                $er=true;
                $erm="Username is already taken";
            }
            else{
                /* Include Openwell's PHpass to salt and hash the password. A lenght of less than 20 is the error condition */
                require_once '../include/PasswordHash.php';
                $hasher= new PasswordHash(8, FALSE);
                $hash=$hasher->HashPassword($pwd);
                if(strlen($hash) < 20){
                    $er=TRUE;
                    $erm="Something went wrong, please try again.";
                }
                else{
                    $pwd=$hash; //Hashing successful. Assign the computed hash as the password.
                    date_default_timezone_set("Asia/Kolkata");
                    $createAccountTime = date("Y-m-d H:i:s");
                    $email=  mysqli_real_escape_string($link,$email);
                    $username=  mysqli_real_escape_string($link,$username);
                    $sqlQuery="INSERT INTO membasic (username,email,password,joinTime) VALUES ('$username','$email','$pwd','$createAccountTime')";
                    if(mysqli_query($link,$sqlQuery)){
                            require_once('sendEmail.php');
                            $id=mysqli_insert_id($link);
                            $x="";
                            for($i=1;$i<=6;$i++){
                                $temp=  mt_rand(65,140);
                                if(($temp>64 && $temp<91) || ($temp>96 && $temp<123))
                                    $x.=chr($temp);
                                else
                                    $x.=$temp;
                            }
                            mysqli_query($link,"INSERT INTO new_mem VALUES($id,'$x')") or die("no key");
                            if(sendMail($email,$id,$username,$x)){
                                    $emailSent=true;
                            }else{
                                    $er=true;
                                    $erm="It seems like we couldn't send a message to your email ID. Please contact the system admin.";
                            }	
                    }
                    else{
                            $er=true;
                            $erm="Oops, something went wrong. Please try once more or contact the system administrator.";
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
  <title>BIT UnOfficial | Sign Up</title>
  <meta charset="utf-8">
  <meta name="Keywords" content="Sign Up,Register,Membership,BIT,Bangalore Institute Of Technology,VTU,KR Road,K.R Road,KIMS">
  <meta name="Description" content="Everything you need to know about Bangalore Institute Of Technology - The Unofficial Website: The better alternative">
  <meta name="viewport" content="width=device-width" initial-scale=1>
  <link rel="stylesheet" type="text/css" href="../Style_Folder/outline.css">
  <link rel="stylesheet" type="text/css" href="../Style_Folder/signup.css">
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
		<?php 
		if(!$emailSent){
		echo "<div id=\"rbox-holder\">
			<h2>Sign Up : BIT UnOfficial</h2>
			<form id=\"rForm\" action=\"signup.php\" method=\"POST\">";
				if($er) echo "<h4 id=\"reg-error-msg\">$erm</h4>"; 
				echo "
				<label for=\"username\">Userame</label><input type=\"text\" name=\"username\" id=\"username\" class=\"r-input\">
				<br>
				<label for=\"email\">Email</label><input type=\"text\" name=\"email\" id=\"email\" class=\"r-input\">
				<br>
				<label for=\"pwd\">Password</label><input type=\"password\" name=\"password\" id=\"pwd\" class=\"r-input\">
				<br>
				<label for=\"rpwd\">Re-Enter Password</label><input type=\"password\" name=\"rpassword\" id=\"rpwd\" class=\"r-input\">
				<br>
				<input type=\"submit\" id=\"rSubmit\" value=\"Sign Up\">
			</form>	
			<a href=\"login.php\">Already a member? Login</a>
		</div>";	
		}else{
			echo "<div id=\"simpleMessage\">\n\t<h2>Registration Successful</h2>\n\t\t<br>An email has been sent to your email ID.<br>\n\t\tPlease activate your account through a link present in the message.\n</div>";
		}
		?>
            </div>
	</section>
	<?php footer(); ?>
</body>
</html>	