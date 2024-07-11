<?php

// classe générique de getsiondes objects fu modèlede données
//pour l'utiliser on a les methodes
/*
Classe _model : classe générique de gestion des objets du modèle de données
(on a un _ dans le nom pour être sûr de ne pas avoir de table de ce nom)


Pour l'utiliser, on a les méthodes
    load(id) : chargement d'un objet depuis la BDD par son id
    is() : indique si l'objet est chargé / existe (true si existe, false sinon)
    get(nomChamp) : récupération de la valeur d'un champ (valeur physique)
    getTarget($nomChamps) : 
    id() : récupère l'id
    set(nomChamp, valeur) : affectation d'une valeur à un champ
    insert() : ajout de l'objet courant dans la BDD
    update() : mise à jour de l'objet courant dans la BDD
    delete() : suppression de l'objet courant de la BDD
    listAll(+:-tri1, +/-tri2) : récupération de tous les champs
    listeTrier() : trie une liste valoriser par les setters
*/

class _model
{
    // attribut:
    //description du modèle de l'object de la table
    protected $table = "";
    protected $fields = [];
    //liste des nom des champs, avec le type de champs
    //["nomChamp1" => typeChamp1 ...]
    //types de champ géré 
    protected $links = []; // liste des liens sortants : 
    //tableau qui pour chaque liens 
    // (exemple : ["fournisseurs" => "fournisseurs"])
    //stockeer un object precis
    protected $id = 0;
    protected $values = [];     // On stockera les valeurs sous la forme [ "nom" => "Blanchot"", "prenom" => "Christophe, "email" => "cblanchot@cbcd.fr" ]
    protected $targets = []; // on stockera pour les liens 
    //constructeur 
    function __construct($id = null)
    {
        //cette fonction se declanche a chaque fois qu'on instancie un objet new nomClasse()
        //les paramètre du constructeur devront etre balorisés les paranthèses du new nomClasse()
        // rôle : charger l'objet corespondant a l'id
        //retour : constructeur pas de retour
        // si l'id est non null 
        // charger l'objet avec cet id
        if (!is_null($id)) {
            $this->load($id);
        }
    }
    // méthodes magiques

    function __get($name)
    {
        // Rôle : invoqué quand on utilise l'expression  $obj->attribut ($name sera alors attribut)
        //          et que attribut n'est pas accessible (protégé, ou inexistant)
        // Paramètres : 
        //      $name : nom de l'attribut invoqué
        // Retourne : la valeur que l'on veut lui donner

        if ($name == "id") return $this->id();
        else if (in_array($name, $this->fields)) return $this->get($name);
    }

    function __set($name, $value)
    {
        // Rôle : invoqué quand on utilise l'expression  $obj->attribut = valeur 
        //          et que attribut n'est pas accessible (protégé, ou inexistant)
        // Paramètres : 
        //      $name : nom de l'attribut invoqué
        //      $value : valeur de l'expression à la doite du =
        // Retourne : néant

        if (in_array($name, $this->fields)) $this->set($name, $value);
    }

