<?php
// controleur : enregister_compte.php
// Gestionnaire de la page d'enregistrement d'un compte
// parametre : $_POST(pseudo, password,verif-password,force,agilite,resistance)
// action : enregistrement d'un compte dans la bdd

//initialisation
require_once('utils/init.php');

//instancier un nouveau personnage
$personnage = new personnage();
$personnage->loadFromTab($_POST);
//on verifie que le pseudo n'existe pas 
if($personnage->pseudoExiste($_POST['pseudo']) == true){
    //si le pseudo existe 
    //retour a la page de connexion
    require_once("templates/page/vue_form_connexion.php");
    exit;
}
//on verifie que les mots de passe sont identiques
if($_POST['password'] != $_POST['verif-password']){
    //si les mots de passe ne sont pas identiques
include_once "templates/page/vue_form_inscription.php";
exit;
}else{
    //si les mots de passe sont identiques
    $personnage->set("password", password_hash($_POST["password"],PASSWORD_DEFAULT));
    $personnage->insert();
    //on redirige vers la page de connexion
    include_once "templates/page/vue_form_connexion.php";
}
