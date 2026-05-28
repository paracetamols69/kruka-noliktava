const products_table_body = document.querySelector("tbody#products");
const overlay_container = document.getElementById("overlay-container");
const new_product_form = document.getElementById("new-product-form");
const edit_product_form = document.getElementById("edit-product-form");

async function GetAllProducts() {
    const req = await fetch("../api/products/get_all_products.php");
    const res = await req.json();

    if (res.error) {
        DisplayError(res.error);
    }
    else if (res.success) {
        DisplaySuccess(res.success);
    }

    return res;
}

async function DeleteProduct(id) {
    const req = await fetch("../api/products/delete_product.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ product_id: id })
    });

    const res = await req.json();
    if (res.error) {
        DisplayError(res.error);
    }
    else if (res.success) {
        DisplaySuccess(res.success);
    }
    
    UpdateProductsTable();
}

function InsertProductsTableRow(id, product_name, stock) {
    const row = document.createElement("tr");

    row.innerHTML = `
    <td>${id}</td>
    <td>${product_name}</td>
    <td>${stock}</td>
    <td>
        <button class="editBtn" onclick="ToggleEditProductForm(${id}, '${product_name}', ${stock})">Edit</button>
        <button class="deleteBtn" onclick="DeleteProduct(${id})">Delete</button>
    </td>
    `;

    products_table_body.append(row);
}

async function UpdateProductsTable() {
    products_table_body.innerHTML = "";
    const products = await GetAllProducts();

    products.forEach(product => {
        InsertProductsTableRow(product.id, product.product_name, product.stock);
    });
}

function ToggleNewProductForm() {
    overlay_container.classList.toggle("active");
    new_product_form.classList.toggle("active");
}

async function AddNewProduct() {
    const productName = document.getElementById("new-product-name").value;
    const productCount = document.getElementById("new-product-count").value;

    const req = await fetch("../api/products/add_product.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ product_name: productName, count: productCount })
    });

    const res = await req.json();
    if (res.error) {
        DisplayError(res.error);
    }
    else if (res.success) {
        DisplaySuccess(res.success);
    }

    ToggleNewProductForm();
    UpdateProductsTable();
}

function ToggleEditProductForm(id = null, product_name = null, stock = null) {
    if (edit_product_form.classList.contains("active")) {
        overlay_container.classList.remove("active");
        edit_product_form.classList.remove("active");
    } else {
        document.getElementById("edit-product-id").value = id;
        document.getElementById("edit-product-name").value = product_name;
        document.getElementById("edit-product-count").value = stock;

        overlay_container.classList.add("active");
        edit_product_form.classList.add("active");
    }
}

async function SaveProductData() {
    const id = document.getElementById("edit-product-id").value;
    const productName = document.getElementById("edit-product-name").value;
    const productCount = document.getElementById("edit-product-count").value;

    const req = await fetch("../api/products/update_product.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ product_id: id, product_name: productName, count: productCount })
    });

    const res = await req.json();
    if (res.error) {
        if (typeof DisplayError === "function") DisplayError(res.error);
        else alert(res.error);
    } else {
        DisplaySuccess(res.success);
        ToggleEditProductForm();
        UpdateProductsTable();
    }
}