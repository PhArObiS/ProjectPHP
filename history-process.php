<!DOCTYPE html>
<html>
<head>
    <title>Game History</title>
    <style>
        table {
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid black;
            padding: 5px;
        }
    </style>
</head>
<body>
    <h1>Game History</h1>

    <table>
        <tr>
            <th>Score Time</th>
            <th>Player ID</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Result</th>
            <th>Lives Used</th>
        </tr>
        <?php
        // Connect to the database
        $servername = 'localhost';
        $username = 'root';
        $password = '';
        $dbname = 'kidsgames';

        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die('Connection failed: ' . $conn->connect_error);
        }

        // Fetch the game history from the history view
        $sql = 'SELECT * FROM history';
        $result = $conn->query($sql);

        $history = array();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $history[] = $row;
            }
        }

        // Close the database connection
        $conn->close();

        foreach ($history as $entry) {
            echo '<tr>';
            echo '<td>' . $entry['scoreTime'] . '</td>';
            echo '<td>' . $entry['id'] . '</td>';
            echo '<td>' . $entry['fName'] . '</td>';
            echo '<td>' . $entry['lName'] . '</td>';
            echo '<td>' . $entry['result'] . '</td>';
            echo '<td>' . $entry['livesUsed'] . '</td>';
            echo '</tr>';
        }
        ?>
    </table>

    <a href="index.html">Go Back to Log In</a>
    <br>
    <a href="game.php">play another game</a>

</body>
</html>
