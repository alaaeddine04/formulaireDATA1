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

    ob_start(); // Capture du contenu HTML
?>
<div class='container'>
    <h1>Tableau des Moyennes Pondérées des Étudiants</h1>
    <table>
        <tr>
            <th>Numéro Étudiant</th>
            <th>Nom</th>
            <th>Prénom</th>
            <th>Filière</th>
            <th>Moyenne Pondérée</th>
        </tr>
        <?php
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
        ?>
    </table>

    <?php
    if ($count > 0) {
        $formattedTotalAverage = number_format($totalAverage / $count, 2);
        $formattedMinAverage = number_format($minAverage, 2);
        $formattedMaxAverage = number_format($maxAverage, 2);

        echo "<div class='stats'>
                <p>Moyenne Générale : " . $formattedTotalAverage . "</p>
                <p>Moyenne Minimale : " . $formattedMinAverage . "</p>
                <p>Moyenne Maximale : " . $formattedMaxAverage . "</p>
              </div>";
    }
    ?>
</div>
<?php
    echo ob_get_clean(); // Récupère et affiche le contenu capturé
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}
?>
