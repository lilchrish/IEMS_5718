<?php 
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
        if (empty($_POST['password'])){
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
        $pwd = hash_hmac("sha256", $_POST['password'], $user['salt']);
        if($pwd != $user['password']){
		header("Location: login.php?error=Password is wrong");
	}else{

		$exp = time() + 3600 * 24 * 2;
		$token = array('email' => $user['email'], 'exp' => $exp, 'k' => hash_hmac('sha256', $exp.$user['password'], $userr['salt']));
		setcookie('auth', json_encode($token), $exp,'/','', true, true);
		$_SESSION['auth'] = $token;
                $_SESSION['userid'] = $user['userid'];
                $_SESSION['email'] = $user['email'];
		$_SESSION['is_admin'] = $user['role'];
		session_regenerate_id();
                if($user['role'] == 1)
                        header("Location: admin.php");
                else
                        header("Location: index.php");
                exit();
        }
}else{
        $sqll = "INSERT INTO users (email, password,salt) VALUES (?, ?, ?);";
	$qq = $db -> prepare($sqll);
	$salt = generateSalt();
	$pwd = hash_hmac("sha256", $_POST['password'], $salt);
        $qq -> execute(array($_POST['email'], $pwd, $salt));
	header("Location: index.php");
        exit;

}


?>
