// Je declare mes constante
const show = document.getElementById('show');
const showReview = document.getElementById('show-review');
const password = document.querySelector('#password');
const verifPassword = document.querySelector('#verif-password');
const force = document.getElementById('force');
const resultForce = document.getElementById('resultForce');
const agilite = document.getElementById('agilite');
const resultAgilite = document.querySelector('.resultAgilite');
const resistance = document.getElementById('resistance');
const resultResistance = document.getElementById('resultResistance');
const points = document.querySelector('.pts');
let pointADistribuer = 15;
let pointForce = 3;
let pointAgilite = 3;
let pointResistance = 3;

// j'ecoute les evenements pour afficher les mots de passe
show.addEventListener('click', () => {
    password.type = (password.type === "password") ? "text" : "password";
});
showReview.addEventListener('click', () => {
    verifPassword.type = (password.type === "password") ? "text" : "password";
});
//Gestion de distribution des point 
agilite.addEventListener('change', (e) => {
    resultAgilite.innerText = e.target.value
    pointAgilite = e.target.value
    decrementePoint(15)
});
force.addEventListener('change', (a) => {
    resultForce.innerText = a.target.value;
    pointForce = a.target.value;
    decrementePoint(15)
});
resistance.addEventListener('change', (b) => {
    resultResistance.innerText = b.target.valueAsNumber;
    pointResistance = b.target.valueAsNumber;
    decrementePoint(15)
});
/**
 * decrementer point passer en parametre
 * @param {number} valeurPoint 
 * return neant
 */
function decrementePoint (valeurPoint){
    if(pointADistribuer > 0){
        pointADistribuer = valeurPoint - (pointForce*1 + pointAgilite*1 + pointResistance*1)
        points.innerHTML = pointADistribuer
    }else {
        alert("Plus de point a distribuer")
    }
}
decrementePoint(15)