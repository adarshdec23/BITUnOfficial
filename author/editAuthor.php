<?php

/*
  Project name : BITuN
  Start Date : 22 Mar, 2015 2:32:27 PM
  Author: Adarsh
  Purpose :
 */
session_start();
    include 'classes/authorClass.php';
    $author = new author();
    $author_id = $author->authenticate();
    if(!$author_id){
        header('location:../index.php');
        exit();
    }                   
    $er=false;
    $suc=false;
    
    $result = $author->getAuthorDetails($author_id);
    
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
                if($ext==="jpg"){
                    array_map('unlink', glob("profile_pictures/".$result['author_name'].".jpg"));//Delete previous fprfile pictures in the directory 
                    if(move_uploaded_file($_FILES['newPic']['tmp_name'],"profile_pictures/".$result['author_name'].".jpg")){
                        
                        mysqli_query($link,"UPDATE author SET profile_pic=1 WHERE author_id=$author_id") or die("alright then");
                        $suc=true;
                        $sucMsg="Profile picture updated";
                        @unlink($_FILES['newPic']['tmp_name']);
                        $result['profile_pic'] = 1; //Update result

                    }else{
                        $er=true;
                        $erm="Oops, somethings seems to have gone wrong, please try again.";
                    }
                }else{
                    $er=true;
                    $erm="Image must be a jpg.";
                }
            }
        }
        else if($_POST['formNo']==3){
            if(isset($_POST['aboutAuthor']) && $_POST['aboutAuthor'] !=""){
                $aboutAuthor = mysqli_real_escape_string($link,$_POST['aboutAuthor']);
                if(mysqli_query($link,"UPDATE author SET about_author='$aboutAuthor' WHERE author_id = $author_id")){
                    $suc = TRUE;
                    $sucMsg = "Updated";
                    $result = $author->getAuthorDetails($author_id);
                }
                else{
                    $er = TRUE;
                    $erm = "DB Error.";
                }
            }
            else{
                $er = TRUE;
                $erm = "About author cannot be blank";
            }
        }
    }

?>
<!DOCTYPE HTML>
<html>
<head>
  <title><?php echo $result['author_name'];?></title>
  <meta charset="utf-8">
  <meta name="Keywords" content="<?php echo $result['author_name'];?>,BIT,Bangalore Institute Of Technology,VTU,KR Road,K.R Road,KIMS">
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
                if($result['profile_pic'])   
                    echo "<img src='profile_pictures/".$result['author_name'].".jpg' title='profile_pictures/".$result['author_name'].".jpg'>\n";
                else    echo'<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" baseProfile="full" width="76" height="76" viewBox="0 0 76.00 76.00" enable-background="new 0 0 76.00 76.00" xml:space="preserve">
	<path fill="#000000" fill-opacity="1" stroke-width="0.2" stroke-linejoin="round" d="M 38,17.4167C 33.6278,17.4167 30.0833,20.9611 30.0833,25.3333C 30.0833,29.7056 33.6278,33.25 38,33.25C 42.3723,33.25 45.9167,29.7056 45.9167,25.3333C 45.9167,20.9611 42.3722,17.4167 38,17.4167 Z M 30.0833,44.3333L 29.4774,58.036C 32.2927,59.4011 35.4528,60.1667 38.7917,60.1667C 41.5308,60.1667 44.1496,59.6515 46.5564,58.7126L 45.9167,44.3333C 46.9722,44.8611 49.0834,49.0833 49.0834,49.0833C 49.0834,49.0833 50.1389,50.6667 50.6667,57L 55.4166,55.4167L 53.8333,47.5C 53.8333,47.5 50.6667,36.4167 44.3332,36.4168L 31.6666,36.4168C 25.3333,36.4167 22.1667,47.5 22.1667,47.5L 20.5833,55.4166L 25.3333,56.9999C 25.8611,50.6666 26.9167,49.0832 26.9167,49.0832C 26.9167,49.0832 29.0278,44.8611 30.0833,44.3333 Z "/>
</svg>'."\n";
                ?>
                <form id="profilePicEditor" method="post" action="editAuthor.php" enctype="multipart/form-data">
                    <input type="file" name="newPic" id="picInput">
                    <input type="hidden" value="1" name="formNo">
                    <input type="submit" class="editPSubmit" value="Change Author Picture">
                </form>
            </div>
            <div id="profileEditorHolder">
                <h2>About Me</h2>
                <p id="aboveForm"><img src="../Images/appbar.add.png">Click to Toggle</p>
                <form id="passwordEditForm" method="post" action="editAuthor.php">
                    <label for="oldpwd">About Me</label>
                    <textarea name="aboutAuthor"  class="editP-input"><?php echo $result['about_author'];?></textarea>
                    <input type="hidden" value="3" name="formNo">
                    <input type="submit" id="pwdSubmit" class="editPSubmit" value="Update">
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
