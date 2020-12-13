<?php

/**
 * PictureQuiz
 *
 * @license  GPL2
 * @author   Gero Gothe
 */
 
// must be run within Dokuwiki
if(!defined('DOKU_INC')) die();

class syntax_plugin_picturequiz extends DokuWiki_Syntax_Plugin {

    
    function getSort(){
        return 300;
    }
    
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
        $this->Lexer->addSpecialPattern('<picturequiz>.*?</picturequiz>', $mode, 'plugin_picturequiz');
    }

    /**
     * Handler to prepare matched data for the rendering process
    */
    public function handle($match, $state, $pos, Doku_Handler $handler) {
        return trim($match);
    }

    /**
     * @param   $mode     string        output format being rendered
     * @param   $renderer Doku_Renderer the current renderer object
     * @param   $data     array         data created by handler()
     * @return  boolean                 rendered correctly?
     */
    public function render($mode, Doku_Renderer $renderer, $data) {
        
        if($mode == 'xhtml') {

            # Necessary for multiple instances on one page
            global $tagged_quiz_instances;
            $tagged_quiz_instances++;
            
            $t = $tagged_quiz_instances-1; # this instance nr (important!)
            
            $lines = explode("\n",$data); # the data is checked line by line

            # The variables to check
            $image        = '';            # the image
            $buttons      = Array();       # the buttons
            $edit         = false;         # edit mode?
            $hide_btn_txt = false;
            $min_width    = null;          # smallest width to which the image is scaled, before overflow is activated
            $overflow     = false;
            
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
                
                # Edit mode?
                if (strpos($l,"edit_mode")===0) $edit = true;
                
                # Hide Button Text
                if (strpos($l,"hide_button_text")===0) $hide_btn_txt = true;
                
                # Minimum image width
                if (strpos($l,"min_width:")===0) {
                    $i = intval(substr($l,10));
                    if ($i>0) $min_width = $i;$overflow=true;
                }
            
            }
            

            # Include basic javascript object definition once when creating the first instance
            if ($t==0) $renderer->doc .= "<script>" . (file_get_contents(DOKU_PLUGIN."/picturequiz/picture.js")) . "</script>";
            
            # Buttons
            for ($c=0;$c<count($buttons);$c++) {
                $btn_code .= 'tagged_image_main[tagged_image_instance].add_button('.($buttons[$c]).");\n    ";
            }

            $replacements = Array (
                                    '%OVERFLOW%' => ($edit || $overflow? "overflow:auto":""),
                                    '%MAXWIDTH%' => ($edit? "":"max-width:100%;"),
                                    '%MINWIDTH%' => ($min_width == null? "":"min-width:".$min_width.'px;'),
                                    '%EDITMODE%' => ($edit? "true":"false"),
                                    '%EDITDISP%' => ($edit? "":"display:none;"),
                                    '%HIDEBTXT%' => ($hide_btn_txt? "false":"true"),
                                    '%NR%'       => $t,
                                    '%BUTTONS%'  => $btn_code,
                                    '%IMAGE%'    => $image
                                   );

            $inc = file_get_contents(DOKU_PLUGIN."/picturequiz/basic.html");
            
            foreach ($replacements as $key => $value) {
                $inc = str_replace($key,$value,$inc);
            }

            $renderer->doc .= $inc;
            
            return true;
        }

        return false;
    }
}
