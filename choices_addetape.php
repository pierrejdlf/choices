<?
session_start();
echo '<link href="utils/style.css" rel="stylesheet" type="text/css">';
echo '<script type="text/javascript" src="utils/utils.js"> </script>';

global $eMode,$dbserver,$dbuser,$dbpass,$dbname,$enextid,$enexttext,$etype;

$dbserver="---";
$dbuser="---";
$dbpass="---";
$dbname="---";

$eMode=$_GET['m'];


if (!isset($_REQUEST["submit"])) {
	echo '<form method="POST" action="?=$_SERVER[\'PHP_SELF\']" enctype="multipart/form-data">';
	
	echo '<p align="center"><b>';
	if($eMode==0) echo 'CREATING';
	else echo 'EDITING ('.$eMode.')';
	echo '</b><br><br>';

	$dbconn = @mysql_connect($dbserver,$dbuser,$dbpass) or exit("SERVER Unavailable"); 
	@mysql_select_db($dbname,$dbconn) or exit("DB Unavailable");
	if($eMode!=0) { // GET values of existing
		$sql = 'SELECT title,author,content,nextid,nexttext,type FROM choices_etapes WHERE id = '.$eMode;
		$result = @mysql_query($sql,$dbconn) or exit("QUERY FAILED!");  
		while ($rs=mysql_fetch_array($result)) {
			$etitle=$rs[0];
			$eauthor=$rs[1];
			$econtent=$rs[2];
			$enextid=unserialize($rs[3]);
			$enexttext=unserialize($rs[4]);
			$etype=$rs[5];
		}
	}
	mysql_close($dbconn);

$linkForms=echoLinkForms();
echo '<table class="tindex">
  <tr>
    <td>Type (10)</td>
    <td><input type="text" name="type" size="10" maxlength="10" value="'.$etype.'"></td>
  </tr>
  <tr>
    <td>Author (10)</td>
    <td><input type="text" name="author" size="10" maxlength="10" value="'.$eauthor.'"></td>
  </tr>
  <tr>
    <td>Title (27)</td>
    <td><input type="text" name="title" size="20" maxlength="27" value="'.$etitle.'"></td>
  </tr>
  <tr>
    <td>Image (240x180)</td>
    <td><input type="file" name="imgfile"></td>
  </tr>
  <tr>
    <td>Story (30)</td>
    <td><textarea id="txtarea" cols="40" rows="10" name="content" onKeyDown="limitTextarea(this.form.content,this.form.remLen,30);" onKeyUp="limitTextarea(this.form.content,this.form.remLen,30);">'.$econtent.'</textarea><input readonly type="text" name="remLen" size="3" maxlength="3" value="30"></td>
  </tr>
  <tr>
    <td>Follows (27)</td>
    <td>'.$linkForms.'</td>
  </tr>
</table>
'; 

//echo '
//<br><br><br>
//<img src="captcha/CaptchaSecurityImages.php?width=100&height=40&characters=5" alt="captcha" />
//Security Code: 
//<input id="security_code" name="security_code" type="text" size="7"/>';
echo '<br>';
//echo '<input value="Cancel" name="btn1" type=button onClick="goToIndex();">';
echo '<input type="submit" name="submit" value="Save">';
echo '</form>';
echo '</p>';

$_SESSION['eMode']=$eMode;

} else {
   if(($_SESSION['security_code'] == $_POST['security_code']) && (!empty($_SESSION['security_code'])) ) {
      processUpload() ;
      unset($_SESSION['security_code']);
   } else {
      //echo 'BIG CAPTCHA PROBLEM' ;
      processUpload() ;
   }
}
/////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////
function echoLinkForms() {
	global $eMode,$dbserver,$dbuser,$dbpass,$dbname,$enextid,$enexttext;
	$RES="";
	// Getting all existing ETAPES (no selected)
	$dbconn = @mysql_connect($dbserver,$dbuser,$dbpass) or exit("SERVER Unavailable"); 
	@mysql_select_db($dbname,$dbconn) or exit("DB Unavailable");
	$sql = "SELECT id,title FROM choices_etapes ORDER BY id"; 
	$result = @mysql_query($sql,$dbconn) or exit("QUERY FAILED!"); 
	$theValuesCode='<option value="0">0. NOT LINKED YET</option>';
	while ($rs=mysql_fetch_array($result)) { 
		$theid=$rs[0];
		$thetitle=$rs[1];
		if($theid!=$eMode)
			$theValuesCode.='<option value="'.$theid.'">'.$theid.'. '.$thetitle.'</option>';	
	}
	for($i=0;$i<3;$i=$i+1) { // EACH POSSIBLE LINK
		if( $eMode!=0 && $i<sizeof($enextid) ) { // EDITING and row<maxlinks
			$specialValuesCode='<option value="0">0. NOT LINKED YET</option><br>';
			$RES.='<input type="text" name="linktxt'.$i.'" maxlength="27" value="'.$enexttext[$i].'">';
			$RES.='<select id="sellink"'.$i.' name="linksel'.$i.'">';
			$sql = 'SELECT id,title FROM choices_etapes ORDER BY id'; 
			$result = @mysql_query($sql,$dbconn) or exit("QUERY FAILED!"); 
			while ($rs=mysql_fetch_array($result)) {
				$theid=$rs[0];
				if($theid!=$eMode) { // no link possible to self
					$thetitle=$rs[1];
					if($theid==$enextid[$i])
						$specialValuesCode.='<option value="'.$theid.'" selected="selected">'.$theid.'. '.$thetitle.'</option>';
					else
						$specialValuesCode.='<option value="'.$theid.'">'.$theid.'. '.$thetitle.'</option>';
				}
			}
			$RES.=$specialValuesCode;
			$RES.='</select><br>';
		} else { // CREATING
			$RES.='<input type="text" name="linktxt'.$i.'" maxlength="27">';
			$RES.='<select id="sellink"'.$i.' name="linksel'.$i.'">';	
			$RES.=$theValuesCode;
			$RES.='</select><br>';
		}	
	}
	mysql_close($dbconn);
	return $RES ;
}
/////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////
function processUpload() {
	global $dbserver,$dbuser,$dbpass,$dbname;
	
	// faire toutes les verifs!!!
	$imgdata=file_get_contents($_FILES['imgfile']['tmp_name']); 
	$imgdata=addslashes($imgdata);
	$imgtype=$_FILES['imgfile']['type'];
	$title=$_POST['title'] ;
	$author=$_POST['author'] ;
	$content=$_POST['content'] ;
	$type=$_POST['type'] ;
	$theLinks=array() ;
	$nl=0;
	for($i=0;$i<3;$i=$i+1) {
		$theLinkText=$_POST['linktxt'.$i] ; 
		if($theLinkText != "") {
			$theLinksT[$nl]=$theLinkText ;
			$theLinksI[$nl]=$_POST['linksel'.$i] ;
			//echo '<br>'.$theLinksT[$nl].' - '.$theLinksI[$nl];
			$nl+=1;
		}
	}
	$mqlinkedI=serialize($theLinksI); //takes the data from a post operation...
	$mqlinkedT=serialize($theLinksT);
	
	$dbconn = @mysql_connect($dbserver,$dbuser,$dbpass) or exit("SERVER Unavailable"); 
	@mysql_select_db($dbname,$dbconn) or exit("DB Unavailable"); 
	  
	$eMode=$_SESSION['eMode'];
	if($eMode==0) { // CREATION MODE
	  	$sql='INSERT INTO choices_etapes (id) VALUES(NULL)';
	  	@mysql_query($sql,$dbconn) or exit("QUERY FAILED!"); 
	  	$lid=mysql_insert_id();
	  	//echo 'CREATION OK<br>';
	}
	else { // EDIT MODE
		$lid=$eMode;
		//echo 'EDITION OK<br>';
	}
	
	//echo 'THE ID : '.$lid.'  ';
	if($_FILES['imgfile']['size']!=0) {
		  $sql='UPDATE choices_etapes SET imgtype = "'.$imgtype.'" WHERE id = '.$lid;
		  @mysql_query($sql,$dbconn) or exit("QUERY FAILED!"); 
		  $sql='UPDATE choices_etapes SET imgdata = "'.$imgdata.'" WHERE id = '.$lid;
		  @mysql_query($sql,$dbconn) or exit("QUERY FAILED!");
	}
	//else echo 'filesize=0!<br>';
	  
	  $sql='UPDATE choices_etapes SET type = "'.$type.'" WHERE id = '.$lid;
	  @mysql_query($sql,$dbconn) or exit("QUERY FAILED!"); 
	  $sql='UPDATE choices_etapes SET title = "'.$title.'" WHERE id = '.$lid;
	  @mysql_query($sql,$dbconn) or exit("QUERY FAILED!"); 
	  $sql='UPDATE choices_etapes SET author = "'.$author.'" WHERE id = '.$lid; 
	  @mysql_query($sql,$dbconn) or exit("QUERY FAILED!"); 
	  $sql='UPDATE choices_etapes SET content = "'.$content.'" WHERE id = '.$lid;
	  @mysql_query($sql,$dbconn) or exit("QUERY FAILED!");
	  
	  $sql='UPDATE choices_etapes SET nextid = \''.$mqlinkedI.'\' WHERE id = '.$lid;
	  @mysql_query($sql,$dbconn) or exit("QUERY FAILED!"); 
	  $sql='UPDATE choices_etapes SET nexttext = \''.$mqlinkedT.'\' WHERE id = '.$lid;
	  @mysql_query($sql,$dbconn) or exit("QUERY FAILED!"); 
  	
	
  //echo "QUE:".$sql;
  mysql_close($dbconn); 

  //echo '<a href="index.php">index</a>';
  echo '<meta http-equiv="Refresh" content="0;url=index.php?p='.$lid.'">';

}
?> 
