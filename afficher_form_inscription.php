<?php
// controleur : afficher_form_inscription
// role : mets en forme le formulaire d'inscription
// parametre : neant

//initialisation
require_once "utils/init.php";

if(session_isconnected()){
    session_deconnect();
}

//appel du template 
include "templates/page/vue_form_inscription.php";