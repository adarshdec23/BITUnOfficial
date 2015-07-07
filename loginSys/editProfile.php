<?php 
    session_start();
    if(!isset($_SESSION['id'])){
        header('location:../index.php');
        exit();
    }
    $id=$_SESSION['id'];
    $er=false;
    $suc=false;
    require_once('../include/newCon.php');
    if(isset($_POST['formNo'])){        
        if($_POST['formNo']== 1){
            if($_FILES['newPic']['size']===0 || $_FILES['newPic']['size']>1000000 || $_FILES['newPic']['error']>0){
                $er=true;
                $erm="File too large/small. ";
                @unlink($_FILES['newPic']['tmp_name']);
            }else{
                $nama=  explode('.',$_FILES['newPic']['name']);
                $ext=end($nama);
                $ext=  strtolower($ext);
                if($ext==="jpeg" || $ext==="jpg" || $ext==="gif" || $ext==="png"){
                    array_map('unlink', glob("../member/$id.*"));//Delete previous fprfile pictures in the directory 
                    if(move_uploaded_file($_FILES['newPic']['tmp_name'],"../member/$id.$ext")){
                        $_SESSION['imgExist']=1;
                        $_SESSION['imgExt']="$ext";
                        mysqli_query($link,"UPDATE membasic SET profilePic=1,profilePicExt='$ext' WHERE ID=$id");
                        $suc=true;
                        $sucMsg="Profile picture updated";
                        @unlink($_FILES['newPic']['tmp_name']);
                        function img_resize($target, $newcopy, $w, $h, $ext) {
                            list($w_orig, $h_orig) = getimagesize($target);
                            $scale_ratio = $w_orig / $h_orig;
                            if (($w / $h) > $scale_ratio) {
                                   $w = $h * $scale_ratio;
                            } else {
                                   $h = $w / $scale_ratio;
                            }
                            $img = "";
                            $ext = strtolower($ext);
                            if ($ext == "gif"){ 
                              $img = imagecreatefromgif($target);
                            } else if($ext =="png"){ 
                              $img = imagecreatefrompng($target);
                            } else { 
                              $img = imagecreatefromjpeg($target);
                            }
                            $tci = imagecreatetruecolor($w, $h);
                            imagecopyresampled($tci, $img, 0, 0, 0, 0, $w, $h, $w_orig, $h_orig);
                            imagejpeg($tci, $newcopy, 80);
                        }
                        img_resize("../member/$id.$ext", "../member/".$id."_thumb.jpg", 130,130, $ext);
                    }else{
                        $er=true;
                        $erm="Oops, somethings seems to have gone wrong, please try again.";
                    }
                }else{
                    $er=true;
                    $erm="Image must be a jpeg,gif or png.";
                }
            }
        }
        else if($_POST['formNo']==3){
            include_once '../include/PasswordHash.php';
            $checker=new PasswordHash(8,FALSE);
            $result=mysqli_query($link,"SELECT password FROM membasic WHERE ID=$id");
            if(mysqli_num_rows($result)>0){
                $row=mysqli_fetch_array($result,MYSQLI_ASSOC);
                if($checker->CheckPassword($_POST['oldpwd'], $row['password'])){
                    $pwd=$_POST['pwd'];
                    if(strlen($pwd)>7){
                        if($_POST['pwd']===$_POST['rpwd']){
                            $pwd=$checker->HashPassword($pwd);
                            if(mysqli_query($link,"UPDATE membasic SET password='$pwd' WHERE ID=$id")){
                                $suc=true;
                                $sucMsg="Your password has been changed.";
                            }else{
                                $er=true;
                                $erm="Oops, something went wrong. Please try again.";
                            }
                        }else{
                            $er=true;
                            $erm="Re-entered password did not match your new password.";
                        }
                    }else{
                        $er=true;
                        $erm="Password must be a minumum of 8 characters.";
                    }
                }else{
                    $er=true;
                    $erm="Invalid Password.";
                }
            }else{
                $er=true;
                $erm="Oops, something went wrong. Please try again.";
            }
        }
    }
    $username=$_SESSION['username'];
    $result=mysqli_query($link,"SELECT joinTime,lastLogin,profilePic,profilePicExt FROM membasic WHERE id=$id");
    $row=mysqli_fetch_array($result,MYSQLI_ASSOC);
    $imgExist=$row["profilePic"];
    $imgExt=$row["profilePicExt"];
