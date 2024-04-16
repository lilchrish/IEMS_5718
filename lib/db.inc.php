<?php
function iems5718_DB() {
        // connect to the database
        // TODO: change the following path if needed
        // Warning: NEVER put your db in a publicly accessible location

        // You may need to install php-sqlite3: sudo apt-get install php-sqlite3
        // and sqlite3 if your linux does not have it.
        // Create the database by running sqlite3: sqlite3 cart.db
        // And create the tables using the sample code in Lecture Note 5 - Web and DB server.
        // use "ls -l" to check if your Apache (or web server) has the permission to access it

        // THE CODE PROVIDED is NOT GUARANTEED to be SECURE!.
        $db = mysqli_connect('buythebest.cvc6844gen9o.ap-northeast-1.rds.amazonaws.com', 'buythebest-admin','huangjiaqi8024', 'buythebest');
        if ($db -> connect_errno) {
                echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
                exit();
        }
        return $db;
}

function iems5718_cat_fetchall() {
    // DB manipulation
    global $db;
    $db = iems5718_DB();
    $sql = "SELECT * FROM category LIMIT 100;";
    $q = $db -> query($sql);
    return $q;
}

function iems5718_cat_fetchByName($name) {
    // DB manipulation
    global $db;
    $db = iems5718_DB();
    $sql = "SELECT * FROM category where name = '$name';";
    $q = $db -> query($sql);
    $result = '';
    foreach ($q as $v){
     $result .= $v['catid'];
    }
    return $result;
}

function iems5718_prod_fetchByCatid($cid) {
    // DB manipulation
    global $db;
    $db = iems5718_DB();
    $sql = "SELECT * FROM product WHERE catid = '$cid';";
    $q = $db -> query($sql);
    return $q;
}

function iems5718_prod_fetchByNameID($namee, $cid){
    // DB manipulation
    global $db;
    $db = iems5718_DB();
    $sql = "SELECT * FROM product WHERE catid = '$cid' AND name = '$namee';";
    $q = $db -> query($sql);
    return $q; 
}

// Since this form will take file upload, we use the tranditional (simpler) rather than AJAX form submission.
// Therefore, after handling the request (DB insert and file copy), this function then redirects back to admin.html
function iems5718_prod_insert() {
    // input validation or sanitization
    if ($_SERVER["REQUEST_METHOD"] == "POST"){
    	if (!preg_match('/^\d*$/', $_POST['catid']))
        	throw new Exception("invalid-catid");
    	$_POST['catid'] = (int) $_POST['catid'];
    	if (!preg_match('/^[\w\-]+$/', $_POST['name']))
        	throw new Exception("invalid-name");
    	if (!preg_match('/^[\d\.]+$/', $_POST['price']))
        	throw new Exception("invalid-price");
    	if (!preg_match('/[\w\s\p{P}]+/', $_POST['description']))
       		throw new Exception("invalid-text");
    }
    // DB manipulation
    global $db;
    $db = iems5718_DB();

    // TODO: complete the rest of the INSERT command


    // Copy the uploaded file to a folder which can be publicly accessible at incl/img/[pid].jpg
    if ($_FILES["file"]["error"] == 0
        && $_FILES["file"]["type"] == "image/jpeg"
        && mime_content_type($_FILES["file"]["tmp_name"]) == "image/jpeg"
        && $_FILES["file"]["size"] < 5000000) {


        $catid = $_POST["catid"];
        $name = $_POST["name"];
        $price = $_POST["price"];
        $desc = $_POST["description"];
        $sql="INSERT INTO product (catid, name, price, description) VALUES (?,?,?,?);";
	$q = $db -> prepare($sql);
	$q -> execute(array($catid,$name,$price,$desc));
        $lastId = mysqli_insert_id($db);
        // Note: Take care of the permission of destination folder (hints: current user is apache)
                // Check your /etc/apache2/site-enabled and apache2.conf for the "user"
                // also, you need to set file upload option in your php.ini
                // To find php.ini, run "php --ini"
        if (move_uploaded_file($_FILES["file"]["tmp_name"], "/var/www/html/images/" . $lastId . ".jpg")) {
            // redirect back to original page; you may comment it during debug
            header('Location: admin.php');
            exit();
        }
    }
    // Only an invalid file will result in the execution below
    // To replace the content-type header which was json and output an error message
    header('Content-Type: text/html; charset=utf-8');
    echo 'Invalid file detected. <br/><a href="javascript:history.back();">Back to admin panel.</a>';
    exit();
}

// TODO: add other functions here to make the whole application complete

