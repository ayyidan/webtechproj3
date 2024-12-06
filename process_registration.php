<?php
// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Database connection settings
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

// Sanitize and validate input
$id = $_POST['id'];
$first_name = $_POST['first_name'];
$last_name = $_POST['last_name'];
$project_title = $_POST['project_title'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$time_slot = $_POST['time_slot'];

$errors = [];

// Validate ID (check for 8-digit ID and if the ID is already registered)
if (!preg_match('/^\d{8}$/', $id)) {
    $errors[] = "Student ID must be 8 digits.";
}

// Check if the ID already exists in the database
$stmt = $conn->prepare("SELECT id FROM students WHERE id = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $errors[] = "The student ID $id is already registered.";
}
$stmt->free_result();  // Free the result set after checking ID

// Validate First and Last Name
if (!preg_match('/^[A-Za-z]+$/', $first_name)) {
    $errors[] = "First name must contain only letters.";
}
if (!preg_match('/^[A-Za-z]+$/', $last_name)) {
    $errors[] = "Last name must contain only letters.";
}

// Validate Email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Invalid email format.";
}

// Validate Phone Number
if (!preg_match('/^\d{3}-\d{3}-\d{4}$/', $phone)) {
    $errors[] = "Phone number must be in the format 999-999-9999.";
}

// Check for errors
if (!empty($errors)) {
    echo json_encode(['success' => false, 'errors' => $errors]);
    exit();
}

// Check if the email is already registered
$stmt = $conn->prepare("SELECT id FROM students WHERE email = ?");
$stmt->bind_param('s', $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    // Email is already registered
    echo json_encode(['success' => false, 'errors' => ["The email address $email is already registered."]]);
    exit();
}
$stmt->free_result();  // Free the result set after checking email

// Check time slot availability before proceeding
$timeSlotQuery = "SELECT available_seats FROM time_slots WHERE slot_id = ?";
$stmt = $conn->prepare($timeSlotQuery);
$stmt->bind_param('i', $time_slot);
$stmt->execute();
$stmt->bind_result($available_seats);
$stmt->fetch();
$stmt->free_result();  // Free the result set after fetching the data

if ($available_seats <= 0) {
    // If no available seats, return error
    echo json_encode(['success' => false, 'errors' => ["Sorry, this time slot is fully booked."]]);
    exit();
}

// Check if the student is already registered
$stmt = $conn->prepare("SELECT time_slot FROM students WHERE id = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();
$stmt->free_result();  // Free the result set after fetching the data

if ($result->num_rows > 0) {
    // Student already registered
    $row = $result->fetch_assoc();
    $previous_time_slot = $row['time_slot'];

    if ($previous_time_slot === $time_slot) {
        echo json_encode(['success' => false, 'errors' => ["You are already registered for this time slot."]]);
        exit();
    }

    // Update existing registration
    $stmt = $conn->prepare("UPDATE students SET first_name = ?, last_name = ?, project_title = ?, email = ?, phone = ?, time_slot = ? WHERE id = ?");
    $stmt->bind_param('ssssssi', $first_name, $last_name, $project_title, $email, $phone, $time_slot, $id);
    $stmt->execute();

    // Adjust seat counts for time slots
    $conn->query("UPDATE time_slots SET available_seats = available_seats - 1 WHERE slot_id = $time_slot");
    $conn->query("UPDATE time_slots SET available_seats = available_seats + 1 WHERE slot_id = $previous_time_slot");

    // Return success message
    echo json_encode(['success' => true, 'message' => "Registration updated successfully."]);
    exit();
} else {
    // New registration
    $stmt = $conn->prepare("INSERT INTO students (id, first_name, last_name, project_title, email, phone, time_slot) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param('isssssi', $id, $first_name, $last_name, $project_title, $email, $phone, $time_slot);
    $stmt->execute();

    // Decrement available seats
    $conn->query("UPDATE time_slots SET available_seats = available_seats - 1 WHERE slot_id = $time_slot");

    // Return success message
    echo json_encode(['success' => true, 'message' => "Registration successful."]);
    exit();
}
?>
