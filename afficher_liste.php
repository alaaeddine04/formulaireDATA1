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

// Sélectionner toutes les entrées de la table "nationalites"
$sql = "SELECT * FROM nationalites";
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
    echo "<tr><th>Code</th><th>Nationalite</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr><td>" . $row["code"] . "</td><td>" . $row["nationalite"] . "</td></tr>";
    }
    echo "</table>";
} else {
    echo "Aucune donnée trouvée dans la table.";
}

$conn->close();
?>
