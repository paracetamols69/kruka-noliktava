const products_table_body = document.querySelector("tbody#products");

const overlay_container = document.getElementById("overlay-container");
const new_product_form = document.getElementById("new-product-form");

async function GetAllProducts() {
    const req = await fetch("../api/get_all_products.php");
    const res = await req.json();

    if (res.error) {
        DisplayError(res.error);
    }

    return res;
}

async function DeleteProduct(id) {
    const req = await fetch("../api/delete_product.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ product_id: id })
    });

    const res = await req.json();
    if (res.error) {
        DisplayError(res.error);
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
        <button onclick="EditProduct(${id})">Edit</button>
        <button onclick="DeleteProduct(${id})">Delete</button>
    </td>
    `

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

    const req = await fetch("../api/add_product.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ product_name: productName, count: productCount })
    });

    const res = await req.json();
    if (res.error) {
        DisplayError(res.error);
    }

    ToggleNewProductForm();
    UpdateProductsTable();
}