<?php
/**
 * ID-Show
 *
 * @license    MIT
 * @author     Gero Gothe <practical@medizin-lernen.de>
 */


# must be run within Dokuwiki
if(!defined('DOKU_INC')) die();


class action_plugin_idshow extends DokuWiki_Action_Plugin {

    public function register(Doku_Event_Handler $controller) {
        $controller->register_hook('TPL_CONTENT_DISPLAY', 'BEFORE', $this, 'showid');
    }

    function showid(Doku_Event $event, $param) {
        global $ID;
        echo "<div class='plugin__idshow'>";
        echo "Page-ID: <span>$ID</span>";
        echo "</div>";
    }

}

//Setup VIM: ex: et ts=4 enc=utf-8 :
