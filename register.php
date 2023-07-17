<?php
// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'DatabaseConnection.php';

$dbConnection = new DatabaseConnection('localhost', 'root', '');

// Check the connection
if ($dbConnection->getConnection()->connect_error) {
    die("Connection failed: " . $dbConnection->getConnection()->connect_error);
}

try {
    // get the data from AJAX
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];

    // // perform validation here, for instance, check if username already exists
    // $stmt = $dbConnection->getConnection()->prepare("SELECT * FROM users WHERE username = ?");
    // $stmt->bind_param("s", $username);
    // $stmt->execute();
    // $result = $stmt->get_result();
    // if ($result->num_rows > 0) {
    //     throw new Exception("Username already taken!");
    // }

    // Check if password and confirm_password match
    if ($password !== $confirm_password) {
        throw new Exception("Passwords do not match!");
    }

    // Hash the password before storing it
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);
    $dbConnection->selectDatabase('kidsgames');

   // insert the new user into the database and get the registrationOrder
   $registrationOrder = $dbConnection->insertPlayer($first_name, $last_name, $username);

   // insert the foreign key into the authenticator table
   $dbConnection->insertAuthenticator($hashed_password, $registrationOrder);


    echo json_encode(["success" => true, "message" => "Registration successful!"]);
    header("Location: index.html");
} catch (Exception $e) {
    // Respond with error message in case of error
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}

?>
