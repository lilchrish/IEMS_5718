<?php
session_start();
ini_set('display_errors',1);
error_reporting(E_ALL);
$conn = mysqli_connect('buythebest.cvc6844gen9o.ap-northeast-1.rds.amazonaws.com', 'buythebest-admin','huangjiaqi8024', 'buythebest');
// Get the selected products from the AJAX request
$products = json_decode(file_get_contents('php://input'), true);
// Generate the digest
$currency = 'HKD';
$merchantEmail = 'sb-jzplm30408430@business.example.com';
$salt = uniqid(); // Generate a random salt

$pl = '';
$digestData = $currency . '|' . $merchantEmail . '|' . $salt;
$totalPrice = 0;
foreach ($products as $product) {
  $pid = $product['pid'];
  $quantity = $product['quantity'];

  // Retrieve the current price of the product from the database
  $sql = "SELECT price FROM product WHERE pid = '$pid';";
  $q = $conn -> query($sql);
  $price = 0;
  foreach($q as $prodvalue){
	  $price = $prodvalue['price']; 
  }
  $digestData .= '|' . $pid . '|' . $quantity . '|' . $price;
  $pl .= $pid . '&';
  $totalPrice += $price * $quantity;
  $q -> close();
}
$result = substr($pl,0,-1);
$digestData .= '|' . $totalPrice;
$digest = hash('sha256', $digestData);
// Store the order details in the database
$username = 'guest'; 
// Assuming guest checkout, you can modify this based on user login status
if(isset($_SESSION['userid']) && isset($_SESSION['email'])){
	$username = $_SESSION['email'];
}

$connn = mysqli_connect('buythebest.cvc6844gen9o.ap-northeast-1.rds.amazonaws.com', 'buythebest-admin','huangjiaqi8024', 'buythebest');
$sql = "INSERT INTO orders (username, payment_status, product_list, digest) VALUES (?,?,?,?);";
$qq = $connn -> prepare($sql);
$pending = 1;
$qq -> execute(array($username,$pending, $result, $digest));
$lastInsertId = mysqli_insert_id($connn);
$qq->close();

// Send the response back to the client
  $response = array(
    'lastInsertId' => $lastInsertId,
    'digest' => $digest
  );
echo json_encode($response)
?>
