<?php
// class historique_action : gestion des objets de la table historique_action

use LDAP\Result;

class historique extends _model
{
    protected $table = "historique_action";
    protected $fields = ["type_action", "mouvement", "date_heure", "personnage", "adversaire", "vie", "force", "agilite", "resistance", "detail"];
    protected $links = ["personnage" => "personnage", "adversaire"=>"personnage"];
    /**
     * role = trier la liste $hitorisue d'un personnage defini par l'$id et limité a 10 elements
     * @param number ($id)
     * @return objet liste d'objet non indexé
     */
    function historiqueTrier($id)
    {
        $sql = "SELECT `id`, `type_action`, `mouvement`, `date_heure`, `personnage`, `adversaire`, `vie`, `force`, `agilite`, `resistance`,`detail` FROM `historique_action` WHERE `personnage`=:id ORDER BY `date_heure`DESC LIMIT 6";
        $param = [":id" => $id];
        global $bdd;
        $req = $bdd->prepare($sql);
        if (!$req->execute($param)) {
            echo "Erreur de requête $sql";
            return false;
        }
        $liste = $req->fetchAll(PDO::FETCH_ASSOC);
        $result = [];
        foreach ($liste as $data) {
            $obj = new historique;
            $obj->loadFromTab($data);
            $result[] = $obj;
        }
        return $result;
    }
    /**
     * uncrement les valeur de la table historique avec les valeurs de l'hutilisateurt connecter 
     * @param objet ( $user )
     * @return array 
     */
    function tabDataHistorique($user)
    {
        $dataHistorique = [
            "type_action" => "neant",
            "mouvement" => "neant",
            "personnage" => $user->id,
            "adversaire" => 0,
            "room" => $user->room,
            "vie" => $user->vie,
            "agilite" => $user->agilite,
            "force" => $user->force,
            "resistance" => $user->resistance,
            "detail" => "neant",
        ];
        return $dataHistorique;
    }
}
