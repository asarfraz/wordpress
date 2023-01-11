<?php    
$strContent = file_get_contents("https://www.adeelsarfaraz.com/wp-json/wp/v2/posts?categories=56&per_page=2");
$obj = json_decode($strContent);
if (count($obj) > 0) {  
	 for($leftLoop=0; $leftLoop<count($obj); $leftLoop++) { 
		echo "Title = " . $obj[$leftLoop]->title->rendered . "<br>";
		echo "Link = " . $obj[$leftLoop]->link . "<br>";
		echo "Excerpt = " . $obj[$leftLoop]->excerpt->rendered . "<br><br>";
	} 
}           
