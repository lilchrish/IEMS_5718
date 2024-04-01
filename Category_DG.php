<?php
session_start();
require __DIR__.'/lib/db.inc.php';
$res = iems5718_cat_fetchall();
$catoptions = '';
$db = mysqli_connect('buythebest.cvc6844gen9o.ap-northeast-1.rds.amazonaws.com', 'buythebest-admin','huangjiaqi8024', 'buythebest');
?>

<!doctype html>

<html>
    <head>
        <meta charset="utf-8" />
        <title>Main Page</title>
        <link href="css/category_DG_style.css" rel="stylesheet" type="text/css">
    </head>
    <body>
	<?php
                if(isset($_SESSION['userid']) && isset($_SESSION['email'])){
                        echo '<h5> Hello ' . $_SESSION['email'] .'!</h5>';
                        echo '<a href=logout.php>Logout</a>';
                }else{
                        echo '<h5> Hello Guest!</h5>';
                        echo '<a href=login.php>Login</a>';
                }
        ?>
	<script src="shopping_cart.js"></script>
        <header class="header1"> Buy The Best </header>

        <div id="menu"> 
            <p class="home"> 
                <a href="index.php">Home</a> > <a href="Category_DG.php">Dragon Year Edition</a>
            </p>
            <nav>
                <ul class="menu">
                    <li><a href="index.php">Home</a></li>
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
		    <li><a href="#">Contact Us</a></li>
                </ul>
            </nav>
        </div>
        

        <div class="product">

	    <nav id='cart'>
                <h3>Total: $<span id="total_amount">0</span></h3>
                <div id="shopping_cart">
                        <ul id="items"></ul>
                </div>
            </nav>

            <ul class="product_list">
                <?php
                    $id = iems5718_cat_fetchByName('DG');
                    $prodres = iems5718_prod_fetchByCatid($id);
                    
		    $result = '';
		    $idd = 0;
		    foreach ($prodres as $value) {
                        $operations = '<li class = "DG">';
                        $operations .= '<a href="' . $value['name'] . '_'  .'DG.php "> <img src="images/' . $value['pid'] . '.jpg"/>'.$value['name'] .'-DG </a>';
                        $operations .= '<p> $' . $value['price'] . '</p>';
			$operations .= '<button onclick="addtocart('. $idd .');"> add </button> </li>';
			$idd = $idd + 1;
                        $result .= $operations;
                    }
                    echo $result;
                ?>
            </ul>
        </div>

    </body>
</html>