function iems5718_cat_insert() {
	// input validation or sanitization
	if ($_SERVER["REQUEST_METHOD"] == "POST"){
	    if (!preg_match('/^[\w\- ]+$/', $_POST['name']))
                throw new Exception("invalid-name");
	}	
        // DB manipulation
        global $db;
	$db = iems5718_DB();
	// TODO: complete the rest of the INSERT command
        $catid = NULL;
	$name = $_POST["name"];
  	$sql="INSERT INTO category (catid, name) VALUES (?,?);";
	$q = $db -> prepare($sql);
	$q -> execute(array($catid,$name));
	header('Location: admin.php');
	exit();
}

function iems5718_cat_edit(){
        // input validation or sanitization
	if ($_SERVER["REQUEST_METHOD"] == "POST"){
		if (!preg_match('/^\d*$/', $_POST['catid']))
                        throw new Exception("invalid-catid");
        	$_POST['catid'] = (int) $_POST['catid'];
       	 	if (!preg_match('/^[\w\- ]+$/', $_POST['name']))
                        throw new Exception("invalid-name");
	}	
	// DB manipulation
        global $db;
        $db = iems5718_DB();

        // TODO: complete the rest of the INSERT command
        $catid = $_POST["catid"];
        $name = $_POST["name"];

        $sql="UPDATE category SET name = ? WHERE catid = ?;";
	$q = $db -> prepare($sql);
	$q -> execute(array($name, $catid));       
	header('Location: admin.php');
        exit();
}

function iems5718_cat_delete(){
 	// input validation or sanitization
	if ($_SERVER["REQUEST_METHOD"] == "POST"){
	        if (!preg_match('/^\d*$/', $_POST['catid']))
                        throw new Exception("invalid-catid");
        	$_POST['catid'] = (int) $_POST['catid'];
	}
        // DB manipulation
        global $db;
        $db = iems5718_DB();

        // TODO: complete the rest of the INSERT command
        $catid = $_POST["catid"];
        $sql = "DELETE FROM category WHERE catid = ?;";
        $q = $db -> prepare($sql);
	$q -> execute(array($catid));
        header('Location: admin.php');
        exit();
}

function iems5718_prod_delete_by_catid(){
        // input validation or sanitization
	if ($_SERVER["REQUEST_METHOD"] == "POST"){
		if (!preg_match('/^\d*$/', $_POST['catid']))
                        throw new Exception("invalid-catid");
        	$_POST['catid'] = (int) $_POST['catid'];
	}
        // DB manipulation
        global $db;
        $db = iems5718_DB();

        // TODO: complete the rest of the INSERT command
        $catid = $_POST["catid"];
        $sql = "DELETE FROM product WHERE catid = ?;";
        $q = $db -> prepare($sql);
	$q -> execute(array($catid));
        header('Location: admin.php');
        exit();
}

function iems5718_prod_fetchAll(){
        // DB manipulation
        global $db;
        $db = iems5718_DB();
        $sql = "SELECT * FROM product LIMIT 100;";
        $db -> query($sql);
}

function iems5718_prod_fetchOne($input,$inputt){
    $db = iems5718_DB();
    $sql = "SELECT * FROM product WHERE catid = '$input' AND name = '$inputt';";
    $q = $db -> query($sql);
    return $q;
}
function iems5718_prod_edit(){
    // input validation or sanitization
    if($_SERVER["REQUEST_METHOD"] == "POST"){
    	if (!preg_match('/^[\w\- ]+$/', $_POST['name']))
        	throw new Exception("invalid-catid");
    	$_POST['name'] = (int) $_POST['name'];
    	if (!preg_match('/^[\d\.]+$/', $_POST['price']))
        	throw new Exception("invalid-price");
    }
    // DB manipulation
    global $db;
    $db = iems5718_DB();

    // TODO: complete the rest of the INSERT command
    $name = $_POST["name"];
    $price = $_POST["price"];
   
    $sql= "UPDATE product SET price = ? WHERE name = ?;";
    $q = $db -> prepare($sql);
    $q -> execute(array($price,$name));	
    header('Location: admin.php');
    exit();
}

function iems5718_prod_delete(){
    // input validation or sanitization
    if($_SERVER["REQUEST_METHOD"] == "POST"){
    	if (!preg_match('/^[\w\- ]+$/', $_POST['name']))throw new Exception("invalid-name");
    }
    // DB manipulation
    global $db;
    $db = iems5718_DB();

    // TODO: complete the rest of the INSERT command
    $nameProd = $_POST['name'];

    $sql = "DELETE FROM category WHERE name = ?;";
    $q = $db -> prepare($sql);
    $q -> execute(array($nameProd));
    header('Location: admin.php');
    exit();
}

?>
