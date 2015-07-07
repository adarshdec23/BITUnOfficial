<?php
/*
  Project name : BITuN
  Start Date : 21 Apr, 2015 10:46:16 PM
  Author: Adarsh
  Purpose :
 * NOTE: Every function here works on the assumption that verifyOwnership has been called once.
 */

include 'authorClass.php';
class editArticle extends author{
	private $article_id;
	function editArticle() {
		parent::author();
	}
	
	function verifyOwnership($article_id, $author_id){
		
		$article_id = (int)$article_id;
		$res = $this->con->query("SELECT * FROM article_to_author WHERE article_id = $article_id");
		if($this->con->errno){
            $this->er=TRUE;
            $this->erm="Database error";
        }    
        if($res->num_rows != 1){
            $this->er=TRUE;
            $this->erm="Requested article does not exist";
        }
		$row = $res->fetch_assoc();
		if($row['author_id'] == $author_id){
			$this->article_id = $article_id;
			$this->author_id = $author_id;
		}
		else if($_SESSION['author_id'] == 1){
			$this->article_id = $article_id;
			$this->author_id = 1;
		}
		else{
			$this->er= TRUE;
			$this->erm = "You are not the owner of this article.";
		}
	}
	
	function getArticleDetails(){
		
		$res = $this->con->query("SELECT * FROM article WHERE ID = $this->article_id");
		if($this->con->errno){
            $this->er=TRUE;
            $this->erm="Database error1";
			return FALSE;
        }    
		$row = $res->fetch_assoc();
		return $row;
	}
	
	function processTitles($title,$smallTitle){
		
		if(strlen($title) > 256 || empty($title))
		{
			$this->er = TRUE;
			$this->erm = "Title empty or too large. [max] = 256 characters.";
			return FALSE;
		}
		if(strlen($smallTitle) > 100 || empty($smallTitle))
		{
			$this->er = TRUE;
			$this->erm = "Small title too or too large. [max] = 100 characters.";
			return FALSE;
		}
		return TRUE;
	}
	
	function processContent($content){
		
		if(strlen($content) < 20){
			$this->er = TRUE;
			$this->erm = "Content insufficient. Please add more description.";
			return FALSE;
		}
		
		if(strlen($content) > 29999){
			$newContent['content1'] = array_slice($content, 0,29999);
			$newContent['content2'] = array_slice($content, 30000,29999);
		}
		else{
			$newContent['content1'] = $content;
			$newContent['content2'] = NULL;
		}
		return $newContent;
	}
	
	function processKeywords(&$keywords) {
		
		$keywords = trim($keywords);
		if(empty($keywords)){
			$this->er = TRUE;
			$this->erm = "Please enter that keywords/tags for this article. All keywords must be separated by a comma.";
			return FALSE;
		}
		if(strlen($keywords) > 500){
			$this->er = TRUE;
			$this->erm = "Too many keywords.";
			return FALSE;
		}
		return TRUE;
	}

	function processDescription(&$description){
		
		$description = trim($description);
		if(empty($description)){
			$this->er = TRUE;
			$this->erm = "Please enter a few words about the article as a description.";
			return FALSE;
		}
		if(strlen($description) > 150){
			$this->er = TRUE;
			$this->erm = "Description too large. Max 150 characters.";
			return FALSE;
		}
		return TRUE;
	}
	
	function  saveUpdate($post, $newContent){
		
		if( !($stmt = $this->con->prepare("UPDATE article SET heading = ?, smallHeading = ?, content1 = ?,content2 =?, descript =?, kwords =?  WHERE ID = $this->article_id ") ) ){
			$this->er = TRUE;
			$this->erm = "DB Error.";
			return FALSE;
		}
    
		if(! $stmt->bind_param("ssssss",$post['title'],$post['smallTitle'],$newContent['content1'],$newContent['content2'], $post['description'], $post['keywords'])){
			$this->er = TRUE;
			$this->erm = "DB Error.";
			return FALSE;
		}
		if(! $stmt->execute()){
			$this->er = TRUE;
			$this->erm = "DB Error.";
			return FALSE;
		}
		return TRUE;
	}
			
	function updateArticle($post){
		if(!$this->processTitles($post['title'],$post['smallTitle']))
			return FALSE;
		$newContent = $this->processContent($post['content']);
		if($newContent == FALSE)
			return FALSE;
		
		if(!$this->processKeywords($post['keywords']))
			return FALSE;
		
		if(!$this->processDescription($post['description']))
			return FALSE;
		
		if(!$this->saveUpdate($post,$newContent))
			return FALSE;
		return TRUE;
	}
	
	function getImageDetails(){
		$res = $this->con->query("SELECT * FROM images WHERE articleID = $this->article_id");
		if($this->con->errno){
            $this->er=TRUE;
            $this->erm="Database error";
			return FALSE;
        }

		$row = $res->fetch_assoc();
		return $row['address'];
	}
}
?>