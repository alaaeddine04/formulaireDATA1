<?php
// Assurez-vous d'avoir défini les informations de connexion correctes
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bddtp1";

// Vérification si le formulaire a été soumis
if (isset($_POST['code_module'])) {
    // Récupérer l'ID du module à supprimer
    $module_id = $_POST['code_module'];

    // Connexion à la base de données
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Vérification de la connexion
    if ($conn->connect_error) {
        die("La connexion à la base de données a échoué : " . $conn->connect_error);
    }

    // Requête SQL pour supprimer le module
    $sql = "DELETE FROM Module WHERE CodeModule = '$module_id'";

    // Exécution de la requête de suppression
    if ($conn->query($sql) === TRUE) {
        echo "Le module a été supprimé avec succès.";
    } else {
        echo "Erreur lors de la suppression du module : " . $conn->error;
    }

    // Fermeture de la connexion à la base de données
    $conn->close();
}
?>
