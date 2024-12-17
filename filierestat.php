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
$module = $_GET['module'];

// Query to count students based on the conditions
$sql_greater_or_equal_10 = "SELECT COUNT(*) AS count FROM notes 
                            INNER JOIN formulaire_data ON notes.Num_Etudiant = formulaire_data.numero
                            WHERE formulaire_data.Filiere = ? AND notes.Code_module = ? AND notes.Note >= 10";

$sql_less_than_10 = "SELECT COUNT(*) AS count FROM notes 
                     INNER JOIN formulaire_data ON notes.Num_Etudiant = formulaire_data.numero
                     WHERE formulaire_data.Filiere = ? AND notes.Code_module = ? AND notes.Note < 10";

$stmt1 = $conn->prepare($sql_greater_or_equal_10);
$stmt1->bind_param("ss", $filiere, $module);
$stmt1->execute();
$result1 = $stmt1->get_result();
$row1 = $result1->fetch_assoc();
$greater_or_equal_10 = $row1['count'];

$stmt2 = $conn->prepare($sql_less_than_10);
$stmt2->bind_param("ss", $filiere, $module);
$stmt2->execute();
$result2 = $stmt2->get_result();
$row2 = $result2->fetch_assoc();
$less_than_10 = $row2['count'];

// Return data as JSON
echo json_encode([
    "greater_or_equal_10" => $greater_or_equal_10,
    "less_than_10" => $less_than_10
]);

$conn->close();
?>
