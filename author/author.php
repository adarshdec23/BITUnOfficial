<?php
/*
  Project name : BITuN
  Start Date : 7 Mar, 2015 4:29:16 PM
  Author: Adarsh
  Purpose :
 */
if(!isset($_GET['id'])){
    header("LOCATION: ../");
    exit();
}
session_start();
$er=FALSE;

if(!is_numeric($_GET['id'])){
    $er=TRUE;
}
else{
    $author_id = (int)$_GET['id'];
    require_once 'classes/authorClass.php';
    $newAuthor = new author();
    $authorRes = $newAuthor->getAuthorDetails($author_id);
    if(!$authorRes)
        $er= TRUE;
}

?>

<!DOCTYPE HTML>
<html>
<head>
  <title><?php echo $authorRes['author_name'] ;?> | BIT UnOfficial</title>
  <meta charset="utf-8">
  <meta name="Keywords" content="BIT,Bangalore Institute Of Technology,VTU,KR Road,K.R Road,KIMS">
  <meta name="Description" content="Everything you need to know about Bangalore Institute Of Technology - The Unofficial Website: The better alternative">
  <link rel="stylesheet" type="text/css" href="../Style_Folder/outline.css">
  <link rel="stylesheet" type="text/css" href="../Style_Folder/profile.css">
  <link rel="shortcut icon" href="http://bitunofficial.com/Images/Logo.ico">
  <script src="http://ajax.aspnetcdn.com/ajax/jQuery/jquery-1.10.2.min.js"></script>
  <?php require_once '../outline.php'; ga(); ?>
</head>
<body>
	
                <?php 
		head();
		?> 
	<section>
            <?php 
                if($er){
                    echo "<div id='simpleMessage'>
                        <h2>This account doesn't exist</h2>
                        $newAuthor->erm :/
                        </div>
                        </section>";
                        footer(); 
                        echo "</body>
                            </html>	";
                    exit();
                }
            ?>
		<div id="profileHolder">
				<h2><?php echo $authorRes['author_name'];?></h2>
				<?php 
				if($authorRes['profile_pic'])	echo"<img src=\"profile_pictures/".$authorRes['author_name'].".jpg\" title='".$authorRes['author_name']."'>\n";
				else	echo'<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" baseProfile="full" width="76" height="76" viewBox="0 0 76.00 76.00" enable-background="new 0 0 76.00 76.00" xml:space="preserve">
	<path fill="#000000" fill-opacity="1" stroke-width="0.2" stroke-linejoin="round" d="M 38,17.4167C 33.6278,17.4167 30.0833,20.9611 30.0833,25.3333C 30.0833,29.7056 33.6278,33.25 38,33.25C 42.3723,33.25 45.9167,29.7056 45.9167,25.3333C 45.9167,20.9611 42.3722,17.4167 38,17.4167 Z M 30.0833,44.3333L 29.4774,58.036C 32.2927,59.4011 35.4528,60.1667 38.7917,60.1667C 41.5308,60.1667 44.1496,59.6515 46.5564,58.7126L 45.9167,44.3333C 46.9722,44.8611 49.0834,49.0833 49.0834,49.0833C 49.0834,49.0833 50.1389,50.6667 50.6667,57L 55.4166,55.4167L 53.8333,47.5C 53.8333,47.5 50.6667,36.4167 44.3332,36.4168L 31.6666,36.4168C 25.3333,36.4167 22.1667,47.5 22.1667,47.5L 20.5833,55.4166L 25.3333,56.9999C 25.8611,50.6666 26.9167,49.0832 26.9167,49.0832C 26.9167,49.0832 29.0278,44.8611 30.0833,44.3333 Z "/></svg>'."\n";
				?>
				<ul>
					<li class="profileLeftList">Author Name</li><li class="profileRightList"><?php echo $authorRes['author_name'];?></li>
                                        <li class="profileLeftList">Articles</li><li class="profileRightList"><?php echo $authorRes['article_count'];?></li>
                                        <li class="profileLeftList">Status</li><li class="profileRightList"><?php if($authorRes['author_status']) echo "Active"; else echo "Retired"?></li>
                                        <li class="profileLeftList">About the Author</li><li class="profileRightList"><?php echo $authorRes['about_author'];?></li>
				</ul>
				<?php 
                                if($newAuthor->authenticate() == $_GET['id'])
                                    echo '<span id="linkToEdit"><a href="editAuthor.php">Edit My Profile</a></span>';
                                ?>
		</div>
	</section>
	<?php footer(); ?>
</body>
</html>	