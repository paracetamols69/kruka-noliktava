const orders_table_body = document.querySelector("tbody#orders");
const new_order_form = document.getElementById("new-order-form");
const orderSelector = document.getElementById("new-order-product-select");

async function DeleteOrder(id) {
    const req = await fetch("../api/orders/delete_order.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ order_id: id })
    });

    const res = await req.json();
    
    if (res.error) {
        DisplayError(res.error);
    }
    else if (res.success) {
        DisplaySuccess(res.success);
    }
    
    UpdateOrdersTable();
}

async function GetAllOrders() {
    const req = await fetch("../api/orders/get_all_orders.php");
    const res = await req.json();

    if (res.error) {
        DisplayError(res.error);
    }
    else if (res.success) {
        DisplaySuccess(res.success);
    }

    return res;
}

function InsertOrdersTableRow(id, product_id, count, status, created_at) {
    const row = document.createElement("tr");

    row.innerHTML = `
    <td>${id}</td>
    <td>${product_id}</td>
    <td>${count}</td>
    <td>${status}</td>
    <td>${created_at}</td>
    <td>
        <button onclick="EditOrder(${id})">Edit</button>
        <button onclick="DeleteOrder(${id})">Delete</button>
    </td>
    `

    orders_table_body.append(row);
}

async function UpdateOrdersTable() {
    orders_table_body.innerHTML = "";
    const orders = await GetAllOrders();

    orders.forEach(order => {
        InsertOrdersTableRow(order.id, order.product_id, order.count, order.status, order.created_at);
    });
}

async function ToggleNewOrderForm() {
    overlay_container.classList.toggle("active");
    new_order_form.classList.toggle("active");

    const req = await fetch("../api/products/get_all_products.php");


    if (new_order_form.classList.contains("active")) {
        const req = await fetch("../api/products/get_all_products.php");
        const products = await req.json();
        
        orderSelector.innerHTML = '<option value="">Izvēlies produktu</option>';

        products.forEach(product => {
            const option = document.createElement("option");
            option.value = product.id;
            option.textContent = product.product_name;
            
            orderSelector.append(option);
        });
    }
}

async function SaveOrderData() {
    const order_id = 0;
}

async function AddNewOrder() {
    const product_id = orderSelector.value;
    const count = document.getElementById("new-order-count").value;

    const req = await fetch("../api/orders/add_order.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ product_id: product_id, count: count })
    });

    const res = await req.json();

    if (res.error) {
        DisplayError(res.error);
    }
    else if (res.success) {
        DisplaySuccess(res.success);
    }

    ToggleNewOrderForm();
    UpdateOrdersTable();
}

UpdateOrdersTable();
