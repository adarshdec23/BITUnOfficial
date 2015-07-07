	<?php
/* Project: BITuN
 * By: Adarsh
 * Started On: 05-Jun-2014 19:14:32
 *Purpose:This page displays an article. The article ID, is obtained by get.
 */
$er=false;

if(!isset($_GET['id'])){
    header("location: /index.php");
    exit();
}

$id=$_GET['id'];
if(!is_numeric($id)){
    $er=TRUE;
}
else{
    require_once 'include/newCon.php';
    $result=mysqli_query($link,"SELECT * from article WHERE ID=$id");
    if(mysqli_num_rows($result)){
        $row=mysqli_fetch_array($result,MYSQLI_ASSOC);
    }
    else
        $er=true;
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title> <?php if(!$er)echo $row['heading']."  | ";?>Bangalore Institute of Technology - The Unofficial website </title>
        <meta charset="utf-8">
        <meta name="Keywords" content="<?php echo $row['kwords']."," ?>BIT,Bangalore Institute Of Technology">
        <meta name="Description" content="<?php if($row['descript']) echo $row['descript']; else echo "Everything you need to know about Bangalore Institute Of Technology - The Unofficial Website: The better alternative";?>">
        <meta name="viewport" content="width=device-width" initial-scale=1>
        <link rel="stylesheet" type="text/css" href="/Style_Folder/outline.css">
        <link rel="stylesheet" type="text/css" href="/Style_Folder/art.css">
        <link rel="shortcut icon" href="http://bitunofficial.com/Images/Logo.ico">
        <script src="http://ajax.aspnetcdn.com/ajax/jQuery/jquery-1.10.2.min.js"></script>
        <script src="/js/basic.js"></script>
        <?php require_once 'outline.php'; ga(); ?>
    </head>
    <body>	
        <?php
        head(); ?>
        <section>
            <div id="content">
                <?php
                if(!$er){
                    $text_for_url = str_replace(array(" ","-","!"), "_", $row['smallHeading']); //Replace space and - with underscore
                    echo'<h3><a href="/art/'.$row["ID"].'/'.$text_for_url.'">'.$row['heading'].'</a> <span class="ralign smallText">'.$row['date'].' </span></h3>'."\n";
                    if($row['checkimg']!=0){       
                                    $resulti=  mysqli_query($link,"SELECT ID,heading,address,title,alt FROM images WHERE articleID=$id");
                                    $irow=  mysqli_fetch_assoc($resulti);
                                    $imgAdr=$irow['address'];
									$imgTitle = current(explode(',', $irow['title']));
									$imgAlt = current(explode(',', $irow['alt']));
                                    $imgAdr=  preg_replace('%(images\/)(.*?)\.(.*?){3}%i', '$1large_thumbs/$2.$3', $imgAdr);
                                    $imgID=$irow['ID'];
                                    $text_for_url = str_replace(array(" ","-","!"), "_", $irow['heading']);
                                    $ar=explode(',',$imgAdr);
                                    echo "\t\t\t\t\t\t".'<div class="ss">
                            <a href="/img/'.$imgID.'/'.$text_for_url.'" id="link'.$imgID.'">
                                <img class="iss" title="'.$imgTitle.'" src=\''.$ar[0].'\' alt="'.$imgAlt.'">
                            </a>
                            <div class="li icon" onclick="lclick(\''.$imgAdr.'\',\''.$imgID.'\',\''.$irow['title'].'\',\''.$irow['alt'].'\')">
                                <img src="/Images/larrow.ico">
                            </div>	
                            <div class="ri icon" onclick="rclick(\''.$imgAdr.'\',\''.$imgID.'\',\''.$irow['title'].'\',\''.$irow['alt'].'\')">
                                <img src="/Images/rarrow.ico">
                            </div>
                        </div> <!-- End of slideshow div -->'."\n";
                            }
                            $textToDisp = $row['content1']."</p><p>".$row['content2'];
                            $textToDisp = str_replace("\n","</p><p class='para'>", $textToDisp);
                             echo "\t\t\t\t\t\t<p>".$textToDisp."</p>
                        \n";
                             
                        /*Obtain the author name and printing*/
                             
                        require_once 'author/classes/editArticleClass.php'; 
                        $eArticle= new editArticle();
                        $authorRes = $eArticle->getAuthorIdAndName($id);
                        if($authorRes){
                            echo "<h3>Author : <a href='/author/author.php?id=".$authorRes['author_id']."'>".$authorRes['author_name']." </a></h3>";
                        }
                        // Finished printing the author name
						//include 'author/classes/editArticleClass.php';
						if(isset($_SESSION['author_id']))
						{
							$eArticle->verifyOwnership($id, $_SESSION['author_id']);
							if(!$eArticle->er){
								echo "<p> <a href='/author/editArticle.php?id=$id'>Edit Article</a></p>";
							}
						}
                        // Finished printing the author name
						?>
						<br><br>
						<div id="disqus_thread"></div>
						<script type="text/javascript">
							var disqus_shortname = 'bitunofficial';
							var disqus_identifier = '<?= $id ?>';
							/* * * DON'T EDIT BELOW THIS LINE * * */
							(function() {
								var dsq = document.createElement('script'); dsq.type = 'text/javascript'; dsq.async = true;
								dsq.src = '//' + disqus_shortname + '.disqus.com/embed.js';
								(document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
							})();
						</script>
						<noscript>Please enable JavaScript to view the <a href="https://disqus.com/?ref_noscript" rel="nofollow">comments powered by Disqus.</a></noscript>
						<?php
                    }else{
                        echo "<h3>Oops, the page you requested does not exit</h3>";
                    }
                ?>
            </div> <!-- End of content div -->
            <?php right(); ?>	
        </section>
        <?php footer(); ?> 
    </body>
</html>	