<?php
/* Project: BITuN
 * By: Adarsh
 * Started On: 05-Jun-2014 18:50:04
  Purpose: Has the outline of a page belonging the BIT UnOfficial. Contains the
 * menu,footer.
 */
 
 function ga(){
 
	//Function for analytics
 
 }
 
function head(){
    echo '<header>
            <h1><a href="/index.php">Bangalore Institute of Technology</a></h1>
            <h3><a href="/index.php">The <span class="redText">Un</span>official Website</a></h3>
            <div  id="menudiv">
                <ul>
                    
                    <a href="/"><li>Home</li></a>
                    <a href="/result.php"><li class="high">BIT Results</li></a>
                    <a href="/aboutBIT.php"><li>About BIT</li></a>
                    <a href="/placement.php"><li>Placements</li></a>
					<a href="/chat"><li class="high">Chat</li></a>
                    <a href="/img"><li>Gallery</li></a>
					<a href="/Manthan/index.php"><li>Manthan</li></a>
                    <a href="/club"><li>Clubs</li></a>
                </ul>	
            </div> <!--End of menu div -->';
    @session_start();
    echo "<div id='loginBox'>
        <ul>"; 
    if(isset($_SESSION['username']) && isset($_SESSION['id'])){
        $username=$_SESSION['username'];  
        echo "<li>$username<ul>";
         echo "<li><a href='/loginSys/profile.php'>Profile</a></li>
             <li><a href='/loginSys/editProfile.php'>Edit Profile</a></li>
            <li><a href='/loginSys/logout.php'>Logout</a></li>
            ";
    }
    else{
        echo"<li>Login/Register<ul>";
        if(!isset($_GET['retAddr']))
            $retAddr = urlencode($_SERVER['REQUEST_URI']);
        else
            $retAddr = urlencode ($_GET['retAddr']);
        echo "<li><a href='/loginSys/login.php?retAddr=$retAddr'>Login</a></li>
              <li><a href='/loginSys/signup.php'>Register</a></li>  
            ";
    }
           echo"</ul>
               </li>
            </ul>
            </div>
           </header>
            ";
}
function footer(){
    echo '
        <div id="footer">
            <ul>
                <li><a href="/aboutUs.php">About Us</a></li> |
                <li><a href="/faq.php">Frequently Asked Questions</a></li> 
           ';
        if(isset ($_SESSION['author_id']) )
            echo '<li> | <a href="/author/author.php?id='.$_SESSION['author_id'].'">Author Page</a></li>'
                . ' <li> | <a href="/author/article.php">New Article</a></li>';
        echo ''
        . '</ul>';
        echo '
        <div id="social">
            <a href="https://www.facebook.com/BITUnOfficial" rel="publisher"><img src="/Images/fb.png"></a>
            <a href="https://twitter.com/BITUnOfficial" rel="publisher"><img src="/Images/twitter.png"></a>
            <a href="https://plus.google.com/102308904209764963720" rel="publisher"><img src="/Images/google.png"></a>
        </div>
            <div id="copyRight">&copy; BIT Unofficial 2015
           </div></div><!--End of footer -->'."\n";
           
}
function right(){
    global $link;
	if(!isset($link))
		include 'include/newCon.php';
    $result=mysqli_query($link,"SELECT ID,smallHeading,content1,checkimg FROM article WHERE checkimg=1 ORDER BY ID DESC LIMIT 5") or die("die die die");
    echo '
        <div id="robj">
            <div class="individObj">
                <h3>Recent</h3>';
                echo "<div id='slideShow'>
                    <div id='bulletHolder'>";
                for($i=0 ; $i<mysqli_num_rows($result) ; $i++)
                        echo"<div class='bullet' id='b1'></div>";
                    echo"</div> <!-- End of bulletHolder -->";
                echo "\n\t\t\t\t";
                while($row=  mysqli_fetch_array($result,MYSQLI_ASSOC)){
                    $ctx=$row['content1'];
                    $ctx=strip_tags($ctx);
                    $ctx=trim($ctx);
                    $txt= substr($ctx,0,100);
                    if(strlen($ctx)>100)
                        $txt.=".....";
                    $id=$row['ID'];     
                    $resulti=  mysqli_query($link,"SELECT ID,address FROM images WHERE articleID=$id");
                    $irow=  mysqli_fetch_assoc($resulti);
                    $imgAdr=$irow['address'];
                    $ar=  explode(',',$imgAdr);
                    $firstAddr=$ar[0];
                    $firstAddr=  preg_replace('/(\/images\/)(.*)/i', '$1med_thumbs/$2', $firstAddr);
                    $text_for_url = str_replace(array(" ","!","-"),"_",$row['smallHeading']);
                   echo '<div class="rEmptyWrap">
                    <div class="rImgDisp">
                    <a href="/art/'.$row['ID'].'/'.$text_for_url.'">
                    <div class="headingWImg">'.$row['smallHeading'].'</div>
                    <img src="'.$firstAddr.'" title="'.$row['smallHeading'].'"></a>
                 </div>
                 <div class="textWImg">'.$txt.'</div></div><!--End of empty wrapper -->';
                }
                echo '
                    </div> <!--End of slideshow -->
            </div><!--End of one individual obj Recent-->
            <div class="individObj" id="quickLinks">
                <h3>Quick Links</h3>
                <ul>
					<li><a href="http://vtu.ac.in/time-table-for-ug-pg-for-examination-june-july-2015/" target="_blank">VTU Exam Time Table</a></li>
                    <li><a href="http://bitunofficial.com/Images/Calendar_of_events_2015.png" target="blank">Calendar Of Events 2015</a></li>
                    <li><a href="https://youtu.be/SP_SPhgxfA0" target="blank"> Manthan 2015 - Logo Launch</a></li>
                </ul>
            </div> <!-- End of quick links -->    

        </div><!--End of robj -->';
        

}
?>