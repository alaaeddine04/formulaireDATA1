<?php
// Connexion à la base de données
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
                    echo "<form method='post' action='send.php'>";
                     echo "<br><br><br>";
                     echo "<div style='width: 50%; margin: auto; background-color: #f9f9f9; padding: 20px; border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); font-family: Arial, sans-serif;'>";

                     echo "<h3 style='color: #333;'>Informations de l'étudiant :</h3>";
                     echo "<p style='margin: 5px 0;'><strong>Nom :</strong> " . $etudiant['nom'] . "</p>";
                     echo "<p style='margin: 5px 0;'><strong>Prénom :</strong> " . $etudiant['prenom'] . "</p>";
                     echo "<p style='margin: 5px 0;'><strong>Filière :</strong> " . $etudiant['filiere'] . "</p>";
                     
                     echo "<form method='POST' action='envoi_bulletin.php'>";
                     echo "<input type='hidden' id='name' name='name' value='" . $etudiant['nom'] . "'>";
                     echo "<input type='hidden' id='prename' name='prename' value='" . $etudiant['prenom'] . "'>";
                     echo "<input type='hidden' id='fil' name='fil' value='" . $etudiant['filiere'] . "'>";
                     
                     echo "<label for='studentEmail' style='display: block; margin: 10px 0 5px; font-weight: bold;'>E-mail étudiant :</label>";
                     echo "<input type='text' id='studentEmail' name='studentEmail' value='" . $user['email'] . "' style='width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 5px;'>";
                     
                     echo "<label for='txt' style='display: block; margin: 10px 0 5px; font-weight: bold;'>Message :</label>";
                     echo "<textarea id='txt' name='txt' style='width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 5px;'></textarea>";
                     
                     echo "<button type='submit' style='margin-top: 15px; padding: 10px 15px; background-color: #4CAF50; color: white; border: none; border-radius: 5px; cursor: pointer;'>Envoyer le bulletin par e-mail</button>";
                     echo "</form>";
                     
                     echo "</div>";
                     
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
        background-color: #4CAF50;
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
                        echo "<form method='post' action='send.php'>";
                       echo "<label for='moyenne' style='display: block; margin: 10px 0 5px; font-weight: bold;'>Moyenne :</label>";
echo "<input type='text' name='moyenne' value='" . number_format($moyenne, 2) . "' readonly 
      style='width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 5px; background-color: #f9f9f9; font-size: 16px; color: #333;'>";

                        echo "</form>";

                       
                    } else {
                        echo "Aucune note disponible pour cet étudiant.";
                    }
                } else {
                    echo "Étudiant inexistant.";
                }
                $showLoginForm = false;
            } else {
                header("Location: formulaire_index.php");
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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Page de connexion</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <hr>
    <br><br><br>
    <form method="post">

    <?php if ($showLoginForm) { ?>
    <div class="login-container">
        <?php if (!empty($message)) { ?>
            <p><?php echo $message; ?></p>
        <?php } ?>
        
            <h2>Connexion</h2>
            <input type="email" id="email" name="email" placeholder="Adresse e-mail" required>
            <input type="password" id="password" name="password" placeholder="Mot de passe" required>
            <input type="submit" name="submitType" value="valider" formaction="valider.php">
            <input type="submit" onclick="showUserTypeForm()" value="Inscription" formaction="inscription.php">
            <div id="userTypeForm" style="display: none;">
                <select name="role" onchange="updateUserType()">
                    <option value="Admin">Admin</option>
                    <option value="User">User</option>
                </select>
            </div>
        </form>
    </div>
    <?php } ?>
    <script>
        function showUserTypeForm() {
            document.getElementById('userTypeForm').style.display = 'block';
        }

        function updateUserType() {
            var selectedRole = document.querySelector('select[name="roleSelection"]').value;
            document.getElementById('userType').value = selectedRole;
            document.querySelector('form').submit(); // Soumettre le formulaire
        }
    </script>
</body>
</html>
