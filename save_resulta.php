<?php
// Connexion à la base de données
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bddtp1";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("La connexion à la base de données a échoué : " . $conn->connect_error);
}

// Récupérer les données du formulaire
$num_etudiant = $_POST["num_etudiant"];
$moyenne = $_POST["moyenne"];
$status = $_POST["status"];

// Mettre à jour la moyenne et le statut dans la table formulaire_data
$sql_update = "UPDATE formulaire_data SET Moyenne = $moyenne, Resultat = '$status' WHERE numero = $num_etudiant";

if ($conn->query($sql_update) === TRUE)