?>
<!DOCTYPE HTML>
<html>
<head>
  <title><?php echo "$username";?></title>
  <meta charset="utf-8">
  <meta name="Keywords" content="<?php echo "$username";?>,BIT,Bangalore Institute Of Technology,VTU,KR Road,K.R Road,KIMS">
  <meta name="Description" content="Everything you need to know about Bangalore Institute Of Technology - The Unofficial Website: The better alternative">
  <meta name="viewport" content="width=device-width" initial-scale=1>
  <link rel="stylesheet" type="text/css" href="../Style_Folder/outline.css">
  <link rel="stylesheet" type="text/css" href="../Style_Folder/editProfile.css">
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
                echo"<div class='editP-msg' id='er'>$erm</div>";
            }if($suc){
                echo"<div class='editP-msg' id='suc'>$sucMsg</div>";
            }
            ?>
            <div id="pictureEditHolder">
                <?php 
                if($imgExist)   echo"<img src=\"../member/$id.$imgExt\" title=\"$username\">\n";
                else    echo'<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" baseProfile="full" width="76" height="76" viewBox="0 0 76.00 76.00" enable-background="new 0 0 76.00 76.00" xml:space="preserve">
	<path fill="#000000" fill-opacity="1" stroke-width="0.2" stroke-linejoin="round" d="M 38,17.4167C 33.6278,17.4167 30.0833,20.9611 30.0833,25.3333C 30.0833,29.7056 33.6278,33.25 38,33.25C 42.3723,33.25 45.9167,29.7056 45.9167,25.3333C 45.9167,20.9611 42.3722,17.4167 38,17.4167 Z M 30.0833,44.3333L 29.4774,58.036C 32.2927,59.4011 35.4528,60.1667 38.7917,60.1667C 41.5308,60.1667 44.1496,59.6515 46.5564,58.7126L 45.9167,44.3333C 46.9722,44.8611 49.0834,49.0833 49.0834,49.0833C 49.0834,49.0833 50.1389,50.6667 50.6667,57L 55.4166,55.4167L 53.8333,47.5C 53.8333,47.5 50.6667,36.4167 44.3332,36.4168L 31.6666,36.4168C 25.3333,36.4167 22.1667,47.5 22.1667,47.5L 20.5833,55.4166L 25.3333,56.9999C 25.8611,50.6666 26.9167,49.0832 26.9167,49.0832C 26.9167,49.0832 29.0278,44.8611 30.0833,44.3333 Z "/>
</svg>'."\n";
                ?>
                <form id="profilePicEditor" method="post" action="editProfile.php" enctype="multipart/form-data">
                    <input type="file" name="newPic" id="picInput">
                    <input type="hidden" value="1" name="formNo">
                    <input type="hidden" name="MAX_FILE_SIZE" value="1000000">
                    <input type="submit" class="editPSubmit" value="Change Profile Picture">
                </form>
            </div>
            <div id="profileEditorHolder">
                <h2>Edit Password</h2>
                <p id="aboveForm"><img src="../Images/appbar.add.png">Make sure no one is watching....</p>
                <form id="passwordEditForm" method="post" action="editProfile.php">
                    <label for="oldpwd">Old Password</label><input type="password" name="oldpwd" id="oldpwd" class="editP-input">
                    <br>
                    <label for="pwd">New Password</label><input type="password" name="pwd" id="pwd" class="editP-input">
                    <br>
                    <label for="rpwd">Re-Enter New Password</label><input type="password" name="rpwd" id="rpwd" class="editP-input">
                    <br>
                    <input type="hidden" value="3" name="formNo">
                    <br>
                    <input type="submit" id="pwdSubmit" class="editPSubmit" value="Change Password">
                </form>
            </div>
        </section>
    <?php footer(); ?>
    <script>
        $('#aboveForm').click(function(){
        $('#passwordEditForm').slideToggle();
        if($('#aboveForm img').attr('src')==="../Images/appbar.add.png")
             $('#aboveForm img').attr('src',"../Images/appbar.minus.png");
         else	
             $('#aboveForm img').attr('src',"../Images/appbar.add.png");
       });
    </script>
</body>
</html>