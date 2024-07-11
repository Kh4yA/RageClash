<?php
// controleur de deconnexion 
// role : deconnecte la session en cours
//parmetre : neant

//initialisation
require_once('utils/init.php');
session_deconnect();
include_once "templates/page/vue_form_connexion.php";
