<?php
// controleur de test : utilisation de la librairie tierce gérées par composeur

// initilialsation,

include "utils/init.php";

$mailer = new PHPMailer\PHPMailer\PHPMailer();
print_r($mailer);