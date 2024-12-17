<?php
// Connexion à la base de données (assurez-vous d'avoir défini les informations de connexion correctes)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bddtp1";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("La connexion à la base de données a échoué : " . $conn->connect_error);
}

// Récupérer le numéro d'étudiant et le code du module à partir de la requête POST
$num_etudiant = $_POST["Numero"];
$code_module = $_POST["CodModule"];

// Requête pour supprimer la note de l'étudiant pour le module donné
$sql = "DELETE FROM notes WHERE Num_Etudiant = $num_etudiant AND Code_module = '$code_module'";

if ($conn->query($sql) === true) {
    echo "Note supprimée avec succès.";
} else {
    echo "Erreur lors de la suppression de la note : " . $conn->error;
}

$conn->close();
?>
