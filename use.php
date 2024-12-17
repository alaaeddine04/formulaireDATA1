<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bddtp1"; // Replace with your database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Add user functionality
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_user'])) {
    $email = $_POST['email'];
    $mdp = $_POST['mdp'];
    $role = $_POST['role'];

    $sql_add = "INSERT INTO user (email, mdp, role) VALUES (?, ?, ?)";
    $stmt_add = $conn->prepare($sql_add);
    $stmt_add->bind_param("sss", $email, $mdp, $role);
    $stmt_add->execute();
    $stmt_add->close();
    echo "<p>User added successfully!</p>";
}

// Modify user functionality
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['modify_user'])) {
    $user_id = $_POST['user_id'];
    $email = $_POST['email'];
    $mdp = $_POST['mdp'];
    $role = $_POST['role'];

    $sql_modify = "UPDATE user SET email = ?, mdp = ?, role = ? WHERE id = ?";
    $stmt_modify = $conn->prepare($sql_modify);
    $stmt_modify->bind_param("sssi", $email, $mdp, $role, $user_id);
    $stmt_modify->execute();
    $stmt_modify->close();
    echo "<p>User modified successfully!</p>";
}

// Delete user functionality
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_user'])) {
    $user_id = $_POST['user_id'];

    $sql_delete = "DELETE FROM user WHERE id = ?";
    $stmt_delete = $conn->prepare($sql_delete);
    $stmt_delete->bind_param("i", $user_id);
    $stmt_delete->execute();
    $stmt_delete->close();
    echo "<p>User deleted successfully!</p>";
}

// Fetch all users
$sql_users = "SELECT id, email, mdp, role FROM user";
$result_users = $conn->query($sql_users);
$roles = ['Admin', 'User'];
$counts = [10, 50];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
</head>
<body>
    <h1>User Management</h1>

    <!-- Add User Form -->
    <h2>Add User</h2>
    <form method="POST">
        <input type="text" name="email" placeholder="Email" required>
        <input type="password" name="mdp" placeholder="Password" required>
        <select name="role" required>
            <option value="admin">Admin</option>
            <option value="user">User</option>
        </select>
        <button type="submit" name="add_user">Add User</button>
    </form>

    <!-- Display Users -->
    <h2>All Users</h2>
    <?php
    if ($result_users->num_rows > 0) {
        echo "<table border='1'>
                <tr>
                    <th>Email</th>
                    <th>Password</th>
                    <th>Role</th>
                    <th>Actions</th>
                </tr>";
        while ($row = $result_users->fetch_assoc()) {
            echo "<tr>
                    <td>" . htmlspecialchars($row['email']) . "</td>
                    <td>" . htmlspecialchars($row['mdp']) . "</td>
                    <td>" . htmlspecialchars($row['role']) . "</td>
                    <td>
                        <!-- Modify User Form -->
                        <form method='POST' style='display:inline-block;'>
                            <input type='hidden' name='user_id' value='" . $row['id'] . "'>
                            <input type='text' name='email' value='" . htmlspecialchars($row['email']) . "' required>
                            <input type='password' name='mdp' value='" . htmlspecialchars($row['mdp']) . "' required>
                            <select name='role' required>
                                <option value='admin' " . ($row['role'] === 'admin' ? 'selected' : '') . ">Admin</option>
                                <option value='user' " . ($row['role'] === 'user' ? 'selected' : '') . ">User</option>
                            </select>
                            <button type='submit' name='modify_user'>Modify</button>
                        </form>
                        <!-- Delete User Form -->
                        <form method='POST' style='display:inline-block;'>
                            <input type='hidden' name='user_id' value='" . $row['id'] . "'>
                            <button type='submit' name='delete_user'>Delete</button>
                        </form>
                    </td>
                  </tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No users found.</p>";
    }
    ?>

    <?php
    // Fetch role statistics
    $sql_role_stats = "SELECT role, COUNT(*) AS count FROM user GROUP BY role";
    $result_stats = $conn->query($sql_role_stats);

    $roles = [];
    $counts = [];
    if ($result_stats->num_rows > 0) {
        while ($row = $result_stats->fetch_assoc()) {
            $roles[] = $row['role'];
            $counts[] = $row['count'];
        }
    }
    ?>

    <h2>Role Statistics</h2>
    <div>
        <canvas id="roleChart"></canvas>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        // Pass PHP data to JavaScript using json_encode
        const roles = <?php echo json_encode($roles); ?>;
        const counts = <?php echo json_encode($counts); ?>;

        // Bar Chart
        const ctxBar = document.getElementById('roleChart').getContext('2d');
        new Chart(ctxBar, {
            type: 'bar',
            data: {
                labels: roles,
                datasets: [{
                    label: 'Number of Users',
                    data: counts,
                    backgroundColor: ['#4CAF50', '#FF5733', '#FFC300', '#33C3FF'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Pie Chart
        const ctxPie = document.getElementById('rolePieChart').getContext('2d');
        new Chart(ctxPie, {
            type: 'pie',
            data: {
                labels: roles,
                datasets: [{
                    label: 'User Distribution',
                    data: counts,
                    backgroundColor: ['#4CAF50', '#FF5733', '#FFC300', '#33C3FF']
                }]
            },
            options: {
                responsive: true
            }
        });
    </script>

<!-- HTML canvas elements -->
<canvas id="roleChart" width="400" height="200"></canvas>
<canvas id="rolePieChart" width="400" height="200"></canvas>

</body>
</html>
