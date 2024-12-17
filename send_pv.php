<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';
require 'phpmailer/src/Exception.php';

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bddtp1";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Récupérer la liste des enseignants
    $emailQuery = "SELECT email FROM enseignant";
    $emailStmt = $conn->query($emailQuery);
    $emails = $emailStmt->fetchAll(PDO::FETCH_COLUMN);

    // Capture the HTML content from the previous page
    ob_start();
    include 'meftahi.php'; // Ensure this file outputs the HTML content
    $content = ob_get_clean(); // Get the buffered content

    // Configurer PHPMailer
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'alaakadari22@gmail.com'; // Votre adresse email
    $mail->Password = 'wfpo upyz cgjn momj'; // Votre mot de passe ou mot de passe d'application
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    $mail->setFrom('alaakadari22@gmail.com', 'Admin');
    $mail->Subject = 'Tableau des Moyennes Pondérées des Étudiants';
    $mail->isHTML(true);
    $mail->Body = $content;

    // Envoyer l'email à chaque enseignant
    foreach ($emails as $email) {
        $mail->addAddress($email);
        $mail->send();
        $mail->clearAddresses(); // Nettoyer les adresses pour éviter les doublons
    }

    echo "Emails envoyés avec succès.";
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
} catch (Exception $e) {
    echo "Erreur lors de l'envoi de l'email : " . $e->getMessage();
}

$conn = null;
?>