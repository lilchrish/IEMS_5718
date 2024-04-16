<?php
session_start();
ini_set('display_errors',1);
        error_reporting(E_ALL);
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
    <link href="css/cart_page_style.css" rel="stylesheet" type="text/css">
  </head>
  <body> <?php
                if(isset($_SESSION['userid']) && isset($_SESSION['email'])){
                        echo '<h5> Hello ' . $_SESSION['email'] .'!</h5>';
                        echo '<a href=logout.php>Logout</a>';
                }else{
                        echo '<h5> Hello Guest!</h5>';
                        echo '<a href=login.php>Login</a>';
                }
        ?><header class="header1"> Buy The Best </header>
    <script src="shopping_cart.js"></script>
    <div id="menu">
      <p class="home"><a href="#">Home</a></p>
      <nav>
        <ul class="menu">
          <li><a href="index.php">Home</a></li>
          <li class="category"><a href="#">category</a>
            <ul class="submenuC"> <?php
                                foreach ($res as $catvalue){
                                    $catoptions .= '<li class = "subCategory"><a href = "Category_'. $catvalue['name'] .'.php">' . $catvalue['name'] . '</a>';
                                    $prodres = iems5718_prod_fetchByCatid($catvalue['catid']);
                                    $subcatops = '<ul class= "subSubMenuC">';
                                    foreach($prodres as $prodvalue){
                                        $subcatops .= '<li><a href = "' . $prodvalue['name'] . '_' . $catvalue['name'] . '.php">' . $prodvalue['name'] . '</a></li>';
                                    }
                                    $subcatops .= '</ul>';
                                    $catoptions .= $subcatops . '</li>';
                                }
                                echo $catoptions;
                            ?></ul>
          </li>
          <li class="cart"><a href="cart.php">Cart</a></li>
          <li><a href="#">Contact Us</a></li>
        </ul>
      </nav>
    </div>
    <!--Shopping Cart-->
    <div class="container" id="ct">
      <form action="https://3.113.178.224/ipn-listener.php" method="POST" id="form1"></form>
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
      <script>
        async function generatePaypalForm() {
          var form = this.document.getElementById("form1");
          var storage = localStorage.getItem('cart_storage');
          if (storage == null) {
            return;
          } else {
            storage = JSON.parse(storage);
          }
          const response = await fetch("https://3.113.178.224/products.php?pid[]=" + Object.keys(storage).join('&pid[]='));
          products = await response.json();
          nav = document.createElement('nav');
          nav.id = 'cart';
          h3 = document.createElement('h3');
          h3.textContent = 'Total: $';
          span = document.createElement('span');
          span.id = 'total_amount';
          span.textContent = 0;
          h3.appendChild(span);
          divv = document.createElement('shopping_cart');
          ul = document.createElement('ul');
          ul.id = 'items';
          divv.appendChild(ul);
          nav.appendChild(h3);
          nav.appendChild(divv);
          form.appendChild(nav);
          refreshCart();
          form.appendChild(createInput('hidden', 'cmd', '_cart'));
          form.appendChild(createInput('hidden', 'upload', '1'));
          form.appendChild(createInput('hidden', 'business', 'sb-jzplm30408430@business.example.com'));
          invoice = document.createElement('input');
          invoice.type = "hidden";
          invoice.name = "invoice";
          invoice.id = "invoice";
          custom = document.createElement('input');
          custom.type = "hidden";
          custom.name = "custom";
          custom.id = "custom";
          form.appendChild(invoice);
          form.appendChild(custom);
          var i = 1;
          Object.keys(storage).forEach((pid) => {
            products[pid].pid = pid;
            form.appendChild(createInput('hidden', "item_name_" + i, products[pid].name));
            form.appendChild(createInput('hidden', "amount_" + i, products[pid].price));
            form.appendChild(createInput('hidden', "quantity_" + i, storage[pid]));
            i += 1;
          });
          submit = document.createElement('input');
          submit.type = "submit";
          submit.class = "btn btn-success";
          submit.form = "form1";
          submit.value = "Checkout";
          form.appendChild(submit);
          this.document.getElementById('ct').appendChild(form);
          $(this.document).ready(function() {
            $('#form1').submit(function(event) {
              event.preventDefault(); // Cancel the default form submission
              // Collect the selected products and their quantities
              var storage = localStorage.getItem('cart_storage');
              if (storage == null) {
                return;
              } else {
                storage = JSON.parse(storage);
              }
              var products = [];
              Object.keys(storage).forEach((pid) => {
                products.push({
                  pid: pid,
                  quantity: storage[pid]
                });
              });
              // Send the AJAX request to the server
              var xhr = new XMLHttpRequest();
              xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                  var resp = JSON.parse(xhr.responseText);
                  console.log(resp);
                  var input1 = document.getElementById('invoice');
                  input1.value = resp.lastInsertId;
                  var input2 = document.getElementById('custom');
                  input2.value = resp.digest;
                  //console.log(form);
                  var form = document.getElementById('form1');
                  form.submit();
                }
              }
              xhr.open("POST", "https://3.113.178.224/checkout-process.php");
              xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
              data = JSON.stringify(products);
              console.log(data);
              xhr.send(data);
              //clear the shopping cart
              localStorage.clear();
              refreshCart();
            });
          });
        }

        function createInput(type, name, value) {
          const input = document.createElement('input');
          input.type = type;
          input.name = name;
          input.value = value;
          return input;
        }
        generatePaypalForm();
      </script>
      <button type="button" class="btn btn-success" type="submit" form="form1" value="Submit" style="display: none"> Checkout <span class="glyphicon glyphicon-play"></span>
      </button>
      </td>
    </div>
  </body>
</html>
