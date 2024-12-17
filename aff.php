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

// Sélectionner toutes les entrées de la table "formulaire_data"
$sql = "SELECT * FROM formulaire_data";
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

    echo "<table>";
    echo "<tr><th>Numéro</th><th>Civilité</th><th>Pays</th><th>Nom</th><th>Prénom</th><th>Adresse</th><th>Postal 1</th><th>Postal 2</th><th>Plateforme</th><th>Application</th><th>Nationalité</th><th>Filière</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row["numero"] . "</td>";
        echo "<td>" . $row["civilite"] . "</td>";
        echo "<td>" . $row["pays"] . "</td>";
        echo "<td>" . $row["nom"] . "</td>";
        echo "<td>" . $row["prenom"] . "</td>";
        echo "<td>" . $row["adresse"] . "</td>";
        echo "<td>" . $row["postal1"] . "</td>";
        echo "<td>" . $row["postal2"] . "</td>";
        echo "<td>" . $row["plateform"] . "</td>";
        echo "<td>" . $row["application"] . "</td>";
        echo "<td>" . $row["nationalite"] . "</td>";
        echo "<td>" . $row["filiere"] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "Aucune donnée trouvée dans la table.";
}

$conn->close();
?>
