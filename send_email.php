<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bddtp1";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch num_etudiant from POST request
$numEtudiant = $_POST['numero'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $numEtudiant) {
    $recipientEmail = $_POST['recipient_email']; // Email entered by the user

    // Fetch student info
    $sqlStudent = "SELECT nom, prenom, filiere FROM formulaire_data WHERE numero = '$numEtudiant'";
    $resultStudent = $conn->query($sqlStudent);
    $studentData = $resultStudent->fetch_assoc();

    if (!$studentData) {
        die("No student found with ID $numEtudiant");
    }

    // Fetch student grades
    $sqlGrades = "
        SELECT 
            module.DesignationModule AS Nom_module, 
            notes.Code_module, 
            notes.Coefficient, 
            notes.Note 
        FROM notes 
        INNER JOIN module ON notes.Code_module = module.CodeModule 
        WHERE notes.Num_Etudiant = '$numEtudiant'";
    $resultGrades = $conn->query($sqlGrades);

    // Calculate grades summary
    $sumCoefficients = 0;
    $sumCoeffNotes = 0;
    $average = 0;
    $notesList = [];

    while ($row = $resultGrades->fetch_assoc()) {
        $notesList[] = $row;
        $sumCoefficients += $row['Coefficient'];
        $sumCoeffNotes += $row['Coefficient'] * $row['Note'];
    }

    if ($sumCoefficients > 0) {
        $average = $sumCoeffNotes / $sumCoefficients;
    }

    $result = $average >= 10 ? "réussi" : "échoué";

    // Prepare email content
    $emailBody = "<h1>Bulletin de Note</h1>";
    $emailBody .= "<p><strong>Nom:</strong> " . htmlspecialchars($studentData['nom']) . "</p>";
    $emailBody .= "<p><strong>Prénom:</strong> " . htmlspecialchars($studentData['prenom']) . "</p>";
    $emailBody .= "<p><strong>Filière:</strong> " . htmlspecialchars($studentData['filiere']) . "</p>";
    $emailBody .= "<h2>Notes:</h2>";
    $emailBody .= "<table border='1' cellspacing='0' cellpadding='5' style='border-collapse: collapse; width: 100%;'>
                    <thead>
                        <tr>
                            <th style='background-color: #5cb85c; color: #fff;'>Code Module</th>
                            <th style='background-color: #5cb85c; color: #fff;'>Nom du Module</th>
                            <th style='background-color: #5cb85c; color: #fff;'>Coefficient</th>
                            <th style='background-color: #5cb85c; color: #fff;'>Note</th>
                        </tr>
                    </thead>
                    <tbody>";

    foreach ($notesList as $note) {
        $emailBody .= "<tr>
                        <td>" . htmlspecialchars($note['Code_module']) . "</td>
                        <td>" . htmlspecialchars($note['Nom_module']) . "</td>
                        <td>" . htmlspecialchars($note['Coefficient']) . "</td>
                        <td>" . htmlspecialchars($note['Note'] !== null ? $note['Note'] : 'Note non saisie') . "</td>
                       </tr>";
    }

    $emailBody .= "</tbody></table>";
    $emailBody .= "<p><strong>Somme des Coefficients:</strong> $sumCoefficients</p>";
    $emailBody .= "<p><strong>Somme (Coeff * Notes):</strong> $sumCoeffNotes</p>";
    $emailBody .= "<p><strong>Moyenne:</strong> " . number_format($average, 2) . "</p>";
    $emailBody .= "<p><strong>Résultat:</strong> $result</p>";

    // Send the email
    $mail = new PHPMailer(true);
    try {
        // SMTP Configuration
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'alaakadari22@gmail.com'; // Your email
        $mail->Password = 'wfpo upyz cgjn momj'; // App password (or real password)
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        // Email setup
        $mail->setFrom('alaakadari22@gmail.com', 'Bulletin Admin');
        $mail->addAddress($recipientEmail, "{$studentData['prenom']} {$studentData['nom']}");
        $mail->Subject = "Bulletin de Note - Étudiant: $numEtudiant";
        $mail->isHTML(true);
        $mail->Body = $emailBody;

        // Send email
        $mail->send();
        echo "L'e-mail a été envoyé avec succès.";
    } catch (Exception $e) {
        echo "L'envoi de l'e-mail a échoué. Erreur de PHPMailer : {$mail->ErrorInfo}";
    }
} else {
    echo "Étudiant inexistant ou donnée manquante.";
}

// Close database connection
$conn->close();
?>
