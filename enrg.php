<?php
// Vérifie si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupère les données du formulaire
    $code = $_POST["code"];
    $nationalite = $_POST["nationalite"];
    


    // Connexion à la base de données
    $conn = new mysqli('localhost', 'root', '', 'bddtp1');

    // Vérifie la connexion
    if ($conn->connect_error) {
        die("La connexion à la base de données a échoué: " . $conn->connect_error);
    }

    // Prépare la requête SQL pour insérer les données dans la table
    $sql = "INSERT INTO nationalites (code, nationalite) 
            VALUES (?, ?)";
    
    // Utilise une déclaration préparée pour éviter les injections SQL
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $code,$nationalite);


    // Exécute la requête d'insertion
    if ($stmt->execute()) {
        echo "Enregistrement réussi.";
    } else {
        echo "Erreur lors de l'enregistrement: " . $stmt->error;
    }

    // Ferme la déclaration préparée et la connexion à la base de données
    $stmt->close();
    $conn->close();
} else {
    // Redirige l'utilisateur vers le formulaire s'il tente d'accéder à ce fichier directement
    header("Location: nationaliteList.html");
    exit;
}
?>
