<?php
require __DIR__.'/lib/db.inc.php';
require __DIR__.'/lib/csrf_nonce.php';

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
		$db = mysqli_connect('buythebest.cvc6844gen9o.ap-northeast-1.rds.amazonaws.com', 'buythebest-admin','huangjiaqi8024', 'buythebest'); 
		$q = $db -> prepare('SELECT salt, password FROM users WHERE email = ?');
		if($q -> execute(array($t['email'])) && ($r = $q -> get_result() -> fetch_assoc()) && $t['k'] == hash_hmac("sha256", $t['exp'].$r['password'],$r['salt'])){
			$_SESSION['auth'] = $_COOKIE['auth'];
			$auth_email = $t['email'];
			$checkR = true;
		}
	}
}
if(!$checkR){
	throw new Exception("Invalid authentication!");
}

$res = iems5718_cat_fetchall();
$options = '';


foreach ($res as $value){
    $options .= '<option value="'.$value["catid"].'"> '.$value["name"].' </option>';
}
?>

<?php
                if(isset($_SESSION['userid']) && isset($_SESSION['email'])){
                        echo '<h5> Hello ' . $_SESSION['email'] .'!</h5>';
                        echo '<a href=logout.php>Logout</a>';
                }else{
                        echo '<h5> Hello Guest!</h5>';
                        echo '<a href=login.php>Login</a>';
                }
?>
<br>
<html>
<h3> The most recent 10 orders</h3>
<table align = "left" border = "1" cellpadding = "3" cellspacing = "0">  
<tr>  
<td>pid</td>  
<td>userName</td> 
<td>payment_status</td>
<td>product_list</td>  
<td>digest</td>
</tr>
	<?php
		$db = mysqli_connect('buythebest.cvc6844gen9o.ap-northeast-1.rds.amazonaws.com', 'buythebest-admin','huangjiaqi8024', 'buythebest');
                $q = $db -> query('SELECT * FROM orders ORDER BY pid DESC LIMIT 10');
		
		foreach ($q as $val){
			echo '<tr>';
			echo '<td>' .$val['pid']. '</td>'; 
		       	echo '<td>' .$val['userName']. '</td>';
  			echo '<td>' .$val['payment_status']. '</td>';  
			echo '<td>' .$val['product_list']. '</td>';
			echo '<td>' .$val['digest']. '</td>'; 
    			echo '</tr>';  
		}
	?>
</table>
</html>
</br>
<html>
    <fieldset>
        <legend> New Product</legend>
        <form id="prod_insert" method="POST" action="admin-process.php?action=prod_insert"
        enctype="multipart/form-data">
            <label for="prod_catid"> Category *</label>
            <div> <select id="prod_catid" name="catid"><?php echo $options; ?></select></div>
            <label for="prod_name"> Name *</label>
            <div> <input id="prod_name" type="text" name="name" required="required" pattern="^[\w\-]+$"/></div>
            <label for="prod_price"> Price *</label>
            <div> <input id="prod_price" type="text" name="price" required="required" pattern="^\d+\.?\d*$"/></div>
            <label for="prod_desc"> Description *</label>
            <div> <textarea id = "prod_desc" name="description" rows="7" cols="30" required="required" pattern="/[\w\s\p{P}]+/"> </textarea> </div>
            <label for="prod_image"> Image * </label>
            <div> <input type="file" name="file" required="true" accept="image/jpeg"/> </div>
	    <input type="submit" value="Submit"/>
	    <input type="hidden" name="nonce" value="<?php echo generateNonce(33,'prod_insert',30); ?>"/>
	</form>
    </fieldset>
</html>

<html>
    <fieldset>
        <legend> Edit Product</legend>
        <form id="prod_edit" method="POST" action="admin-process.php?action=prod_edit"
        enctype="multipart/form-data">
            <label for="prod_name"> Name *</label>
            <div> <input id="prod_name" type="text" name="name" required="required" pattern="^[\w\-]+$"/></div>
            <label for="prod_price"> Price *</label>
            <div> <input id="prod_price" type="text" name="price" required="required" pattern="^[\w\-]+$"/></div>
	    <input type="submit" value="Submit"/>
	    <input type="hidden" name="nonce" value="<?php echo generateNonce(33,'prod_edit',30); ?>"/>
        </form>
    </fieldset>
</html>

<html>
    <fieldset>
        <legend> Delete Products by catid</legend>
        <form id="prod_delete_by_catid" method="POST" action="admin-process.php?action=prod_delete_by_catid"
        enctype="multipart/form-data">
            <label for="prod_catid"> Category *</label>
	    <div> <select id="prod_catid" name="catid"><?php echo $options; ?></select></div>
	    <input type="submit" value="Submit"/>
	    <input type="hidden" name="nonce" value="<?php echo generateNonce(33,'prod_delete_by_catid',30); ?>"/>
        </form>
    </fieldset>
</html>

<html>
    <fieldset>
        <legend> New Category</legend>
        <form id="cat_insert" method="POST" action="admin-process.php?action=cat_insert"
        enctype="multipart/form-data">
            <label for="cat_name"> Name </label>
            <div> <input id="cat_name" type="text" name="name" required="required" pattern="^[\w\-]+$"/></div>
	    <input type="submit" value="Submit"/>
	    <input type="hidden" name="nonce" value="<?php echo generateNonce(33,'cat_insert',30); ?>"/>
        </form>
    </fieldset>
</html>

<html>
    <fieldset>
        <legend> Edit Category</legend>
        <form id="cat_edit" method="POST" action="admin-process.php?action=cat_edit"
        enctype="multipart/form-data">
            <label for="cat_catid"> Category *</label>
            <div> <select id="cat_catid" name="catid"><?php echo $options; ?></select></div>
            <label for="cat_name"> Name </label>
	    <div> <input id="cat_name" type="text" name="name" required="required" pattern="^[\w\-]+$"/></div>
	    <input type="submit" value="Submit"/>
	    <input type="hidden" name="nonce" value="<?php echo generateNonce(33,'cat_edit',30); ?>"/>
        </form>
    </fieldset>
</html>

<html>
    <fieldset>
        <legend> Delete Category</legend>
        <form id="cat_delete" method="POST" action="admin-process.php?action=cat_delete"
        enctype="multipart/form-data">
            <label for="cat_catid"> Category *</label>
	    <div> <select id="cat_catid" name="catid"><?php echo $options; ?></select></div>
            <input type="submit" value="Submit"/>
	    <input type="hidden" name="nonce" value="<?php echo $nonce = generateNonce(33, 'cat_delete', 30);?>"/>
	</form>
    </fieldset>
</html>

                                                            
