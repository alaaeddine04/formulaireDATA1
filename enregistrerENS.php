<?php
// Vérifie si le formulaire a été soumis
if (isset($_POST['enregistrer'])) {
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
    $numero = $_POST['numero'];
    $civilite = $_POST['Civilite'];
    $nomPrenom = $_POST['nomprenom'];
    $adresse = $_POST['adresse'];
    $dateNaissance = $_POST['datenaissance'];
    $lieuNaissance = $_POST['lieunaissance'];
    $pays = $_POST['pays'];
    $grade = $_POST['grade'];
    $specialite = $_POST['specialite'];

    // Prépare la requête d'insertion
    $sql = "INSERT INTO Enseignant (Numero, Civilite, NomPrenom, Adresse, DateNaissance, LieuNaissance, Pays, Grade, Specialite) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);

    // Vérifie si la préparation de la requête a réussi
    if ($stmt === false) {
        die("Erreur de préparation de la requête : " . $conn->error);
    }

    // Lie les paramètres à la requête
    $stmt->bind_param("issssssss", $numero, $civilite, $nomPrenom, $adresse, $dateNaissance, $lieuNaissance, $pays, $grade, $specialite);

    // Exécute la requête
    if ($stmt->execute()) {
        echo "Enregistrement réussi.";
    } else {
        echo "Erreur lors de l'enregistrement : " . $stmt->error;
    }

    // Ferme la connexion à la base de données
    $conn->close();
} else {
    echo "Le formulaire n'a pas été soumis.";
}
?>
