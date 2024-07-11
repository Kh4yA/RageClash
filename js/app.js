const suivant = document.getElementById("suivant");
const precedente = document.getElementById("precedente");
const menuJoueur = document.getElementById("user");
const ptsForces = document.querySelectorAll(".pts-force");
const ptsVie = document.querySelectorAll(".pts-vie");
const ptsAgilite = document.querySelectorAll(".pts-agilite");
const ptsResistance = document.querySelectorAll(".pts-resistance");
const salleAcuelle = document.getElementById("salleActuelle");
const gameJoueurs = document.getElementById("chargerJoueur"); // sélectionne le plateau de jeu
const resistanceForce = document.getElementById("resistanceForce"); // sélectionne la flèche résistance force
const forceResistance = document.getElementById("forceResistance"); // sélectionne flèche force en résistance

let option = { 
    method: 'GET',
    headers: {
        'Content-Type': 'application/json'
    }
}

/**
 * recharger la page du plateau de jeu
 */
function rechargerPage() {
    fetch("preparer_plateau_jeu_json.php", option)
    .then(res => res.json())
    .then(data => {
        console.log(data);
        rafraichirPage(data.personnage, data, data.historique);
    })
    .catch(err => {
        console.log(err);
    });
}

/**
 * Rafraîchir la page de la liste des joueurs
 * @param {array} data 
 * @param {object} dataCurrentPlayer 
 * @param {array} dataHistorique 
 */
function rafraichirPage(data, dataCurrentPlayer, dataHistorique) {
    modifierPointForce(dataCurrentPlayer);
    modifierPointAgilite(dataCurrentPlayer);
    modifierPointResistance(dataCurrentPlayer);
    modifierPointVie(dataCurrentPlayer);
    ModifierHistorique(dataHistorique, dataCurrentPlayer);
    modifierSalleJeu(data, dataCurrentPlayer);
    modifierPiece(dataCurrentPlayer);
}

/**
 * Modifier le numéro de la pièce en fonction du joueur
 * @param {object} dataCurrentPlayer
 */
function modifierPiece(dataCurrentPlayer) {
    salleAcuelle.innerHTML = `<p>Salle n° ${dataCurrentPlayer.joueursActif[0].room}</p>`;
}

/**
 * Met à jour la liste des joueurs dans la salle
 * @param {array} data 
 * @param {object} dataCurrentPlayer 
 */
function modifierSalleJeu(data, dataCurrentPlayer) {
    let roomCurrentPlayer = dataCurrentPlayer.joueursActif[0].room;
    gameJoueurs.innerHTML = "";
    data.forEach(elt => {
        if (elt.room === roomCurrentPlayer) {
            gameJoueurs.innerHTML += `<p class="flex item-center selectAdversaire" data-id=${elt.id}>${elt.pseudo}<img src="img/attaque.png" alt="image d'épée croisée"></p>`;
        }
    });
    // Réattacher les événements après la mise à jour du DOM
    attacherClickALaSalleDeJeu()
}

/**
 * Réattacher les écouteurs d'événements aux adversaires
 */
function attacherClickALaSalleDeJeu() {
    document.querySelectorAll(".selectAdversaire").forEach(elt => {
        // on ajoute un ecouteur d'evenement au click
        elt.addEventListener('click', (e) => {
            // on recupere l'id du joueur selectionné
            console.log(e.currentTarget.dataset.id);
            let id = e.currentTarget.dataset.id;
            // on lance la fonction pour attaquer le joueur selectionné
            fetch(`attaquer.php?id=${id}`, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json'
                }
            })
            .then(res => {
                if (!res.ok) {
                    throw new Error('probleme de reponse');
                }
                return res.json();
            })
            .then(data => {
                console.log(data);
                rechargerPage(); // Appelle votre fonction pour recharger la page
            })
            .catch(error => {
                console.error('probleme avec la requete fetch:', error);
            });
        });
    });
}

