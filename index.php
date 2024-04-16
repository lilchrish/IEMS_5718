<?php
header('Access-Control-Allow-Origin: *'); 
session_start();
require __DIR__.'/lib/db.inc.php';
$res = iems5718_cat_fetchall();
$catoptions = '';
$db = mysqli_connect('buythebest.cvc6844gen9o.ap-northeast-1.rds.amazonaws.com', 'buythebest-admin','huangjiaqi8024', 'buythebest');
// Again, it is NOT SECURE. Why? and how to make it "secure"?
?>

<!doctype html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>Main Page</title>
        <link href="css/main_page_style.css" rel="stylesheet" type="text/css">
    </head>
    <body>
        <?php
                if(isset($_SESSION['userid']) && isset($_SESSION['email'])){
                        echo '<h5> Hello ' . $_SESSION['email'] .'!</h5>';
                        echo '<br><a href=portal.php>profile</a></br>';
                        echo '<a href=logout.php>Logout</a>';
                }else{
			echo '<h5> Hello Guest!</h5>';
			echo '<br><a href=portal.php>profile</a></br>';
                        echo '<a href=login.php>Login</a>';
                }
        ?>
	<script src="shopping_cart.js"></script>	
        <div id="menu"> 
            <p class="home"> <a href="#">Home</a> </p>
            <nav>
                <ul class="menu">
                    <li><a href="#">Home</a></li>
		    <li class= "category">
                        <a href="#">category</a>
                        <ul class= "submenuC">
                            <?php
				foreach ($res as $catvalue){
                                    $catoptions .= '<li class = "subCategory"> <a href = "Category_'. $catvalue['name'] .'.php">' . $catvalue['name'] . '</a>';
                                    $prodres = iems5718_prod_fetchByCatid($catvalue['catid']);
                                    $subcatops = '<ul class= "subSubMenuC">';
                                    foreach($prodres as $prodvalue){
                                        $subcatops .= '<li> <a href = "' . $prodvalue['name'] . '_' . $catvalue['name'] . '.php">' . $prodvalue['name'] . '</a></li>';
                                    }
                                    $subcatops .= '</ul>';
                                    $catoptions .= $subcatops . '</li>';
                                }
				echo $catoptions;
                            ?>
                        </ul>
		    </li>
		    <li><a href="cart.php">Cart</a></li>
                    <li><a href="#">Contact Us</a></li>
                </ul>
            </nav>
        </div>

        <div class="product">
            <nav id='cart'>
      		<h3>Total: $<span id="total_amount">0</span></h3>
      		<div id="shopping_cart">
			<ul id="items"></ul>
			<a href='cart.php'>Go To Cart</a>
		</div>
		
    	    </nav>

	    <?php 
		$result = '<ul class="product_list">';
		$tmp = '';
		$idd = 0;
		foreach ($res as $value) {
			$prod = iems5718_prod_fetchByCatid($value['catid']);
			foreach($prod as $prodres){
				$tmp = '<li class = "' . $value['name'] . '">';
				$tmp .= '<a href="' . $prodres['name'] . '_'. $value['name'] . '.php">';
				$tmp .= '<img src = "images/' . $prodres['pid'] . '.jpg"/> '. $prodres['name'].'-' . $value['name'] . '</a>';
				$tmp .= '<p> $' . $prodres['price'] . '</p>';
				$tmp .= '<button onclick="addtocart('. $idd .');"> add </button> </li>';
				$idd = $idd + 1;
				$result .= $tmp;
			}
		}		
		echo $result . '</ul>';
	    ?>
        </div>

    </body>
</html>
