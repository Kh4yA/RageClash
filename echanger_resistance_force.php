<?php
// template : echanger
// role : echanger point de resistance  en force
// parametre : session_idConnecte
//initialisation
require_once('utils/init.php');
if(session_idconnected() == 0){
    include "templates/page/vue_form_connexion.php";
    exit;
}
//on fait avancer le joeur
session_userconnected()->resistanceEnForce();