    function is()
    {
        if ($this->id() !== 0) {
            return false;
        } else {
            return true;
        }
    }
    // getters 
    /**
     * Fonction get qui reccupere les valeurs
     */
    function id()
    {
        return $this->id;
    }
    function get($name)
    {
        if (isset($this->values[$name])) {
            return $this->values[$name];
        } else {
            return $this->fields[$name];
        }
    }
    function getTarget($fieldName)
    {
        // Rôle : retourner un objet pointé par un champ
        // paramètre : 
        //      $fieldName : nom du champ
        // Retour : objet (d'une classe héritée de la classe _model), chargé avec l'objet pointé
        //       si on ne trouve pas :
        //          si champ inconnu ou pas un lien : retourne un objet _model (vide)
        //          si le champ est un lien, mais vide, ou pas d'bjet en face : le bon objet, mais pas chargé

        // At-on déjà la cible (dans $this->targets)
        if (isset($this->targets[$fieldName])) {
            return $this->targets[$fieldName];
        }


        // Est-ce que c'est un lien ?
        if (!isset($this->links[$fieldName])) {
            // Ce n'est pas un lien : on retourne un objet de la classe _model
            $this->targets[$fieldName] = new _model();
            return $this->targets[$fieldName];
        }

        // c'est un lien : l'objet pointé est de la classe indéiquée dans $this->links[$fieldName]
        $nomClasse = $this->links[$fieldName];
        $this->targets[$fieldName] = new $nomClasse($this->get($fieldName));

        return $this->targets[$fieldName];
    }
    //setters
    /**
     * changer la valeur
     * paramètre nouvelle valeur de l'attribut
     */
    function set($name, $values)
    {
        $this->values[$name] = $values;
        return true;
    }
    /**
     *Charger les attribut(pas l'id) de l'objet si il existe Pour chaque élément du tablaeu
     */
    function loadFromTab($table)
    {
        foreach ($this->fields as $fieldName) {
            if (isset($table[$fieldName])) {
                $this->values[$fieldName] = $table[$fieldName];
            }
        }
        return true;
    }
    function makeRequestParamForSet()
    {
        // Rôle : préparer (et retourner) le tableau de valorisation des paramètres pour une mise à jour des champs
        // Paramètres : néant
        // Retour : le tableau contenant les valeurs associées aux :nomChamp (pour chaque champ)
        //               [ ":nomChamp1" => valeur1, ":nomChamp2" => valeur2, ... ]
        // Initialise un tableau vide pour les param
        $param = [];
        foreach ($this->fields as  $fieldName) {
            //verif si le champs a remplir est plein
            if (isset($this->values[$fieldName])) {
                $param[":$fieldName"] = $this->values[$fieldName];
            }
        }
        // Retourne le tableau de param
        return $param;
    }
    function makeRequestSet()
    {
        // Rôle : construire la partie d'une requête de mise à jour ou de création valorisant mles champs
        // paramètres : néant
        // Retour : le texte à mettre derrère SET dans une requête SQL : `nomChamp1` = :nomChamp1, `nomChamp2` = :noùmChamp2, ...
        // Initialise un tableau vide pour les paramètres
        // Initialise un tableau vide pour les paramètres
        $model = [];
        // Boucle à travers les champs et construit la partie SET de la requête SQL
        // si le champs et vide tu ne fait rien
        foreach ($this->fields as $fieldName) {
            //verif si le champs a remplir est plein
            if (isset($this->values[$fieldName])) {
                $model[] = "`$fieldName` = :$fieldName";
            }
        }
        // Retourne la partie SET sous forme de texte SQL
        return implode(",", $model);
    }

