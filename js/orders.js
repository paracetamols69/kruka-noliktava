const orders_table_body = document.querySelector("tbody#orders");

async function GetAllOrders() {
    const req = await fetch("../api/get_all_orders.php");
    const res = await req.json();

    console.log(res);
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

UpdateOrdersTable();