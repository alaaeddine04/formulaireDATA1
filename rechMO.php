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

    // Requête SQL pour récupérer les données du module correspondant au code de module recherché
    $sql = "SELECT * FROM Module WHERE CodeModule = '$searchedId'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Affichage des données
        while ($row = $result->fetch_assoc()) {
            // Créer un formulaire avec les valeurs préremplies pour chaque champ
            echo " <head>
           
        
            <!-- Lien vers le fichier CSS externe -->
            <link rel='stylesheet' type='text/css' href='style.css'>
        </head>";
            echo "<form action='modifMO.php' method='post'>";
            echo "<label for='code_module'>Code Module:</label><br />";
            echo "<input type='text' id='code_module' name='code_module' value='" . $row['CodeModule'] . "' /><br /><br />";

            echo "<label for='designation'>Désignation Module:</label><br />";
            echo "<input type='text' id='designation' name='designation' value='" . $row['DesignationModule'] . "' /><br /><br />";

            echo "<label for='coefficient'>Coefficient:</label><br />";
            echo "<input type='text' id='coefficient' name='coefficient' value='" . $row['Coefficient'] . "' /><br /><br />";

            echo "<label for='volume_horaire'>Volume Horaire:</label><br />";
            echo "<input type='text' id='volume_horaire' name='volume_horaire' value='" . $row['VolumeHoraire'] . "' /><br /><br />";
                   // option
                   echo "<label>Option:</label>";
            echo "<input type='radio' id='option' name='option' value='semestrielle' " . ($row['option'] == 'semestrielle' ? 'checked' : '') . "/>";
            echo "<label for='semestre'>semestre</label>";
            echo "<input type='radio' id='optiion' name='option' value='annuel' " . ($row['option'] == 'annuel' ? 'checked' : '') . "/>";
            echo "<label for='annuel'>annuel</label><br><br>";
            //*********** */
            echo "<label for='filiere'>Filière:</label><br />";
            echo "<select id='filiere' name='filiere'>";
            echo "<option value='TC' " . ($row['Filiere'] == 'TC' ? 'selected' : '') . ">TC</option>";
            echo "<option value='2SC' " . ($row['Filiere'] == '2SC' ? 'selected' : '') . ">2SC</option>";
            echo "<option value='3ISIL' " . ($row['Filiere'] == '3ISIL' ? 'selected' : '') . ">3ISIL</option>";
            echo "<option value='3SI' " . ($row['Filiere'] == '3SI' ? 'selected' : '') . ">3SI</option>";
            echo "<option value='M1' " . ($row['Filiere'] == 'M1' ? 'selected' : '') . ">M1</option>";
            echo "<option value='M2ISI' " . ($row['Filiere'] == 'M2ISI' ? 'selected' : '') . ">M2ISI</option>";
            echo "<option value='M2WIC' " . ($row['Filiere'] == 'M2WIC' ? 'selected' : '') . ">M2WIC</option>";
            echo "<option value='M2RSSI' " . ($row['Filiere'] == 'M2RSSI' ? 'selected' : '') . ">M2RSSI</option>";
            echo "<option value='1ING' " . ($row['Filiere'] == '1ING' ? 'selected' : '') . ">1ING</option>";
            echo "<option value='2ING' " . ($row['Filiere'] == '2ING' ? 'selected' : '') . ">2ING</option>";
            echo "</select><br /><br />";

            echo "<input type='submit' value='Modifier' />";
            echo "</form>";
        }
    } else {
        echo "Aucun résultat trouvé pour ce Code Module.";
    }

    $conn->close();
}
