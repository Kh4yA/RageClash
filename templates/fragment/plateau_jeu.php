<?php
// template fragment : plateu jeu
// parametre : $listesJoueurs (tableau non indexé) objet personnage, $joueur->id
?>

<?php
?>
    <div data-objet="personnage" class="adversaire" id="chargerJoueur">
            <?php
            foreach($listeJoueurs as $joueur){
                ?>
            <p class="flex item-center selectAdversaire" data-id = <?= $joueur->id ?>><?= $joueur->pseudo ?><img src="img/attaque.png" alt="image d'epee croise"></p>
                <?php
    }
    ?>
    </div>
<?php
?>