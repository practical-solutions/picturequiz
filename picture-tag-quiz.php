<?php
/**
 * Plugin Name: Tagged-Image and Tables Quiz
 * Description: Click on Pictures/Tables in order to show labels
 * Version: 1.0
 * Author: Gero Gothe
 * License: GPL2
 */
 
/*  Copyright 2015  Gero Gothe  (email : gero.gothe@yahoo.de)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

function tagged_img_quiz_scripts(){
	wp_enqueue_script('tagged-picture-quiz',plugins_url( 'script.js' , __FILE__ ) );
	#wp_register_style( 'learning-system-style', plugins_url('style.css', __FILE__) );
	#wp_enqueue_style( 'learning-system-style' );
}

$tagged_quiz_instances=-1;

include_once "functions.php";

add_action( 'wp_enqueue_scripts', 'tagged_img_quiz_scripts' );
add_shortcode("picture_quiz", "create_picture_quiz");

?>
