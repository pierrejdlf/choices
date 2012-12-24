<?
echo '<link href="utils/style.css" rel="stylesheet" type="text/css">';
if (!isset($_REQUEST["submit"])) { 
?>
<form method="POST" action="<?=$_SERVER['PHP_SELF']?>" enctype="multipart/form-data"> 
Title
<input type="text" name="title" size="10">
Image
<input type="file" name="imgfile">
<br>
<textarea cols="40" rows="15" name="content"></textarea>
<br>
<input type="submit" name="submit" value="upload">
</form>
<? 
} else {
	// faire toutes les verifs!!!
$imgdata = file_get_contents($_FILES['imgfile']['tmp_name']); 
$imgdata=addslashes($imgdata); 
$title=$_POST['title'] ;
$content=$_POST['content'] ;

$dbserver="---";
$dbuser="---";
$dbpass="---";
$dbname="---";

  $dbconn = @mysql_connect($dbserver,$dbuser,$dbpass) or exit("SERVER Unavailable"); 
  @mysql_select_db($dbname,$dbconn) or exit("DB Unavailable"); 
  
  $sql = 'INSERT INTO choices_etapes VALUES(NULL,"'.$_FILES['imgfile']['type'].'","'.$imgdata.'","'.$title.'","'.$content.'")';
  
  @mysql_query($sql,$dbconn) or exit("QUERY FAILED!"); 
  mysql_close($dbconn); 

  echo '<a href="index.php">Success</a>';
  
}; 
?> 