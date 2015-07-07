<?php
	session_start();
	$_SESSION=array();
	$a=session_destroy();
	if($a){
		header("location: login.php");		
	}
	else
		echo "LogOut error, please manually clear active logins through your browser";
?>
