<?php
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
        <link href="css/ShellieMay_DG.css" rel="stylesheet" type="text/css">
    </head>
    <body>

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

        <div class="product">
            <?php
                $id = iems5718_cat_fetchByName('DG');
                $sm = iems5718_prod_fetchByNameID('Shelliemay',$id);
                foreach ($sm as $smn){
                    echo '<img src="images/' . $smn['pid'] . '.jpg" alt="ShellieMay New Year Edition"/>';
                    echo '<p id="description"> Description:' . $smn['description'] . '</p>';
                    echo '<p id="price"> $' . $smn['price'] . ' </p>';
                    echo '<button class="add"> add</button>';
            }
        ?>
    </div>
    </body>
</html>
