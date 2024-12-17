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

$sql_notes = "SELECT formulaire_data.numero AS Num_Etudiant, 
                     formulaire_data.Nom, 
                     formulaire_data.Prenom, 
                     formulaire_data.Filiere, 
                     module.DesignationModule, 
                     notes.Coefficient, 
                     notes.Note 
              FROM notes 
              INNER JOIN module ON notes.Code_module = module.CodeModule
              INNER JOIN formulaire_data ON notes.Num_Etudiant = formulaire_data.numero
              WHERE formulaire_data.Filiere = ? AND module.CodeModule = ?
              ORDER BY formulaire_data.numero";
$stmt = $conn->prepare($sql_notes);
$stmt->bind_param("ss", $filiere, $module);
$stmt->execute();
$result_notes = $stmt->get_result();

if ($result_notes->num_rows > 0) {
    while ($row = $result_notes->fetch_assoc()) {
        echo "<tr>
                <td>{$row['Num_Etudiant']}</td>
                <td>{$row['Nom']} {$row['Prenom']}</td>
                <td>{$row['Filiere']}</td>
                <td>{$row['DesignationModule']}</td>
                <td>{$row['Coefficient']}</td>
                <td>" . ($row['Note'] !== null ? $row['Note'] : 'Note non saisie') . "</td>
              </tr>";
    }
} else {
    echo "<tr><td colspan='6'>Aucune note disponible pour ce module dans la filière sélectionnée.</td></tr>";
}

$conn->close();
?>
