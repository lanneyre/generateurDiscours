<?php 
/**
 * @package Generateur Discours
 * @version 0.0.1
*/
/*
Plugin Name: Générateur de discours
Description: Un générateur de discours en mode langue de bois
Author: Lanneyre
Version: 0.0.1
*/

// J'appelle ma class Discours
include_once plugin_dir_path(__FILE__)."/class/Discours.php";

// J'enregistre le crochet d'activation du module. Autrement dit lorsque j'activerai mon module c'est la fonction install de la class Discours qui s'executera
register_activation_hook(__FILE__, array('Discours', 'install'));
// J'enregistre le crochet de suppression du module. Autrement dit lorsque je supprimerai mon module c'est la fonction uninstall de la class Discours qui s'executera
// En revanche si je ne fais que le désactiver il ne se passera rien pour ça il faudra utiliser la seconde ligne que j'ai commenté
register_uninstall_hook(__FILE__, array('Discours', 'uninstall'));
//register_deactivation_hook(__FILE__, array('Discours', 'uninstall'));

// J'execute la fonction createDiscours de la class Discours au chargement de toute les pages
add_action('init', array('Discours', 'createDiscours'));