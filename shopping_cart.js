function addtocart(pid){
    var storage = localStorage.getItem('cart_storage');
    storage = storage ? JSON.parse(storage): {};
    
    if(storage[pid] == undefined) {
        storage[pid] = 1;
        localStorage.setItem('cart_storage', JSON.stringify(storage));
        console.log(storage);
        add_newProduct(pid);
    } else {
        storage[pid] = storage[pid] + 1;
        document.getElementById('product'+pid).value = storage[pid];
        localStorage.setItem('cart_storage', JSON.stringify(storage));
        // Update the corresponding box or just do complete refresh
        refreshCart();
    }  
}
    
function createProduct(product, quantity) {
    let li = document.createElement('li');
    let input = document.createElement('input');
    input.setAttribute("type", "number");
    input.value = quantity;
    input.addEventListener('input', function(){updateItem(product.pid);});
    input.id = "product"+product.pid;
    li.textContent = product.name + " $" + product.price;
    li.appendChild(input);
    return li;
}

async function add_newProduct(pid){
    var storage = localStorage.getItem('cart_storage');
    console.log(storage);
    if (storage==null) {
        return;
    } else {
        storage = JSON.parse(storage);
    }
    const response = await fetch("https://3.113.178.224/products.php?pid[]="+pid);
    products = await response.json();
    products[pid].pid = pid;
    
    document.getElementById("items").appendChild(createProduct(products[pid], 1));
    document.getElementById("total_amount").innerHTML = parseInt(document.getElementById("total_amount").innerHTML) + parseInt(products[pid].price);
}

function updateItem(pid){
    var storage = JSON.parse(localStorage.getItem('cart_storage'));
    storage[pid] = parseInt(document.getElementById('product'+pid).value) || 0;

    if (parseInt(document.getElementById('product'+pid).value) <= 0) {
        delete storage[pid];
    }
    localStorage.setItem('cart_storage', JSON.stringify(storage));

    refreshCart();
}


async function refreshCart(){ //Compute the whole cart
    var storage = localStorage.getItem('cart_storage');
    if (storage==null) {
        return;
    } else {
        storage = JSON.parse(storage);
    }
    const response = await fetch("https://3.113.178.224/products.php?pid[]="+Object.keys(storage).join('&pid[]='));
    products = await response.json();

    document.getElementById("items").innerHTML = ''; // Remove all html code inside

    var total = 0;

    Object.keys(storage).forEach((pid) => {
        products[pid].pid = pid;
        document.getElementById("items").appendChild(createProduct(products[pid], storage[pid]));
        total += products[pid].price * storage[pid];
    });

    document.getElementById("total_amount").innerHTML = total;
}

refreshCart();
