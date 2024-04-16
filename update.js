async funtion updateCart() {
    var storage = localStorage.getItem('cart_storage');
    if (storage==null) {
        return;
    } else {
        storage = JSON.parse(storage);
    }
    const response = await fetch("http://3.113.178.224/products.php?pid[]="+Object.keys(storage).join('&pid[]='));
    products = await response.json();

    document.getElementById("items").innerHTML = ''; // Remove all html code inside

    var i = 1;

    Object.keys(storage).forEach((pid) => {
        products[pid].pid = pid;
        document.getElementById("item_name_" + i).value = products[pid].name;
	document.getElementById("amount_" + i).value = products[pid].price；
	document.getElementById("quantity_" + i).value = storage[pid];
    });

    document.getElementById("total_amount").innerHTML = total;	

}


updateCart();
