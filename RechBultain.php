<?php
// Connexion à la base de données (assurez-vous d'avoir défini les informations de connexion correctes)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bddtp1";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("La connexion à la base de données a échoué : " . $conn->connect_error);
}

// Récupérer le numéro d'étudiant du formulaire
$num_etudiant = $_POST["Number"];

// Rechercher l'étudiant
$sql = "SELECT Nom, Prenom, Filiere, Civilite FROM formulaire_data WHERE numero = $num_etudiant";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $nom = $row["Nom"];
    $prenom = $row["Prenom"];
    $filiere = $row["Filiere"];
    $civilite = $row["Civilite"];

    // Liste des filières disponibles
    $filieres = array("TC", "2 SC", "3 ISIL", "3SI", "M1", "M2ISI", "M2WIC", "M2RSSI", "1ING", "2ING");

    // Récupérer les modules depuis la table "module" pour la filière de l'étudiant
    $sql_modules = "SELECT CodeModule, DesignationModule FROM module WHERE Filiere = '$filiere'";
    $result_modules = $conn->query($sql_modules);

    $modules = array(); // Tableau pour stocker les modules

    if ($result_modules->num_rows > 0) {
        while ($row_module = $result_modules->fetch_assoc()) {
            $modules[$row_module["CodeModule"]] = $row_module["DesignationModule"];
        }
    }

    // Récupérer les informations de la note depuis la table "notes"
    $sql_note = "SELECT Code_module, Coefficient, Note FROM notes WHERE Num_Etudiant = $num_etudiant";
    $result_note = $conn->query($sql_note);

    $codeModule = "";
    $coefficient = "";
    $note = "";

    if ($result_note->num_rows > 0) {
        $row_note = $result_note->fetch_assoc();
        $codeModule = $row_note["Code_module"];
        $coefficient = $row_note["Coefficient"];
        $note = $row_note["Note"];
    }

    // Récupérer le coefficient du module à partir de la table "module"
    if (!empty($codeModule)) {
        $sql_module_coefficient = "SELECT Coefficient FROM module WHERE CodeModule = '$codeModule'";
        $result_module_coefficient = $conn->query($sql_module_coefficient);
        
        if ($result_module_coefficient->num_rows > 0) {
            $row_module_coefficient = $result_module_coefficient->fetch_assoc();
            $coefficient = $row_module_coefficient["Coefficient"];
        }
    }

    // Affichez les informations de l'étudiant et de la note sur un autre formulaire
    ?>
    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Votre Titre de Page</title>
    <!-- Lien vers le fichier CSS externe -->
     <link rel="stylesheet" type="text/css" href="style.css"> 
</head>
    <form action="EnrgBul.php" method="post">
        <label for="Numero">Numero :</label>
        <input type="text" id="Numero" name="Numero" value="<?php echo $num_etudiant; ?>" readonly>
        <br /><br />
        <label for="Civilite">Civilité :</label>
        <input type="radio" id="monsieur" name="Civilite" value="Monsieur" <?php echo ($civilite == "Monsieur") ? "checked" : ""; ?>>
        <label for="monsieur">Monsieur</label>
        <input type="radio" id="madame" name="Civilite" value="Madame" <?php echo ($civilite == "Madame") ? "checked" : ""; ?>>
        <label for="madame">Madame</label>
        <input type="radio" id="mademoiselle" name="Civilite" value="Mademoiselle" <?php echo ($civilite == "Mademoiselle") ? "checked" : ""; ?>>
        <label for="mademoiselle">Mademoiselle</label>
        <br /><br />
        <label for="NomPrenom">Nom/Prénom :</label>
        <input type="text" id="NomPrenom" name="NomPrenom" value="<?php echo $nom . ' ' . $prenom; ?>" readonly>
        <br /><br />
        
<label for="Filiere">Filière:</label>
<input type="text" id="Filiere" name="Filiere" value="<?php echo $filiere; ?>" readonly>


        <br /><br />
    
        <label for="Module">Module :</label>
        <select id="Module" name="Module" onchange="updateFields() ">
    <?php
    foreach ($modules as $moduleCode => $designationModule) {
        echo "<option value='$moduleCode'>$designationModule</option>";
    }
    ?>
</select>

        <br /><br />
        <label for="CodModule">Code Module:</label>
        <input type="text" id="CodModule" name="CodModule" value="<?php echo $codeModule; ?>" readonly><br /><br />
        <label for="Cof">Coefficient:</label>
        <input type="text" id="Cof" name="Cof" value="<?php echo $coefficient; ?>" readonly><br /><br />


        <label for="Note">Note:</label>
<input type="text" id="Note" name="Note" value="<?php echo empty($note) ? 'Note non Saisie' : $note; ?>" /><br /><br />

        <input type="submit" value="Enregistrer" formaction="EnrgBul.php" />
        <input type="submit" value="Modifier" formaction="MoBul.php/" />
        <input type="submit" value="Supprimer" formaction="SuppgBul.php/" />
        <input type="submit" value="Afficher Bultain" formaction="off.php/" />
        <input type="submit" value="Afficher Liste" formaction="on.php/" />
        
    </form>

   
    <script>
function updateFields() {
    var selectedModule = document.getElementById("Module");
    var selectedModuleValue = selectedModule.options[selectedModule.selectedIndex].value;
    
    // Récupérer le numéro d'étudiant
    var numEtudiant = document.getElementById("Numero").value;
    
    // Faites une requête AJAX pour obtenir le code du module, le coefficient et la note
    var xhr = new XMLHttpRequest();
    xhr.open("GET", "getModuleInfo.php?num_etudiant=" + numEtudiant + "&module=" + selectedModuleValue, true);
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            var response = JSON.parse(xhr.responseText);
            var codModuleInput = document.getElementById("CodModule");
            var cofInput = document.getElementById("Cof");
            var noteInput = document.getElementById("Note");
            
            codModuleInput.value = response.codeModule ? response.codeModule : '';
            cofInput.value = response.coefficient ? response.coefficient : '';
            noteInput.value = response.note !== null ? response.note : 'Note non saisie';
        }
    };
    xhr.send();
}

// Appeler la fonction pour initialiser les champs lorsque la page est chargée
updateFields();
</script>


    <?php
} else {
    echo "Étudiant inexistant.";
}

$conn->close();
?>