    /**
     * fonction qui s'occupe du chargement des contacts
     * paramètre : id du contact a charger
     * Retour : true si on l'a trouvé, false sinon
     */
    function load($id)
    {
        $field = [];
        foreach ($this->fields as $fieldName) {
            $field[] = "`$fieldName`";
        }
        $sql = "SELECT `id`," . implode(',', $field) . " FROM `$this->table` WHERE `id`=:id";
        $param = [":id" => $id];
        global $bdd;
        $req = $bdd->prepare($sql);
        if (!$req->execute($param)) {
            echo "Erreur requete sql $sql";
            return false;
        }
        $listes = $req->fetchAll(PDO::FETCH_ASSOC);
        if (empty($listes)) {
            return false;
        }
        $object = $listes[0];
        $this->id = $object["id"];
        foreach ($this->fields as $fieldName) {
            $this->values[$fieldName] = $object[$fieldName];
        }
        return true;
    }
    /**
     * Function qui suprime un contact de la base de donnée
     * paramètre : néant
     * retour : true si ok / false si non
     */
    function delete()
    {
        $sql = "DELETE FROM `$this->table` WHERE `id`=:id";
        $param = [":id" => $this->id];
        global $bdd;
        $req = $bdd->prepare($sql);
        if (!$req->execute($param)) {
            echo "Erreur sql $sql";
            return false;
        }
        $this->id = 0;
        return true;
    }
    /**
     * rôle : ajouter un article dans la base de donnée
     * paramètre : neant
     * retour : true si ok / false si non
     */
    function insert()
    {
        $sql = "INSERT INTO `$this->table`SET " . $this->makeRequestSet();
        $param  = $this->makeRequestParamForSet();
        global $bdd;
        $req = $bdd->prepare($sql);
        if (!$req->execute($param)) {
            return false;
        }
        $this->id = $bdd->lastInsertId();
        return true;
    }
    /**
     * Rôle : modifier un article dans la base de donnée
     * paramètre : neant
     * retourne : true si ok / false si non
     */
    function update()
    {
        $sql = "UPDATE  `$this->table` SET " . $this->makeRequestSet() . " WHERE `id` = :id ";
        $param = $this->makeRequestParamForSet();
        $param[":id"] = $this->id;
        // On prépare la requête
        global $bdd;
        $req = $bdd->prepare($sql);
        //  - on exécute cette requête
        if (!$req->execute($param)) {
            // Erreur sur la requête
            return false;
        }
        return true;
    }
    /**
     * Rôle : donner la liste de tous les objets de cette calsse (depuis la BDD)
     * @param : gérer les critères de tri"+/-nomChamp", "+/-nnomChamp", ....
     *  @return : liste d'objet de la classe courante, indexées par les id 
     */
    function listAll(...$tris)
    {
        $sql = "SELECT ";
        // Construire la liste des champs encadrés par ` 
        // On met d'abord l'id
        $tableau = ["`id`"];
        foreach ($this->fields as $nomChamp) {
            $tableau[] = "`$nomChamp`";
        }
        $sql .= implode(", ", $tableau);
        $sql .= " FROM `$this->table` ";
        // Construire la liste des critères de tri
        $tabOrder = [];
        foreach ($tris as $tri) {
            // tri : +nomChamp ou - nomChamp ou nomChamp
            $car1 = substr($tri, 0, 1);
            if ($car1 === "-") {
                $ordre = "DESC";
                $nomField = substr($tri, 1);
            } else if ($car1 === "+") {
                $ordre = "ASC";
                $nomField = substr($tri, 1);
            } else {
                $ordre = "ASC";
                $nomField = $tri;
            }
            $tabOrder[] = "`$nomField` $ordre";
        }
        if (!empty($tabOrder))  $sql .= " ORDER BY " . implode(",", $tabOrder);
        // préparer / exécuter
        global $bdd;
        $req = $bdd->prepare($sql);
        if (!$req->execute()) {
            // Echec de la requête
            return [];
        }
        // Construire le tableau résultat
        $result = [];
        // tant que j'ai une ligne de résultat de la requête à lire
        while ($tabObject = $req->fetch(PDO::FETCH_ASSOC)) {
            // "transférer" $tabObject en objet de la classe courante
            // Récupération du nom de la classe de l'objet courant
            $classe = get_class($this);
            $obj = new $classe();
            // Charger l'objet
            $obj->loadFromtab($tabObject);
            // ON ajoute cela dans $result
            $result[] = $obj;
        }
        return $result;
    }
    /**
     * role : donne la liste de les objets de la classe
     * @param : neant
     * @return : liste d'objet indexé par l'id
     */
    function listeTrier()
    {
        // role : donne la liste de les objets de la classe
        //parametre : neant
        //retourne : liste d'objet indexé par l'id
        $model = [];
        $param = [];
        // Boucle à travers les champs et construit la partie SET de la requête SQL
        // si le champs et vide tu ne fait rien
        foreach ($this->values as $fieldName => $fieldValue) {
            //verif si le champs a remplir est plein
            if (!empty($this->values[$fieldName])) {
                $model[] = "`$fieldName` = :$fieldName";
                $index = ":$fieldName";
                $param[$index] = $fieldValue;
            }
        }
        // construire la requete $sql 
        $sql = " SELECT * FROM `$this->table` ";
        if (!empty($param))  $sql .= " WHERE " . implode(" AND ", $model);
        // préparer / exécuter
        global $bdd;
        $req = $bdd->prepare($sql);
        if (!$req->execute($param)) {
            // Echec de la requête
            echo "Erreur requete sql : $sql";
            return false;
            return [];
        }

        // Construire le tableau résultat
        $result = [];
        // tant que j'ai une ligne de résultat de la requête à lire
        while ($tabObject = $req->fetch(PDO::FETCH_ASSOC)) {
            // "transférer" $tabObject en objet de la classe courante
            // Récupération du nom de la classe de l'objet courant
            $classe = get_class($this);
            $obj = new $classe();
            // Charger l'objet
            $obj->loadFromtab($tabObject);
            $obj->id = $tabObject["id"];
            // ON ajoute cela dans $result
            $result[] = $obj;
        }

        return $result;
    }
    function toTab()
    {
        // Rôle : retourner un tableau des valeurs des champs de cet objet
        // Paramètres : néant
        // Retour : tableau des valeurs indexé par le nom des champs
        //          exemple : [ "id" => 12, "nom" => "Blanchot", "prenom" => "Christophe"]
        $tab = [];
        foreach ($this->fields as $field) {
            $tab[$field] = $this->$field;
        }
        return $tab;
    }
    function listChamps()
    {
        // Rôle : construire la liste des champs de la table pour une requête SELECT
        // Paramètres : néant
        // Retour : texte du type ìd`, `nom`, `prenom`
        $result = "";
        foreach ($this->fields as $field) {
            $result .= "`$field`,";
        }
        $result = rtrim($result, ", ");
        return $result;
    }

