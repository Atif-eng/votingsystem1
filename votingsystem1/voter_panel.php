<?php  session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="index.css">
    <style>
        .container-candidate-box{
            display: flex;
            border: 14px;
            gap: 70px;
        }
       .candidate-box{
        padding: 10px;
        border: 1px solid black;
       }

        .candidate-image {
            width: 200px;
            height: 150px;
            object-fit: cover;
        }

        .submit-button {
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .default-image {
            width: 200px;
            height: 100px;
            background-color: #ddd;
        }
    </style>
    <title>Document</title>
</head>
</body>

    </html>
<body>

    <form action="#" method="post">
        <h1>Select candidate</h1>
        <input type="submit" name="voter_logout" value="Log out">
    </form>
    <?php
    if (!isset($_SESSION["cnic"])) {
        header('Location: voter_login.php');
    } elseif (isset($_POST['voter_logout'])) {
        if (isset($_SESSION['cnic'])) {
            // destroy the session
            session_destroy();
        }
        header('location: voter_login.php');
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
    else {
        echo "ATIF CONNECTION OK";
    }

    // Function to calculate and display winning party
    function displayWinner($conn)
    {
        $sql = "SELECT partyname, COUNT(*) as votes FROM candidate_voter_form WHERE vote_casted = 1 GROUP BY partyname ORDER BY votes DESC LIMIT 1";

        $result = mysqli_query($conn, $sql);

        if (!$result) {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        } else {
            $row = mysqli_fetch_assoc($result);
            echo "<p>Winning Party: " . $row['partyname'] . " (Total Votes: " . $row['votes'] . ")</p>";
        }
    }
    $sql = "SELECT id,partyname, name, mark, image, total_votes, vote_casted FROM candidate_voter_form";
    $result = mysqli_query($conn, $sql);

    if (!$result) {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    } else {?>
        <div class="container-candidate-box"><?php
            if ($_SESSION["cnic"]) {
                $cnic = $_SESSION["cnic"];
                // Query to check if the user has already cast a vote
                $checkVoteQuery = "SELECT vote_casted FROM candidate_voter_form WHERE cnic = '$cnic'";
                $checkVoteResult = mysqli_query($conn, $checkVoteQuery);
            
                if ($checkVoteResult) {
                    $voteRow = mysqli_fetch_assoc($checkVoteResult);
                    if ($voteRow['vote_casted'] == 0) {
                        // Proceed with fetching candidates since the user has not cast a vote
                        $candidateQuery = "SELECT id, name, mark, partyname, total_votes, image FROM candidate_voter_form";
                        $candidateResult = mysqli_query($conn, $candidateQuery);
            
                        if ($candidateResult) {
                            while ($row = mysqli_fetch_assoc($candidateResult)) {
                                if (!empty($row["mark"])) { ?>
                                    <div class="candidate-box">
                                        <?php
                                        // Display default image if no image is provided
                                        if (!empty($row["image"])) {
                                            echo "<img class='candidate-image' src='" . $row["image"] . "' alt='Candidate Image'>";
                                        } else {
                                            echo "<div class='default-image'></div>";
                                        }
                                        echo "<p>Name: " . $row["name"] . "</p>";
                                        echo "<p>Mark: " . $row["mark"] . "</p>";
                                        echo "<p>Party Name: " . $row["partyname"] . "</p>";
                                        echo "<p>Total Votes: " . $row["total_votes"] . "</p>";
                                        ?>
                                         <form method="post">
                                            <input type='hidden' name='selected_candidate' value='<?= $row['id'] ?>'>
                                            <input type='hidden' name='candidate_form_<?= $row['id'] ?>' value='true'>
                                            <button type="submit" name="submit_vote" value="<?= $row['id'] ?>" class="submit-button">Submit Vote</button>
                                        </form>
                                    </div>
                                <?php
                                }
                            }
                        } else {
                            echo "Error fetching candidates: " . mysqli_error($conn);
                        }
                    } else {
                        while ($row = mysqli_fetch_assoc($result)) {
                            if (!empty($row["mark"])) { ?>
                                <div class="candidate-box">
                                        <?php
                                        // Display default image if no image is provided
                                        if (!empty($row["image"])) {
                                            echo "<img class='candidate-image' src='" . $row["image"] . "' alt='Candidate Image'>";
                                        } else {
                                            echo "<div class='default-image'></div>";
                                        }
                            
                                        echo "<p>Name: " . $row["name"] . "</p>";
                                        echo "<p>Mark: " . $row["mark"] . "</p>";
                                        echo "<p>Party Name: " . $row["partyname"] . "</p>";
                                        echo "<p>Total Votes: " . $row["total_votes"] . "</p>";
                                        ?>

                                            <button type="button" name="submit_vote" value="<?= $row['id'] ?>" style="opacity:0.5" class="submit-button">Submit Vote</button>
                                        
                                </div>
                            <?php
                            }
                        }
                    }
                } else {
                    echo "Error checking vote status: " . mysqli_error($conn);
                }
            }
        
        
        ?></div><?php

        // // Display winning party
        // displayWinner($conn);
    }
    if (isset($_POST['submit_vote'])) {
        $selected_candidate = $_POST['submit_vote'];
        $cnic = $_SESSION['cnic'];
        
        // Update vote_casted status and increment total_votes
        $update_sql = "UPDATE candidate_voter_form 
                       SET total_votes = total_votes + 1 
                       WHERE id = '$selected_candidate'";
    
        $update_user = "UPDATE candidate_voter_form 
                        SET vote_casted = 1
                        WHERE cnic = '$cnic'";
        
        $update_result = mysqli_query($conn, $update_sql);
        $result = mysqli_query($conn, $update_user);
        
        if (!$update_result && !$result) {
            echo "Error updating vote status: " . mysqli_error($conn);
        } else {
            echo "Vote casted successfully!";
            header('Location:voter_panel.php');
            exit();
        }
    }
    
    // $highestVotesQuery = "SELECT * FROM candidate_voter_form 
    //                   WHERE total_votes = (
    //                         SELECT MAX(total_votes) FROM candidate_voter_form 
    //                         WHERE mark IS NOT NULL AND total_votes > 0
    //                   ) AND mark IS NOT NULL AND total_votes > 0";
    $highestVotesQuery = "SELECT * FROM candidate_voter_form 
    WHERE total_votes = (SELECT MAX(total_votes) FROM candidate_voter_form)";




    $highestVotesResult = mysqli_query($conn, $highestVotesQuery);

    if ($highestVotesResult) {
        echo "<p>Candidates/Parties with the highest votes:</p>";
        while ($row = mysqli_fetch_assoc($highestVotesResult)) {
            echo "<p>Name: " . $row['name'] . "</p>";
            echo "<p>Party Name: " . $row['partyname'] . "</p>";
            echo "<p>Total Votes: " . $row['total_votes'] . "</p>";
            echo "<hr>"; // Separating each candidate or party details
        }
    } else {
        echo "Error fetching candidates/parties with the highest votes: " . mysqli_error($conn);
    }

    mysqli_close($conn);
    ?>