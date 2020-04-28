<?php 
/**
 * @package Generateur Discours
 * @version 0.0.1
*/
/**
 * 
 */
class Discours 
{
	// Fonction appelée lorsqu'on active le module 	
	static function install(){
		// J'appelle ici la variable qui permet de faire le lien avec la bdd
		global $wpdb;

		// la variable $phrases contient les segments de phrases pour en créer une aléatoirement qui a est grammaticalement juste mais vide de sens
		$phrases = [
			"segment1" => [
		"Mesdames, Messieurs,",
		"Je reste fondamentalement persuadé que",
		"Dès lors, sachez que je me battrai pour faire admettre que",
		"Par ailleurs, c'est en toute connaissance de cause que je peux affirmer que",
		"Je tiens à vous dire ici ma détermination sans faille pour clamer haut et fort que",
		"J'ai depuis longtemps (ai-je besoin de vous le rappeler ?) défendu l'idée que",
		"Et c'est en toute conscience que je déclare avec conviction que",
		"Et ce n'est certainement pas vous, mes chers compatriotes, qui me contredirez si je vous dis que"
		], "segment2" => [
		"la conjoncture actuelle",
		"la situation d'exclusion que certains d'entre vous connaissent",
		"l'acuité des problèmes de la vie quotidienne",
		"la volonté farouche de sortir notre pays de la crise",
		"l'effort prioritaire en faveur du statut précaire des exclus",
		"le particularisme dû à notre histoire unique",
		"l'aspiration plus que légitime de chacun au progrès social",
		"la nécessité de répondre à votre inquiétude journalière, que vous soyez jeunes ou âgés,"
		], "segment3" => [
		"doit s'intégrer à la finalisation globale",
		"oblige à la prise en compte encore plus effective",
		"interpelle le citoyen que je suis et nous oblige tous à aller de l'avant dans la voie",
		"a pour conséquence obligatoire l'urgente nécessité",
		"conforte mon désir incontestable d'aller dans le sens",
		"doit nous amener au choix réellement impératif",
		"doit prendre en compte les préoccupations de la population de base dans l'élaboration",
		"entraîne une mission somme toute des plus exaltantes pour moi : l'élaboration"
		], "segment4" => [
		"d'un processus allant vers plus d'égalité.",
		"d'un avenir s'orientant vers plus de progrès et plus de justice.",
		"d'une restructuration dans laquelle chacun pourra enfin retrouver sa dignité.",
		"d'une valorisation sans concession de nos caractères spécifiques.",
		"d'un plan correspondant véritablement aux exigences légitimes de chacun.",
		"de solutions rapides correspondant aux grands axes sociaux prioritaires.",
		"d'un programme plus humain, plus fraternel et plus juste.",
		"d'un projet porteur de véritables espoirs, notamment pour les plus démunis."
		]
		];

		// Je créé la table phrases contenant les phrases ci-dessus pour pouvoir les réutiliser plus tard
		// {$wpdb->prefix} permet de récupérer le préfix de la bdd histoire que ce module soit transposable à tous les wp
		$wpdb->query("CREATE TABLE `{$wpdb->prefix}phrases` ( `id` INT NOT NULL AUTO_INCREMENT ,  `segment` VARCHAR(100) NOT NULL , `text` TEXT NOT NULL , PRIMARY KEY (`id`));");

		// Je créé la table discours qui sauvegardera les discours générés et leur date de création
		$wpdb->query("CREATE TABLE `{$wpdb->prefix}discours` ( `id` INT NOT NULL AUTO_INCREMENT , `date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP, `text` TEXT NOT NULL , PRIMARY KEY (`id`));");

		// La variable $phrases est un tableau associatif dont les clés sont les noms des segments et les valeurs associées sont des tableaux contenants les fameux bouts de phrases
    	foreach ($phrases as $key => $value) {
    		# code...
    		// Pour chaque segment 
    		foreach ($value as $phrase) {
    			# code...
    			// j'insert dans la table phrase les valeurs dont j'ai besoin
    			$wpdb->insert("{$wpdb->prefix}phrases", 
    				array( 
				        'segment' => $key,  
				        'text' => $phrase 
				    ), 
				    array( 
					    '%s', 			// %s Pour les chaines de caractère String
					    '%s' 			// %d Pour les valeurs numériques de type Integer
					    				// %f Pour les valeurs numériques de type Float
					)  
    			);
    		}
    	}
	}


