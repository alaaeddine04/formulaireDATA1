<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Assurez-vous de prendre des mesures de sécurité pour éviter les attaques par injection SQL ici

    $code_module = $_POST['code_module'];
    $designation = $_POST['designation'];
    $coefficient = $_POST['coefficient'];
    $volume_horaire = $_POST['volume_horaire'];
    $filiere = $_POST['filiere'];
    $option = $_POST['option'];

    // Connexion à la base de données
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "bddtp1";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("La connexion à la base de données a échoué : " . $conn->connect_error);
    }

    // Requête SQL pour mettre à jour les données du module
    $sql = "UPDATE Module SET DesignationModule='$designation', Coefficient=$coefficient, VolumeHoraire=$volume_horaire, Filiere='$filiere', option='$option' WHERE CodeModule='$code_module'";

    if ($conn->query($sql) === TRUE) {
        echo "Les données du module ont été mises à jour avec succès";
    } else {
        echo "Erreur lors de la mise à jour des données du module : " . $conn->error;
    }

    $conn->close();
}
