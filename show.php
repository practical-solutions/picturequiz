<?php
include_once "functions.php";

$dim = getDimension("$pic","picturequiz/bilder/");
if ($edit == "yes") {$dim[0]+= 20;$dim[1]+= 60;}

echo '<iframe src="http://frauenheilkunde.medizin-lernen.de/picquiz.php?file='.$pic.'&edit='.$edit.'" style="border:0px;width:100%;height:'.($dim[1]+20).'px" style="overflow:hidden"></iframe>';

?>
