<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="./css/gameForm.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/animejs/3.2.1/anime.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.bundle.min.js"></script>
    <title>LaSalleQuizApp</title>
</head>
<body id="home">
<header>
    <div class="navbar-container">
        <nav class="navbar navbar-expand-lg navbar-dark">
            <a class="navbar-brand" href="index.html">
                <span class="text-primary-logo"><strong>LaSalle</strong></span>
                <span class="text-Logo"><strong>QuizApp.</strong></span>
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item active">
                        <a class="nav-link" href="index.html">log out</a>
                    </li>
                   
                    <li class="nav-item">
                        <a class="nav-link" href="history-process.php">history</a>
                    </li>
                </ul>
            </div>
        </nav>
    </div>
</header>
<main>
    <div class="game-container">
        <h1 class="heading">Quiz App</h1>
        <section class="gameform">
        <?php
    session_start();

    require_once 'DatabaseConnection.php';

    // Start the timer when the game starts
    if (!isset($_SESSION['time_start'])) {
        $_SESSION['time_start'] = time();
    }

    // Retrieve the levels
    $levels = include 'levels.php';

    // Retrieve the current level index from the POST data or set it to 0 by default
    $levelIndex = $_POST['levelIndex'] ?? 0;

    // Retrieve the current level
    $level = $levels[$levelIndex];

    // Retrieve user input and expected answer from the POST data
    $userInput = $_POST['input'] ?? null;
    $expectedAnswer = $_POST['expectedAnswer'] ?? '';

    // Check if the user gave up
    $giveUp = isset($_POST['giveUp']);

 // Check if the session variable for lives is set
 $lives = $_SESSION['lives'] ?? 0;

    // Convert user's input array to a string
    $userInputString = implode('', (array)$userInput);

    if (isset($_POST['giveUp'])) {
        // The user gave up, so the result is incomplete
        $resultMessage = 'incomplet';
        $giveUp = true;
        echo "<h1>Partie incomplète (partie abandonnée ou compte utilisateur déconnecté: cancel, time-out ou sign-out)</h1>";
        echo "<p>Le joueur a abandonné la partie de jeu en cours.</p>";
        echo "<p>Partie terminée.</p>";
          // Game over, retrieve the data
        //   $lives = $_SESSION['lives'];

          $time_start = $_SESSION['time_start'];
          $time_end = time();
          $time_taken = $time_end - $time_start; // Calculate the time taken
          $formatted_time = gmdate("0000-00-00 H:i:s", $time_taken);
      
              // Insert the score into the database
              $dbConnection = new DatabaseConnection("localhost", "root", "");
              $dbConnection->selectDatabase('kidsgames');
      
              $username = $_SESSION['username'];
              $registrationOrder = $dbConnection->getRegistrationOrderByUsername($username);
              $dbConnection->insertScore($formatted_time, $resultMessage, $lives, $registrationOrder);
              $_SESSION['lives'] = 0;


    } else {
        $giveUp = false;
    }
    

    

    if ($userInput !== null  ) {
        // Check if the user input matches the expected answer for the current level
        
// Check if the user input matches the expected answer for the current level
        $isCorrect = (strcasecmp(trim($userInputString), trim($expectedAnswer)) === 0);
        if (!$isCorrect && !$giveUp) {
            // Increment lives if the answer is incorrect
            $lives++;
            $_SESSION['lives'] = $lives;

            if ($lives >= 6) {
                $resultMessage = 'echec';
                $time_start = $_SESSION['time_start'];
                $time_end = time();
                $time_taken = $time_end - $time_start; // Calculate the time taken
                $formatted_time = gmdate("0000-00-00 H:i:s", $time_taken);
            
                    // Insert the score into the database
                    $dbConnection = new DatabaseConnection("localhost", "root", "");
                    $dbConnection->selectDatabase('kidsgames');
            
                    $username = $_SESSION['username'];
                    $registrationOrder = $dbConnection->getRegistrationOrderByUsername($username);
                    $dbConnection->insertScore($formatted_time, $resultMessage, $lives, $registrationOrder);
    
                echo "<h1>Game Over!</h1>";
                echo "<p>Sorry, you ran out of lives.</p>";
                echo "<button onclick=\"location.href='game.php'\">Play Again</button>";
                session_destroy();
                exit; // Stop further processing
            }
        }
        // Display the result message based on correctness
        if ($isCorrect) {
            
            if ($levelIndex == count($levels) - 1) {
                // User completed all levels
                $resultMessage = 'succes';
                $time_start = $_SESSION['time_start'];
                $time_end = time();
                $time_taken = $time_end - $time_start; // Calculate the time taken
                $formatted_time = gmdate("0000-00-00 H:i:s", $time_taken);
            
                    // Insert the score into the database
                    $dbConnection = new DatabaseConnection("localhost", "root", "");
                    $dbConnection->selectDatabase('kidsgames');
            
                    $username = $_SESSION['username'];
                    $registrationOrder = $dbConnection->getRegistrationOrderByUsername($username);
                    $dbConnection->insertScore($formatted_time, $resultMessage, $lives, $registrationOrder);
                echo "<h1>Congratulations! You completed all levels successfully.</h1>";
                echo "<p>Well done!</p>";
                echo "<button onclick=\"location.href='game.php'\">Play Again</button>";
                session_destroy();
            } else {
                // User completed the current level
                $nextLevelIndex = $levelIndex + 1;
                echo "<h1>Success!</h1>";
                echo "<p>Congratulations! You completed the level successfully.</p>";
                echo "<p>Proceed to the next level.</p>";
                echo "<form action=\"game.php\" method=\"post\">";
                echo "<input type=\"hidden\" name=\"levelIndex\" value=\"$nextLevelIndex\">";
                echo "<button type=\"submit\">Next Level</button>";
                echo "</form>";
                
            }
        } else {
            // User failed in the current level
            echo "<h1>Failure!</h1>";
            echo "<p>Oops! Your arrangement is incorrect.</p>";
            echo "<p>Please try again.</p>";
            echo "<p>Expected Answer: " . $expectedAnswer . "</p>";
            echo "<p>User Input: " . $userInputString . "</p>";
            echo "<form action=\"game.php\" method=\"post\">";
            echo "<input type=\"hidden\" name=\"levelIndex\" value=\"$levelIndex\">";
            echo "<button type=\"submit\">Try Again</button>";
            echo "</form>";
            $lives++;
            

        }
    } else {
        // Display the form for the current level
        echo "<h2 id=\"levelTitle\">" . $level['title'] . "</h2>";
        echo "<p id=\"levelDescription\">Instructions: Arrange the given characters/numbers according to the specified order.</p>";

        echo "<form id=\"gameForm\" class=\"level\" action=\"game.php\" method=\"POST\">";
        echo "<div class=\"header\">";
        echo "<div class=\"top-left\">";
        echo "User: " . $_SESSION['username'];
        echo "</div>";
        echo "<div class=\"top-right\">";
        echo "Lives used: " . $lives ."/6";
        echo "</div>";
        echo "</div>";

        echo "<p id=\"lettersToArrange\">" . $level['randomLetters'] . "</p>"; // Display random letters before user input

        $numInputBoxes = $level['numInputBoxes'];

        for ($i = 0; $i < $numInputBoxes; $i++) {
            echo "<input type=\"text\" name=\"input[]\" required>";
        }

        echo "<input type=\"hidden\" id=\"levelIndex\" name=\"levelIndex\" value=\"$levelIndex\">";
        echo "<input type=\"hidden\" name=\"expectedAnswer\" value=\"" . $level['expectedAnswer'] . "\">"; // Add hidden input for expected answer
        echo "<button type=\"submit\" name=\"submit\">Submit</button>";
        echo "<button id=\"giveUpButton\" type=\"submit\" name=\"giveUp\" formnovalidate>I Give Up!</button>";
        echo "</form>";
    }
    ?>
        </section>
    </div>
</main>
<footer class="footer-container">
    <div class="col-md-12">
        <p class="text-center">LaSalle Quiz App &copy; 2023</p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
<script src="./js/script.js"></script>
</body>
</html>
