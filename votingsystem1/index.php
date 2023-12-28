<?php 
  session_start();
  if(isset($_SESSION['user_name'])){
    header('Location: admin_panel.php');
}

if(isset($_SESSION['cnic'])){
    header('Location: voter_panel.php');
}

if(isset ($_SESSION['admin_logout'])){
    header('location: index.php' );
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>voting system</title>
    <link rel="stylesheet" href="index.css">
</head>
<body style="background-color:powderblue;">

    <div class="container">  
    <div class="buttons-wrapper">
    <h1>Voting system</h1>
    
 

    <div class="btns">
    <a href="admin_login.php" class="btn btn-success" target="_blank">Admin</a>
    <a href="voter_login.php" class="btn btn-success" target="_blank">Voter</a>
    <a href="visitor_panel.php" class="btn btn-success" target="_blank">Visitor</a>
    <!--<button>Admin</button>
    <button>Voter</button>
    <button>visitor</button>-->
    </div>
    </div>
</div>
</body>
</html>































