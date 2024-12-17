<?php
// stat.php

// Simulated data for male and female students
$maleStudentsCount = 30;
$femaleStudentsCount = 20;
?>

<!DOCTYPE html>
<html>
<head>
    <title>Statistiques Étudiants</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .chart-container {
            width: 45%;
            margin: 10px;
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
<div>
    <form id="emailForm" method="POST" action="send_state.php">
        <label for="studentEmail" style="display: block; margin: 10px 0 5px; font-weight: bold;">E-mail étudiant :</label>
        <input type="text" id="studentEmail" name="recipient_email" value="" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 5px;">

        <label for="txt" style="display: block; margin: 10px 0 5px; font-weight: bold;">Message :</label>
        <textarea id="txt" name="txt" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 5px;"></textarea>

        <button type="submit" style="margin-top: 15px; padding: 10px 15px; background-color: #4CAF50; color: white; border: none; border-radius: 5px; cursor: pointer;">Envoyer le bulletin par e-mail</button>
    </form>
</div>
<br/> <br/>

<div>
    <script>
        var maleStudentsCount = <?php echo $maleStudentsCount; ?>;
        var femaleStudentsCount = <?php echo $femaleStudentsCount; ?>;

        // Histogram chart
        var ctxHistogram = document.getElementById('histogramChart').getContext('2d');
        var histogramChart = new Chart(ctxHistogram, {
            type: 'bar',
            data: {
                labels: ['Masculin', 'Féminin'],
                datasets: [{
                    label: 'Nombre d\'étudiants par sexe',
                    data: [maleStudentsCount, femaleStudentsCount],
                    backgroundColor: ['rgba(54, 162, 235, 0.5)', 'rgba(255, 99, 132, 0.5)'],
                    borderColor: ['rgba(54, 162, 235, 1)', 'rgba(255, 99, 132, 1)'],
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

        // Pie chart
        var totalStudents = maleStudentsCount + femaleStudentsCount;
        var malePercentage = (maleStudentsCount / totalStudents) * 100;
        var femalePercentage = (femaleStudentsCount / totalStudents) * 100;

        var ctxPie = document.getElementById('pieChart').getContext('2d');
        var pieChart = new Chart(ctxPie, {
            type: 'pie',
            data: {
                labels: ['Masculin', 'Féminin'],
                datasets: [{
                    label: 'Proportion d\'étudiants par sexe',
                    data: [malePercentage, femalePercentage],
                    backgroundColor: ['rgba(54, 162, 235, 0.5)', 'rgba(255, 99, 132, 0.5)'],
                    borderColor: ['rgba(54, 162, 235, 1)', 'rgba(255, 99, 132, 1)'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });

        // Save charts as images
        function saveChartAsImage() {
            var histogramImage = ctxHistogram.canvas.toDataURL();
            var pieImage = ctxPie.canvas.toDataURL();

            // Send images to the server
            fetch('save_charts.php', {
                method: 'POST',
                body: JSON.stringify({
                    histogram: histogramImage,
                    pie: pieImage
                }),
                headers: { 'Content-Type': 'application/json' }
            })
            .then(response => response.json())
            .then(data => console.log('Images saved successfully'))
            .catch(error => console.error('Error saving images:', error));
        }

        // Save images after rendering
        setTimeout(saveChartAsImage, 1000);
    </script>
</div>
</body>
</html>
