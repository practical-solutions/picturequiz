<?php
function create_picture_quiz($atts,$content=null){
	global $tagged_quiz_instances;
	$tagged_quiz_instances++;
	$t = $tagged_quiz_instances;
	
	$dir = "";
	if (isset($atts["dir"])) $dir = $atts["dir"];
	
	
	# Image
	preg_match('~{image}([^{]*){/image}~i', $content, $match);
	$image = $match[1];
	if ($image == null) return "No image declared.";
	
	preg_match('~{quelle}([^{]*){/quelle}~i', $content, $match);
	$quelle = $match[1];
	if ($quelle == null) {$quelle = "";}
	
	# Buttons
	$buttons = Array();
	do {
		preg_match('~{button}([^{]*){/button}~i', $content, $match);
		$temp = $match[1];
		$content = str_replace($match[0],'',$content);
		if ($temp != null) $buttons[] = $match[1];
	} while  ($temp!=null);
	
	# Button Text
	if (strpos($content,'{hide_button_text}') === false) {$hide_btn_txt=true;} else {$hide_btn_txt=false;}
	
	# Set min-width / overflow activated
	preg_match('~{min-width}([^{]*){/min-width}~i', $content, $match);
	$min_width = $match[1];
	if ($min_width != null) {$overflow=true;} else {$overflow=false;}
	
	# Attributes
	if (isset($atts['edit']) && $atts['edit']=="yes") {$edit=true;} else {$edit=false;}
	
	if ($edit) {
		
		$data = '<textarea style="width:100%;" id="code'.$t.'" onkeyup="javascript:setTags(\'code'.$t.'\');"></textarea>';
		}
	
	
	$data.= '
			<div class="tagged_image_container" style="'.($edit || $overflow? "overflow:auto":"").'">
				
				<canvas id="quizCanvas" style="'.
				($edit? "":"max-width:100%;").
				($min_width == null? "":"min-width:".$min_width.';').'
				"></canvas>
			</div>
			';
			
	
	//$data.= "<script src='".($atts["url"]).$dir."script.js'></script>";
	//$data.= '<script>'.file_get_contents($dir."script.js").'</script>';
	
	$data.= '
			<script>
				tagged_image_instance++;
     
				document.getElementById("quizCanvas").setAttribute("id", "quizCanvas"+tagged_image_instance);
				
				
				tagged_image_canvas.push(document.getElementById("quizCanvas"+tagged_image_instance));
				tagged_image_context.push(tagged_image_canvas[tagged_image_instance].getContext("2d"));
				tagged_image_pic.push(new Image());

				tagged_image_main.push(new tagged_img(tagged_image_canvas[tagged_image_instance], 		tagged_image_pic[tagged_image_instance]));
				tagged_image_main[tagged_image_instance].editMode = '.($edit? "true":"false").';
				tagged_image_main[tagged_image_instance].show_btn_caption = '.($hide_btn_txt? "true":"false").';
			';
				
				
	for ($c=0;$c<count($buttons);$c++) {
		$data.= 'tagged_image_main[tagged_image_instance].add_button('.($buttons[$c]).');';
	}
	
	$data.= '
				tagged_image_pic[tagged_image_instance].onload = function() {
					tagged_image_canvas['.$t.'].width = tagged_image_pic['.$t.'].width;
					tagged_image_canvas['.$t.'].height = tagged_image_pic['.$t.'].height;
					tagged_image_main['.$t.'].fullWidth = tagged_image_pic['.$t.'].width;
					
					tagged_image_main['.$t.'].paint();
				};

				tagged_image_canvas[tagged_image_instance].addEventListener("click", function(evt) {      
					if (tagged_image_main['.$t.'].editMode) writeMessage(tagged_image_canvas['.$t.'],evt,document.getElementById("code"+'.$t.'));
					tagged_image_main['.$t.'].click(evt,tagged_image_canvas['.$t.'].clientWidth);
				}, false);

				tagged_image_pic[tagged_image_instance].src="'.($atts["url"]).$dir."fetch.php?file=".$image.'";
				
				'.($quelle<>""? 'tagged_image_main[tagged_image_instance].addQuelle("'.$quelle.'");':"").'
			</script>
			';
			
	return $data;
}

# Übergabe der (Text-)Datei, geliefert werden die Dimensionen des Bildes
function getDimension($file,$dir=""){
	$content = file_get_contents($dir.$file);
	
	# Image
	preg_match('~{image}([^{]*){/image}~i', $content, $match);
	$image = $match[1];
	if ($image == null) return false;
	
	$d=getimagesize($dir.$image);
	return $d;
}
?>
