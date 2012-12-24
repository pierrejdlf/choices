<?
echo '<link href="utils/style.css" rel="stylesheet" type="text/css">';
global $gotid;
$gotid=$_GET['p'] ;
if($gotid==0) $gotid=getRandomId();
?>

<script type="text/javascript" src="js/swfobject.js"></script>
<script type="text/javascript">
swfobject.embedSWF(
  "open-flash-chart.swf", "my_chart", "270", "80",
  "9.0.0", "expressInstall.swf",
  {"data-file":"choices_getjson.php?id=<? echo $gotid; ?>"}
  );

function stack_on_click( id, index ){
	//alert( 'GOTO:'+id);
	id=id+138;
	document.location.href="?p="+id;
}
</script>

<body> 
<?
function echoMenu() {
	global $gotid;
	$res= '<a href="choices_addetape.php">CREATE</a> | ' ;
	$res.='<a href="choices_addetape.php?m='.$gotid.'">EDIT</a> | ' ;
	$res.='<a href="choices_seeall.php">ALL</a> | ' ;
	$res.='<a href="choices_seeall.php?t=begin">STARTERS</a> | ' ;
	$res.='<a href="index.php">RANDOM</a>' ;
	$res.='<br>' ;
	return $res;
}

echo '<br><br>';
echoEtape($gotid) ;

function getRandomId() {
	$dbserver="---";
	$dbuser="---";
	$dbpass="---";
	$dbname="---";
	$dbconn = @mysql_connect($dbserver,$dbuser,$dbpass) or exit("SERVER Unavailable"); 
	@mysql_select_db($dbname,$dbconn) or exit("DB Unavailable"); 
	$sql = "SELECT id FROM choices_etapes ORDER BY Rand()"; 
	$result = @mysql_query($sql,$dbconn) or exit("QUERY FAILED!");	
	while ($rs=mysql_fetch_array($result)) {
		$res=$rs[0];
	}
	mysql_close($dbconn);
	return $res;
}
	
function echoEtape($id) {
	$dbserver="---";
	$dbuser="---";
	$dbpass="---";
	$dbname="---";
	$dbconn = @mysql_connect($dbserver,$dbuser,$dbpass) or exit("SERVER Unavailable"); 
	@mysql_select_db($dbname,$dbconn) or exit("DB Unavailable"); 
	
	$sql = 'SELECT id,imgtype,title,author,content,nextid,nexttext FROM choices_etapes WHERE id="'.$id.'"'; 
	
	$result = @mysql_query($sql,$dbconn) or exit("QUERY FAILED!"); 
	
	while ($rs=mysql_fetch_array($result)) {
		$theMenu=echoMenu();
		$theContent=str_replace("\n", '<br>', $rs[4]);
		$theLinksList="";
	  $lA=unserialize($rs[5]); //id
      $lB=unserialize($rs[6]); //text
	    for($i=0;$i<sizeof($lA);$i=$i+1) {
	    	if($lA[$i]==0)
	    		$theLinksList.='> '.$lB[$i].' <a href="choices_addetape.php?m='.$id.'">[edit target]</a><br>' ;
	    	else
	    		$theLinksList.='> <a href="?p='.$lA[$i].'">'.$lB[$i].' ('.$lA[$i].')</a><br>' ;
	    }
   	 
		echo '
		<table class="tindex" width="270" align="center">
  <tr>
    <td><div align="center">'.$theMenu.'</div></td>
  </tr>
  <tr>
    <td valign="top" height="280">'.$rs[0].'. '.$rs[2].'<br><br>
    <img width=270 height=180 src="choices_getimage.php?id='.$rs[0].'">
    	<br><br>'.$theContent.'
    	</td>
  </tr>
  <tr>
    <td align=left>'.$theLinksList.'</td>
  </tr>
  <tr>
    <td align=center><div id="my_chart"></div></td>
  </tr>
</table>
';
	}; 
	mysql_close($dbconn);
}
?>
</body>
