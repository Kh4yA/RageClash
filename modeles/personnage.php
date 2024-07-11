<?php
// class personnage : gestion des objets personnages
class personnage extends _model
{
    protected $table = "personnage";
    protected $fields = ["pseudo", "password", "vie", "force", "agilite", "resistance", "room"];
    private $aRiposter;
    private $aEsquiver;
    private $aDefendu;
    /**
     * Appel le tableau a retourner pour JSON
     * @param neant
     * @return aray
     */
    function gereTabJson()
    {
        $listeJoueurs = $this->controlEtChargeSalle($this->room);
        $historique = new historique();
        $listeHistorique = $historique->historiqueTrier(session_idconnected());
        //on on creer un nouveau tableau pour le joueur actif 
        $resultJoueurActif = [];
        if(isset($resultJoueurActif)){
            $resultJoueurActif[] = [
                "id" => $this->id,
                "pseudo" => $this->pseudo,
                "vie" => $this->vie,
                "force" => $this->force,
                "agilite" => $this->agilite,
                "resistance" => $this->resistance,
                "room" => $this->room
            ];
        }
        // Pour chaque joueur, on crée un objet
        $resultJoueurs = [];
        foreach ($listeJoueurs as $joueur) {
            $resultJoueurs[] = [
                "id" => $joueur->id,
                "pseudo" => $joueur->pseudo,
                "room" => $joueur->room,
            ];
        }
        // Pour chaque historique, on crée un nouvel objet dans un tableau vide
        $resultHistorique = [];
        foreach ($listeHistorique as $historique) {
            $resultHistorique[] = [
                "type_action" => $historique->type_action,
                "date_heure" => $historique->date_heure,
                "mouvement" => $historique->mouvement,
                "personnage" => $this->id,
                "adversaire" => $historique->adversaire,
                "vie" => $historique->vie,
                "force" => $historique->force,
                "agilite" => $historique->agilite,
                "resistance" => $historique->resistance,
                "detail" => $historique->detail,
            ];
        }
        // Fusionner les résultats des joueurs et de l'historique en un seul tableau
        $result = [
            "joueursActif" => $resultJoueurActif,
            "personnage" => $resultJoueurs,
            "historique" => $resultHistorique,
        ];

        return $result;
    }

    /**
     * Role : cherche l'id de l'objet par le pseudo
     * parametre : $name
     * retourne : $this->id
     */
    function searchIdParPseudo($name)
    {
        $sql = "SELECT `id`, `pseudo`, `password`, `vie`, `force`, `agilite`, `resistance`, `room` FROM `personnage` WHERE `pseudo`= :pseudo";
        $param = [":pseudo" => $name];
        global $bdd;
        $req = $bdd->prepare($sql);
        if (!$req->execute($param)) {
            //echo "Erreur de requete";
            print_r($param);
        }
        $listes = $req->fetchAll(PDO::FETCH_ASSOC);
        $data = $listes[0];
        $this->id = $data["id"];
        return $this->id;
    }

