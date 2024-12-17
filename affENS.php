<?php
// Database connection (ensure correct connection details)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bddtp1";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("La connexion à la base de données a échoué : " . $conn->connect_error);
}

// Select all entries from the "Enseignant" table
$sql = "SELECT * FROM Enseignant";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<style>
    body {
        font-family: 'Arial', sans-serif;
        background-color: #f8f9fa;
        margin: 0;
        padding: 0;
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
    }

    .container {
        max-width: 1000px;
        margin: 20px;
        padding: 20px;
        background-color: #ffffff;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    h1 {
        color: #333;
        text-align: center;
        margin-bottom: 20px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
        border-radius: 8px;
        overflow: hidden;
    }

    th, td {
        padding: 12px;
        text-align: left;
        border-bottom: 1px solid #dee2e6;
    }

    th {
        background-color: #5cb85c;
        color: white;
        font-weight: bold;
    }

    tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    tr:hover {
        background-color: #f1f1f1;
    }

    td {
        color: #495057;
    }

    /* Responsive design for smaller screens */
    @media (max-width: 768px) {
        .container {
            padding: 15px;
        }

        th, td {
            padding: 10px;
            font-size: 14px;
        }
    }
    </style>";

    echo "<div class='container'>
            <h1>Liste des Enseignants</h1>
            <table>
                <tr><th>Numero</th><th>Civilite</th><th>NomPrenom</th><th>Adresse</th><th>DateNaissance</th><th>LieuNaissance</th><th>Pays</th><th>Grade</th><th>Specialite</th></tr>";

    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>" . htmlspecialchars($row["Numero"]) . "</td>
                <td>" . htmlspecialchars($row["Civilite"]) . "</td>
                <td>" . htmlspecialchars($row["NomPrenom"]) . "</td>
                <td>" . htmlspecialchars($row["Adresse"]) . "</td>
                <td>" . htmlspecialchars($row["DateNaissance"]) . "</td>
                <td>" . htmlspecialchars($row["LieuNaissance"]) . "</td>
                <td>" . htmlspecialchars($row["Pays"]) . "</td>
                <td>" . htmlspecialchars($row["Grade"]) . "</td>
                <td>" . htmlspecialchars($row["Specialite"]) . "</td>
              </tr>";
    }

    echo "</table></div>";
} else {
    echo "Aucune donnée trouvée dans la table.";
}

$conn->close();
?>
