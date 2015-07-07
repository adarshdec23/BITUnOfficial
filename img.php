<?php
/* Project: BITuN
 * By: Adarsh
 * Started On: 05-Jun-2014 22:46:59
  Purpose:Gallery section of BIT UnOfficial.
 */
function dispEr($toPrint,$ending=""){
    echo "<div class='imgHeading'>Sorry, the $toPrint you requested does not exist.$ending</div>\n";
}
function images($pgNo=1){
    $startRow=($pgNo-1)*9;
    include 'include/newCon.php';
    $result=mysqli_query($link,"SELECT COUNT(*) FROM images");//Getting no row elements for page no system
    $count=mysqli_fetch_row($result);
    $rowCount=$count[0];//$rowCount has no of rows, this is processed later
    $totPages=ceil($rowCount/9);
    if($pgNo>$totPages){
        dispEr("page");
        return;
    }
    $result=mysqli_query($link,"SELECT * FROM images ORDER BY ID DESC LIMIT $startRow,9")or die("connection error");//Select 8 entries starting from the current one
    echo "<div class='imgHeading'>Recent Albums</div>";
    $i=1;
    while($row=mysqli_fetch_array($result,MYSQLI_ASSOC)){//Display the image
        $textToDisp=$row['heading'];
        $text_for_url = str_replace(array(" ","-","!"), "_", $textToDisp); //Replace space and - with underscore
        $addr=$row['address'];
        $imgArr=explode(',',$addr);
        $imgToDisp=$imgArr[0];
        $imgToDisp=  preg_replace('/(images\/)(.*)/i', '$1med_thumbs/$2', $imgToDisp);
        $iid=$row['ID'];
        echo "<div class='imgHolder'>
                <a href='img/$iid/$text_for_url'><img src='$imgToDisp' title='$textToDisp'><div class='coverText'>$textToDisp</div></a>
            </div>\n";
        if($i%3==0)
            echo "<div class='imgHR'></div>\n";
        $i++;
    }
    
    echo "<div id='pageNumHolder'>
        <a href='/img' class='highlightedPage'>&lt;&lt;</a>\n";
    $curPage=$pgNo-3;
    $j=1;
    while($curPage<=0 && $j<=2){//Checking if previous three pages are valid pages
        $curPage++;
        $j++;
    }
    $i=1;
    while($curPage>=1 && $i<=3 && $curPage<$pgNo){//Printing links to previous three pages
        echo "<a href='/img?p=$curPage' class='pageNum'>$curPage</a>\n";
        $i++;
        $curPage+=1;
    }
    echo "<a href='/img?p=$pgNo' class='highlightedPage'>$pgNo</a>\n";//Bring the current page no.
    $i=1;
    $curPage=$pgNo+1;
    while($curPage<=$totPages && $i<=3){//Printing next three pages
        echo "<a href='/img?p=$curPage' class='pageNum'>$curPage</a>\n";
        $i++;
        $curPage+=1;
    }
    echo "<a href='/img?p=$totPages' class='highlightedPage'>&gt;&gt;</a>
        </div>\n";
}


