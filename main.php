<?php
require __DIR__.'/lib/db.inc.php';
$res = iems5718_cat_fetchall();

$products = '<ul>';

# Note that it is NOT SECURE! Why? and how to make it "secure"?

foreach ($res as $value){
    $products .= '<li><a href = "'.$value["file"].'"> '.$value["name"].'</a></li>';
}

$products .= '</ul>';

echo '<div id = "maincontent">
<div id = "products">'.$products.'
</div>
</div>';

?>

