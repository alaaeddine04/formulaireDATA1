<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bddtp1";
$message = '';  // Démarrer la session si ce n'est pas déjà fait
try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['studentEmail'])) {
        // Récupérer l'adresse e-mail de l'utilisateur depuis le formulaire
        $email = $_POST['studentEmail'];
        $nom = $_POST['name'];
        $prenom = $_POST['prename'];
        $filiere = $_POST['fil'];
        $txt = $_POST['txt'];
        // Autres informations à inclure dans l'e-mail
        $subject = "Bulletin de notes";
    // Requête pour récupérer l'utilisateur par son email depuis la base de données
    $stmt = $conn->prepare("SELECT * FROM user WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $user = $stmt->fetch();

    // Récupérer les notes de l'étudiant avec les noms des matières depuis la base de données
    $stmt_notes_etudiant = $conn->prepare("SELECT notes.Note, notes.Coefficient, module.DesignationModule 
FROM notes 
INNER JOIN module ON notes.Code_module = module.CodeModule 
WHERE notes.Num_Etudiant = :etudiant_id");
    $stmt_notes_etudiant->bindParam(':etudiant_id', $user['etudiant_id']);
    $stmt_notes_etudiant->execute();
    $notes_etudiant = $stmt_notes_etudiant->fetchAll();
    
    if ($notes_etudiant) {
        $totalNotes = 0;
        $totalCoefficients = 0;

        // Calcul de la moyenne
        foreach ($notes_etudiant as $note) {
            $totalNotes += $note['Note'] * $note['Coefficient'];
            $totalCoefficients += $note['Coefficient'];
        }

        $moyenne = ($totalCoefficients > 0) ? ($totalNotes / $totalCoefficients) : 0;

        // Affichage du bulletin
        $message .=  "<style>
body {
font-family: 'Arial', sans-serif;
background-color: #f4f4f4;
margin: 0;
padding: 0;
}

.container {
max-width: 800px;
margin: 0 auto;
padding: 20px;
}

h1 {
color: #333;
text-align: center;
}

p {
color: #666;
margin-bottom: 10px;
}

table {
width: 100%;
border-collapse: collapse;
margin-top: 20px;
}

th, td {
border: 1px solid #ddd;
padding: 12px;
text-align: left;
}

th {
background-color: #0366d6;
color: white;
}

tr:nth-child(even) {
background-color: #f2f2f2;
}

tr:hover {
background-color: #ddd;
}
</style>";
        $message ="<h2>Bulletin de notes</h2>";
        $message .= "Nom : $nom";

        $message .= "<br><br>";
        $message .= "Prenom : $prenom";
        $message .= "<br><br>";
        $message .= "Filiere : $filiere";
        $message .= "<br><br>";
        $message .= "$txt";
        $message .= "<table border='1'>";
        $message .= "<tr><th>Matière</th><th>Note</th></tr>";

        foreach ($notes_etudiant as $note) {
            $message .= "<tr><td>{$note['DesignationModule']}</td><td>{$note['Note']}</td></tr>";
        }

        $message .= "</table>";
        $message .= "<br><br><br>";
        $message .= "<form method='post' action='send.php'>";
        $message .= "<input type='text' name='moyenne' value='" . number_format($moyenne, 2) . "'>";
        $message .= "</form>";

       
    
        } else {
            $message = "Aucun bulletin de notes disponible.";
        }

        // Configuration de PHPMailer
        $mail = new PHPMailer(true);
        try {
            // Configuration de l'email
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; // Remplacez par votre serveur SMTP
            $mail->SMTPAuth = true;
            $mail->Username = 'kaddourisaadedu@gmail.com'; // Remplacez par votre email
            $mail->Password = 'yvvd inki arwr hmnb'; // Remplacez par votre mot de passe SMTP
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;
            // Paramètres de l'email
            $mail->setFrom('votre_email@gmail.com', 'Departement informatique');
     
            // Utiliser l'e-mail de l'utilisateur récupéré depuis le formulaire
            $mail->addAddress($email);
                 // Contenu de l'email
                 $mail->isHTML(true);
                 $mail->Subject = $subject;
                 $mail->Body = $message;
            // Envoi de l'email
            $mail->send();
            echo 'Le bulletin a été envoyé avec succès à ' . $email;
        } catch (Exception $e) {
            echo "L'e-mail n'a pas pu être envoyé. Erreur : {$mail->ErrorInfo}";
        }
    } else {
        echo "Aucune adresse e-mail n'a été fournie.";
    }
} else {
    echo "Méthode de requête incorrecte.";
}
?>
   
