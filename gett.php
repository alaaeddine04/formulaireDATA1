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

if (isset($_GET['module'])) {
    $selectedModule = $_GET['module'];

    // Effectuez une requête SQL pour obtenir le coefficient et la note du module
    $sql_module_info = "SELECT CodeModule, Coefficient, Note FROM module WHERE CodeModule = '$selectedModule'";
    $result_module_info = $conn->query($sql_module_info);

    if ($result_module_info->num_rows > 0) {
        $row_module_info = $result_module_info->fetch_assoc();
        $codeModule = $row_module_info["CodeModule"];
        $coefficient = $row_module_info["Coefficient"];
        $note = $row_module_info["Note"];

        $response = array('codeModule' => $codeModule, 'coefficient' => $coefficient, 'note' => $note);
        echo json_encode($response);
    } else {
        echo json_encode(array('codeModule' => 'N/A', 'coefficient' => 'N/A', 'note' => 'N/A'));
    }
} else {
    echo json_encode(array('codeModule' => 'N/A', 'coefficient' => 'N/A', 'note' => 'N/A'));
}

$conn->close();
?>
