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

// Query to get all unique "Filiere" values
$sql_filieres = "SELECT DISTINCT Filiere FROM formulaire_data";
$result_filieres = $conn->query($sql_filieres);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notes de tous les étudiants</title>
    <style>
        /* Styling remains as before */
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
        select, table {
            width: 100%;
            margin-top: 20px;
            padding: 10px;
            font-size: 16px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }
        table {
            border-collapse: collapse;
        }
        th, td {
            padding: 14px 20px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
    </style>
    <script>
        function fetchModules() {
            const filiere = document.getElementById("filiereSelect").value;

            if (filiere) {
                // Fetch modules based on selected Filiere
                fetch("fetch_modules.php?filiere=" + encodeURIComponent(filiere))
                    .then(response => response.json())
                    .then(data => {
                        // Populate Module dropdown
                        const moduleSelect = document.getElementById("moduleSelect");
                        moduleSelect.innerHTML = '<option value="">-- Choisir un module --</option>';
                        data.forEach(module => {
                            moduleSelect.innerHTML += `<option value="${module.CodeModule}">${module.DesignationModule}</option>`;
                        });
                        document.getElementById("moduleTable").innerHTML = "<tr><td colspan='6'>Sélectionnez un module pour afficher les notes.</td></tr>";
                    })
                    .catch(error => console.error("Erreur :", error));
            } else {
                document.getElementById("moduleSelect").innerHTML = "<option value=''>-- Choisir un module --</option>";
                document.getElementById("moduleTable").innerHTML = "<tr><td colspan='6'>Sélectionnez une filière pour afficher les modules.</td></tr>";
            }
        }

        function fetchNotes() {
            const filiere = document.getElementById("filiereSelect").value;
            const module = document.getElementById("moduleSelect").value;

            if (filiere && module) {
                fetch(`fetch_notes.php?filiere=${encodeURIComponent(filiere)}&module=${encodeURIComponent(module)}`)
                    .then(response => response.text())
                    .then(data => {
                        document.getElementById("moduleTable").innerHTML = data;
                    })
                    .catch(error => console.error("Erreur :", error));
            }
        }
    </script>
</head>
<body>
    <div class="container">
        <h1>Notes de tous les étudiants</h1>

        <!-- Filière dropdown -->
        <label for="filiereSelect">Sélectionnez une filière :</label>
        <select id="filiereSelect" onchange="fetchModules()">
            <option value="">-- Choisir une filière --</option>
            <?php
            while ($row_filiere = $result_filieres->fetch_assoc()) {
                echo "<option value='" . htmlspecialchars($row_filiere['Filiere']) . "'>" . htmlspecialchars($row_filiere['Filiere']) . "</option>";
            }
            ?>
        </select>

        <!-- Module dropdown -->
        <label for="moduleSelect">Sélectionnez un module :</label>
        <select id="moduleSelect" onchange="fetchNotes()">
            <option value="">-- Choisir un module --</option>
        </select>

        <!-- Table to display modules and notes -->
        <table>
            <thead>
                <tr>
                    <th>Numéro d'étudiant</th>
                    <th>Nom/Prénom</th>
                    <th>Filière</th>
                    <th>Module</th>
                    <th>Coefficient</th>
                    <th>Note</th>
                </tr>
            </thead>
            <tbody id="moduleTable">
                <tr>
                    <td colspan="6">Sélectionnez une filière pour afficher les modules.</td>
                </tr>
            </tbody>
        </table>
        <input type="button" value="Afficher Statistiques" onclick="fetchStatistics()" />
<canvas id="gradeChart" width="400" height="200" style="margin-top: 20px;"></canvas>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script> <!-- Include Chart.js library -->

    </div>
</body>
</html>

<?php $conn->close(); ?>
