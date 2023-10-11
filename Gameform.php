<div id="loading-animation-container">
  <!-- Animation goes here -->
  <div id="spinner"></div>
</div>

<?php
session_start(); // Start a session
if (!isset($_SESSION['lives'])) {
    $_SESSION['lives'] = 0; // Set initial lives to 0 if it's not already set
}

$levels = include 'levels.php';

$levelIndex = $_POST['levelIndex'] ?? 0;
$level = $levels[$levelIndex];
$userInput = $_POST['input'] ?? null;
$expectedAnswer = $_POST['expectedAnswer'] ?? '';

// Check if the user gave up
$giveUp = isset($_POST['giveUp']);

// Check if user input is an array
if (is_array($userInput)) {
    // Convert user's input array to a string
    $userInputString = implode('', $userInput);

    // Check if the user gave up
    if ($giveUp) {
        echo "<h1>Partie incomplète</h1>";
        echo "<p>Vous avez abandonné la partie ou vous avez été déconnecté.</p>";
        // Add any additional messages or actions you want here
    } else {
        // Check if the user input matches the expected answer for the current level
        if (strcasecmp($userInputString, $expectedAnswer) === 0) {
            // User succeeded in the current level
            if ($levelIndex == count($levels) - 1) {
                // User completed all levels
                echo "<h1>Congratulations! You completed all levels successfully.</h1>";
                echo "<p>Well done!</p>";
                echo "<button onclick=\"location.href='GameForms.php'\">Play Again</button>";
                $rsultmessage1 = 'reussite';
                
                session_destroy(); // Reset the session
            } else {
                // User completed the current level
                $nextLevelIndex = $levelIndex + 1;
                echo "<h1>Success!</h1>";
                echo "<p>Congratulations! You completed the level successfully.</p>";
                echo "<p>Proceed to the next level.</p>";
                echo "<form action=\"GameForms.php\" method=\"post\">";
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
            echo "<form action=\"GameForms.php\" method=\"post\">";
            echo "<input type=\"hidden\" name=\"levelIndex\" value=\"$levelIndex\">";
            echo "<button type=\"submit\">Try Again</button>";
            echo "</form>";
        }
    }
} else {
    // User input is not an array, handle the error gracefully
    echo "<h1>Error!</h1>";
    echo "<p>Invalid user input. Please try again.</p>";
    echo "<form action=\"GameForms.php\" method=\"post\">";
    echo "<input type=\"hidden\" name=\"levelIndex\" value=\"$levelIndex\">";
    echo "<button type=\"submit\">Try Again</button>";
    echo "</form>";
}
?>

