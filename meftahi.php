<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bddtp1";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Récupérer la liste des filières
    $filiereQuery = "SELECT DISTINCT Filiere FROM formulaire_data";
    $filiereStmt = $conn->query($filiereQuery);
    $filieres = $filiereStmt->fetchAll(PDO::FETCH_ASSOC);

    // Vérifier si une filière est sélectionnée
    $selectedFiliere = isset($_POST['filiere']) ? $_POST['filiere'] : '';

    // Construire la requête pour récupérer les étudiants et leurs moyennes pondérées
    $query = "
    SELECT n.Num_Etudiant, e.nom, e.prenom, e.Filiere,
    SUM(n.Note * n.Coefficient) / SUM(n.Coefficient) AS Moyenne_Ponderee
    FROM notes n
    INNER JOIN formulaire_data e ON n.Num_Etudiant = e.numero
    WHERE e.Filiere LIKE :filiere
    GROUP BY n.Num_Etudiant
    ";

    // Préparer et exécuter la requête
    $stmt = $conn->prepare($query);
    $stmt->bindValue(':filiere', '%' . $selectedFiliere . '%');
    $stmt->execute();
    $studentAverages = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo "<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f0f2f5;
        margin: 0;
        padding: 0;
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
    }

    .container {
        background-color: #fff;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
        padding: 20px;
        max-width: 800px;
        width: 100%;
    }

    h1 {
        color: #333;
        text-align: center;
        margin-bottom: 20px;
    }

    p {
        color: #666;
        font-size: 1.1em;
        margin-bottom: 10px;
        text-align: center;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    th, td {
        padding: 12px;
        text-align: left;
    }

    th {
        background-color: #5cb85c;
        color: #fff;
        border-top: 2px solid #5cb85c;
    }

    tr {
        border-bottom: 1px solid #ddd;
    }

    tr:hover {
        background-color: #f1f1f1;
    }

    tr:nth-child(even) {
        background-color: #fafafa;
    }

    .stats {
        text-align: center;
        margin-top: 15px;
        font-weight: bold;
        color: #555;
    }

    .print-button {
        display: block;
        margin: 20px auto 0;
        background-color: #5cb85c;
        color: #fff;
        border: none;
        padding: 10px 15px;
        cursor: pointer;
        font-size: 16px;
        border-radius: 5px;
        text-align: center;
    }

    .print-button:hover {
        background-color: #4cae4c;
    }
    </style>";

    echo "<div class='container'>
            <h1>Tableau des Moyennes Pondérées des Étudiants</h1>";

    // Formulaire de sélection de filière
    echo "<form method='POST' action=''>
            <label for='filiere'>Sélectionnez une filière :</label>
            <select name='filiere' id='filiere' onchange='this.form.submit()'>
                <option value=''>Toutes les filières</option>";
    foreach ($filieres as $filiere) {
        $selected = $selectedFiliere == $filiere['Filiere'] ? 'selected' : '';
        echo "<option value='" . htmlspecialchars($filiere['Filiere']) . "' $selected>" . htmlspecialchars($filiere['Filiere']) . "</option>";
    }
    echo "</select>
          </form>";

    echo "<table>
            <tr>
                <th>Numéro Étudiant</th>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Filière</th>
                <th>Moyenne Pondérée</th>
            </tr>";

    $totalAverage = 0;
    $count = count($studentAverages);
    $minAverage = PHP_INT_MAX;
    $maxAverage = -PHP_INT_MAX;

    foreach ($studentAverages as $average) {
        $formattedAverage = number_format($average['Moyenne_Ponderee'], 2);

        echo "<tr>
                <td>" . htmlspecialchars($average['Num_Etudiant']) . "</td>
                <td>" . htmlspecialchars($average['nom']) . "</td>
                <td>" . htmlspecialchars($average['prenom']) . "</td>
                <td>" . htmlspecialchars($average['Filiere']) . "</td>
                <td>" . $formattedAverage . "</td>
            </tr>";

        $totalAverage += $average['Moyenne_Ponderee'];
        $minAverage = min($minAverage, $average['Moyenne_Ponderee']);
        $maxAverage = max($maxAverage, $average['Moyenne_Ponderee']);
    }

    echo "</table>";

    $formattedTotalAverage = number_format($totalAverage / $count, 2);
    $formattedMinAverage = number_format($minAverage, 2);
    $formattedMaxAverage = number_format($maxAverage, 2);

    echo "<div class='stats'>
            <p>Moyenne Générale : " . $formattedTotalAverage . "</p>
            <p>Moyenne Minimale : " . $formattedMinAverage . "</p>
            <p>Moyenne Maximale : " . $formattedMaxAverage . "</p>
          </div>";

    echo "<button class='print-button' onclick='window.print()'>Imprimer</button>
    
          <<form method='POST' action='send_pv.php'>
            
            <button class='print-button' type='submit'>Envoyer les emails</button>
            </form>

    
          </div>";

} catch(PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}

$conn = null;
?>
