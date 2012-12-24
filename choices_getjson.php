<?php
$currentId=$_GET['id'] ;

include_once './php-ofc-library/open-flash-chart.php';
$bar_stack = new bar_stack();
// set a cycle of 2 colours:
$bar_stack->set_colours( array('#50284A', '#C4D318' ) );

////////////////////// DATABASE THINGS
$dbserver="---";
$dbuser="---";
$dbpass="---";
$dbname="---";
$dbconn = @mysql_connect($dbserver,$dbuser,$dbpass) or exit("SERVER Unavailable"); 
@mysql_select_db($dbname,$dbconn) or exit("DB Unavailable"); 
$sql = "SELECT id,date,title,author,content,nextid,nexttext,type FROM choices_etapes ORDER BY id"; 
$result = @mysql_query($sql,$dbconn) or exit("QUERY FAILED!"); 
mysql_close($dbconn); 

while ($rs=mysql_fetch_array($result)) {
	$thetype=$rs[7];
	if($type=="" || ($type!="" && $thetype==$type)) {
		$idlink='<a href="index.php?p='.$rs[0].'">'.$rs[0].'</a>';
 		//echo $idlink ;
 		//echo $rs[1]."</td>";
 		//echo $rs[3];
  		//echo $rs[7];
  		//echo $rs[2];
  		//echo $rs[4];
		// Longueur du texte
		$lenn=0.5+strlen($rs[4])/100;
   		$lA=unserialize($rs[5]);
    	$lB=unserialize($rs[6]);
    	$list="";
		$outt=0;
    	for($i=0;$i<sizeof($lA);$i=$i+1) {
    		//$idlink='<a href="index.php?p='.$lA[$i].'">('.$lA[$i].')</a>';
    		$list.='('.$lA[$i].') '.$lB[$i].'<br>' ;
			$outt=$outt+0.5 ;
    	}
    	//echo $list;
		$recaledId=$rs[0];
		if($recaledId==$currentId) {
			//echo "YYYYYYYYYYYYYYYYYYYYY";
			$bar_stack->append_stack( array($outt, new bar_stack_value($lenn, '#ff0000')) );
			}
		else {
			$bar_stack->append_stack( array($outt,$lenn) );
		}
	}
}

/*
$bar_stack->set_keys(
    array(
        new bar_stack_key( '#C4D318', 'Kiting', 13 ),
        new bar_stack_key( '#50284A', 'Work', 13 ),
        new bar_stack_key( '#7D7B6A', 'Drinking', 13 ),
        new bar_stack_key( '#ff0000', 'XXX', 13 ),
        new bar_stack_key( '#ff00ff', 'What rhymes with purple? Nurple?', 13 ),
        )
    );
*/
//$bar_stack->set_tooltip( 'page [#x_label#]<br>(#val# caractères)' );
$bar_stack->set_on_click('stack_on_click');
// FLASH CHART PART
$y = new y_axis();
$y->set_range( 0, 5, 2 );
$x = new x_axis();
$x->set_steps( 5 );
//$tooltip = new tooltip();
//$tooltip->set_hover();
$chart = new open_flash_chart();
$chart->add_element( $bar_stack );
$chart->set_x_axis( $x );
$chart->add_y_axis( $y );
//$chart->set_tooltip( $tooltip );
$chart->set_bg_colour( '#FFFFFF' );
echo $chart->toString();
?>
