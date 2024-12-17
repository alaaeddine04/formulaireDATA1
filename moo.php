<!DOCTYPE html>
<html>
<head>
    <title>Statistiques Étudiants</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .chart-container {
            width: 45%;
            margin: 20px;
            float: left;
        }
    </style>
</head>
<body>

    <div class="chart-container">
        <canvas id="histogramChart"></canvas>
    </div>
    <div class="chart-container">
        <canvas id="pieChart"></canvas>
    </div>

    <?php
    
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "bddtp1";
    
    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
        $query = "
        SELECT n.Num_Etudiant, e.nom, e.prenom,
        SUM(n.Note * n.Coefficient) / SUM(n.Coefficient) AS Moyenne_Ponderee
        FROM notes n
        INNER JOIN formulaire_data e ON n.Num_Etudiant = e.numero
        GROUP BY n.Num_Etudiant
        ";
    
        $stmt = $conn->query($query);
        $studentAverages = $stmt->fetchAll(PDO::FETCH_ASSOC);
    

        $totalAverage = 0;
        $count = count($studentAverages);
        $minAverage = PHP_INT_MAX;
        $maxAverage = -PHP_INT_MAX;
        $ajourne = 0;
        $admi = 0;
        
        foreach ($studentAverages as $average) {
            $formattedAverage = number_format($average['Moyenne_Ponderee'], 2);

            $totalAverage += $average['Moyenne_Ponderee'];
            $minAverage = min($minAverage, $average['Moyenne_Ponderee']);
            $maxAverage = max($maxAverage, $average['Moyenne_Ponderee']);

            // Calcul du statut de l'étudiant (ajourné ou admis)
            if ($formattedAverage < 10) {
                $ajourne++;
            } else {
                $admi++;
            }
        }

        // Calcul des pourcentages
        $totalStudents = $count;
        $admiPercentage = ($admi / $totalStudents) * 100;
        $ajournePercentage = ($ajourne / $totalStudents) * 100;
        
        // Génération du graphique pour le nombre d'étudiants admis ou ajournés
        echo "<script>
            var ctxHistogram = document.getElementById('histogramChart').getContext('2d');
            var histogramChart = new Chart(ctxHistogram, {
                type: 'bar',
                data: {
                    labels: ['Admis', 'Ajourné'],
                    datasets: [{
                        label: 'Nombre d\'étudiants par statut',
                        data: [$admi, $ajourne],
                        backgroundColor: [
                            'rgba(54, 162, 235, 0.5)',
                            'rgba(255, 99, 132, 0.5)'
                        ],
                        borderColor: [
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 99, 132, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        </script>";

        // Génération du graphique pour le pourcentage d'étudiants admis ou ajournés
        echo "<script>
            var ctxPie = document.getElementById('pieChart').getContext('2d');
            var pieChart = new Chart(ctxPie, {
                type: 'pie',
                data: {
                    labels: ['Admis', 'Ajourné'],
                    datasets: [{
                        label: 'Proportion d\'étudiants par statut',
                        data: [$admiPercentage, $ajournePercentage],
                        backgroundColor: [
                            'rgba(54, 162, 235, 0.5)',
                            'rgba(255, 99, 132, 0.5)'
                        ],
                        borderColor: [
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 99, 132, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            });
        </script>";

    } catch(PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }
    ?>
</body>
</html>
