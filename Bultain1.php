<?php
session_start();
// Connexion à la base de données (assurez-vous d'avoir défini les informations de connexion correctes)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bddtp1";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("La connexion à la base de données a échoué : " . $conn->connect_error);
}

// Sélectionner les codes de la table "module"
$sql = "SELECT * FROM module";
$result = $conn->query($sql);

$module = array(); // Tableau pour stocker les désignations de module

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $module[] = $row["DesignationModule"];
    }
}
if (isset($_SESSION['user_name'])) {
    $nomUtilisateur = $_SESSION['user_name'];
    echo "<script>alert('Bienvenue, $user_name !');</script>";
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Bulletin de Notes</title>
    <link rel="stylesheet" type="text/css" href="style.css" /> 
    <style>
    /* Menu Container */
    .menu-container {
        display: flex;
        justify-content: center;
        background-color: #333;
        padding: 10px 0;
    }

    /* Style for menu buttons */
    .menu-container input[type="submit"] {
        background-color: #555;
        color: white;
        border: none;
        padding: 10px 20px;
        margin: 5px;
        cursor: pointer;
        font-size: 16px;
        border-radius: 5px;
        transition: background-color 0.3s ease, transform 0.3s ease;
    }

    /* Hover effect for buttons with zoom */
    .menu-container input[type="submit"]:hover {
        background-color: #777;
        transform: scale(1.1); /* Zoom effect */
    }

    /* Style for form fields */
    .form-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        margin-top: 20px;
    }

    /* Style for labels and input */
    label {
        font-weight: bold;
        margin-bottom: 5px;
    }
    
    #Number {
        min-width: 150px;
        max-width: 300px;
        width: 100%;
        padding: 8px;
        font-size: 16px;
        border: 1px solid #ccc;
        border-radius: 5px;
        margin-bottom: 10px;
    }

    /* Style for search button */
    .search-button {
        background-color: #4CAF50;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
        transition: background-color 0.3s ease;
    }

    .search-button:hover {
        background-color: #45a049;
    }
</style>

</head>
<body>

    <!-- Menu Section -->
    <div class="menu-container">
        <form action="RechBultain.php" method="post">



            <input type="submit" value="Bulletin de Notes" formaction="Bultain1.php" />
            <input type="submit" value="logout" formaction="Login.php" style="background-color: red;" />

        </form>
    </div>

    <!-- Form Section -->
    <div class="form-container">
        <form action="off.php" method="post">
            <label for="Number">Numéro :</label>
            <input type="text" id="Number" name="numero" />

            <input type="submit" value="Rechercher" class="search-button" />
        </form>
    </div>

</body>
</html>
