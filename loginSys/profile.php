<?php
session_start();
$er=false;
if(!isset($_SESSION['id'])){
	header("location:../index.php");
	exit();
}else if(isset($_GET['id'])){
    if($_SESSION['id']==$_GET['id']){
        $my=true;
    }else{
        $my=false;
        $id=$_GET['id'];
        if(!is_numeric($id)){
            $er=TRUE;
        }
        else{
            require_once('../include/newCon.php');
            $result=mysqli_query($link,"SELECT username,joinTime,lastLogin,profilePic,profilePicExt,comments FROM membasic WHERE ID=$id") or die("die die die");
            if(mysqli_num_rows($result)){
                $row=mysqli_fetch_array($result,MYSQLI_ASSOC);
                $username=$row['username'];
                $joinTime=$row["joinTime"];
                $lastLogin=$row["lastLogin"];
                $imgExist=$row["profilePic"];
                $imgExt=$row["profilePicExt"];	
                $comments=$row['comments'];
            }else{
                $er=true;
            }
        }
    }    
}else{
    $my=true;
}
    if($my){
        $username=$_SESSION['username'];
	$id=$_SESSION['id'];
	require_once('../include/newCon.php');
	$result=mysqli_query($link,"SELECT email,joinTime,lastLogin,profilePic,profilePicExt,comments FROM membasic WHERE ID=$id");
	$row=mysqli_fetch_array($result,MYSQLI_ASSOC);
	$email=$row["email"];
	$joinTime=$row["joinTime"];
	$lastLogin=$row["lastLogin"];
	$imgExist=$row["profilePic"];
	$imgExt=$row["profilePicExt"];	
        $comments=$row['comments'];
    }
?>
<!DOCTYPE HTML>
<html>
<head>
  <title><?php echo "$username";?> | BIT UnOfficial</title><meta charset="utf-8">
  <meta charset="utf-8">
  <meta name="Keywords" content="<?php echo "$username";?>,BIT,Bangalore Institute Of Technology,VTU,KR Road,K.R Road,KIMS">
  <meta name="Description" content="Everything you need to know about Bangalore Institute Of Technology - The Unofficial Website: The better alternative">
  <meta name="viewport" content="width=device-width" initial-scale=1>
  <link rel="stylesheet" type="text/css" href="../Style_Folder/outline.css">
  <link rel="stylesheet" type="text/css" href="../Style_Folder/profile.css">
  <link rel="shortcut icon" href="http://bitunofficial.com/Images/Logo.ico">
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
                if($er){
                    echo "<div id='simpleMessage'>
                        <h2>This account doesn't exist</h2>
                        The person you are looking for is not a member :/
                        </div>
                        </section>";
                        footer(); 
                        echo "</body>
                            </html>	";
                    exit();
                }
            ?>
		<div id="profileHolder">
				<h2><?php echo "$username";?></h2>
				<?php 
				if($imgExist)	echo"<img src=\"../member/$id.$imgExt\" title=\"$username\">\n";
				else	echo'<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" baseProfile="full" width="76" height="76" viewBox="0 0 76.00 76.00" enable-background="new 0 0 76.00 76.00" xml:space="preserve">
	<path fill="#000000" fill-opacity="1" stroke-width="0.2" stroke-linejoin="round" d="M 38,17.4167C 33.6278,17.4167 30.0833,20.9611 30.0833,25.3333C 30.0833,29.7056 33.6278,33.25 38,33.25C 42.3723,33.25 45.9167,29.7056 45.9167,25.3333C 45.9167,20.9611 42.3722,17.4167 38,17.4167 Z M 30.0833,44.3333L 29.4774,58.036C 32.2927,59.4011 35.4528,60.1667 38.7917,60.1667C 41.5308,60.1667 44.1496,59.6515 46.5564,58.7126L 45.9167,44.3333C 46.9722,44.8611 49.0834,49.0833 49.0834,49.0833C 49.0834,49.0833 50.1389,50.6667 50.6667,57L 55.4166,55.4167L 53.8333,47.5C 53.8333,47.5 50.6667,36.4167 44.3332,36.4168L 31.6666,36.4168C 25.3333,36.4167 22.1667,47.5 22.1667,47.5L 20.5833,55.4166L 25.3333,56.9999C 25.8611,50.6666 26.9167,49.0832 26.9167,49.0832C 26.9167,49.0832 29.0278,44.8611 30.0833,44.3333 Z "/>
</svg>'."\n";
				?>
				<ul>
					<li class="profileLeftList">Username</li><li class="profileRightList"><?php echo"$username";?></li>
					<li class="profileLeftList">Join Date</li><li class="profileRightList"><?php echo"$joinTime";?></li>
					<?php if($my) echo "<li class='profileLeftList'>Email ID</li><li class='profileRightList'>$email</li>"; ?>
                                        <li class="profileLeftList">Total comments</li><li class="profileRightList"><?php echo"$comments";?></li>
					<?php
                                        if($my)
                                            echo '<li class="profileLeftList">Last Login</li><li class="profileRightList">'.$lastLogin.'</li>';
                                        else
                                            echo '<li class="profileLeftList">Last Active</li><li class="profileRightList">'.$lastLogin.'</li>';
                                        ?>
				</ul>
				<?php 
                                if($my)
                                    echo '<span id="linkToEdit"><a href="editProfile.php">Edit Profile</a></span>';
                                ?>
		</div>
	</section>
	<?php footer(); ?>
</body>
</html>	