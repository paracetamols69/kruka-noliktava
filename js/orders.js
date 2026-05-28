const orders_table_body = document.querySelector("tbody#orders");
const new_order_form = document.getElementById("new-order-form");
const orderSelector = document.getElementById("new-order-product-select");

const edit_order_form = document.getElementById("edit-order-form");
const orderEditProductSelector = document.getElementById("edit-order-product-select");

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
        <button class="editBtn" onclick="ToggleEditOrderForm(${id}, ${product_id}, ${status})">Edit</button>
        <button class="deleteBtn" onclick="DeleteOrder(${id})">Delete</button>
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
    const order_id = document.getElementById("edit-order-id").value;
    const product_id = document.getElementById("edit-order-product-select").value;
    const status = document.getElementById("edit-order-status-select").value;
    const count = document.getElementById("edit-order-count").value;

    const req = await fetch("../api/orders/update_order.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({order_id: order_id, product_id: product_id, new_count: count, new_status: status})
    });

    const res = await req.json();

    if (res.error) {
        DisplayError(res.error);
    }
    else if (res.success) {
        DisplaySuccess(res.success);
    }

    ToggleEditOrderForm(order_id);
    UpdateOrdersTable();
}

async function ToggleEditOrderForm(id = null, product_id = null, status = null) {
    overlay_container.classList.toggle("active");
    edit_order_form.classList.toggle("active");

    if (!overlay_container.classList.contains("active")) {
        return;
    }

    document.getElementById("edit-order-id").value = id;
    const req = await fetch("../../api/products/get_all_products.php");
    const res = await req.json();

    if (res.error) {
        DisplayError(res.error);
    }

    if (edit_order_form.classList.contains("active")) {
        const req = await fetch("../../api/products/get_all_products.php");
        const products = await req.json();
        
        if (products.error) {
            DisplayError(res.error);
        }

        orderEditProductSelector.innerHTML = '<option value="">Izvēlies produktu</option>';

        products.forEach(product => {
            console.log(product);
            const option = document.createElement("option");
            option.value = product.id;
            option.textContent = product.product_name;

            orderEditProductSelector.append(option);
        });

        document.getElementById("edit-order-product-select").value = product_id;
        document.getElementById("edit-order-status-select").value = status;
    }
}

async function AddNewOrder() {
    const product_id = orderSelector.value;
    console.log(product_id);
    const count = document.getElementById("new-order-count").value;
    console.log(count);

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
