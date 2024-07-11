<?php
// template fragment : menu joueur
// parametre : $user
?>
<div  data-objet="personnage"  id="user">
    <h3>Bonjour</h3>
    <p><?= $user->pseudo ?></p>
</div>
<div class="bar-progress flex direction-column item-center">
    <label for="file">Vie :<p class="pts-vie"><?= $user->vie ?></p></label>
    <progress id="file" max="100" class="pts-vie"value="<?= $user->vie ?>"></progress>
    <label for="file">Force :<p class="pts-force" ><?= $user->force ?></p></label>
    <progress id="file" max="15"  class="pts-force" value="<?= $user->force ?>"></progress>
    <label for="file">Agilité :<p class="pts-agilite"><?= $user->agilite ?></p></label>
    <progress id="file" max="15" class="pts-agilite" value="<?= $user->agilite ?>"></progress>
    <label for="file">Resistance :<p class="pts-resistance"><?= $user->resistance ?></p></label>
    <progress id="file" max="15" class="pts-resistance" value="<?= $user->resistance ?>"></progress>
</div>
<p>Echange de point :</p>
<div class="echange">
    <div>
        <p>Force <span class="pts-force"><?= $user->force ?></span></p>
    </div>
    <div class="flex justify-center">
        <div id="resistanceForce"><img src="img/flecheHaut.png" alt="image d'une fleche a haut"></div>
        <div id="forceResistance"><img src="img/flecheBas.png" alt="image d'une fleche a bas"></div>
    </div>
    <div>
        <p >résistance <span class="pts-resistance"><?= $user->resistance ?></span></p>
    </div>
</div>
<button class="deconnexion"><a href="deconnexion.php">Deconnexion</a></button>