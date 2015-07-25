<?php

 function ajaxResponse($startID,$initialDisplay = FALSE){
    include 'author/include/aCon.php';
    if(!is_numeric($startID))
        die ("FALSE");
    $startID = (int)$startID;
	if($initialDisplay)
		$result = $aLink->query("SELECT * FROM article ORDER BY ID DESC LIMIT 7");
	else
		$result = $aLink->query("SELECT * FROM article WHERE ID < $startID ORDER BY ID DESC LIMIT 7");
    if($result->num_rows < 1)
        die("FALSE");
    $stringToBeSent = "";
    while($row = $result->fetch_assoc()){
            $id=$row['ID'];
			$text_for_url = str_replace(array(" ","-","!"), "_", $row['smallHeading']); //Replace space and - with underscore 
			echo '<h3 id="'.$id.'"><a href="art/'.$row["ID"].'/'.$text_for_url.'">'.$row['heading'].'</a> <span class="ralign smallText">'.$row['date'].'
			</span></h3>'."\n";
			echo  "\t\t\t\t\t".'<div class="artHolder">'."\n";
            if($row['checkimg']!=0){       
                    $resulti=  $aLink->query("SELECT ID,address,title,alt FROM images WHERE articleID=$id");
                    $irow=  $resulti->fetch_assoc();
                    $imgAdr=$irow['address'];
					$imgTitle = current(explode(',', $irow['title']));
					$imgAlt = current(explode(',', $irow['alt']));
                    $imgAdr=  preg_replace('%(images\/)(.*?)\.(.*?){3}%i', '$1large_thumbs/$2.$3', $imgAdr);
                    $imgID=$irow['ID'];
                    $ar=explode(',',$imgAdr);
                    echo  "\t\t\t\t\t\t".'<div class="ss">
            <a href="art/'.$row["ID"].'/'.$text_for_url.'" id="link'.$imgID.'">
                <img class="iss" title="'.$imgTitle.'" src=\''.$ar[0].'\' alt="'.$imgAlt.'">
            </a>
            <div class="li icon" onclick="lclick(\''.$imgAdr.'\',\''.$imgID.'\',\''.$irow['title'].'\',\''.$irow['alt'].'\')">
                <img src="/Images/larrow.jpg" alt=\'Left Arrow\'>
            </div>	
            <div class="ri icon" onclick="rclick(\''.$imgAdr.'\',\''.$imgID.'\',\''.$irow['title'].'\',\''.$irow['alt'].'\')">
                <img src="/Images/rarrow.jpg" alt=\'Right Arrow\'>
            </div>
        </div> <!-- End of slideshow div -->'."\n";
            }
            $textToDisp=$row['content1'];
			$textToDisp = str_replace("\n","<br>", $textToDisp);
            $textToDisp=trim($textToDisp);
            if(strlen($textToDisp)>500){
                $textToDisp="<p class='para'>".(substr($textToDisp,0,499))."......<a href='art/$id/$text_for_url'>Read More</a></p>";
            }
            else
                $textToDisp="<p class='para'>".$textToDisp."</p>";
            echo  "\t\t\t\t\t\t".$textToDisp."
    </div><!-- End of artHolder --> \n\n\t\t\t\t\t";
    }

}