    function sqlToList($sql, $param)
    {
        // Role : à partir d'une requête SQL et de ses paramètres, 
        //      générer une liste d'objets
        // Paramètres:
        //      $sql : texte de la requête SQL (avec des parametres :xxx)
        //      $param : tableau de valorisation des paramètres de la requête
        // Retour : tableau d'objets de la classe courante (indexés par l'ID)
        $req = $this->sqlExecute($sql, $param);
        $result = [];
        while ($tabObject = $req->fetch(PDO::FETCH_ASSOC)) {
            $classe = get_class($this);
            $obj = new $classe();
            $obj->loadFromtab($tabObject);
            $result[$obj->id] = $obj;
        }
        return $result;
    }
    function sqlExecute($sql, $param = [])
    {
        // Role : exécuter une requête SQL sur la BDD
        // Paramètres:
        //      $sql : texte de la requête SQL (avec des parametres :xxx)
        //      $param : tableau de valorisation des paramètres de la requête
        // Retour : 
        //      Objet requete exécutée (requête au sens PDO, que l'on pourra donc interroger comme on veut)
        global $bdd;
        $req = $bdd->prepare($sql);
        if (!$req->execute($param)) {
            // Echec de la requête
            echo "Erreur requete sql : $sql";
            return false;
        }
        return $req;
    }
    function  listEtendue($filtres = [], $tris = [])
    {
        // Rôle : extraire une liste d'objet de cette classe, avec des critères de tri et de filtrage
        // Paramètres :
        //      $filtres : tableau permettant de définir des filtres du type `nomChmap`= valeur
        //      $tris : liste des critères de tri, 
        //              chaque critère est de la forme : "+/-nomChamp", "+/-nnomChamp", ....
        //             chaque critère est donc le nom du champ précédé de - pour un tri descedant, 
        //              optionnellement de + pour un tri ascendant
        // Retour : tableau d'objets de la classe courante (indexés par l'ID)
        $sql = "SELECT ";
        // Construire la liste des champs encadrés par ` 
        // On met d'abord l'id
        $sql .= "`id`, ";
        $tableau = $this->toTab();
        $sql .= implode(", ", $tableau);
        $sql .= " FROM `$this->table` ";
        $param = [];
        $tabFiltre = [];
        foreach ($filtres as $fieldName => $valeur) {
            $tabFiltre[] = "`$fieldName` = :$fieldName";
            $param[":nomChamps"] = $valeur;
        }
        if (!empty($tabFiltre)) $sql .= " WHERE " . implode(", ", $tabFiltre);
        // Construire la liste des critères de tri
        $tabOrder = [];
        foreach ($tris as $tri) {
            // tri : +nomChamp ou - nomChamp ou nomChamp
            $car1 = substr($tri, 0, 1);
            if ($car1 === "-") {
                $ordre = "DESC";
                $nomField = substr($tri, 1);
            } else if ($car1 === "+") {
                $ordre = "ASC";
                $nomField = substr($tri, 1);
            } else {
                $ordre = "ASC";
                $nomField = $tri;
            }
            $tabOrder[] = "`$nomField` $ordre";
        }
        if (!empty($tabOrder))  $sql .= " ORDER BY " . implode(", ", $tabOrder);
        $req = $this->sqlExecute($sql,);
        print_r($req);
        // Construire le tableau résultat
        $result = [];
        // tant que j'ai une ligne de résultat de la requête à lire
        while ($tabObject = $req->fetch(PDO::FETCH_ASSOC)) {
            // "transférer" $tabObject en objet de la classe courante
            // Récupération du nom de la classe de l'objet courant
            $classe = get_class($this);
            $obj = new $classe();
            // Charger l'objet
            $obj->loadFromtab($tabObject);
            $obj->id = $tabObject["id"];
            // ON ajoute cela dans $result
            $result[] = $obj;
        }
        return $result;
    }
}
