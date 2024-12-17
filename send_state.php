<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "tp33";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch gender statistics (you can replace this with actual values from the database)
$maleCount = 30;
$femaleCount = 20;

// Total students
$totalStudents = $maleCount + $femaleCount;
$malePercentage = ($maleCount / $totalStudents) * 100;
$femalePercentage = ($femaleCount / $totalStudents) * 100;

// HTML content for email
$emailBody = "<h1>Statistiques des Étudiants</h1>";
$emailBody .= "<p><strong>Nombre total d'étudiants:</strong> $totalStudents</p>";
$emailBody .= "<p><strong>Masculin:</strong> $maleCount ($malePercentage%)</p>";
$emailBody .= "<p><strong>Féminin:</strong> $femaleCount ($femalePercentage%)</p>";
$emailBody .= "<h2>Graphiques</h2>";
$emailBody .= "<p>Histogramme:</p>";
$emailBody .= "<img src='cid:histogramChart'>";
$emailBody .= "<p>Camembert:</p>";
$emailBody .= "<img src='cid:pieChart'>";

// Send email
$mail = new PHPMailer(true);
try {
    // SMTP Configuration
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'alaakadari22@gmail.com'; // Your email
    $mail->Password = 'wfpo upyz cgjn momj'; // App password
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    // Email setup
    $mail->setFrom('alaakadari22@gmail.com', 'Statistiques Admin');
    $mail->addAddress($_POST['recipient_email']);
    $mail->Subject = "Statistiques des Étudiants";
    $mail->isHTML(true);
    $mail->Body = $emailBody;

    // Attach charts as inline images
    $mail->addEmbeddedImage('charts/histogram_chart.png', 'histogramChart');
    $mail->addEmbeddedImage('charts/pie_chart.png', 'pieChart');

    // Send email
    $mail->send();
    echo "L'e-mail contenant les statistiques a été envoyé avec succès.";
} catch (Exception $e) {
    echo "L'envoi de l'e-mail a échoué. Erreur de PHPMailer : {$mail->ErrorInfo}";
}

// Close the database connection
$conn->close();
?>
