<?php
// template : vue_form_connexion
// description : formulaire de connexion
// param : neant

?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <link rel="stylesheet" href="css/style.css">
        <title>Page de connexion</title>
    </head>
    <body>
        <?php include "templates/fragment/header.php"; ?>
        <div class="container-1440 login margin-top5">
            <form action="connexion_page_accueil.php" method="POST" class="flex direction-column">
                <div class="mb30">
                    <label for="pseudo">Pseudo :</label>
                    <input type="text" name="pseudo" id="pseudo">
                </div>
                <div class="margin-bottom5">
                    <label for="password">Mot de passe :</label>
                    <input type="password" name="password" id="password">
                    <p id="afficher">Afficher mot de passe</p>
                </div>
                <input type="submit" value="Connexion">
            </form>
        </div>
        <div class="container-1440">
    <p class="text-center add">Vous n'avez pas de compte ? <a href="afficher_form_inscription.php">Inscrivez-vous</a></p> 
        </div>
        <script src="js/connexion.js"></script>
    </body>
</html>