

<?php
// Connect to database
$host = 'localhost';
$username = 'root'; 
$password = 'root'; 
$dbname = 'web_technology_class';

// Establish database connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'errors' => ['Database connection failed: ' . $conn->connect_error]]);
    exit();
}

// Query to get all registered students
$query = "SELECT * FROM students";
$result = $conn->query($query);

echo "<link rel='stylesheet' href='styles.css'>";  

// Check if there are students
if ($result->num_rows > 0) {
    echo "<h1>List of Registered Students</h1>";
    echo "<table border='1'>";
    echo "<thead><tr><th>ID</th><th>First Name</th><th>Last Name</th><th>Project Title</th><th>Email</th><th>Phone</th><th>Time Slot</th></tr></thead><tbody>";

    // Fetch and display each student
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>" . htmlspecialchars($row['id']) . "</td>
                <td>" . htmlspecialchars($row['first_name']) . "</td>
                <td>" . htmlspecialchars($row['last_name']) . "</td>
                <td>" . htmlspecialchars($row['project_title']) . "</td>
                <td>" . htmlspecialchars($row['email']) . "</td>
                <td>" . htmlspecialchars($row['phone']) . "</td>
                <td>" . htmlspecialchars($row['time_slot']) . "</td>
              </tr>";
    }

    echo "</tbody></table>";
} else {
    echo "<p>No students registered yet.</p>";
}
?>
<a href="register2.php">
    <button type="button">Back to Registration</button>
</a>

