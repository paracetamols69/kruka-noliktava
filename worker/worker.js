const products_table_body = document.querySelector("tbody#products");

async function GetAllProducts() {
    const req = await fetch("../api/get_all_products.php");
    const res = await req.json();

    return res;
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

function InsertProductsTableRow(id, name, stock) {
    const row = document.createElement("tr");

    row.innerHTML = `
    <td>${id}</td>
    <td>${name}</td>
    <td>${stock}</td>
    <td>
        <button onclick="EditProduct(${id})">Edit</button>
        <button onclick="DeleteProduct(${id})">Delete</button>
    </td>
    `

    products_table_body.append(row);
}

async function UpdateTable() {
    products_table_body.innerHTML = "";
    const products = await GetAllProducts();
    
    products.forEach(product => {
        InsertProductsTableRow(product.id, product.product_name, product.stock);
    });
}

UpdateTable();
