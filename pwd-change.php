<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include_once('lib/csrf_nonce.php');
if(!verifyNonce($_POST['nonce'])){
        throw New Exception('csrf-attack!!!');
}
session_start();
$db = mysqli_connect('buythebest.cvc6844gen9o.ap-northeast-1.rds.amazonaws.com', 'buythebest-admin','huangjiaqi8024', 'buythebest');
if ($db -> connect_errno) {
        echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
        exit();
}

//input validation
if ($_SERVER["REQUEST_METHOD"] == "POST"){
        if (empty($_POST['email'])){
                header("Location: login.php?error=Email is required");
                exit();
        }
        if (empty($_POST['old_password'])){
                header("Location: login.php?error=Password is required");
                exit();
	} 
	if (empty($_POST['new_password'])){
                header("Location: login.php?error=Password is required");
                exit();
        }
        $regex = '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/';
        if (!preg_match($regex, $_POST['email'])){
                header("Location: login.php?error=Email format is incorrect!");
                exit();
        }
}
$sql = "SELECT * FROM users WHERE email = ?;";
$q = $db -> prepare($sql);
$q -> execute(array($_POST['email']));
$result = $q -> get_result();
$user = $result -> fetch_assoc();
if($user){
        $pwd = hash_hmac("sha256", $_POST['old_password'], $user['salt']);
        if($pwd != $user['password']){
		header("Location: login.php?error=Old Password is incorrect");
	}else{	
		$salt = generateSalt();
		$newPwd = hash_hmac("sha256", $_POST['new_password'], $salt);
		$sqll = "UPDATE users SET password = ? , salt = ? WHERE email = ?;";
		$qq = $db -> prepare($sqll);
		$qq -> execute(array($newPwd, $salt, $_POST['email']));
		header("Location: logout.php");
	}
}else{
	header("Location: login.php?error=User not exist");
	exit();
}
