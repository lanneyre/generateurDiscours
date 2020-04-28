<?php 
/**
 * @package Genrateur Discours
 * @version 0.0.1
*/
/*
Plugin Name: Générateur de discours
Description: Un générateur de discours en mode langue de bois
Author: Lanneyre
Version: 0.0.1
*/


include_once plugin_dir_path(__FILE__)."/class/Discours.php";

register_activation_hook(__FILE__, array('Discours', 'install'));
register_uninstall_hook(__FILE__, array('Discours', 'uninstall'));

add_action('init', array('Discours', 'createDiscours'));