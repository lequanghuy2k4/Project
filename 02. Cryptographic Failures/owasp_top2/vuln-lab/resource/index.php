<?php
session_start();//session start, allow to store user data across pages
$db = new SQLite3('database.db');//create SQL database connect to database.db, if no file create new one
$loginError = '';

if (isset($_POST['username']) && isset($_POST['password'])) { //check user input
    $username = $_POST['username'];
    $raw_password = $_POST['password'];

    $password = md5($raw_password);//encode user password then take that to compare from the database
    
    $stmt = $db->prepare('SELECT account_number, password FROM users WHERE username = :username');
    /*prepare SQL statement to retrieves the account number and password for a user with the provided username */
    $stmt->bindValue(':username', $username, SQLITE3_TEXT);/*prevent sql injection by bind the user input
    to a variable then the database will treat the special character of the input will be treat as data 
    instead of syntax*/
    
    $result = $stmt->execute();
    $user = $result->fetchArray();//take data from database

    if ($user && $password === $user['password']) {//check if user and pass is legit and not flase
        $_SESSION['user_id'] = $user['account_number'];//store user acc number in user_id
        header("Location: account.php?account_number=" . $user['account_number']);// if success redirect to account.php
        exit;//prevent any execution for security
    } else {
        $loginError = "Invalid username or password.";
    }
}
include 'index.html';
?>


