<?php
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

        <header class="header1"> Buy The Best </header>
	
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
                    <li><a href="#">Contact Us</a></li>
                </ul>
            </nav>
        </div>

        <div class="product">

            <Nav class="shopping_list">
                <h2 class="ds"> Shopping List </h2>
                <ul class="shopping_list">
                    <li> Olu Mel-NY <input type = "number" min="0"> </li>
                    <li> Linabell-NY <input type = "number" min="0"> </li>
                    <li> ShellieMay-NY <input type = "number" min="0"> </li>
                    <li> Olu Mel-DG <input type = "number" min="0"> </li>
                    <li> Linabell-DG <input type = "number" min="0"> </li>
                    <li> ShellieMay-DG <input type = "number" min="0"> </li>
                    <button> Check out </button>
                </ul>
            </Nav>
	
	    <?php 
		$result = '<ul class="product_list">';
		$tmp = '';
		foreach ($res as $value) {
			$prod = iems5718_prod_fetchByCatid($value['catid']);
			foreach($prod as $prodres){
				$tmp = '<li class = "' . $value['name'] . '">';
				$tmp .= '<a href="' . $prodres['name'] . '_'. $value['name'] . '.php">';
				$tmp .= '<img src = "images/' . $prodres['pid'] . '.jpg"/> '. $prodres['name'].'-' . $value['name'] . '</a>';
				$tmp .= '<p> $' . $prodres['price'] . '</p>';
				$tmp .= '<button> add </button> </li>';
				$result .= $tmp;
			}
		}		
		echo $result . '</ul>';
	    ?>
        </div>

    </body>
</html>
