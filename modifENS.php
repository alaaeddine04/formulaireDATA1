<?php
// Vérification si l'ID et les autres champs sont présents
    // Assurez-vous d'avoir défini les informations de connexion correctes
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "bddtp1";

    // Connexion à la base de données
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Vérification de la connexion
    if ($conn->connect_error) {
        die('Erreur de connexion à la base de données : ' . $conn->connect_error);
    }

    // Récupération des valeurs des champs
    $numero = $_POST['numero'];
    $civilite = $_POST['Civilite'];
    $nomPrenom = $_POST['nomprenom'];
    $adresse = $_POST['adresse'];
    $dateNaissance = $_POST['datenaissance'];
    $lieuNaissance = $_POST['lieunaissance'];
    $pays = $_POST['pays'];
    $grade = $_POST['grade'];
    $specialite = $_POST['specialite'];

    // Requête SQL pour mettre à jour l'enregistrement
    $sql = "UPDATE Enseignant SET 
            Civilite='$civilite', 
            NomPrenom='$nomPrenom', 
            Adresse='$adresse', 
            DateNaissance='$dateNaissance', 
            LieuNaissance='$lieuNaissance', 
            Pays='$pays', 
            Grade='$grade', 
            Specialite='$specialite' 
            WHERE Numero='$numero'";

    // Exécution de la requête de mise à jour
    if ($conn->query($sql) === TRUE) {
        echo "Enregistrement mis à jour avec succès";
    } else {
        echo "Erreur lors de la mise à jour de l'enregistrement : " . $conn->error;
    }

    // Fermeture de la connexion à la base de données
    $conn->close();

?>
