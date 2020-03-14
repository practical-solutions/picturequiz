<?php
$tagged_quiz_instances=-1;

$url = "http://frauenheilkunde.medizin-lernen.de/";


$atts = array(
			  "dir"=>"picturequiz/",
			  "url"=>$url,
			  "edit"=>$_GET['edit'],
			  "id"=>$_GET['id']
			  );

include $atts["dir"].'functions.php';


$content = file_get_contents($atts["dir"].'bilder/'.$_GET["file"]);

$data = create_picture_quiz($atts, $content);

echo $data;
?>
