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
        echo "<p>Civilite: " . $_POST["civilite"] . "</p>";
        echo "<p>Pays: " . $_POST["pays"] . "</p>";
        echo "<p>Nom: " . $_POST["nom"] . "</p>";
        echo "<p>Prénom: " . $_POST["prenom"] . "</p>";
        echo "<p>Adresse: " . $_POST["adresse"] . "</p>";
        echo "<p>Code Postal: " . $_POST["postal1"] . "</p>";
        echo "<p>Localité: " . $_POST["postal2"] . "</p>";
        
        if (isset($_POST["plateform"])) {
            echo "<p>Plateforme(s): " . implode(", ", $_POST["plateform"]) . "</p>";
        }
        
        echo "<p>Application: " . $_POST["application"] . "</p>";
    } else {
        echo "<p>Aucune donnée soumise.</p>";
    }
    ?>

    <p><a href="ens.php">Retour au formulaire</a></p>
</body>
</html>
