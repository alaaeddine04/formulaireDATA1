<?php
// Vérification si l'ID et les autres champs sont présents
if (isset($_POST['numero']) && isset($_POST['civilite']) && isset($_POST['pays']) && isset($_POST['nom']) && isset($_POST['prenom']) && isset($_POST['adresse']) && isset($_POST['postal1']) && isset($_POST['postal2']) && isset($_POST['plateform']) && isset($_POST['application']) && isset($_POST['nationalite']) && isset($_POST['filiere'])) {
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
    $civilite = $_POST['civilite'];
    $pays = $_POST['pays'];
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $adresse = $_POST['adresse'];
    $postal1 = $_POST['postal1'];
    $postal2 = $_POST['postal2'];
    $plateform = implode(", ", $_POST['plateform']); // Convertit le tableau en chaîne de caractères
    $application = $_POST['application'];
    $nationalite = $_POST['nationalite'];
    $filiere = $_POST['filiere']; // Récupération de la valeur de la filière

    // Requête SQL pour mettre à jour l'enregistrement
    $sql = "UPDATE formulaire_data SET 
            numero='$numero', 
            civilite='$civilite', 
            pays='$pays', 
            nom='$nom', 
            prenom='$prenom', 
            adresse='$adresse', 
            postal1='$postal1', 
            postal2='$postal2', 
            plateform='$plateform', 
            application='$application', 
            nationalite='$nationalite',
            filiere='$filiere' 
            WHERE numero='$numero'";

    // Exécution de la requête de mise à jour
    if ($conn->query($sql) === TRUE) {
        echo "Enregistrement mis à jour avec succès";
    } else {
        echo "Erreur lors de la mise à jour de l'enregistrement : " . $conn->error;
    }

    // Fermeture de la connexion à la base de données
    $conn->close();
} else {
    echo "Veuillez fournir toutes les valeurs nécessaires pour la mise à jour de l'enregistrement";
}
?>
