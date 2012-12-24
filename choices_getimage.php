<?
$dbserver="---";
$dbuser="---";
$dbpass="---";
$dbname="---";

$dbconn = @mysql_connect($dbserver,$dbuser,$dbpass) or exit("SERVER Unavailable"); 
@mysql_select_db($dbname,$dbconn) or exit("DB Unavailable"); 

$sql = "SELECT imgtype,imgdata FROM choices_etapes WHERE id = ". $_GET["id"]; 


$result = @mysql_query($sql,$dbconn) or exit("QUERY FAILED!"); 
mysql_close($dbconn);
$contenttype = @mysql_result($result,0,"imgtype"); 
$image = @mysql_result($result,0,"imgdata"); 

header("Content-type: $contenttype"); 
echo $image; 
?>