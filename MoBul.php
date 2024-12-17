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

// Récupérer le numéro d'étudiant du formulaire
$num_etudiant = $_POST["Numero"];

// Récupérer le code du module à modifier
$codeModule = $_POST["CodModule"];

// Récupérer la nouvelle note de l'utilisateur
$newNote = $_POST["Note"];

// Mettre à jour la note dans la base de données
$sql_update_note = "UPDATE notes SET Note = $newNote WHERE Num_Etudiant = $num_etudiant AND Code_module = '$codeModule'";
if ($conn->query($sql_update_note) === TRUE) {
    echo "Note mise à jour avec succès.";
} else {
    echo "Erreur lors de la mise à jour de la note : " . $conn->error;
}

$conn->close();
?>
