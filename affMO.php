<?php
// Assurez-vous d'avoir défini les informations de connexion correctes
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bddtp1";

// Connexion à la base de données
$conn = new mysqli($servername, $username, $password, $dbname);

// Vérification de la connexion
if ($conn->connect_error) {
    die("La connexion à la base de données a échoué: " . $conn->connect_error);
}

// Sélectionner toutes les entrées de la table "Module"
$sql = "SELECT * FROM Module";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<style>
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
</style>";
    echo "<table>";
    echo "<tr><th>Code Module</th><th>Désignation Module</th><th>Coefficient</th><th>Volume Horaire</th><th>Filière</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row["CodeModule"] . "</td>";
        echo "<td>" . $row["DesignationModule"] . "</td>";
        echo "<td>" . $row["Coefficient"] . "</td>";
        echo "<td>" . $row["VolumeHoraire"] . "</td>";
        echo "<td>" . $row["Filiere"] . "</td>";
        echo "<td>" . $row["option"] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "Aucune donnée trouvée dans la table.";
}

// Fermeture de la connexion à la base de données
$conn->close();
