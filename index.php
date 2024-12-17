<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Page de connexion</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body, html {
    margin: 0;
    font-family: Arial, sans-serif;
    background-color: #f0f2f5;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}

.login-container {
    background-color: #fff;
    padding: 20px 30px;
    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    width: 100%;
    max-width: 400px;
    text-align: center;
}

h2 {
    margin-bottom: 20px;
    color: #333;
    font-size: 24px;
}

input[type="email"],
input[type="password"],
select {
    width: 100%;
    padding: 10px;
    margin-bottom: 15px;
    border: 1px solid #ccc;
    border-radius: 5px;
    font-size: 16px;
}

input[type="submit"],
input[type="button"] {
    width: 100%;
    padding: 10px;
    margin-top: 10px;
    border: none;
    border-radius: 5px;
    background-color: #007bff;
    color: #fff;
    font-size: 16px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

input[type="submit"]:hover,
input[type="button"]:hover {
    background-color: #0056b3;
}

.error-message {
    color: red;
    font-size: 14px;
    margin-bottom: 10px;
}

#userTypeForm {
    margin-top: 20px;
    text-align: left;
}
    </style>
  
</head>
<body>
    <div class="login-container">
        <?php
        if (isset($_GET['error']) && $_GET['error'] == 1) {
            echo "<p>User Inexistant. Inscrivez-vous.</p>";
        }
        ?>
        <form  method="post" >
            <h2>Login</h2>
            <input type="email" id ="email" name="email" placeholder="Adresse e-mail" required>
            <input type="password" id="password" name="password" placeholder="Mot de passe" required>
           
            <input type="submit" name="submitType" value="Valider" formaction="valider.php">
            <input  type="submit" onclick="showUserTypeForm()" value ="Inscription" formaction="inscription.php">
            <div id="userTypeForm" style="display: none;">
    <select name="role" onchange="updateUserType()">
        <option value="Admin">Admin</option>
        <option value="User">User</option>
    </select>
</div>


        </form>
    </div>

    <script>
        function showUserTypeForm() {
            document.getElementById('userTypeForm').style.display = 'block';
        }

        function updateUserType() {
    var selectedRole = document.querySelector('select[name="roleSelection"]').value;
    document.getElementById('userType').value = selectedRole;
    document.querySelector('form').submit(); // Soumettre le formulaire
}

    </script>
</body>
</html>
