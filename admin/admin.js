const users_table_container = document.getElementById("users-table-container");
const users_button = document.getElementById("users-button");

const users_table_body = document.querySelector("tbody#users");
const products_table_body = document.querySelector("tbody#products");

const overlay_container = document.getElementById("overlay-container");
const new_product_form = document.getElementById("new-product-form");

async function GetAllUsers() {
    const req = await fetch("../api/get_all_users.php");
    const res = await req.json();

    return res;
}

async function GetAllProducts() {
    const req = await fetch("../api/get_all_products.php");
    const res = await req.json();

    console.log(res);
    return res;
}

async function DeleteUser(id) {
    const req = await fetch("../api/delete_user.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ user_id: id })
    });

    const res = await req.json();
    console.log(res);

    UpdateTable();
}

async function DeleteProduct(id) {
    const req = await fetch("../api/delete_product.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ product_id: id })
    });

    const res = await req.json();
    console.log(res);

    UpdateTable();
}

function InsertUsersTableRow(id, username, role, created_at) {
    const row = document.createElement("tr");

    row.innerHTML = `
    <td>${id}</td>
    <td>${username}</td>
    <td>${role}</td>
    <td>${new Date(created_at * 1000).toLocaleString()}</td>
    <td>
        <button onclick="EditUser(${id})">Edit</button>
        <button onclick="DeleteUser(${id})">Delete</button>
    </td>
    `

    users_table_body.append(row);
}

function InsertProductsTableRow(id, product_name, stock) {
    const row = document.createElement("tr");

    row.innerHTML = `
    <td>${id}</td>
    <td>${product_name}</td>
    <td>${stock}</td>
    <td>
        <button onclick="EditUser(${id})">Edit</button>
        <button onclick="DeleteUser(${id})">Delete</button>
    </td>
    `

    products_table_body.append(row);
}

async function UpdateUsersTable() {
    users_table_body.innerHTML = "";
    const users = await GetAllUsers();

    users.forEach(user => {
        InsertUsersTableRow(user.id, user.username, user.role, user.created_at);
    });
}

async function UpdateProductsTable() {
    products_table_body.innerHTML = "";
    const products = await GetAllProducts();

    products.forEach(product => {
        InsertProductsTableRow(product.id, product.product_name, product.stock);
    });
}

async function ToggleTable(buttonObject) {
    document.querySelectorAll(".table-select-button").forEach(button => {
        button.classList.remove("active");
    });

    document.querySelectorAll(".table-container").forEach(container => {
        container.classList.remove("active");
    });

    const tableContainerId = buttonObject.dataset.tableId;

    document.getElementById(tableContainerId).classList.add("active");
    buttonObject.classList.add("active");

    switch (tableContainerId) {
        case "users-table-container":
            await UpdateUsersTable();
            break;

        case "products-table-container":
            console.log("nig");
            await UpdateProductsTable();
            break;
    }
}

function ToggleNewProductForm() {
    overlay_container.classList.toggle("active");
    new_product_form.classList.toggle("active");
}

function AddNewProduct() {
    
}


