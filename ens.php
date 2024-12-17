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
        $nationalites[] = $row["code"];
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8" />
    <title>Formulaire</title>
    <link rel="stylesheet" type="text/css" href="style.css" />
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
    
    
   <br>
<form action="enregistrerENS.php" method="post">
  <div class="menu-container">
    <input type="submit" value="etudiant" formaction="index.php" />
    <input type="submit" value="enseignant" formaction="ens.php" />
    <input type="submit" value="module" formaction="module.html" />
    <input type="submit" value="bulletin de notes" formaction="Bultain.php" />
   <!-- <input type="submit" value="PV" formaction="meftahi.php/" />
    <input type="submit" name="stat_button" value="Statistiques" formaction="stat.php" />
    <input type="submit" name="mo" value="Statistiques" formaction="moo.php" />
    <input type="submit" name="mo" value="Afficher List Users " formaction="Use.php" />
    <input
        type="submit"
        name="mo"
        value="Statistique_user  "
        formaction="Statuse.php"
      />
    <br><br>--> 
    </div>

    <input type="text" searchedId="searchedId" name="searchedId" />
    <input type="submit" value="Rechercher" formaction="rechercherENS.php" /><br><br><hr>
  <label for="numero">Numéro :</label>
  <input type="text" id="numero" name="numero" /><br /><br />

 <label>Civilité:</label>
      <input type="radio" id="monsieur" name="Civilite" value="Monsieur" />
      <label for="monsieur">Monsieur</label>
      <input type="radio" id="madame" name="Civilite" value="Madame" />
      <label for="madame">Madame</label>
      <input
        type="radio"
        id="mademoiselle"
        name="Civilite"
        value="Mademoiselle"
      />
      <label for="mademoiselle">Mademoiselle</label><br /><br />

  <label for="nomprenom">NOM/Prénom :</label>
  <input type="text" id="nomprenom" name="nomprenom" /><br /><br />

  <label for="adresse">Adresse :</label>
  <input type="text" id="adresse" name="adresse" /><br /><br />

  <label for="datenaissance">Date de Naissance :</label>
  <input type="date" id="datenaissance" name="datenaissance" /><br /><br />

  <label for="lieunaissance">Lieu de Naissance :</label>
  <input type="text" id="lieunaissance" name="lieunaissance" /><br /><br />

  <label for="pays">Pays:</label>
      <select id="pays" name="pays">
      <?php
        foreach ($nationalites as $nationalite) {
            echo "<option value=\"$nationalite\">$nationalite</option>";
        }
        ?></select
      ><br /><br />

  <label for="grade">Grade :</label>
  <select id="grade" name="grade">
    <option value="Assistant">Assistant</option>
    <option value="MAB">MAB</option>
    <option value="MAA">MAA</option>
    <option value="MCB">MCB</option>
    <option value="MCA">MCA</option>
    <option value="Professeur">Professeur</option></select
  ><br /><br />

  <label for="specialite">Spécialité:</label>
<select id="specialite" name="specialite">
    <option value="informatique">Informatique</option>
    <option value="mathematiques">Mathématiques</option>
    <option value="anglais">Anglais</option>
    <option value="autres">Autres</option>
</select>
<br /><br />

  <input type="submit" value="Affichage PHP" formaction="traitementENS.php" />
  <input
        type="submit"
        value="Affichage JavaScript"
        onclick="affichageJavascript()"
      />
  <input type="submit" value="Enregistrer" name="enregistrer" />
  <input type="submit" value="Affichage Liste" formaction="affENS.php" />
  <input
    type="file"
    id="imageFile"
    accept="image/*"
    style="display: none"
  />
  <input type="submit" value= "Insérer une image" onclick="chooseImage()">
  



  <input
    type="submit"
    value="Supprimer"
    formaction="suppENS.php"
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
    var civiliteElements = document.getElementById("Civilite").value;
    var pays = document.getElementById("pays").value;
    var nomPrenom = document.getElementById("nomprenom").value;
    var adresse = document.getElementById("adresse").value;
    var dateNaissance = document.getElementById("datenaissance").value;
    var lieuNaissance = document.getElementById("lieunaissance").value;
    var grade = document.getElementById("grade").value;
    var specialite = document.getElementById("specialite").value;

    var message =
      "Numéro: " +
      numero +
      "\nCivilité: " +
      civiliteElements +
      "\nPays: " +
      pays +
      "\nNom/Prénom: " +
      nomPrenom +
      "\nAdresse: " +
      adresse +
      "\nDate de Naissance: " +
      dateNaissance +
      "\nLieu de Naissance: " +
      lieuNaissance +
      "\nGrade: " +
      grade +
      "\nSpécialité: " +
      specialite;

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
</form>
