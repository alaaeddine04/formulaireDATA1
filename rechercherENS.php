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
    $sql = "SELECT * FROM Enseignant WHERE Numero = '$searchedId'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Affichage des données
        while ($row = $result->fetch_assoc()) {
            // Créer un formulaire avec les valeurs préremplies pour chaque champ
            echo " <head>
           
        
            <!-- Lien vers le fichier CSS externe -->
            <link rel='stylesheet' type='text/css' href='style.css'>
        </head>";
            echo "<form action='modifENS.php' method='post'>";
            echo "<label for='numero'>Numéro:</label>";
            echo "<input type='text' id='numero' name='numero' value='" . $row['Numero'] . "'/><br /><br />";
   
            echo "<label>Civilité:</label>";
            echo "<input type='radio' id='monsieur' name='Civilite' value='Monsieur' " . ($row['Civilite'] == 'Monsieur' ? 'checked' : '') . "/>";
            echo "<label for='monsieur'>Monsieur</label>";
            echo "<input type='radio' id='madame' name='Civilite' value='Madame' " . ($row['Civilite'] == 'Madame' ? 'checked' : '') . "/>";
            echo "<label for='madame'>Madame</label>";
            echo "<input type='radio' id='mademoiselle' name='Civilite' value='Mademoiselle' " . ($row['Civilite'] == 'Mademoiselle' ? 'checked' : '') . "/>";
            echo "<label for='mademoiselle'>Mademoiselle</label><br/><br/>";

            // NomPrenom
            echo "<label for='nomprenom'>NOM/Prénom:</label>";
            echo "<input type='text' id='nomprenom' name='nomprenom' value='" . $row['NomPrenom'] . "' /><br /><br />";

            // Adresse
            echo "<label for='adresse'>Adresse:</label>";
            echo "<input type='text' id='adresse' name='adresse' value='" . $row['Adresse'] . "' /><br /><br />";

            // DateNaissance
            echo "<label for='datenaissance'>Date de Naissance:</label>";
            echo "<input type='date' id='datenaissance' name='datenaissance' value='" . $row['DateNaissance'] . "' /><br /><br />";

            // LieuNaissance
            echo "<label for='lieunaissance'>Lieu de Naissance:</label>";
            echo "<input type='text' id='lieunaissance' name='lieunaissance' value='" . $row['LieuNaissance'] . "' /><br /><br />";

           // Pays
           echo "<label for='pays'>pays:</label>";
           echo "<select id='pays' name='pays'>";
           foreach ($nationalites as $code) {
               echo "<option value='$code' " . ($row['pays'] == $code ? 'selected' : '') . ">$code</option>";
           }
           echo "</select><br /><br />";
            // Grade
            echo "<label for='grade'>Grade:</label>";
            echo "<select id='grade' name='grade'>
            <option value='Assistant' " . ($row['Grade'] == 'Assistant' ? 'selected' : '') . ">Assistant</option>
            <option value='MAB' " . ($row['Grade'] == 'MAB' ? 'selected' : '') . ">MAB</option>
            <option value='MAA' " . ($row['Grade'] == 'MAA' ? 'selected' : '') . ">MAA</option>
            <option value='MCB' " . ($row['Grade'] == 'MCB' ? 'selected' : '') . ">MCB</option>
            <option value='MCA' " . ($row['Grade'] == 'MCA' ? 'selected' : '') . ">MCA</option>
            <option value='Professeur' " . ($row['Grade'] == 'Professeur' ? 'selected' : '') . ">Professeur</option></select><br /><br />";

            // Specialite
            echo "<label for='specialite'>Spécialité:</label>";
            echo "<select id='specialite' name='specialite'>
            <option value='informatique' " . ($row['Specialite'] == 'informatique' ? 'selected' : '') . ">Informatique</option>
            <option value='mathematiques' " . ($row['Specialite'] == 'mathematiques' ? 'selected' : '') . ">Mathématiques</option>
            <option value='anglais' " . ($row['Specialite'] == 'anglais' ? 'selected' : '') . ">Anglais</option>
            <option value='autres' " . ($row['Specialite'] == 'autres' ? 'selected' : '') . ">Autres</option></select><br /><br />";

            echo "<input
                    type='submit'
                    value='Modifier'
                    onclick=\"return confirm('Êtes-vous sûr de vouloir modifier cet enseignant?')\"
                  />";
            echo "</form>";
        }
    } else {
        echo "Aucun résultat trouvé pour ce numéro.";
    }

    $conn->close();
}
?>
