<?
echo '<link href="utils/style.css" rel="stylesheet" type="text/css">';

$type=$_GET['t'] ;
	
$dbserver="---";
$dbuser="---";
$dbpass="---";
$dbname="---";

$dbconn = @mysql_connect($dbserver,$dbuser,$dbpass) or exit("SERVER Unavailable"); 
@mysql_select_db($dbname,$dbconn) or exit("DB Unavailable"); 

$sql = "SELECT id,date,title,author,content,nextid,nexttext,type FROM choices_etapes ORDER BY id"; 

$result = @mysql_query($sql,$dbconn) or exit("QUERY FAILED!"); 
mysql_close($dbconn); 

echo '<table align="center" class="tindex">'; 
//echo "<tr><th>imgid</th><th>imgtype</th><th>imgdata</th></tr>\n"; 
while ($rs=mysql_fetch_array($result)) {
	$thetype=$rs[7];
	if($type=="" || ($type!="" && $thetype==$type)) {
	$idlink='<a href="index.php?p='.$rs[0].'">'.$rs[0].'</a>';
  echo "<tr><td>".$idlink."</td>"; 
  echo '<td><img width=64 height=48 src="choices_getimage.php?id='.$rs[0].'"></td>';
  echo "<td width=30>".$rs[1]."</td>";
  echo "<td>".$rs[3]."</td>";
  echo "<td>".$rs[7]."</td>";
  echo "<td>".$rs[2]."</td>";
  echo "<td width=270>".$rs[4]."</td>";
    
    $lA=unserialize($rs[5]);
    $lB=unserialize($rs[6]);
    $list="";
    for($i=0;$i<sizeof($lA);$i=$i+1) {
    	//$idlink='<a href="index.php?p='.$lA[$i].'">('.$lA[$i].')</a>';
    	$list.='('.$lA[$i].') '.$lB[$i].'<br>' ;
    }
    echo "<td>".$list."</td>";
  echo '</tr>';
	}
}; 
echo "</table>"; 


?> 