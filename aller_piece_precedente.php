<?php
// template : aller a la piece precedente
// role : fait reculer d'une piece le personnage
// parametre : session_idConnecte / objet personnage
//initialisation
require_once('utils/init.php');
if(session_idconnected() == 0){
    include "templates/page/vue_form_connexion.php";
    exit;
}
//on fait avancer le joeur
session_userconnected()->reculer();

