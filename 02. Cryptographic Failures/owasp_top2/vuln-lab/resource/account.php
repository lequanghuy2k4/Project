<?php
session_start();
$db = new SQLite3('database.db');//connect to database

function getAccountDetails($db, $accountNumber) { //get account detail
    $stmt = $db->prepare('SELECT * FROM users WHERE account_number = :accountNumber');
    $stmt->bindValue(':accountNumber', $accountNumber, SQLITE3_INTEGER);
    $result = $stmt->execute();

    return $result->fetchArray();
}

$accountDetails = null;

$userAccountNumber = $_SESSION['user_id'];//retrieves logged-in user's account number from user_id

if (isset($_GET['account_number'])) { //check if url contain account number
    $accountNumber = $_GET['account_number'];//if yes assign value to $accountNumber
    
    if ($accountNumber == $userAccountNumber) {//check if match
        $accountDetails = getAccountDetails($db, $accountNumber);//if match, get acc details then assign to
    } else {
        header("Location: account.php?account_number=" . $userAccountNumber);
        //if not match,kick user to their own page prevent from access other account
    }

} else {
    header("Location: account.php?account_number=" . $userAccountNumber);
    exit;
}
include 'account.html';
?>

