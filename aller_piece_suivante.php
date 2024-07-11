<?php
// controleur : aller_piece_suivante
//role : va a la piece suivznte
//param : $user / objet $personnage

//initialisation
require_once('utils/init.php');
if(session_idconnected() == 0){
    include "templates/page/vue_form_connexion.php";
    exit;
}
//on fait avancer le joueur
session_userconnected()->avancer();
