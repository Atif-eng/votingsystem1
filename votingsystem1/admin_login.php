<?php
session_start();

if (isset($_SESSION['cnic'])){
    header('Location: voter_panel.php');
}
if (isset($_SESSION['user_name'])){
    header('Location: admin_panel.php');
}
if ( isset($_POST['user_name']) && isset($_POST['user_pass'])) {

        if( $_POST['user_name'] == 'atif' && $_POST['user_pass'] == '1447' ) {
            $_SESSION['user_name'] = "atif";
            // Check if the user_name session exists
            header('Location: admin_panel.php');

        } 
}

 // first check if user has filled the form by using isset and $_POST variables

    // if user has entered username and pass then perform the db connection and the query

    // right a query that will select the username and password from new_my where user name and password
    // is same as what user entered in the form.

    // Note: to get what user entered in the form we can use the $_POST variable

    // query = 'SELECT user_name, user_password from new_my where user_name=$_POST['user_name'] and user_password=$_POST['user_pass']

    // Check if the form has been submitted
if (isset($_POST['submit']) && isset($_POST['user_name']) && isset($_POST['user_pass']) ) {

    // Write the SQL query
    $input_username = $_POST['user_name'];
    $input_password = $_POST['user_pass'];

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "mynew";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT user_name, user_password FROM new_my WHERE user_name = '$input_username' AND user_password = '$input_password'";

    // Execute the query
    $result = $conn->query($sql);


    // Check if the query was successful
    if ( $result->num_rows > 0 ) {
        header("location:admin_panel.php");
        // Free the result set
        $result->free();
    } else {
        // Query failed
        echo "<script>alert('Username or Password not correct');</script>";
    }    

}
    ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voting System - Admin Login</title>
    <style>
.back-button {
    position: absolute;
    top: 20px;
    left: 20px;
}

.back-button button {
    background-color: red;
    color: white;
    cursor: pointer;
    padding: 8px 20px;
    border-radius: 5px;
    font-weight: 700;
    border: 1px solid red;
    transition: 0.3s all;
}
.back-button button:hover {
    background-color: transparent;
    color: red
}
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
            text-align: center;
        }

        h1 {
            color: #333;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-top: 20px;
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
    <div class="back-button">
        <button type="button" onclick="location.href='index.php'">Back To!</button>
    </div>

    <div class="container">
        <div class="admin1">
            <h1>Admin Login</h1>
            
        </div>
        <hr>
        <form action="admin_login.php" method="post">
            <label for="user_name">User Name:</label>
            <input type="text" id="user_name" name="user_name" required>

            <label for="user_pass">User Password:</label>
            <input type="password" id="user_pass" name="user_pass" required>

            <input type="submit" name="submit" value="Submit">
        </form>
    </div>

</body>

</html>














































