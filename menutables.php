<!DOCTYPE html>
<html>
<head>
    <title>tablesmenu</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<form action='index.php' method='POST' style='display:inline;'>
                    <input type='hidden' name='id' value='" . htmlspecialchars($row['id'] ?? '') . "'>
                    <button type='submit'>Etudiant</button>
                </form> 
                
                <form action='module.html' method='POST' style='display:inline;'>
                    <input type='hidden' name='id' value='" . htmlspecialchars($row['id'] ?? '') . "'>
                    <button type='submit'>Enseignant</button>
                </form>
                <form action='module.php' method='POST' style='display:inline;'>
                    <input type='hidden' name='id' value='" . htmlspecialchars($row['id'] ?? '') . "'>
                    <button type='submit'>Module</button>
                </form>
                    <form action='note.php' method='POST' style='display:inline;'>
                    <input type='hidden' name='id' value='" . htmlspecialchars($row['id'] ?? '') . "'>
                    <button type='submit'>note</button>
                </form>
                <form action='tables.php' method='POST' style='display:inline;'>
                    <input type='hidden' name='id' value='" . htmlspecialchars($row['id'] ?? '') . "'>
                    <button type='submit'>tables</button>
                </form>
    
</body>