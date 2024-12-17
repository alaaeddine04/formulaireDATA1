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

// Récupérer le numéro d'étudiant du formulaire
$num_etudiant = $_POST["numero"];

// Rechercher l'étudiant
$sql = "SELECT Nom, Prenom, Filiere, Civilite FROM formulaire_data WHERE numero = $num_etudiant";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $nom = $row["Nom"];
    $prenom = $row["Prenom"];
    $filiere = $row["Filiere"];
    $civilite = $row["Civilite"];

    // Récupérer les informations de la note
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
                * {
                    box-sizing: border-box;
                }

                body {
                    font-family: Arial, sans-serif;
                    background-color: #f3f6fb;
                    margin: 0;
                    padding: 0;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    height: 100vh;
                }

                .container {
                    max-width: 800px;
                    width: 100%;
                    background-color: #fff;
                    border-radius: 8px;
                    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
                    padding: 20px;
                    margin: 20px;
                    color: #333;
                }

                h1, h2 {
                    text-align: center;
                    color: #444;
                    margin-top: 0;
                }

                .info {
                    display: flex;
                    justify-content: space-between;
                    font-size: 1rem;
                    color: #555;
                    background-color: #eaf0f6;
                    padding: 15px;
                    border-radius: 6px;
                    margin-bottom: 20px;
                }

                .info p {
                    margin: 0;
                }

                table {
                    width: 100%;
                    border-collapse: collapse;
                    margin-top: 20px;
                    font-size: 0.95rem;
                    background-color: #f9f9f9;
                    border-radius: 6px;
                    overflow: hidden;
                }

                th, td {
                    padding: 12px;
                    text-align: left;
                }

                th {
                    background-color: #5cb85c;
                    color: #fff;
                    font-weight: 600;
                    text-transform: uppercase;
                }

                td {
                    border-bottom: 1px solid #e0e0e0;
                }

                tr:hover {
                    background-color: #eef2f7;
                }

                .summary {
                    margin-top: 20px;
                    display: flex;
                    justify-content: space-between;
                    background-color: #eaf0f6;
                    padding: 10px 15px;
                    border-radius: 6px;
                    font-size: 1rem;
                    font-weight: bold;
                }
                
                .summary div {
                    color: #333;
                }

                .summary .highlight {
                    color: #5cb85c;
                }

                button {
                    margin: 10px;
                    padding: 10px 15px;
                    font-size: 1rem;
                    border: none;
                    border-radius: 5px;
                    cursor: pointer;
                }
                .print-btn {
                    background-color: #007bff;
                    color: #fff;
                }
                .mail-btn {
                    background-color: #28a745;
                    color: #fff;
                }
                .phpmailer-btn {
                    background-color: #ffc107;
                    color: #fff;
                }
                button:hover {
                    opacity: 0.9;
                }
            </style>
            <script>
                // Fonction pour imprimer la page
                function imprimerPage() {
                    window.print();
                }
            </script>
        </head>
        <body>
            <div class="container">
                <h1>Notes de l'étudiant</h1>
                <div class="info">
                    <p><strong>Numéro d'étudiant :</strong> <?php echo $num_etudiant; ?></p>
                    <p><strong>Nom/Prénom :</strong> <?php echo $nom . ' ' . $prenom; ?></p>
                    <p><strong>Filière :</strong> <?php echo $filiere; ?></p>
                </div>
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

                <div class="summary">
                    <div><span class="highlight">Moyenne :</span> <?php echo number_format($moyenne, 2); ?></div>
                    <div><span class="highlight">Somme des coefficients :</span> <?php echo $totalCoefficients; ?></div>
                    <div><span class="highlight">Somme des produits (Note * Coefficient) :</span> <?php echo $totalNotes; ?></div>
                </div>

                <!-- Boutons -->
                <button class="print-btn" onclick="imprimerPage()">Imprimer</button>


                <form method="post" action="send_email.php">
                    <input type="hidden" name="numero" value="<?php echo $num_etudiant; ?>">
                    <input type="email" name="recipient_email" placeholder="Adresse e-mail du destinataire" required>
                    <input type="hidden" name="action" value="sendMailPHPMailer">
                    <button class="phpmailer-btn" type="submit">Envoyer Mail </button>
                </form>

            </div>
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
