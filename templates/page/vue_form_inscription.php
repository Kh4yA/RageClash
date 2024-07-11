<?php
// template : vue_form_creation
// role : mise en forme du formulaire de creation
// parametre : neant

?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="css/style.css">
    <title>Inscription</title>
</head>

<body>
    <?php require_once "templates/fragment/header.php"; ?>
    <section class="container-1440 flex inscription margin-top5">
        <div class="large-5">
            <form action="enregistrer_creation.php" method="POST">
                <div class="mb30">
                    <label for="pseudo">Pseudo :</label>
                    <input type="text" name="pseudo" id="pseudo" placeholder="pseudo" required>
                </div>
                <div class="mb30">
                    <label for="password">Mot de passe :</label>
                    <input type="password" name="password" id="password" required>
                    <p id="show"><span>Afficher mot de passe</span></p>
                </div>
                <div class="mb30">
                    <label for="verif-password">Retapez mot de passe :</label>
                    <input type="password" name="verif-password" id="verif-password" required>
                    <p id="show-review"><span>Afficher mot de passe</span></p>
                </div>
                <div class="distribut-pts flex space-between">
                    <div class="range large-4">
                        <div class="flex">
                            <div>
                                <label for="force">Force :</label>
                                <input type="range" name="force" id="force" min="3" max="10" value="3" step="1" list="tickmarks">
                                <datalist id="tickmarks">
                                    <option value="0"></option>
                                    <option value="1"></option>
                                    <option value="2"></option>
                                    <option value="3"></option>
                                    <option value="4"></option>
                                    <option value="5"></option>
                                    <option value="6"></option>
                                    <option value="7"></option>
                                    <option value="8"></option>
                                    <option value="9"></option>
                                    <option value="10"></option>
                                </datalist>
                            </div>
                            <div>
                                <p id="resultForce">3</p>
                            </div>
                        </div>
                        <div class="flex">
                            <div>
                                <label for="agilite">Agilité :</label>
                                <input type="range" name="agilite" id="agilite" min="3" max="10" value="3" step="1" list="tickmarks">
                                <datalist id="tickmarks">
                                    <option value="0"></option>
                                    <option value="1"></option>
                                    <option value="2"></option>
                                    <option value="3"></option>
                                    <option value="4"></option>
                                    <option value="5"></option>
                                    <option value="6"></option>
                                    <option value="7"></option>
                                    <option value="8"></option>
                                    <option value="9"></option>
                                    <option value="10"></option>
                                </datalist>
                            </div>
                            <div>
                                <p class="resultAgilite">3</p>
                            </div>
                        </div>
                        <div class="flex">
                        <div>
                            <label for="resistance">Résistance :</label>
                            <input type="range" name="resistance" id="resistance" min="3" max="10" value="3" step="1" list="tickmarks">
                            <datalist id="tickmarks">
                                <option value="0"></option>
                                <option value="1"></option>
                                <option value="2"></option>
                                <option value="3"></option>
                                <option value="4"></option>
                                <option value="5"></option>
                                <option value="6"></option>
                                <option value="7"></option>
                                <option value="8"></option>
                                <option value="9"></option>
                                <option value="10"></option>
                            </datalist>
                        </div>
                        <div>
                            <p id="resultResistance">3</p>
                        </div>
                        </div>
                    </div>
                    <div>
                        <p>points restant :</p>
                        <p class="pts"><b>0</b></p>
                    </div>
                </div>
                <input type="submit" value="Enregistrer">
            </form>
        </div>
        <div class="large-5">
            <h2>REGLE DE DISTRIBUTION DES POINTS</h2>
            <p>Chaque personnage que vous créez possède trois propriétés principales : la force, l'agilité et la résistance. Pour distribuer vos 15 points de manière équilibrée, gardez à l'esprit que chaque propriété doit avoir au moins 3 points et ne peut pas dépasser 10 points. La force détermine la puissance de vos attaques, l'agilité influence vos déplacements et vos chances d'esquiver les attaques ennemies, tandis que la résistance vous rend plus robuste face aux dégâts. Répartissez vos points judicieusement pour créer un personnage capable de s'adapter à différentes situations et de survivre dans l'arène.</p>
        </div>
    </section>
    <script src="js/inscription.js" defer></script>
</body>

</html>