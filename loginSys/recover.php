<?php
session_start();
$suc=false;
$er=false;
if(isset($_SESSION['id'])){
    header("location: ../index.php");
    exit();
}

else if(isset($_GET['id']) && isset($_GET['resetCode'])){
    
    require_once('../include/newCon.php');
    $id=mysqli_real_escape_string($link,$_GET['id']);
    $code=mysqli_real_escape_string($link,$_GET['resetCode']);
    $verifyRequest=  mysqli_query($link, "SELECT * FROM newpass WHERE (personID=$id AND code='$code')");
    if(mysqli_num_rows($verifyRequest)){
        mysqli_query($link,"DELETE FROM newpass WHERE (personID=$id AND code='$code')");
        $result=mysqli_query($link,"SELECT * FROM membasic WHERE ID=$id") or die("two");
        $row=  mysqli_fetch_array($result,MYSQLI_ASSOC);
        $_SESSION["id"]=$row["ID"];
        $_SESSION["username"]=$row["username"];
        $_SESSION["imgExist"]=$row["profilePic"];
        $_SESSION["imgExt"]=$row["profilePicExt"];
        date_default_timezone_set("Asia/Kolkata");
        $curTime = date("Y-m-d H:i:s");
        mysqli_query($link,"UPDATE membasic SET lastLogin='$curTime' WHERE ID=$id") or die("dont die");
        session_regenerate_id();
        session_write_close();
        header("location: editProfile.php");
        exit();
    }else{
        $er=true;
        $erm="Oops,looks like you have already used this link. If you haven't reset your password, type in your email once more to for a new request";
    }
} 
else if(isset($_POST['email'])){
    $email=$_POST['email'];
    require_once('../include/newCon.php');
    $result=mysqli_query($link,"SELECT ID,username FROM membasic WHERE email='".mysqli_real_escape_string($link,$email)."'") or die("please dont 1");
    if(mysqli_num_rows($result)){
        $row=mysqli_fetch_array($result,MYSQLI_ASSOC);
        $id=$row["ID"];
        $previousCheck=  mysqli_query($link,"SELECT ID FROM newpass WHERE personID=$id");
        if(!mysqli_num_rows($previousCheck)){
            $username=$row['username'];
            $x="";
            for($i=1;$i<=6;$i++){
                $temp=  mt_rand(65,140);
                if(($temp>64 && $temp<91) || ($temp>96 && $temp<123))
                    $x.=chr($temp);
                else
                    $x.=$temp;
			}
            date_default_timezone_set("Asia/Kolkata");
            $curTime = date("Y-m-d H:i:s");
            if(!mysqli_query($link,"INSERT INTO newpass (personID,code,requesttime) VALUES ('$id','$x','$curTime')")){
                $er=true;
                $erm="Oops, something went wrong. Please try agin later.";
            }else{
                $headers  = 'MIME-Version: 1.0' . "\r\n";
                $headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
                $headers .= 'To: '.$email. "\r\n";
                $headers .= 'From: Password recovery <webMaster@[Add website]>' . "\r\n";
                $subject="Password Recovery";
                $message="
                    <html>
                    <head>
                        <style>
                        *{
                                width:90%;

                        }
                        h2{
                                background:#0F1A22;
                                color:#fff;
                                padding:10px;
                                font-weight:400;
                        }
                        p{
                                background:#FFF4DA;
                                padding:10px;
                        }
                        </style>
                    </head>
                    <body>
                    <h2>Password Recovery At [my site]</h2>
                    <h3>Hello $username, you have requested for a new password</h3>
                    <p>
                            <b>Account information </b><br><br>
                            Email ID: $email<br>
                            Username :$username<br>
                            To reset your password, click on this link.<br>
                            You will then be redirected, so that you can reset your password.
                            <br>
                            http://mysite/loginSys/recover.php?id=$id&amp;resetCode=$x
                            <br>
                            After clicking on the link, you will redirected, so that you can reset your password.
                            This is a one time link, and will be valid after it has been clicked.
                            <br>
                            Please note: after resetting your password, you will no longer be able to use your old password.<br>
                            If you did not request a password change, please ignore this email, as no changes will be made to your account.
                    </p>	
                    <hr>
                    </body>
                    </html>";
                $message=  wordwrap($message);
                if(mail($email, $subject, $message,$headers)){
                    $suc=true;
                }else{
                    $er=true;
                    $erm="Sorry, we were unable to send you an email. Please try once more";
                }
            }
        }else{
            $er=true;
            $erm="You have already requested a password reset.";
        }
    }else{
        $er=true;
        $erm="The email ID you entered does not belong to any account";
    }
}
?>
<!DOCTYPE HTML>
<html>
<head>
  <title>BIT UnOfficial| Password Recovery</title>
   <link rel="stylesheet" type="text/css" href="../Style_Folder/outline.css">
  <link rel="stylesheet" type="text/css" href="../Style_Folder/recover.css">
  <script src="http://ajax.aspnetcdn.com/ajax/jQuery/jquery-1.10.2.min.js"></script>
  <meta charset="UTF-8">	
</head>
<body>	
    <?php
        require_once('../outline.php');
        head();
        ?> 		
	<section>
        <?php
        if(!$suc){
            if($er) echo"<div id='er' class='rec-msg'>$erm</div>";
            echo"<div id=\"recoverFormHolder\">
            <h2>Password Recovery</h2>
            <form id=\"recoverForm\" method='post' action='recover.php'>
                <label for=\"email\">Enter your Email Address</label>
                <input type=\"text\" id=\"email\" name=\"email\">
                <p>On clicking the button,an email will be sent to you containing instructions on resetting your password. 
                </p>
                <input type=\"submit\" value=\"Recover your Password\" id=\"recoverSubmit\">
            </form>
        </div>\n";
        }else{
            echo"<div id='suc' class='rec-msg'>Hello $username.A recovery email has been sent to $email.</div>\n";
        }
        ?>
    </section>
	<?php footer(); ?> 
</body>
</html>	