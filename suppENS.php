<?php
$id = $_POST['numero'];

// Assurez-vous d'avoir défini les informations de connexion correctes
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bddtp1";

// Connexion à la base de données
$db = mysqli_connect($servername, $username, $password, $dbname);

// Vérification de la connexion
if (!$db) {
    die('Erreur de connexion à la base de données : ' . mysqli_connect_error());
}

// Requête SQL pour supprimer l'enseignant en utilisant l'ID avec une requête préparée
$sql = "DELETE FROM Enseignant WHERE Numero = ?";
$stmt = mysqli_prepare($db, $sql);
mysqli_stmt_bind_param($stmt, "i", $id); // i pour un entier

// Exécution de la requête de suppression
if (mysqli_stmt_execute($stmt)) {
    echo "L'enseignant a été supprimé avec succès";
} else {
    echo "Erreur lors de la suppression de l'enseignant " . mysqli_error($db);
}

// Fermeture de la requête
mysqli_stmt_close($stmt);

// Fermeture de la connexion à la base de données
mysqli_close($db);
?>
