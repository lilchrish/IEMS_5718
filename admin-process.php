<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include_once('lib/db.inc.php');
include_once('lib/csrf_nonce.php');
header('Content-Type: application/json');

//auth token validation
$auth_email = '';
$checkR = false;
//auth token validation
if (empty($_SESSION['auth']) && empty($_COOKIE['auth'])){
        header("Location: login.php");
}
if (!empty($_SESSION['auth'])){
        $auth_email .= $_SESSION['auth']['email'];
        $checkR = true;
}
if (!empty($_COOKIE['auth'])){
        if($t = json_decode($_COOKIE['auth'],true)){
                if (time() > $t['exp']) return $checkR;
                global $db;
                $q = $db -> prepare('SELECT salt, password FROM users WHERE email = ?');
                if($q -> execute(array($t['em'])) && ($r = $q -> get_result() -> fetch_assoc()) && $t['k'] == hash_hmac("sha256", $t['exp'].$r['password'],$r['salt'])){
                        $_SESSION['auth'] = $_COOKIE['auth'];
                        $auth_email = $t['email'];
                        $checkR = true;
                }
        }
}
if(!$checkR){
        throw new Exception("Invalid authentication!");
}

// input validation
if (empty($_REQUEST['action']) || !preg_match('/^\w+$/', $_REQUEST['action'])) {
	echo json_encode(array('failed'=>'undefined'));
	exit();
}

//nonce verify
if (verifyNonce($_POST['nonce'])){
	try {
		if (($returnVal = call_user_func('iems5718_' . $_REQUEST['action'])) === false) {
			if ($db && $db->errorCode()) 
				error_log(print_r($db->errorInfo(), true));
			echo json_encode(array('failed'=>'1'));
		}
		echo 'while(1);' . json_encode(array('success' => $returnVal));
	} catch(PDOException $e) {
		error_log($e->getMessage());
		echo json_encode(array('failed'=>'error-db'));
	} catch(Exception $e) {
		echo 'while(1);' . json_encode(array('failed' => $e->getMessage()));
	}
}else{
	throw new Exception('csrf-attack!!!!');

}
?>
