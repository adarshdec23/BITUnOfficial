<?php
/*
  Project name : BITuN
  Start Date : 21 Mar, 2015 2:40:49 PM
  Author: Adarsh
  Purpose :
 */
session_start();


class clubs{
    
    private $aLink;
    public $er,$erm;
            
    function clubs(){
        include 'author/include/aCon.php';
        $this->aLink=$aLink;
        $this->er=FALSE;
        $this->erm="";
    }
    
    function getClubDetails(){
        $clubLink = $_GET['link'];
        $stmt = $this->aLink->prepare("SELECT c.*
                                FROM clubs c
                                WHERE c.link = ? ");
        $stmt->bind_param("s",$clubLink);
        
        if(!$stmt->execute()){
            $this->er=TRUE;
            $this->erm="Database error";
            return FALSE;
        }
        
        $result = array();
        $stmt->bind_result($result['id'],$result['link'],$result['name'],$result['mission'],$result['description'],$result['facebook'],$result['website']);
        $stmt->store_result();
        $stmt->fetch();
        
        if($stmt->num_rows != 1){
            $this->er = TRUE;
            $this->erm = "No such author exists";
            return FALSE;
            
        }
        return $result;
    }
            
    function simpleMsg(){
        echo "<div id='simpleMessage'>
            <h2>Oops, something went wrong....</h2>
            $this->erm
        </div>";
    }
    
    function displayOneItem($heading,$value){
        if($value == NULL || $value == "")
            return;
        $value = str_replace("\n", "<br>", $value);
        echo "<p><b>".$heading."</b> : <br>".$value."</p>";
    }
    
    function showClubDetails(){
        $result = $this->getClubDetails();
        if($this->er){
            $this->simpleMsg ();
            return;
        }
        $conResult = $this->aLink->query("SELECT contact_name,number FROM club_contacts WHERE club_id = ".$result['id']."");
        echo "<h2>".$result['name']."</h2>"
                . "<h3> About </h3>";

        if(file_exists($_SERVER['DOCUMENT_ROOT']."/Images/".$result['link'].".jpg"))
            echo "<img src='/Images/".$result['link'].".jpg' id='clubLogo'>";
        $this->displayOneItem("Mission",$result['mission']);
        $this->displayOneItem("Description",$result['description']);
        $this->displayOneItem("Facebook",$result['facebook']);
        $this->displayOneItem("Website",$result['website']);

        echo "<h3> Contact Details </h3>"
        . "<p>";
        while($row = $conResult->fetch_assoc()){
            $this->displayOneItem($row['contact_name'], $row['number']);
        }
        echo "</p>";

    }
    
    function getAllClubs(){
        $result = $this->aLink->query("SELECT name,link FROM clubs ORDER BY name");
        if($this->aLink->error){
            $this->er = TRUE;
            $this->erm = "Sorry, database error. We are actively working towards a solution.";
        }
        return $result;    
    }
    
    function showAllClubs(){
        
        $alphabets = array(); //Contains the first alphabet of every club
        
        $result = $this->getAllClubs();
        if($this->er){
            $this->simpleMsg();
            return;
        }
        echo "<h3> Clubs at BIT </h3>"
        . "<p>BIT boasts about having a variety of clubs. Here is a comprehensive list of these clubs.</p>";
        while($row = $result->fetch_assoc()){
            $firstAlpha = strtoupper($row['name'][0]);
            if(!in_array($firstAlpha, $alphabets)){
                echo "<h3>$firstAlpha</h3>";
                $alphabets[]=$firstAlpha;
            }
            echo "<div class='imgHolder'>"
                    . "<a href='http://bitunofficial.com/club/".$row['link']."'>"
                    ."<img src='/Images/".$row['link'].".jpg' alt='".$row['link']."'>"
                    ."<div class='coverText'>".$row['name']."</div>"
                    ."</a>"
                    . "</div>";
        }
    }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title> Bangalore Institute of Technology - The Unofficial website </title>
        <meta charset="utf-8">
        <meta name="Keywords" content="Clubs,Club Activity,Rotaract ,Photography, Google Students Club,SAE,E-Magazine,TEDxBIT, Dance Group,Shuttered,BIT,Bangalore Institute Of Technology,VTU,KR Road">
        <meta name="Description" content="A comprehensive guide to all clubs and club related activities in BIT Bangalore. ">
        <meta name="viewport" content="width=device-width" initial-scale=1>
        <link rel="stylesheet" type="text/css" href="http://bitunofficial.com/Style_Folder/outline.css" >
        <link rel="stylesheet" type="text/css" href="/Style_Folder/club.css"> 
        <link rel="shortcut icon" href="http://bitunofficial.com/Images/Logo.ico">
        <script src="http://ajax.aspnetcdn.com/ajax/jQuery/jquery-1.10.2.min.js"></script>
        <script type="text/javascript" src="http://bitunofficial.com/js/basic.js"></script>
        <?php require_once 'outline.php'; ga(); ?>
    </head>
    <body>
        <?php
        
        head();
        ?>
        <section>				
            <div id="content">
                <?php
                $club = new clubs();
                if(isset($_GET['link']) && $_GET['link'] != ""){
                    $club->showClubDetails();
                    }
                else{
                    $club->showAllClubs();
                    }
                ?>
	
           </div> <!-- End of Content div -->
           <?php right(); ?>	
        </section>
        <?php footer(); ?> 
    </body>
</html>
