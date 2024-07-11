<?php
// controleur : connexion_page_accueil
//role : Verifie que l'utilisateur existe et prepare la page acceuil / si existe pas retour connexion
//parametre : $_POST(nom, password) session_connecte(id) 
//initialisation
require_once('utils/init.php');
//on instancie les classes
$personnage = new personnage();
//On verifie que le mot de passe entrer correspond avec celuide la bdd
if($personnage->verifConnexion($_POST["pseudo"], $_POST["password"])){
    //si c'est bon on redirige vers la page d'accueil
    session_connect($personnage->id());
    $user = new personnage(session_idconnected());
}else{
    //on redirige sur la page de connexion
    include_once "templates/page/vue_form_connexion.php";
    exit;
}
$listeJoueurs = $personnage->chargerPersonnageSalle($user->room);
$listeHistorique = $personnage->afficheHistorique();

include_once "templates/page/vue_accueil_jeu.php";
