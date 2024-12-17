<?php
// Vérification si les champs sont présents
if (isset($_POST['code_module']) && isset($_POST['designation']) && isset($_POST['coefficient']) && isset($_POST['volume_horaire']) && isset($_POST['filiere'])) {
    // Assurez-vous d'avoir défini les informations de connexion correctes
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "bddtp1";

    // Connexion à la base de données
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Vérification de la connexion
    if ($conn->connect_error) {
        die("La connexion à la base de données a échoué: " . $conn->connect_error);
    }

    // Récupération des valeurs des champs
    $code_module = $_POST['code_module'];
    $designation = $_POST['designation'];
    $coefficient = $_POST['coefficient'];
    $volume_horaire = $_POST['volume_horaire'];
    $filiere = $_POST['filiere'];
    $option = $_POST['option'];

    // Requête SQL pour insérer un enregistrement
    $sql = "INSERT INTO Module (CodeModule, DesignationModule, Coefficient, VolumeHoraire, Filiere,option) VALUES ('$code_module', '$designation', $coefficient, $volume_horaire, '$filiere','$option')";

    // Exécution de la requête
    if ($conn->query($sql) === TRUE) {
        echo "Enregistrement réussi.";
    } else {
        echo "Erreur lors de l'enregistrement : " . $conn->error;
    }

    // Fermeture de la connexion à la base de données
    $conn->close();
} else {
    echo "Veuillez fournir toutes les valeurs nécessaires pour l'enregistrement.";
}
