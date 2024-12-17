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

// Sélectionner les codes de la table "nationalites"
$sql = "SELECT * FROM nationalites";
$result = $conn->query($sql);

$nationalites = array(); // Tableau pour stocker les codes de nationalité

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $nationalites[] = $row["nationalite"];
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8" />
    <title>Formulaire</title>
<link rel="stylesheet" type="text/css" href="style.css"> 
<style>
        /* Menu Container */
        .menu-container {
            display: flex;
            justify-content: center;
            background-color: #333;
            padding: 10px 0;
        }

        /* Style for menu buttons */
        .menu-container input[type="submit"] {
            background-color: #555;
            color: white;
            border: none;
            padding: 10px 20px;
            margin: 5px;
            cursor: pointer;
            font-size: 16px;
            border-radius: 5px;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        /* Hover effect for buttons with zoom */
        .menu-container input[type="submit"]:hover {
            background-color: #777;
            transform: scale(1.1); /* Zoom effect */
        }
    </style>
</head>
<body>
    <form action="enregistrer.php" method="post">
        <!-- Menu with Zoom Effect -->
        <div class="menu-container">
            <input type="submit" value="etudiant" formaction="index.php" />
            <input type="submit" value="enseignant" formaction="ens.php" />
            <input type="submit" value="module" formaction="module.html" />
            <input type="submit" value="bulletin de notes" formaction="Bultain.php" />
            <input type="submit" value="PV" formaction="meftahi.php/" />
            <input type="submit" value="Statistiques" formaction="stat.php" />
            <input type="submit" name="mo" value="Afficher List Users " formaction="Use.php" />
            <input type="submit" value="Déconnexion" formaction="login.php" style="background-color: red;" />

        </div>
    
    <br /><br />
    <label for="searchedId">Numéro:</label>
    <input type="text" searchedId="searchedId" name="searchedId" style="min-width: 150px; max-width: 300px; width: 100%;"/>
      <input type="submit" value="Rechercher" formaction="rechercher.php" /><br><br><hr>

      <label for="numero">Numéro:</label>
      <input type="text" id="numero" name="numero" /><br /><br />

      <label>Civilité:</label>
      <input type="radio" id="monsieur" name="civilite" value="Monsieur" />
      <label for="monsieur">Monsieur</label>
      <input type="radio" id="madame" name="civilite" value="Madame" />
      <label for="madame">Madame</label>
      <input
        type="radio"
        id="mademoiselle"
        name="civilite"
        value="Mademoiselle"
      />
      <label for="mademoiselle">Mademoiselle</label><br /><br />

      <label for="nom">Nom:</label>
      <input type="text" id="nom" name="nom" /><br /><br />

      <label for="prenom">Prénom:</label>
      <input type="text" id="prenom" name="prenom" /><br /><br />

      <label for="adresse">Adresse:</label>
      <input type="text" id="adresse" name="adresse" /><br /><br />

      <label for="postal1">No Postal:</label>
      <input type="text" id="postal1" name="postal1" />
      <input type="text" id="postal2" name="postal2" /><br /><br />

      <label for="pays">Pays:</label>
      <select id="pays" name="pays">
      <?php
        foreach ($nationalites as $nationalite) {
            echo "<option value=\"$nationalite\">$nationalite</option>";
        }
        ?></select
      ><br /><br />

      <label for="plateform">Plateforme(s):</label>
      <input type="checkbox" id="windows" name="plateform[]" value="Windows" />
      <label for="windows">Windows</label>
      <input
        type="checkbox"
        id="macintosh"
        name="plateform[]"
        value="Macintosh"
      />
      <label for="macintosh">Macintosh</label>
      <input type="checkbox" id="unix" name="plateform[]" value="Unix" />
      <label for="unix">Unix</label><br /><br />

      <label for="application">Application(s):</label>
      <select id="application" name="application">
        <option value="Bureautique">Bureautique</option>
        <option value="DAO">DAO</option>
        <option value="Statistique">Statistique</option>
        <option value="SGBD">SGBD</option>
        <option value="Internet">Internet</option>
        <option value="SAO">SAO</option>
        </select
      ><br /><br />

      <label for="nationalite">Nationalite:</label>
      <select id="nationalite" name="nationalite">
        <?php
        foreach ($nationalites as $code) {
            echo "<option value=\"$code\">$code</option>";
        }
        ?>
      </select><br /><br />
      <label for="filiere">Filière d'inscription:</label>
<select id="filiere" name="filiere">
    <option value="TC">TC</option>
    <option value="2SC">2 SC</option>
    <option value="3ISIL">3 ISIL</option>
    <option value="3SI">3SI</option>
    <option value="M1">M1</option>
    <option value="M2ISI">M2ISI</option>
    <option value="M2WIC">M2WIC</option>
    <option value="M2RSSI">M2RSSI</option>
    <option value="1ING">1ING</option>
    <option value="2ING">2ING</option>
</select>
<br /><br />


      <input type="submit" value="Affichage PHP" formaction="traitement.php" />
      <input
        type="submit"
        value="Affichage JavaScript"
        onclick="affichageJavascript()"
      />
      <input type="submit" value="Enregistrer" name="enregistrer" />
      <input type="submit" value="Affichage Liste" formaction="aff.php" />
      <input
        type="file"
        id="imageFile"
        accept="image/*"
        style="display: none"
      />
      <input type="submit" value= "Insérer une image" onclick="chooseImage()">
      

     <input type="submit" value="Nationalite" formaction="nationaliteList.html" />

      <input
        type="submit"
        value="Supprimer"
        formaction="supprimer.php"
        onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet étudiant?')"
      />
      <div id="imagePreview"></div>
      
    </form>

    <script>
      document.getElementById('choix').addEventListener('change', function() {
    var selectedOption = this.value;
    if (selectedOption === 'etudiant') {
        window.location.href = 'index.php';
    } else if (selectedOption === 'enseignant') {
        window.location.href = 'ens.html';
    } else if (selectedOption === 'module') {
        window.location.href = 'module.html';
    }
});

      function affichageJavascript() {
        var numero = document.getElementById("numero").value;
        var civiliteElements = document.getElementsByName("civilite");
        var civilite;
        for (var i = 0; i < civiliteElements.length; i++) {
          if (civiliteElements[i].checked) {
            civilite = civiliteElements[i].value;
            break;
          }
        }
        var pays = document.getElementById("pays").value;
        var nom = document.getElementById("nom").value;
        var prenom = document.getElementById("prenom").value;
        var adresse = document.getElementById("adresse").value;
        var postal1 = document.getElementById("postal1").value;
        var postal2 = document.getElementById("postal2").value;
        var platformElements = document.querySelectorAll(
          'input[name="plateform[]"]:checked'
        );
        var selectedPlatforms = [];
        for (var i = 0; i < platformElements.length; i++) {
          selectedPlatforms.push(platformElements[i].value);
        }
        var application = document.getElementById("application").value;

        var message =
          "Numéro: " +
          numero +
          "\nCivilité: " +
          civilite +
          "\nPays: " +
          pays +
          "\nNom: " +
          nom +
          "\nPrénom: " +
          prenom +
          "\nAdresse: " +
          adresse +
          "\nCode Postal: " +
          postal1 +
          "\nLocalité: " +
          postal2 +
          "\nPlateforme(s): " +
          selectedPlatforms.join(", ") +
          "\nApplication: " +
          application;

        alert(message);
      }

      function chooseImage() {
        var imageFileInput = document.getElementById("imageFile");
        imageFileInput.click();
      }

      // Lorsque l'utilisateur sélectionne un fichier d'image, afficher un aperçu
      document
        .getElementById("imageFile")
        .addEventListener("change", function (event) {
          var imageFile = event.target.files[0];
          var imagePreview = document.getElementById("imagePreview");

          if (imageFile) {
            var reader = new FileReader();

            reader.onload = function (e) {
              var img = document.createElement("img");
              img.src = e.target.result;
              img.style.maxWidth = "200px"; // Ajustez la taille de l'aperçu selon vos besoins
              imagePreview.innerHTML = "";
              imagePreview.appendChild(img);
            };

            reader.readAsDataURL(imageFile);
          }
        });
    </script>
  </body>
</html>