    /**
     * role : verfie que le pseudo n'existe pas dans la bdd
     * parametre : $pseudo
     * retourne : vrai si il existe : false si non
     */
    function pseudoExiste($pseudo)
    {
        $sql = "SELECT `pseudo` FROM `personnage` WHERE `pseudo`= :pseudo";
        $param = [":pseudo" => $pseudo];
        global $bdd;
        $req = $bdd->prepare($sql);
        if (!$req->execute($param)) {
            //echo "Erreur de requete";
            print_r($param);
        }
        $listes = $req->fetchAll(PDO::FETCH_ASSOC);
        foreach ($listes as $data) {
            if ($data["pseudo"] == $pseudo) {
                return true;
            }
        }
    }
    /**
     * role : verifie la connexion
     * @param string,string (pseudo) & (mot de passe)
     * @return true
     */
    function verifConnexion($pseudo, $password)
    {
        $sql = "SELECT `id`, `pseudo`, `password`, `vie`, `force`, `agilite`, `resistance`, `room` FROM `personnage` WHERE `pseudo`= :pseudo";
        $param = [":pseudo" => $pseudo];
        global $bdd;
        $req = $bdd->prepare($sql);
        if (!$req->execute($param)) {
            //echo "Erreur de requete";
            print_r($param);
        }
        $user = $req->fetch(PDO::FETCH_ASSOC);
        if (empty($user)) {
            return false;
        }
        //on reccupere l'id
        $this->id = $user["id"];
        // ON a récupéré une ligne : on vérifie le mot de passe
        // Donc avec password_verify, on compare le mot de passe à tester ($password)
        //    avec le mot de passe haché de l'utilisateur ($user["password])
        if (password_verify($password, $user["password"])) {
            // L'utilisateur a ce mot de passe
            // on charge l'objet :
            $this->loadFromTab($user);
            return true;
        } else {
            return false;
        }
    }
    /**
     * Role : charge tout les personnages dans une salle defini en parametre
     * @param neant
     * @return array tableau non indexé
     */
    function chargerPersonnageSalle()
    {
        $sql = "SELECT `id`, `pseudo`,`room` FROM `$this->table` WHERE `room`=:room";
        $param = [":room" => $this->room];
        global $bdd;
        $req = $bdd->prepare($sql);
        if (!$req->execute($param)) {
            //echo "Erreur de requete";
        }
        $liste = $req->fetchAll(PDO::FETCH_ASSOC);
        $result = [];
        foreach ($liste as $data) {
            $obj = new personnage();
            $obj->loadFromTab($data);
            $obj->id = $data["id"];
            $result[] = $obj;
        }
        return $result;
    }
    /**
     * controle l'endroit ou l'utilsateur se trouve
     * @param neant
     * @return array tableau non indexé
     */
    function controlEtChargeSalle()
    {
        if ($this->room == 0) {
            $this->set("id", $this->id());
            $liste = $this->listeTrier();
            return $liste;
        } elseif ($this->room > 0) {
            $liste = $this->chargerPersonnageSalle();
            return $liste;
        }
    }
    /**
     * affiche l'historique d'un personnage
     * @param object : objet personnage
     * @return array : liste d'objet non indexé
     */
    function afficheHistorique()
    {
        $historique = new historique();
        $liste = $historique->historiqueTrier($this->id);
        return $liste;
    }
    /**
     * role permet de faire avancer 
     * @param neant
     * @return neant
     */
    function avancer()
    {
        $historique = new historique();
        $tabHistorique = $historique->tabDataHistorique($this);
        if ($this->room >= 0 && $this->room < 9 && $this->agilite > $this->room) {
            $this->set("room", ($this->room + 1));
            $this->set("agilite", ($this->agilite - ($this->room + 1)));
            $this->update();
            $historique->loadFromTab($tabHistorique);
            $historique->set("id", $this->id);
            $historique->set("mouvement", "avancer");
            $historique->insert();
        } else if ($this->room == 9 && $this->get("agilite") > 10) {
            $this->set("room", ($this->room + 1));
            $this->set("agilite", ($this->get("agilite") - ($this->room + 1)));
            $this->update();
            $historique->loadFromTab($tabHistorique);
            $historique->set("id", $this->id);
            $historique->set("mouvement", "avancer");
            $historique->insert();
        }
    }
    /**
     * role permet de faire reculer
     * @param neant
     * @return neant
     */
    function reculer()
    {
        $historique = new historique();
        $tabHistorique = $historique->tabDataHistorique($this);
        if ($this->room > 0) {
            $this->set("room", ($this->room - 1));
            $this->set("vie", ($this->vie + $this->room));
            $this->update();
            $historique->loadFromTab($tabHistorique);
            $historique->set("id", $this->id);
            $historique->set("mouvement", "reculer");
            $historique->insert();
        }
    }
    /**
     * transforme un point de resistance en force et retire point d'agilité
     * @param neant
     * @return neant
     */
    function resistanceEnForce()
    {
        $historique = new historique();
        $tabHistorique = $historique->tabDataHistorique($this);
        if ($this->agilite >= 3 && $this->agilite > 0 && $this->resistance > 1 && $this->force<15) {
            $this->set("force", ($this->force + 1));
            $this->set("resistance", ($this->resistance - 1));
            $this->set("agilite", ($this->get("agilite") - 3));
            $this->update();
            $historique->loadFromTab($tabHistorique);
            $historique->set("mouvement", "rester");
            $historique->insert();
        }
    }
    /**
     * transforme un point de force en resistance et retire 3 point d'agilité 
     * @param neant
     * @return neant
     */
    function forceEnResistance()
    {
        $historique = new historique();
        $tabHistorique = $historique->tabDataHistorique($this);
        if ($this->get("force") > 1 && $this->get("agilite") > 0 && $this->get("agilite") >= 3 &&$this->resistance<15) {
            $this->set("force", ($this->get("force") - 1));
            $this->set("resistance", ($this->get("resistance") + 1));
            $this->set("agilite", ($this->get("agilite") - 3));
            $this->update();
            $historique->loadFromTab($tabHistorique);
            $historique->set("mouvement", "rester");
            $historique->insert();
        }
    }
    /**
     * method esquiver 
     * role : esquiver attaque et affecte les points en consequence
     * @param objet ($idAdversaire)
     * @return true 
     */
    function esquive($idAdversaire)
    {
        $adversaire = new personnage($idAdversaire);
        $historique = new historique();
        $tabHistorique = $historique->tabDataHistorique($this);
        $historique->set("detail","$adversaire->pseudo esquive") ;
        if ($this->force >= 10) {
            $this->force--;
            $this->resistance++;
            $adversaire->agilite--;
            $adversaire->update();
            $this->update();
            $historique->loadFromTab($tabHistorique);
            $historique->set("type_action", "esquive");
            $historique->set("personnage",$this->id);
            $historique->set("adversaire", $adversaire->id);
            $historique->set("detail","$adversaire->pseudo a esquiver mais j'ai plus de 10 point de force") ;
            $historique->insert();
            exit;
        } else {
            $adversaire->agilite--;
            $adversaire->update();
            $historique->loadFromTab($tabHistorique);
            $historique->set("type_action", "esquive");
            $historique->set("adversaire", $adversaire->id);
            $historique->set("detail","Dans ce cas $adversaire->pseudo perd 1 point d'agilité") ;
            $historique->insert();
        }
        $this->aRiposter;
        return true;
    }
    function riposte($idAdversaire)
    {
        /*
        Si notre force est supérieure strictement à celle de l'attaque, on riposte : voir ci-après la riposte. 
        On gagne le combat et un point de vie si on gagne la riposte, on perd le combat et 2 points de vie si on perd la riposte.
        */
        $adversaire = new personnage($idAdversaire);
        $historique = new historique();
        $tabHistorique = $historique->tabDataHistorique($this);
        //l'adversaire riposte et donc du coup devient attaquant
        $adversaire->attaquer($this->id);
        $this->aRiposter = true;
        return true;
    }
    /**
     * Defense contre une attaque
     * @param number (id de l'adversaire)
     * @return true
     */
    // METHOD ATTAQUER
    function attaquer($idAdversaire)
    /*
    Lorsqu'on clique sur un personnage (donc présent dans la même pièce), cela signifie qu'on l'attaque.
     L'attaque est alors automatique, et se fait avec une force déterminée et que l'on ne peut pas choisir (cette force d’attaque est utilisée pour déterminer le déroulement du combat, voir chapitre suivant) : 
        la force de l’attaque est la force de l’attaquant.
        Si l’adversaire esquive et que l’on a 10 points de force ou plus, un point de force devient un point de résistance. 
        Si on gagne le combat, on récupère un point d'agilité (ça motive ! ), ou un point de vie si on a déjà 15 points d'agilité. 
        Si on gagne le combat et que en plus l'on tue l'adversaire, on récupère en plus les points de vie qui lui restaient juste avant le combat. 
        Si on perd le combat : on perd 1 point de vie.
    */
    {
        $adversaire = new personnage($idAdversaire);
        $historique = new historique();
        $tabHistorique = $historique->tabDataHistorique($this);
        /*
        Si notre agilité dépasse la force d'attaque d'au moins 3 points, on esquive. Personne n'a alors gagné ou perdu le combat, et on perd 1 point d'agilité
        */
        if ($adversaire->agilite >= $this->force + 3) {
            $this->esquive($adversaire->id);
            exit;
        }
        /*
        Si notre force est supérieure strictement à celle de l'attaque, on riposte : voir ci-après la riposte. On gagne le combat et un point de vie si on gagne la riposte, on perd le combat et 2 points de vie si on perd la riposte        
        */
        if ($adversaire->force > $this->force) {
            $this->riposte($adversaire->id);
            $historique->loadFromTab($tabHistorique);
            $historique->set("type_action","attaque") ;
            $historique->set("adversaire", $adversaire->id);
            $historique->set("detail","Riposte de $adversaire->pseudo") ;
            $historique->insert();
            exit;
        }
        /*
        Sinon, on se défend : si notre résistance est supérieure ou égale à la force de l'attaque, on gagne le combat, si elle est inférieure, on le perd et on perd en points de vie la différence entre notre résistance et la force de l'attaque.
        */
        if ($adversaire->resistance >= $this->force) {
            if ($this->aRiposter) {
                if ($this->resistance >= $adversaire->force) {
                    $this->vie++;
                    $this->update();
                    $historique->loadFromTab($tabHistorique);
                    $historique->set("detail","$this->pseudo a gagner la riposte : gagne un point de vie");
                    $historique->set("type_action", 'attaque');
                    $historique->set("adversaire", $adversaire->id);
                    $historique->insert();
                    exit;
                } else {
                    $this->vie -= 2;
                    $this->update();
                    $historique->loadFromTab($tabHistorique);
                    $historique->set("detail","$this->pseudo a perdu la riposte : perd 2 points de vie");
                    $historique->set("type_action", 'attaque');
                    $historique->set("personnage", $adversaire->id);
                    $historique->set("adversaire", $this->id);
                    $historique->insert();
                    exit;
                }
            }else{
                $historique->loadFromTab($tabHistorique);
                $historique->set("detail","$adversaire->pseudo gagne le combat contre $this->pseudo!") ;
                $historique->set("type_action", 'attaque');
                $historique->set("personnage", $this->id);
                $historique->set("adversaire", $adversaire->id);
                $historique->insert();
                exit;
            }
        } else {
            $adversaire->vie -= $adversaire->force - $adversaire->resistance;
            $this->agilite<15 ? $this->agilite++ : $this->vie++;
            $this->update();
            $adversaire->update();
            $historique->loadFromTab($tabHistorique);
            $historique->set("detail","$adversaire->pseudo perd le combat contre $this->pseudo!");
            $historique->set("type_action", 'attaque');
            $historique->set("personage", $this->id);
            $historique->set("adversaire", $adversaire->id);
            $historique->insert();
            exit;
        }
    }
}