if(isset($_GET['startID'])){
    ajaxResponse($_GET['startID']);
	die();
}
 if(!isset($_COOKIE['modal_chat1'])){
	setcookie('modal_chat1', 1, time()+24*60*60, '/');
	$showChatModal = TRUE;
}
/*function dispEr($toPrint,$ending=""){
    echo "<h3>Sorry, the $toPrint you requested does not exist.$ending</h3>\n";
}*/
?>
<!DOCTYPE html>
<html>
    <head>
        <title> Bangalore Institute of Technology - The Unofficial website </title>
        <meta charset="utf-8">
        <meta name="Keywords" content="BIT,BIT Bangalore,Bangalore Institute Of Technology,VTU,KR Road,K.R Road,KIMS">
        <meta name="Description" content="Everything you need to know about Bangalore Institute Of Technology - The Unofficial Website: The better alternative">
        <meta name="viewport" content="width=device-width" initial-scale=1>
        <link rel="stylesheet" type="text/css" href="/Style_Folder/outline.css">
        <link rel="stylesheet" type="text/css" href="/Style_Folder/index.css">
        <link rel="shortcut icon" href="http://bitunofficial.com/Images/Logo.ico">
        <?php require_once 'outline.php'; ga(); ?>
    </head>
    <body>	
            <?php
            head();?>
            <div id="showCase">
                <div class="showCaseEntity" id="entity1">
                    Get real time updates about college events. 
                </div>
                <div class="showCaseEntity" id="entity2">
                    Be a part of the BIT community.
                </div>
                <div class="showCaseEntity" id="entity3">
                    Never miss out on anything, anymore.
                </div>  
            </div>
			<?php if(isset($showChatModal) && $showChatModal)
			echo<<<TILL_END
            <div id='modal_chat1' class='modal'>
				<div class='modalVisible'>
					<div class='modalHeading'>
						Have a Question about BIT???
					</div>
					<div class='modalContent'>
						Confused about anything? Want to know the admission procedure? Something on your mind?<br>
						Let us help you clarify your doubts!! You can ask us any thing and we will answer them!
					</div>
					<div class='modalButtonsHolder'>
						<a href='http://BITUnOfficial.com/chat'><div class='modalYesButton'>Yes! I want to chat!</div></a>
						<div class='modalCloseButton'>Nah</div>
					</div>
				</div>
			</div>
TILL_END
			?>
            <section>
				
                <div id="content">
                    <h2>Home : BIT Unofficial - Your Access to everything BIT</h2>
                    <?php
                    include 'include/newCon.php';
                    ajaxResponse(1,TRUE);    
					?>
            </div> <!-- End of content div -->
            <?php right(); ?>	
        </section>
        <?php footer(); ?>
        <img src='Images/loading.gif' style="display:none;">
        <script type="text/javascript">
            document.addEventListener("scroll",sendRequest,false);
            
            var xmlhttp = new XMLHttpRequest();
            
            function attachLoadingImage(){
                var imgL = document.createElement("img");
                imgL.src="Images/loading.gif";
                imgL.id = "loadingImage";
                document.getElementById("content").appendChild(imgL);
            }
            
            function detachLoadingImage(){
                document.getElementById("content").removeChild(document.getElementById("loadingImage"));
            }
            
            xmlhttp.onreadystatechange=function(){
            	if(xmlhttp.readyState === 1){
                    attachLoadingImage();
                    return;
                }
                if(xmlhttp.readyState === 4 && xmlhttp.status === 200){
                    detachLoadingImage();
                    if(xmlhttp.responseText === "FALSE")
                        return;
                    
                    document.getElementById("content").insertAdjacentHTML("beforeend",xmlhttp.responseText);
                    //Re-attach the event listener once again
                    document.addEventListener("scroll",sendRequest,false);
                }
            };
            function sendRequest(){
                if(window.scrollY + window.innerHeight > document.body.clientHeight - 1500){

                    //Remove the event listener to prevent multiple requests from being shot
                    document.removeEventListener("scroll",sendRequest,false);
                    //Compute the id of the last h3 in the content box
                    var allh3 = document.getElementsByTagName("h3");
                    var index = allh3.length-1;
                    while(!allh3[index].id)
                        index--; //Obtain the index of the last h3 with an id
                    var lastID = allh3[index].id;

                    //AJAX request to index.php

                    xmlhttp.open("get","index.php?startID="+lastID, true);
                    xmlhttp.send();
                }
            }
        </script>
		<script src="http://ajax.aspnetcdn.com/ajax/jQuery/jquery-1.10.2.min.js"></script>
        <script src="/js/basic.js"></script>
		<script type="text/javascript">
			$('.modalCloseButton').click(function(){
				$('#modal_chat1').fadeOut();
			});
		</script>
    </body>
</html>	
