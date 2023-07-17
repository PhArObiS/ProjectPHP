<?php

// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'DatabaseConnection.php';
$dbConnection = new DatabaseConnection('localhost', 'root', '');
session_start();
// Check the connection
if ($dbConnection->getConnection()->connect_error) {
    die("Connection failed: " . $dbConnection->getConnection()->connect_error);
}

try {
    // Get the data from the login form
    $username = $_POST['username'];
    $password = $_POST['password'];
    $dbConnection->selectDatabase('kidsgames');
    $dbConnection->verifyCredentials($username, $password);
    $_SESSION['username'] = $username;
    if($dbConnection->verifyCredentials($username, $password) == true){
        header("Location: game.php");
    }
    else{
        header("Location: Mdp.html");
    }
    
    exit();
} catch (Exception $e) {
    // Respond with error message in case of error
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
    header("Location: Mdp.php");


}

?>