/**
 * Modifie les points de force
 * @param {object} dataCurrentPlayer
 */
function modifierPointForce(dataCurrentPlayer) {
    ptsForces.forEach(elt => {
        elt.innerText = dataCurrentPlayer.joueursActif[0].force;
    });
}

/**
 * Modifie les points de vie
 * @param {object} dataCurrentPlayer
 */
function modifierPointVie(dataCurrentPlayer) {
    ptsVie.forEach(elt => {
        elt.innerText = dataCurrentPlayer.joueursActif[0].vie;
    });
}

/**
 * Modifie les points de résistance
 * @param {object} dataCurrentPlayer
 */
function modifierPointResistance(dataCurrentPlayer) {
    ptsResistance.forEach(elt => {
        elt.innerText = dataCurrentPlayer.joueursActif[0].resistance;
    });
}

/**
 * Modifie les points d'agilité
 * @param {object} dataCurrentPlayer
 */
function modifierPointAgilite(dataCurrentPlayer) {
    ptsAgilite.forEach(elt => {
        elt.innerText = dataCurrentPlayer.joueursActif[0].agilite;
    });
}

/**
 * Gère l'historique
 * @param {array} dataHistorique 
 * @param {object} dataCurrentPlayer 
 */
function ModifierHistorique(dataHistorique, dataCurrentPlayer) {
    let currentPlayer = dataCurrentPlayer.joueursActif[0].id;
    let historique = document.getElementById("historique");
    historique.innerHTML = "";
    dataHistorique.forEach(elt => {
        if (elt.personnage === currentPlayer) {
            historique.innerHTML += `
                <div class="card-historique">
                    <div>
                        <p><b>Date : </b>${elt.date_heure}</p>
                    </div>
                    <div class="flex gap10">
                        <div class="flex item-center">
                            <img src="img/life.png" alt="image d'un cœur">
                            <p>${elt.vie}</p>
                        </div>
                        <div class="flex item-center">
                            <img src="img/force.png" alt="image d'une force">
                            <p class="pts-force">${elt.force}</p>
                        </div>
                        <div class="flex item-center">
                            <img src="img/agilite.png" alt="image d'une cible">
                            <p>${elt.agilite}</p>
                        </div>
                        <div class="flex item-center">
                            <img src="img/resistance.png" alt="image d'un bouclier">
                            <p>${elt.resistance}</p>
                        </div>
                    </div>
                    <div>
                        ${elt.type_action ? `<p><b>Action : </b>${elt.type_action}</p>` : ""}
                        ${elt.mouvement ? `<p class="mouvement"><b>Mouvement : </b>${elt.mouvement}</p>` : ""}
                        ${elt.detail ? `<p class="detail"><b>detail : </b>${elt.detail}</p>` : ""}
                    </div>
                </div>
            `;
        }
    });
}

/**
 * Ecouteur d'événement sur le bouton suivant pour aller à la pièce suivante
 */
suivant.addEventListener('click', () => {
    fetch("aller_piece_suivante.php")
    .then(() => {
        rechargerPage();
    });
});

/**
 * Ecouteur d'événement sur le bouton précédent pour aller à la pièce précédente
 */
precedente.addEventListener('click', () => {
    fetch("aller_piece_precedente.php")
    .then(() => {
        rechargerPage();
    });
});

/**
 * Ecouteur d'événement sur le bouton forceResistance pour échanger les points
 */
forceResistance.addEventListener('click', () => {
    fetch("echanger_force_resistance.php")
    .then(() => {
        rechargerPage();
    });
});

/**
 * Ecouteur d'événement sur le bouton resistanceForce pour échanger les points
 */
resistanceForce.addEventListener('click', () => {
    fetch("echanger_resistance_force.php")
    .then(() => {
        rechargerPage();
    });
});

/**
 * Initialiser la page en rechargeant le plateau de jeu
 */
setInterval(rechargerPage, 500);
