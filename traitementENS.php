<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8" />
    <title>Résultat du Formulaire</title>
</head>
<body>
    <h1>Résultat du Formulaire</h1>
    
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        echo "<p>Numéro: " . $_POST["numero"] . "</p>";
        echo "<p>Civilité: " . $_POST["Civilite"] . "</p>";
        echo "<p>Pays: " . $_POST["pays"] . "</p>";
        echo "<p>Nom et Prénom: " . $_POST["nomprenom"] . "</p>";
        echo "<p>Adresse: " . $_POST["adresse"] . "</p>";
        echo "<p>Date de Naissance: " . $_POST["datenaissance"] . "</p>";
        echo "<p>Lieu de Naissance: " . $_POST["lieunaissance"] . "</p>";
        echo "<p>Grade: " . $_POST["grade"] . "</p>";
        echo "<p>Spécialité: " . $_POST["specialite"] . "</p>";
    } else {
        echo "<p>Aucune donnée soumise.</p>";
    }
    ?>

    <p><a href="ens.php">Retour au formulaire</a></p>
</body>
</html>