	// function qui est appelé lors de la désactivation du module 
	static function uninstall(){
		global $wpdb;
	    $wpdb->query("DROP TABLE `{$wpdb->prefix}phrases`, `{$wpdb->prefix}discours`;");
	}

	// Fonction qui génére une phrase à partir des segments stockés dans la table phrase
	static function generatePhrase(){
		global $wpdb;
		// Je part du principe que j'ignore le nombre de segment différent
		// Donc je récupère tous les segments de la bdd
		// Dans la variable $Phrases il n'y en a que 4
		$segments = $wpdb->get_results("SELECT DISTINCT(segment) FROM `{$wpdb->prefix}phrases` ORDER BY segment ASC;");
		// variable qui contiendra la phrase générée
		$phrase = "";
		// get_results renvoi soit un tableau de données soit faux et comme php considère que tableau non-vide est une condition vraie, cela test si j'ai des résultats
		if($segments){
			// Segments que je parcours
			foreach ($segments as $segment) {
				# code...
				// Je récupère un segment au hasard correspondant au segment en cours
				$seg = $wpdb->get_row($wpdb->prepare("SELECT * FROM `{$wpdb->prefix}phrases` WHERE `segment` = %s ORDER BY RAND() LIMIT 1;", $segment->segment));
				// Et je le colle à la fin de $phrase afin de créer ma phrase
				$phrase .= $seg->text." ";
			}
		}
		// Je renvoi la phrase générée
		return $phrase;
	}

	// Function qui génére un discour de plusieurs phrase
	static function generateDiscour(){
		global $wpdb;
		// variable qui contiendra le discour généré
		$discour = "";
		//$nbParagraphe = 4;
		// Je génère un nb aléatoire de paragraphe comprs entre 1 et 10
		$nbParagraphe = rand(1,10);
		// Je vais donc créer une phrase contenue dans une balise <p> $nbParagraphe fois
		for ($i=0; $i < $nbParagraphe; $i++) { 
			# code...
			$discour .= "<p>".SELF::generatePhrase()."</p>";
		}

		// Je sauvegarde mon discours dans la bdd
		$wpdb->insert("{$wpdb->prefix}discours", 
			array( 
		        'text' => $discour, 
		    ), 
		    array( 
			    '%s'
			)  
		);
		// Je renvoi le discour
		return $discour;
	}

	// Function qui affiche tous les discours stockés dans la bdd
	static function afficheDiscours(){
		global $wpdb;
		// Je récupère tous les discours ordonnés par date du plus rescent au plus vieux 
		$discours = $wpdb->get_results("SELECT * FROM `{$wpdb->prefix}discours` ORDER BY date DESC;");
		// variable qui contiendra les discours sauvegardés
		$allDiscours = "";
		// Si il en existe
		if($discours){
			// Pour chacun
			foreach ($discours as $discour) {
				# code...
				// Je stocke la date dans un titre de niveau 2
				$allDiscours .= "<h2>Discour du ". $discour->date."</h2>\n";
				// Et dans la foulée le contenu du discour
				$allDiscours .= $discour->text."\n";
			}
		}

		// Je renvoi le tout
		return $allDiscours;
	}

	// Function qui sera appelé au moment où on initialise la page (crochet : init)
	static function createDiscours(){
		// Je créé 2 shortcodes qui appeleront respectivement le genrateur de discours puis l'affichage
		// Un shortcode est un bout de texte qu'on place dans une page, article, media, etc... Et qui déclenchera l'appelle à une fonction
		// cette fonction devra retourner (return) une chaine de caractère qui viendra remplacer le shortcode
		add_shortcode("createDiscours", array("Discours" , "generateDiscour"));
		add_shortcode("ListDiscours", array("Discours" , "afficheDiscours"));
	}
}