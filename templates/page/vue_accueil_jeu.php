<?php
//template : vue_acceuil_jeu
//parametre : $user
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="css/style.css">
    <title>Rage Clash</title>
</head>

<body>
    <?php include_once "templates/fragment/header.php"; ?>
    <main class="container-1400 large-12">
        <div class="flex">
            <div class="large-2 menu-joueur flex item-center direction-column margin-auto" id="menuJoueur">
                <?php include_once "templates/fragment/menu_joueur.php"; ?>
            </div>
            <div class="large-10 jeu" id="jeu">
                <div class="box-historique large-3 marge-auto margin-bottom5"><h5>Historique</h5></div>
                <?php include_once "templates/fragment/historique.php"; ?>
                <div class="large-3 marge-auto salle" data-id=<?= $user->id() ?>>
                    <div id="salleActuelle">
                        <p>Salle n°<?= $user->room ?></p>
                    </div>
                </div>
                <div class="large-11 marge-auto salle-jeu">
                    <div class="fleche-gauche">
                        <div class="large-1 color">
                            <button id="precedente">reculer</button>
                        </div>
                    </div>
                    <div class="plateau-jeu large-10 marge-auto" id="plateauJeu">
                        <?php include_once "templates/fragment/plateau_jeu.php"; ?>
                    </div>
                    <div class="fleche-droite">
                        <div class="large-1 color">
                            <button id="suivant">avancer</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <script src="js/app.js"></script>
</body>

</html>