<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Assurez-vous de prendre des mesures de sécurité pour éviter les attaques par injection SQL ici
    $searchedId = $_POST['searchedId'];

    // Connexion à la base de données
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "bddtp1";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("La connexion à la base de données a échoué : " . $conn->connect_error);
    }

    // Récupérer les données de la table "nationalites"
    $sql_nationalites = "SELECT code FROM nationalites";
    $result_nationalites = $conn->query($sql_nationalites);

    $nationalites = array(); // Tableau pour stocker les données de nationalités

    if ($result_nationalites->num_rows > 0) {
        while ($row_nationalite = $result_nationalites->fetch_assoc()) {
            $nationalites[] = $row_nationalite["code"];
        }
    }

    // Requête pour récupérer les données correspondant au numéro recherché
    $sql = "SELECT * FROM formulaire_data WHERE numero = '$searchedId'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Affichage des données
        while ($row = $result->fetch_assoc()) {
            echo " <head>
           
        
            <!-- Lien vers le fichier CSS externe -->
            <!-- <link rel='stylesheet' type='text/css' href='style.css'>  -->
        </head>";
            // Créer un formulaire avec les valeurs préremplies pour chaque champ
            echo "<form action='modif.php' method='post'>";
            echo "<label for='numero'>Numéro:</label>";
            echo "<input type='text' id='numero' name='numero' value='" . $row['numero'] . "'/><br /><br />";

            // Civilité
            echo "<label>Civilité:</label>";
            echo "<input type='radio' id='monsieur' name='civilite' value='Monsieur' " . ($row['civilite'] == 'Monsieur' ? 'checked' : '') . "/>";
            echo "<label for='monsieur'>Monsieur</label>";
            echo "<input type='radio' id='madame' name='civilite' value='Madame' " . ($row['civilite'] == 'Madame' ? 'checked' : '') . "/>";
            echo "<label for='madame'>Madame</label>";
            echo "<input type='radio' id='mademoiselle' name='civilite' value='Mademoiselle' " . ($row['civilite'] == 'Mademoiselle' ? 'checked' : '') . "/>";
            echo "<label for='mademoiselle'>Mademoiselle</label><br/><br/>";

            // Nom
            echo "<label for='nom'>Nom:</label>";
            echo "<input type='text' id='nom' name='nom' value='" . $row['nom'] . "'/><br /><br />";

            // Prénom
            echo "<label for='prenom'>Prénom:</label>";
            echo "<input type='text' id='prenom' name='prenom' value='" . $row['prenom'] . "'/><br /><br />";

            // Adresse
            echo "<label for='adresse'>Adresse:</label>";
            echo "<input type='text' id='adresse' name='adresse' value='" . $row['adresse'] . "'/><br /><br />";

            // No Postal
            echo "<label for='p1'>No Postal:</label>";
            echo "<input type='text' id='postal1' name='postal1' value='" . $row['postal1'] . "'/>";
            
            echo "<input type='text' id='postal2' name='postal2' value='" . $row['postal2'] . "'/><br /><br />";

            // Pays
            echo "<label for='pays'>Pays:</label>";
            echo "<select id='pays' name='pays'>";
            foreach ($nationalites as $code) {
                echo "<option value='$code' " . ($row['pays'] == $code ? 'selected' : '') . ">$code</option>";
            }
            echo "</select><br /><br />";

            // Plateforme(s)
            echo "<label for='plateform'>Plateforme(s):</label>";
            $platforms = explode(", ", $row['plateform']); // Assurez-vous que les plateformes sont séparées par une virgule suivie d'un espace
            echo "<input type='checkbox' id='windows' name='plateform[]' value='Windows' " . (in_array('Windows', $platforms) ? 'checked' : '') . "/>";
            echo "<label for='windows'>Windows</label>";
            echo "<input type='checkbox' id='macintosh' name='plateform[]' value='Macintosh' " . (in_array('Macintosh', $platforms) ? 'checked' : '') . "/>";
            echo "<label for='macintosh'>Macintosh</label>";
            echo "<input type='checkbox' id='unix' name='plateform[]' value='Unix' " . (in_array('Unix', $platforms) ? 'checked' : '') . "/>";
            echo "<label for='unix'>Unix</label><br/><br />";

            // Application(s)
            echo "<label for='application'>Application(s):</label>";
            echo "<select id='application' name='application'>";
            $options = ['Bureautique', 'DAO', 'Statistique', 'SGBD', 'Internet', 'SAO']; // Ajoutez d'autres options si nécessaire
            foreach ($options as $option) {
                echo "<option value='$option' " . ($row['application'] == $option ? 'selected' : '') . ">$option</option>";
            }
            echo "</select><br /><br />";
            // Pays
            echo "<label for='nationalite'>Nationalite:</label>";
            echo "<select id='nationalite' name='nationalite'>";
            foreach ($nationalites as $code) {
                echo "<option value='$code' " . ($row['nationalite'] == $code ? 'selected' : '') . ">$code</option>";
            }
            echo "</select><br /><br />";
            // Ajoutez d'autres options d'application ici en suivant le modèle ci-dessus
            echo "<label for='filiere'>filiere:</label>";
echo "<select id='filiere' name='filiere'>";
$options = ['TC', '2 SC', '3 ISIL', '3SI', 'M1', 'M2ISI','M2WIC','M2RSSI','1ING','2ING']; // Ajoutez d'autres options si nécessaire
foreach ($options as $option) {
    echo "<option value='$option' " . ($row['filiere'] == $option ? 'selected' : '') . ">$option</option>";
}
echo "</select><br /><br />";
            
            echo "<input
        type='submit'
        value='Modifier'
        onclick=\"return confirm('Êtes-vous sûr de vouloir modifier cet étudiant?')\"
      />";
 echo "</form>";

        }
    } else {
        echo "Aucun résultat trouvé pour ce numéro.";
    }

    $conn->close();
}
?>
