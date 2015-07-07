<?php
//script sends an email :: test this if it doesn't work try phpmailer
function sendMail($email,$id,$username,$x){
	$to=addslashes($email);
	$subject="BIT UnOfficial : Activate your account";
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
	<h2>Welcome to BIT UnOfficial</h2>
	<h3>Hello $username, you are just one step away from activating your account</h3>
	<p>
		<b>Account information </b><br><br>
		Email ID: $email<br>
		To get your account up and running <a href=\"http://BITUnOfficial.com/loginSys/verifyAccount.php?verifyFor=$id&rand=$x\">
		click on this link.</a><br><br>
		Log in any time to BITUnOfficial to be a part of our wonderful community.
                <br>
		<b>In case you can not see the above link, copy and paste the text below onto your browser's address bar.</b>
		http://BITUnOfficial.com/loginSys/verifyAccount.php?verifyFor=$id&rand=$x
	</p>	
	<hr>
	<h6>If you are not $username please ignore this email</h6> 
	</body>
	</html>";
        $message=  wordwrap($message,67,"\r\n");
	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
	$headers .= 'To: '.$to. "\r\n";
	$headers .= 'From: Account Activation <webMaster@BITUnOfficial.com>' . "\r\n";
	if(mail($to,$subject,$message,$headers))
		return true;
	else
		return false;
}
?>