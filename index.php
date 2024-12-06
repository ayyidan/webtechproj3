<?php
// Include database connection
include('db_connection.php');

// Fetch time slots
$timeSlotsQuery = "SELECT * FROM time_slots WHERE available_seats > 0";
$result = $mysqli->query($timeSlotsQuery);
$timeSlots = [];
while ($row = $result->fetch_assoc()) {
    $timeSlots[] = $row;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Web Technology Class Registration</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Register for Project Demonstration</h1>
    <form id="registrationForm">
        <label for="id">Student ID</label>
        <input type="text" id="id" name="id" placeholder="8-digit Student ID" required>
        <span class="error" id="idError"></span>

        <label for="first_name">First Name</label>
        <input type="text" id="first_name" name="first_name" placeholder="Your First Name" required>
        <span class="error" id="firstNameError"></span>

        <label for="last_name">Last Name</label>
        <input type="text" id="last_name" name="last_name" placeholder="Your Last Name" required>
        <span class="error" id="lastNameError"></span>

        <label for="project_title">Project Title</label>
        <input type="text" id="project_title" name="project_title" placeholder="Your Project Title" required>
        <span class="error" id="projectTitleError"></span>

        <label for="email">Email</label>
        <input type="email" id="email" name="email" placeholder="example@example.com" required>
        <span class="error" id="emailError"></span>

        <label for="phone">Phone Number</label>
        <input type="text" id="phone" name="phone" placeholder="999-999-9999" required>
        <span class="error" id="phoneError"></span>

        <label for="time_slot">Time Slot</label>
        <select id="time_slot" name="time_slot" required>
            <?php
            foreach ($timeSlots as $slot) {
                echo "<option value='" . $slot['slot_id'] . "'>" . $slot['date_time'] . " - " . $slot['available_seats'] . " seats remaining</option>";
            }
            ?>
        </select>
        <span class="error" id="timeSlotError"></span>

        <button type="submit">Register</button>

        <a href="view_students.php">
        <button type="button">View Registered Students</button>
    </a>
    </form>

    <!-- Success Modal -->
    <div id="successModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <p>Registration Successful!</p>
        </div>
    </div>

    <!-- Error Modal (for duplicate email) -->
    <div id="errorModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <p id="modal-message">An error occurred. Please check your input and try again.</p>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Form submission handling via AJAX
        $('#registrationForm').on('submit', function (e) {
            e.preventDefault(); // Prevent the default form submission

            // Get form data
            const formData = $(this).serialize();

            // Clear previous error messages
            document.querySelectorAll('.error').forEach(error => error.textContent = '');

            // AJAX request to check duplicate email
            $.ajax({
                url: 'process_registration.php',
                type: 'POST',
                data: formData,
                success: function(response) {
                    const data = JSON.parse(response);

                    if (!data.success) {
                        // Highlight the error fields
                        if (data.errors) {
                            data.errors.forEach(function (error) {
                                if (error.includes("email")) {
                                    document.getElementById('emailError').textContent = error;
                                    $('#errorModal').show();
                                } else if (error.includes("ID")) {
                                    document.getElementById('idError').textContent = error;
                                } else if (error.includes("first name")) {
                                    document.getElementById('firstNameError').textContent = error;
                                } else if (error.includes("last name")) {
                                    document.getElementById('lastNameError').textContent = error;
                                } else if (error.includes("phone")) {
                                    document.getElementById('phoneError').textContent = error;
                                } else if (error.includes("time slot")) {
                                    document.getElementById('timeSlotError').textContent = error;
                                }
                            });
                        }
                    } else {
                        // Show success modal on successful registration
                        $('#successModal').show();
                    }
                }
            });
        });

        // Close modals
        document.querySelectorAll('.close').forEach((closeBtn) => {
            closeBtn.addEventListener('click', () => {
                document.querySelectorAll('.modal').forEach((modal) => {
                    modal.style.display = 'none';
                });
            });
        });
    </script>
</body>
</html>
