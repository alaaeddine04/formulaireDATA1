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

$num_etudiant = $_GET["num_etudiant"];
$module = $_GET["module"];

// Requête pour obtenir le code du module et le coefficient
$sql_module = "SELECT CodeModule, Coefficient FROM module WHERE CodeModule = '$module'";
$result_module = $conn->query($sql_module);

$response = array(
    "note" => null,
    "codeModule" => "",
    "coefficient" => ""
);

if ($result_module->num_rows > 0) {
    $row_module = $result_module->fetch_assoc();
    $response["codeModule"] = $row_module["CodeModule"];
    $response["coefficient"] = $row_module["Coefficient"];
    
    // Maintenant, récupérez la note spécifique à l'étudiant pour le module sélectionné
    $sql_note = "SELECT Note FROM notes WHERE Num_Etudiant = $num_etudiant AND Code_module = '$module'";
    $result_note = $conn->query($sql_note);
    
    if ($result_note->num_rows > 0) {
        $row_note = $result_note->fetch_assoc();
        $response["note"] = $row_note["Note"];
    }
}

// Retourne la réponse au format JSON
header("Content-type: application/json");
echo json_encode($response);

$conn->close();
?>
