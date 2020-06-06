<?php

// must be run within Dokuwiki
if(!defined('DOKU_INC')) die();

/**
 * medcalc
 *
 * @license  MIT
 * @author   Gero Gothe
 */
class syntax_plugin_picturequiz extends DokuWiki_Syntax_Plugin {

    
    public function getType() {
        return 'substition';
    }

    /**
     * Paragraph Type
     */
    public function getPType() {
        return 'block';
    }

    

    /**
     * @param string $mode
     */
    public function connectTo($mode) {
        #$this->Lexer->addSpecialPattern('<picturequiz>*<\/picturequiz>', $mode, 'plugin_picturequiz');
        $this->Lexer->addSpecialPattern('<picturequiz>.*?</picturequiz>', $mode, 'plugin_picturequiz');
    }

    /**
     * Handler to prepare matched data for the rendering process
     *
    */
    public function handle($match, $state, $pos, Doku_Handler $handler) {
        
        
        
        
        return trim($match);
    }
    
    
    

    /**
     * Create the new-page form.
     *
     * @param   $mode     string        output format being rendered
     * @param   $renderer Doku_Renderer the current renderer object
     * @param   $data     array         data created by handler()
     * @return  boolean                 rendered correctly?
     */
    public function render($mode, Doku_Renderer $renderer, $data) {
        global $lang;
        
        if($mode == 'xhtml') {
            
            
            
            
            
            #----------------------------------------
            
            # Necessary for multiple instances on one page
            global $tagged_quiz_instances;
            $tagged_quiz_instances++;
            
            $t = $tagged_quiz_instances-1;
            
            $lines = explode("\n",$data);
            
            $image = '';
            $buttons = Array();
            
            foreach ($lines as $l) {
            
                # Image
                if (strpos($l,"image:")===0) {
                    $l = trim(substr($l,6));
                    $image .= DOKU_URL . "lib/exe/fetch.php?media=$l";
                }
                
                # Buttons
                if (strpos($l,"button:")===0) {
                    $l = trim(substr($l,7));
                    $buttons[] = $l;
                }
            
            }
            

            # Create code
            
            $data = '
                     <div class="tagged_image_container" style="'.($edit || $overflow? "overflow:auto":"").'">
                        <canvas id="quizCanvas" style="'. ($edit? "":"max-width:100%;") . ($min_width == null? "":"min-width:".$min_width.';').'">
                        </canvas>
                     </div>
                    ';
            
            if ($t==0) $data .= "<script>" . (file_get_contents(DOKU_PLUGIN."/picturequiz/picture.js")) . "</script>";
            
            
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

				tagged_image_pic[tagged_image_instance].src="'.$image.'";
				
				'.($quelle<>""? 'tagged_image_main[tagged_image_instance].addQuelle("'.$quelle.'");':"").'
			</script>';
            
            
            $renderer->doc .= "$data";
            
            #----------------------------------------
            
            return true;
        }

        return false;
    }
}
