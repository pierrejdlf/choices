<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Untitled Document</title>
<style type="text/css">
<!--
body,td,th {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 11px;
	color: #FFFFFF;
}
body {
	background-color: #999999;
}
-->
</style></head>

<body>
<p>&nbsp;</p>
<p>&nbsp;</p>
<div align="center">veuillez vous référer au manuel d'utilisation de la page d'aide de la rubrique<br>du site contenant la plupart des informations : <a href="http://jdlf.info/p">http://jdlf.info/p</a>
<br> 
<br> 
<?php

echo echoSubDirsLinks('./');

function echoSubDirsLinks($dir) {
	$ret="";
	$files = scandir($dir);
	for($u=0;$u<count($files);$u++) {
		if($files[$u]!=".." && $files[$u]!="." && is_dir($files[$u])) {
			$res = '| <a href='.$files[$u].' >'.$files[$u].'</a> |' ;
			//if(strlen($res)>5) $res= ' | '.$res ;			
			$ret.= $res;
		}
	}
	return $ret ;
}

?>
<br>
<br>
et puis merci, aussi.
</div>
</body>
</html>