function display_album($id){
    include 'include/newCon.php';
    $result=  mysqli_query($link,"SELECT * FROM images WHERE ID=$id");
    if(!isset($_GET['offset']))
        $offset=0;
    else
        $offset=$_GET['offset'];
    if(mysqli_num_rows($result)){
        $row=  mysqli_fetch_array($result,MYSQLI_ASSOC);
        $heading=$row['heading'];
        $addr=$row['address'];
        if($offset>=$row['size']){
            dispEr("image");
            return;
        }
        $imgArr=explode(',',$addr);
        $imgToDisp=$imgArr[0+$offset];
        $title=$row['title'];
        $titleArr=explode(',',$title);
        $titleToDisp=$titleArr[0+$offset];
        if($titleToDisp=='0')
            $titleToDisp="";
        if(!empty($row['alt'])){
            $alt=$row['alt'];
            $altArr=explode(',',$alt);
            $altToDisp=$altArr[0+$offset];
            if($altToDisp=='0')
            $altToDisp="";
        }else{
            $altToDisp="";
            $alt="";
        }
        if($row['articleID']!=NULL){
            $artID=$row['articleID'];
			$resultHeading = mysqli_query($link, "SELECT smallHeading FROM article WHERE id = $artID");
			$rowHeading = mysqli_fetch_assoc($resultHeading);
			$text_for_url = str_replace(array(" ","-","!"), "_", $rowHeading['smallHeading']); //Replace space and - with underscore
            echo "<div class='imgHeading'>$heading<a href='/art/$artID/$text_for_url' class='ralign'>Read More</a></div>\n";
			unset($resultHeading);
			unset($rowHeading);
			unset($text_for_url);
        }else
            echo "<div class='imgHeading'>$heading</div>\n";
        echo "<div id='ss'>
                <div id='li' class='icon' onclick=\"lclick('$addr','$title','$alt')\">
                    <img src='/Images/larrow.ico'>
                </div>	
                <div id='ri' class='icon' onclick=\"rclick('$addr','$title','$alt')\">
                    <img src='/Images/rarrow.ico'>
                </div>
                <div id='iss'>
                    <img src='$imgToDisp' alt='$altToDisp'>
                </div>
                <div id='textBelow'>$titleToDisp</div>
            </div>\n";
    }else{
        dispEr("album","<br>Feel free to check out other exciting albums.");
    }
    // Now the fun part of randomising
    echo "<div class='imgHR'></div>
        <div class='imgHeading'>Random Galleries</div>\n";
    $result=mysqli_query($link,"SELECT outter.* 
                                FROM images outter
                                WHERE outter.ID IN
                                    (
                                        SELECT iner.ID
                                        FROM images iner
                                        WHERE iner.ID <> $id
                                        ORDER BY RAND()
                                    )
                                LIMIT 3
                                ") or die(mysqli_error($link));
    while($allRows = mysqli_fetch_assoc($result)){
        $offset=$allRows['size']-1;//ergo, cur[i] represents the row of the first unique ID
        $offset = mt_rand(0, $offset);
        $textToDisp=$allRows['heading'];
        $text_for_url = str_replace(array(" ","-","!"), "_", $textToDisp); //Replace space and - with underscore
        $addr=$allRows['address'];
        $imgArr=explode(',',$addr);
        $imgToDisp=$imgArr[$offset];
        $imgToDisp=  preg_replace('%(\/Images\/)(.*)%i', '$1med_thumbs/$2', $imgToDisp);
        $iid=$allRows['ID'];
        echo "<div class='imgHolder'>
                <a href='/img/$iid/$text_for_url?offset=$offset'><img src='$imgToDisp' title='$textToDisp'><div class='coverText'>$textToDisp</div></a>
            </div>\n";
        
    }
}
function disp_vid($id){
    echo "<div class='imgHeading'>Work in progress, coming soon....</div>";
    echo '<iframe  src="https://www.youtube.com/embed/SP_SPhgxfA0" frameborder="0" allowfullscreen></iframe>';
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title> Bangalore Institute of Technology - The Unofficial website </title>
        <meta charset="utf-8">
        <meta name="Keywords" content="Gallery,Images,Pictures,Video,BIT,Bangalore Institute Of Technology,VTU,KR Road">
        <meta name="Description" content="Images and Videos of to BIT Bangalore. Delight your eyes with beautiful pictures of BIT.">
        <meta name="viewport" content="width=device-width" initial-scale=1>
        <link rel="shortcut icon" href="http://bitunofficial.com/Images/Logo.ico">
        <link rel="stylesheet" type="text/css" href="/Style_Folder/outline.css">
        <link rel="stylesheet" type="text/css" href="/Style_Folder/img.css">
        <script src="http://ajax.aspnetcdn.com/ajax/jQuery/jquery-1.10.2.min.js"></script>
        <script type="text/javascript" src="/js/img.js"></script>
        <?php require_once 'outline.php'; ga(); ?>
    </head>
    <body>
        <?php
        head();
        ?>
        <section>
            <ul id="galleryTab">
                <a href='/img'><li <?php if(!isset($_GET['vidP'])) echo "class='greyBackground'"; ?>>Images</li></a>
                <a href='/img?vidP=1'><li <?php if(isset($_GET['vidP'])) echo "class='greyBackground'"; ?>>Videos</li></a>
            </ul>
            <div id='galleryHolder'>
                <?php 
                    if(isset($_GET['iid'])){
                        display_album($_GET['iid']);
                    }else if(isset($_GET['p'])){
                        images($_GET['p']);
                    }else if(isset($_GET['vidP'])){
                        disp_vid($_GET['vidP']);
                    }else images();
                ?>
            </div>
        </section>
        <?php footer(); ?>
    </body>
</html>
