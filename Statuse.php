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
$num_etudiant = $_POST["Numero"];

// Rechercher l'étudiant
$sql = "SELECT Nom, Prenom, Filiere, Civilite FROM formulaire_data WHERE numero = $num_etudiant";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $nom = $row["Nom"];
    $prenom = $row["Prenom"];
    $filiere = $row["Filiere"];
    $civilite = $row["Civilite"];

    // Récupérer les informations de la note depuis la table "notes" avec jointure sur la table "module"
    $sql_note = "SELECT notes.Code_module, module.DesignationModule, notes.Coefficient, notes.Note 
                 FROM notes 
                 INNER JOIN module ON notes.Code_module = module.CodeModule
                 WHERE notes.Num_Etudiant = $num_etudiant";
    $result_note = $conn->query($sql_note);

    if ($result_note->num_rows > 0) {
        $totalNotes = 0;
        $totalCoefficients = 0;
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Notes de l'étudiant</title>
            <style>
                body {
                    font-family: 'Arial', sans-serif;
                    background-color: #f4f4f4;
                    margin: 0;
                    padding: 0;
                }

                .container {
                    max-width: 800px;
                    margin: 0 auto;
                    padding: 20px;
                }

                h1 {
                    color: #333;
                    text-align: center;
                }

                p {
                    color: #666;
                    margin-bottom: 10px;
                }

                table {
                    width: 100%;
                    border-collapse: collapse;
                    margin-top: 20px;
                }

                th, td {
                    border: 1px solid #ddd;
                    padding: 12px;
                    text-align: left;
                }

                th {
                    background-color: #0366d6;
                    color: white;
                }

                tr:nth-child(even) {
                    background-color: #f2f2f2;
                }

                tr:hover {
                    background-color: #ddd;
                }
            </style>
        </head>
        <body>
            <h1>Notes de l'étudiant</h1>
            <p>Numéro d'étudiant: <?php echo $num_etudiant; ?></p>
            <p>Nom/Prénom: <?php echo $nom . ' ' . $prenom; ?></p>
            <p>Filière: <?php echo $filiere; ?></p>
            <h2>Notes:</h2>
            <table>
                <tr>
                    <th>Code Module</th>
                    <th>Nom du Module</th>
                    <th>Coefficient</th>
                    <th>Note</th>
                </tr>
                <?php
                while ($row_note = $result_note->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row_note["Code_module"] . "</td>";
                    echo "<td>" . $row_note["DesignationModule"] . "</td>";
                    echo "<td>" . $row_note["Coefficient"] . "</td>";
                    echo "<td>" . ($row_note["Note"] !== null ? $row_note["Note"] : 'Note non saisie') . "</td>";
                    echo "</tr>";

                    // Ajouter les notes et coefficients pour calculer la moyenne
                    $totalNotes += $row_note["Note"] * $row_note["Coefficient"];
                    $totalCoefficients += $row_note["Coefficient"];
                }

                // Calcul de la moyenne
                $moyenne = ($totalCoefficients > 0) ? ($totalNotes / $totalCoefficients) : 0;
                ?>
            </table>

            <p>Moyenne: <?php echo number_format($moyenne, 2); ?></p>
        </body>
        </html>
        <?php
    } else {
        echo "Aucune note disponible pour cet étudiant.";
    }
} else {
    echo "Étudiant inexistant.";
}

$conn->close();
?>
