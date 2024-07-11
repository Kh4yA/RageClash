<?php
// controleur : attaquer
// role : Attaquer un adversaire 
// l'attaque selon le cahier des charges
// parametre : $_GET["id"] de l'adversaire

require_once("utils/init.php");
// Initialisation
$user = new personnage(session_idconnected());
//on verifie la connexion
if(session_idconnected() == 0){
    echo "Pas de session connecte";
    exit;
}
// On récupère les paramètres
if (isset($_GET["id"])) {
    $idAdversaire = intval($_GET["id"]);
    // Appeler la méthode pour subir une attaque
    $user->attaquer($idAdversaire);
    // On affiche le résultat
    $result = $user->gereTabJson();
    // Réponse en JSON avec succès
    header('Content-Type: application/json; charset=utf-8');
    //Reponse JSON test voir si je reccupere bien l'id
    $response = ["succes"=>"ok", "id"=>"$idAdversaire"];
    echo json_encode($result);
} else {
    //reponse JSON echoué
    $reponse = ["succes" => "echoue", "id" => "id pas charger"];
    echo json_encode($reponse);
}
