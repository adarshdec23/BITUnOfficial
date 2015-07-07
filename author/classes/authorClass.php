<?php
/* 
Project name : BITuN 
Start Date : 6 Mar, 2015 11:00:09 PM
Author: Adarsh
Purpose : 
*/
class author{
    protected $con;
    public $er=FALSE ;
    public $erm="";
    protected $author_id;
    
    function author(){
        
        include ($_SERVER['DOCUMENT_ROOT']."/author/include/aCon.php");       
        $this->con = $aLink;
        if($this->con->connect_errno){
            echo"DB Error.";
            exit();
        }   
    }
    
    /* ******************************************************** */
    
    function authenticate(){
        
        if(!isset($_SESSION['id']))
            return FALSE;
        
        $res = $this->con->query("SELECT author_id FROM author WHERE user_id =".$_SESSION['id']);
        if($this->con->errno){
            $this->er=TRUE;
            $this->erm="Database error";
            return FALSE;
        }
        
        if($res->num_rows != 1){
            return FALSE;
        }
        
        $row=$res->fetch_assoc();
        $this->author_id=$row['author_id'];
        return $this->author_id;
    }
    
    /* ******************************************************** */
    
    function getAuthorDetails($author_id){
        
        $stmt = $this->con->prepare("SELECT author_name,about_author,author_status,article_count,profile_pic FROM author WHERE author_id = ?");
        $stmt->bind_param("i",$author_id);
        
        if(!$stmt->execute()){
            $this->er=TRUE;
            $this->erm="Database error";
            return FALSE;
        }
        
        $result = array();
        $stmt->bind_result($result['author_name'],$result['about_author'],$result['author_status'],$result['article_count'],$result['profile_pic']);
        $stmt->store_result();
        $stmt->fetch();
        
        if($stmt->num_rows != 1){
            $this->er = TRUE;
            $this->erm = "No such author exists";
            return FALSE;
            
        }
        
        return $result;
        
    }
    
    /* ******************************************************** */
    
    function setAboutAuthor($aboutAuthor){
        
        $stmt = $this->con->prepare("UPDATE author SET about_author = ? WHERE author_id = ".$this->author_id);
        $stmt->bind_param("s",$aboutAuthor);
        return $stmt->execute();

    }
    
   /* function getAuthorID(){
        $result = $this->con->query("SELECT author_id FROM author WHERE user_id =".$_SESSION['id']);
        $row = $result->fetch_assoc();
        return $row['author_id'];
    }
    */
    function getAuthorIdAndName ($article_id){
        
        if(!is_numeric($article_id)){
            return FALSE;
        }
        $article_id = (int) $article_id;
        $stmt = $this->con->prepare("SELECT aa.author_id, a.author_name
                               FROM article_to_author aa, author a
                               WHERE aa.article_id = ?
                               AND aa.author_id = a.author_id " );
        $stmt->bind_param("i", $article_id);
        
        if(!$stmt->execute()){
            $this->er = TRUE;
            $this->erm = "Database error";
            return FALSE;
        }
        
        $result = array();
        $stmt->bind_result($result['author_id'],$result['author_name']);
        $stmt->store_result();
        $stmt->fetch();
        
        if ($stmt->num_rows < 1) {
            $this->er = TRUE;
            $this->erm = "Article does not exist";
            return FALSE;
        }

        return $result;

    }
    
}

?>