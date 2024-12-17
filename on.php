<?php
// Connexion à la base de données
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bddtp1";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("La connexion à la base de données a échoué : " . $conn->connect_error);
}

// Récupérer les informations de la note avec jointure
$sql_notes = "SELECT formulaire_data.numero AS Num_Etudiant, 
                     formulaire_data.Nom, 
                     formulaire_data.Prenom, 
                     module.DesignationModule, 
                     notes.Coefficient, 
                     notes.Note 
              FROM notes 
              INNER JOIN module ON notes.Code_module = module.CodeModule
              INNER JOIN formulaire_data ON notes.Num_Etudiant = formulaire_data.numero
              ORDER BY formulaire_data.numero, module.DesignationModule";
$result_notes = $conn->query($sql_notes);

if ($result_notes->num_rows > 0) {
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Notes de tous les étudiants</title>
        <style>
            /* General styling */
            body {
                font-family: 'Arial', sans-serif;
                background-color: #f9f9f9;
                margin: 0;
                padding: 0;
            }

            .container {
                max-width: 900px;
                margin: 40px auto;
                padding: 20px;
                background-color: #ffffff;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                border-radius: 8px;
            }

            h1 {
                color: #333;
                text-align: center;
                margin-bottom: 20px;
                font-size: 24px;
                font-weight: bold;
            }

            p {
                color: #666;
                margin-bottom: 10px;
                font-size: 14px;
            }

            table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 20px;
                border-radius: 8px;
                overflow: hidden;
            }

            th, td {
                padding: 14px 20px;
                text-align: left;
                border-bottom: 1px solid #ddd;
                font-size: 16px;
            }

            th {
                background-color: #4CAF50;
                color: white;
                font-weight: bold;
            }

            tr:nth-child(even) {
                background-color: #f9f9f9;
            }

            tr:hover {
                background-color: #f1f1f1;
            }

            /* Responsive adjustments */
            @media (max-width: 768px) {
                .container {
                    padding: 10px;
                }

                th, td {
                    font-size: 14px;
                }
            }
        </style>
    </head>
    <body>
        <div class="container">
            <h1>Notes de tous les étudiants</h1>
            <table>
                <tr>
                    <th>Numéro d'étudiant</th>
                    <th>Nom/Prénom</th>
                    <th>Module</th>
                    <th>Coefficient</th>
                    <th>Note</th>
                </tr>
                <?php
                while ($row_note = $result_notes->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row_note["Num_Etudiant"] . "</td>";
                    echo "<td>" . $row_note["Nom"] . ' ' . $row_note["Prenom"] . "</td>";
                    echo "<td>" . $row_note["DesignationModule"] . "</td>";
                    echo "<td>" . $row_note["Coefficient"] . "</td>";
                    echo "<td>" . ($row_note["Note"] !== null ? $row_note["Note"] : 'Note non saisie') . "</td>";
                    echo "</tr>";
                }
                ?>
            </table>
        </div>
    </body>
    </html>
    <?php
} else {
    echo "Aucune note disponible pour les étudiants.";
}

$conn->close();
?>
