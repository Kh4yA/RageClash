<?php
//fragment historique 
//role : met en forme l'historique
// param : $listeHistorique
?>
<div class="historique large-11 marge-auto flex" data-objet="historique" id="historique">
    <?php
    foreach ($listeHistorique as $historique) {
    ?>
        <div class="card-historique">
            <?php
            ?>
            <div>
                <p><b>Date : </b><?= $historique->date_heure ?></p>
            </div>
            <div class="flex gap10">
                <div class="flex item-center">
                    <img src="img/life.png" alt="image d'un coeur">
                    <p><?= $historique->vie ?></p>
                </div>
                <div class="flex item-center">
                    <img src="img/force.png" alt="image d'une force">
                    <p class="pts-force"><?= $historique->force ?></p>
                </div>
                <div class="flex item-center">
                    <img src="img/agilite.png" alt="image d'une cible">
                    <p><?= $historique->agilite ?></p>
                </div>
                <div class="flex item-center">
                    <img src="img/resistance.png" alt="image d'un bouclier">
                    <p><?= $historique->resistance ?></p>
                </div>
            </div>
            <div>
                <?php
                if ($historique->aversaire != null) {
                ?>
                    <p><b>Adversaire : </b><?= $historique->adversaire ?></p>
                <?php
                }
                ?>
                <p><b>Action : </b><?= $historique->type_action ?></p>
                <p class="mouvement"><b>Mouvement : </b><?= $historique->mouvement ?></p>
                <p class="detail"><b>Détail : </b><?= $historique->detail ?></p>
            </div>
        </div>
    <?php
    }
    ?>
</div>