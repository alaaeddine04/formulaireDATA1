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
    $civilite = $_POST['civilite'];
    $pays = $_POST['pays'];
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $adresse = $_POST['adresse'];
    $postal1 = $_POST['postal1'];
    $postal2 = $_POST['postal2'];
    $plateform = isset($_POST['plateform']) ? implode(", ", $_POST['plateform']) : "";
    $application = $_POST['application'];
    $nationalite = $_POST['nationalite'];
    $filiere = $_POST['filiere'];

    // Prépare la requête d'insertion avec la filière
    $sql = "INSERT INTO formulaire_data (numero, civilite, pays, nom, prenom, adresse, postal1, postal2, plateform, application, nationalite, filiere) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);

    // Vérifie si la préparation de la requête a réussi
    if ($stmt === false) {
        die("Erreur de préparation de la requête : " . $conn->error);
    }

    // Lie les paramètres à la requête, y compris la filière
    $stmt->bind_param("ssssssssssss", $numero, $civilite, $pays, $nom, $prenom, $adresse, $postal1, $postal2, $plateform, $application, $nationalite, $filiere);

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
