<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bddtp1";
$message = ''; 
$showLoginForm = true; // Initialisez une variable pour stocker le message
try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

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

    if ($user) {
        // Vérification du mot de passe
        if (password_verify($password, $user['mdp'])) {
            // Mot de passe correct, rediriger en fonction du rôle
            if ($user['role'] == 'User') {
                // Récupérer les données de l'étudiant
                $stmt_etudiant = $conn->prepare("SELECT * FROM formulaire_data WHERE numero = :etudiant_id");
                $stmt_etudiant->bindParam(':etudiant_id', $user['etudiant_id']);
                $stmt_etudiant->execute();
                $etudiant = $stmt_etudiant->fetch();

                // Affichage des détails de l'étudiant
                if ($etudiant) {
                    echo "<br><br><br>";
                    echo "Nom de l'étudiant : " . $etudiant['nom'] . "<br>";
                    echo "Prénom de l'étudiant : " . $etudiant['prenom'] . "<br>";
                    echo "<form method='post'>";
                    echo "<span id='studentEmail' name='studentEmail'>Email : " . $user['email'] . "</span><br>"; 
                    echo "</form>";
                    echo "<br><br><br>";

                    // Affichage des notes de l'étudiant avec les noms des matières
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
                        echo "<style>
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
                        echo "<h2>Bulletin de notes</h2>";
                        echo "<table border='1'>";
                        echo "<tr><th>Matière</th><th>Note</th></tr>";

                        foreach ($notes_etudiant as $note) {
                            echo "<tr><td>{$note['DesignationModule']}</td><td>{$note['Note']}</td></tr>";
                        }

                        echo "</table>";
                        echo "<br><br><br>";
                        echo "Moyenne: " . number_format($moyenne, 2) . "<br>";
                    } else {
                        echo "Aucune note disponible pour cet étudiant.";
                    }
                } else {
                    echo "Étudiant inexistant.";
                }
                $showLoginForm = false;
            } else {
                header("Location: index.php");
                exit();
            }
        } else {
            // Mot de passe incorrect
            $message = "Mot de passe incorrect. Veuillez réessayer.";
        }
    } else {
        // Utilisateur inexistant, message pour inciter à l'inscription
        $message = "Cet utilisateur n'existe pas. Veuillez vous inscrire.";
    }
}

$bulletinNotesHTML = ''; // Remplissez cette variable avec le contenu HTML de votre bulletin de notes

// Stockage du bulletin de notes dans la session
session_start(); // Démarrage de la session si ce n'est pas déjà fait
$_SESSION['bulletinNotes'] = $bulletinNotesHTML;

// Redirection vers la page d'envoi d'e-mail ou autre action nécessaire
header("Location: send.php"); // Remplacez send.php par votre page d'envoi d'e-mail ou l'action nécessaire
exit();
?>