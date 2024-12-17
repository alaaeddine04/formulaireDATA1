<?php
// Connexion à la base de données
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bddtp1";

// Vérification de la connexion
try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

// Traitement du formulaire d'inscription
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role']; // Récupération du rôle depuis le champ caché

    // Hacher le mot de passe
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insérer dans la base de données
    $stmt = $conn->prepare("INSERT INTO user (email, mdp, role) VALUES (:email, :password, :role)");
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $hashed_password);
    $stmt->bindParam(':role', $role);
    $stmt->execute();

    // Redirection en fonction du rôle
    if ($role == 'User') {
        header("Location: Bultain1.php");
    } else {
        header("Location: index.php");
    }
    exit();
}

?>
