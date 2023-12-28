<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="index.css">
    <style>
        .container-candidate-box {
            display: flex;
            border: 14px;
            gap: 70px;
            justify-content: center;
        }

        .candidate-box.topper {
            max-width: 400px;
            margin: 0 auto;
            margin-top: 15px;
        }

        .candidate-box {
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

        .highest-votes {
            background-color: #26b068e8;
            color: white;
        }
    </style>
    <title>Document</title>
</head>

<body>

    <?php
    // Database connection
    session_start();
    if (isset($_SESSION['cnic'])) {
        header('Location: voter_panel.php');
    }


    if (isset($_SESSION['user_name'])) {
        header('Location: voter_panel.php');
    }

    if (isset($_SESSION['logout'])) {
        header('Location: admin_panel.php');
    }

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "votingsystem";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    } 
    // else {
    //     echo "ATIF CONNECTION OK";
    // }

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

    $sql = "SELECT id, partyname, name, mark, image, total_votes, vote_casted FROM candidate_voter_form";
    $result = mysqli_query($conn, $sql);

    if (!$result) {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    } else  ?>
        <div class="container-candidate-box"><?php
                                                $candidateQuery = "SELECT id, name, mark, partyname, total_votes, image FROM candidate_voter_form";
                                                $candidateResult = mysqli_query($conn, $candidateQuery);

                                                if ($candidateResult) {
                                                    while ($row = mysqli_fetch_assoc($candidateResult)) {
                                                        if (!empty($row["mark"])) { ?>
                                                            <div class="candidate-box <?php echo ($row['total_votes'] == getHighestVotes($conn)) ? 'highest-votes' : ''; ?>">
                                                                <?php
                                                                if (!empty($row["image"])) {
                                                                    echo "<img class='candidate-image' src='" . $row["image"] . "' alt='Candidate Image'>";
                                                                } else {
                                                                    echo "<div class='default-image'></div>";
                                                                }
                                                                ?>
                                                                <div>
                                                                    <p>Name: <?php echo $row["name"]; ?></p>
                                                                    <p>Mark: <?php echo $row["mark"]; ?></p>
                                                                    <p>Party Name: <?php echo $row["partyname"]; ?></p>
                                                                    <p>Total Votes: <?php echo $row["total_votes"]; ?></p>
                                                                </div>
                                                            </div>
                                                        <?php
                                                        }
                                                    }
                                                } else {
                                                    echo "Error fetching candidates: " . mysqli_error($conn);
                                                }
                                                ?></div><?php

                                                        $highestVotesQuery = "SELECT * FROM candidate_voter_form 
                                                        WHERE total_votes = (
                                                            SELECT MAX(total_votes) FROM candidate_voter_form 
                                                            WHERE mark IS NOT NULL AND total_votes > 0
                                                        ) AND mark IS NOT NULL AND total_votes > 0";

                                                        $highestVotesResult = mysqli_query($conn, $highestVotesQuery);

                                                        if ($highestVotesResult) {

                                                            while ($row = mysqli_fetch_assoc($highestVotesResult)) {
                                                                echo "<div class='candidate-box highest-votes topper'>";
                                                                echo "<p>Candidates/Parties with the highest votes:</p>";
                                                                echo "<div>";
                                                                echo "<p>Name: " . $row['name'] . "</p>";
                                                                echo "<p>Party Name: " . $row['partyname'] . "</p>";
                                                                echo "<p>Total Votes: " . $row['total_votes'] . "</p>";
                                                                echo "</div>";
                                                                echo "</div>";
                                                                echo "<hr>"; // Separating each candidate or party details
                                                            }
                                                        } else {
                                                            echo "Error fetching candidates/parties with the highest votes: " . mysqli_error($conn);
                                                        }

                                                        mysqli_close($conn);

                                                        function getHighestVotes($conn)
                                                        {
                                                            $query = "SELECT MAX(total_votes) as highest_votes FROM candidate_voter_form WHERE mark IS NOT NULL AND total_votes > 0";
                                                            $result = mysqli_query($conn, $query);

                                                            if ($result) {
                                                                $row = mysqli_fetch_assoc($result);
                                                                return $row['highest_votes'];
                                                            } else {
                                                                return 0;
                                                            }
                                                        }
                                                        ?>
</body>

</html>
