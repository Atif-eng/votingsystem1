<?php
session_start();
if (isset($_SESSION['cnic'])) {
    header('Location: voter_panel.php');
}
// Check if the user is already logged in
if (isset($_SESSION['user_name'])) {
    header('Location: admin_panel.php');
    exit(); // Stop further execution to prevent unnecessary processingz
}
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "votingsystem";

// Create a new connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['submit'])) {
    // Get user input
    $input_CNIC = $_POST['cnic'];
    $input_password = $_POST['pass'];

    // Validate CNIC length
    if (strlen($input_CNIC) !== 15) {
        echo "Invalid CNIC length. Please enter a valid 15-digit CNIC.";
    } else {
        // Use prepared statement to prevent SQL injection
        $stmt = $conn->prepare("SELECT * FROM candidate_voter_form WHERE cnic = ? AND pass = ?");
        $stmt->bind_param("ss", $input_CNIC, $input_password);

        // Execute the query
        $stmt->execute();

        // Store the result
        $result = $stmt->get_result();

        // Check if a row is returned
        if ($result->num_rows === 1) {
            // Fetch user data
            $user_data = $result->fetch_assoc();

            // Store user information in the session
            $_SESSION['cnic'] = $user_data['cnic'];
            $_SESSION['user_id'] = $user_data['user_id']; // Assuming user_id is the column in your table

            // Redirect to voter panel
            header('Location: voter_panel.php');
            exit(); // Stop further execution after redirect
        } else {
            echo "Invalid CNIC or password.";
        }

        $stmt->close();
    }
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voter Login</title>
    <style>
        body {
            background-color: powderblue;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
        }

        h1 {
            text-align: center;
            color: #333;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        label {
            font-weight: bold;
            color: #333;
        }

        input {
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
        }

        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        hr {
            margin-top: 20px;
            margin-bottom: 20px;
            border: 0;
            border-top: 1px solid #ccc;
        }
    </style>
</head>

<body>

    <div class="container">
        <h1>Voter Login</h1>
        <form action="#" method="post">
            <label for="cnic">Your CNIC:</label>
            <input type="text" id="cnic" name="cnic" maxlength="15" required>

            <label for="pass">Your Password:</label>
            <input type="password" id="pass" name="pass" required>

            <input type="submit" name="submit" value="Submit">
        </form>
    </div>

</body>

</html>














