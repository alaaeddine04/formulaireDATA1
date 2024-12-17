<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bddtp1";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("La connexion à la base de données a échoué : " . $conn->connect_error);
}

$filiere = $_GET['filiere'];
$sql_modules = "SELECT CodeModule, DesignationModule FROM module WHERE Filiere = ?";
$stmt = $conn->prepare($sql_modules);
$stmt->bind_param("s", $filiere);
$stmt->execute();
$result_modules = $stmt->get_result();

$modules = [];
while ($row = $result_modules->fetch_assoc()) {
    $modules[] = $row;
}

echo json_encode($modules);
$conn->close();
?>
