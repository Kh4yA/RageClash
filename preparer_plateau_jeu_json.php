<?php
// Controleur : connexion_page_accueil_json
// Rôle : charge le joueur connecté par l'objet, reccuperer tous les joueurs indexé par l'id, preparer la liste de l'historique, prepare le fichier json et encode le $result
// Paramètre : neant

// Initialisation
require_once("utils/init.php");

// Définir le type de contenu et encoder en JSON
$result = session_userconnected()->gereTabJson();
header('Content-Type: application/json; charset=utf-8');
echo json_encode($result);

