<?php

//traitement des errors
ini_set("display_errors", 1);
error_reporting(E_ALL);

global $bdd;
// Gérer les exceptions
try {
    // Code à exécuter
    $bdd = new PDO("mysql:host=localhost;dbname=projets_combat_mdaszczynski;charset=UTF8", "mdaszczynski", "Alt6WH9t%W");
} catch (Throwable $exception) {
    echo "Une erreur c'est produit";
    var_dump($exception);
}
// Pour debugger, on peut ajouter une propriété
$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

/**
 * Rôle : trouver et charger une class
 * param : $class
 * retour : neant
 */
function autoLoadClass ($class) {
    if($class == "_model"){
        include "utils/model.php" ;
    }
    else if (file_exists("modeles/$class.php")) {
        include "modeles/$class.php";
    } 

    if (class_exists($class)) {
        // La classe existe
    }
}
spl_autoload_register("autoloadClass");
require_once "session.php";
include "vendor/autoload.php";
session_activation();
