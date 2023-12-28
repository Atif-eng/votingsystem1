<?php
session_start();

if (!isset($_SESSION["user_name"])) {
    header('Location: admin_login.php');
} elseif (isset($_POST['admin_logout'])) {
    if (isset($_SESSION['user_name'])) {
        // destroy the session
        session_destroy();
    }
    header('location: admin_login.php');
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "votingsystem";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

// ... Your existing code ...

if (
    isset($_POST['add_candidate']) && (
        empty(trim($_POST['party_name'])) ||
        empty(trim($_POST['candidate_name'])) ||
        empty(trim($_POST['party_mark']))
    )
) {
    echo "Please fill in all the requirements.";
} else if (
    isset($_POST['add_candidate']) &&
    !empty(trim($_POST['party_name'])) &&
    !empty(trim($_POST['candidate_name'])) &&
    !empty(trim($_POST['party_mark']))
) {
    // Write the SQL query
    $input_name = mysqli_real_escape_string($conn, $_POST['party_name']);
    $input_partyname = mysqli_real_escape_string($conn, $_POST['candidate_name']);
    $input_mark = mysqli_real_escape_string($conn, $_POST['party_mark']);
    $input_image = mysqli_real_escape_string($conn, $_POST['image']);

    // Check for special characters in input
    if (!preg_match("/^[a-zA-Z0-9 ]*$/", $input_name) ||
        !preg_match("/^[a-zA-Z0-9 ]*$/", $input_partyname) ||
        !preg_match("/^[a-zA-Z0-9 ]*$/", $input_mark)) {
        echo "Special characters are not allowed in input fields.";
    } else {
        // Check if the candidate already exists using CNIC
        $checkCandidateQuery = "SELECT * FROM candidate_voter_form WHERE cnic = '$input_name'";
        $checkCandidateResult = $conn->query($checkCandidateQuery);

        if ($checkCandidateResult->num_rows > 0) {
            echo "Candidate with the provided CNIC already exists.";
        } else {
            // Corrected SQL query with single quotes around values
            $sql = "INSERT INTO candidate_voter_form (name, partyname, mark, image)
                    VALUES ('$input_name', '$input_partyname', '$input_mark','$input_image' )";

            // Execute the query
            $result = $conn->query($sql);

            // Check for errors
            if (!$result) {
                echo "Error: " . $sql . "<br>" . $conn->error;
            } else {
                echo "Record inserted successfully";
            }
        }
    }

    // Close the database connection
    $conn->close();
}


// Voter form
// Create a new connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (
    isset($_POST['Add_voter']) && (
        empty(trim($_POST['Enter_Name'])) ||
        empty(trim($_POST['Enter_CNIC'])) ||
        empty(trim($_POST['Enter_password']))
    )
) {
    echo "Please fill in all the requirements.";
} elseif (
    isset($_POST['Add_voter']) &&
    !empty(trim($_POST['Enter_Name'])) &&
    !empty(trim($_POST['Enter_CNIC'])) &&
    !empty(trim($_POST['Enter_password']))
) {
    // Validate CNIC length
    $input_CNIC = $_POST['Enter_CNIC'];
    if (strlen($input_CNIC) !== 15) {
        echo "Please enter a valid 15-digit CNIC number.";
    } else {
        // Write the SQL query
        $input_name = $_POST['Enter_Name'];
        $input_password = $_POST['Enter_password'];

        // Check if the voter already exists using CNIC
        $checkVoterQuery = "SELECT * FROM candidate_voter_form WHERE cnic = '$input_CNIC'";
        $checkVoterResult = $conn->query($checkVoterQuery);

        if ($checkVoterResult->num_rows > 0) {
            echo "Voter with the provided CNIC already exists.";
        } else {
            // Corrected SQL query with single quotes around values
            $sql = "INSERT INTO candidate_voter_form (name, cnic, pass)
                    VALUES ('$input_name', '$input_CNIC', '$input_password')";

            // Execute the query
            $result = $conn->query($sql);

            // Check for errors
            if (!$result) {
                echo "Error: " . $sql . "<br>" . $conn->error;
            } else {
                echo "Record inserted successfully";

                // Set a session variable for successful voter insertion
                $_SESSION['voter_inserted'] = true;
            }
        }

        // Close the database connection
        $conn->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="index.css">
    <title>Admin panel</title>
</head>
<h1>Admin Panel</h1>
<body style="background-color:powderblue;">
<div class="container1">
    <div class="admin_class">
        <form action="#" method="post">
            <div class="admin2">
                <h2>Candidate Form</h2>
            </div>
            <b>Party Name:</b><input type="text" name="party_name"/><br>
            <b>Name:</b><input type="text" name="candidate_name"/><br>
            <b>Mark:</b><input type="text" name="party_mark"/><br>
            <b>Image<input type="file" name="image" src="" alt=""></b>
            <input type="submit" name="add_candidate" value="Add candidate">
        </form>
        <div class="form-2">
            <h2>Voter Form</h2>
            <form action="#" method="post">
                <b>Name:</b><input type="Name" name="Enter_Name"/><br>
                <b>CNIC:</b><input type="CNIC" name="Enter_CNIC"/><br>
                <b>Password:</b><input type="Password" name="Enter_password"/><br>
                <input type="submit" name="Add_voter" value="Add_voter">
                <div style="text-align:center"> <input type="submit" name="admin_logout" value="Log out"> </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>
