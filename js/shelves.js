const shelves_table_body = document.querySelector("tbody#shelves");
const new_shelf_form = document.getElementById("new-shelf-form");
const productSelector = document.getElementById("shelf-product-select");

async function DeleteShelf(id) {
    const req = await fetch("../api/shelves/delete_shelf.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ shelf_id: id })
    });

    const res = await req.json();
    if (res.error) {
        DisplayError(res.error);
    }
    
    UpdateShelvesTable();
}


async function GetAllShelves() {
    const req = await fetch("../api/shelves/get_all_shelves.php");
    const res = await req.json();

    if (res.error) {
        DisplayError(res.error);
    }
    return res;
}

function InsertShelvesTableRow(id, product_id, stock) {
    const row = document.createElement("tr");

    row.innerHTML = `
    <td>${id}</td>
    <td>${product_id}</td>
    <td>${stock}</td>
    <td>
        <button onclick="EditShelf(${id})">Edit</button>
        <button onclick="DeleteShelf(${id})">Delete</button>
    </td>
    `

    shelves_table_body.append(row);
}

async function UpdateShelvesTable() {
    shelves_table_body.innerHTML = "";
    const shelves = await GetAllShelves();

    shelves.forEach(shelf => {
        InsertShelvesTableRow(shelf.id, shelf.product_id, shelf.stock);
    });
}

async function ToggleNewShelfForm() {
    overlay_container.classList.toggle("active");
    new_shelf_form.classList.toggle("active");

    const req = await fetch("../../api/products/get_all_products.php");
    const res = await req.json();

    if (res.error) {
        DisplayError(res.error);
    }

    if (new_shelf_form.classList.contains("active")) {
        const req = await fetch("../../api/products/get_all_products.php");
        const products = await req.json();
        
        if (products.error) {
            DisplayError(res.error);
        }
        productSelector.innerHTML = '<option value="">Izvēlies produktu</option>';

        products.forEach(product => {
            const option = document.createElement("option");
            option.value = product.id;
            option.textContent = product.product_name;

            productSelector.append(option);
        });
    }
}

async function AddNewShelf() {
    const shelf_id = document.getElementById("new-shelf-id").value;
    const product_id = document.getElementById("new-product-id").value;
    const count = document.getElementById("shelf-stock").value;

    const req = await fetch("../api/shelves/add_shelf.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ shelf_id: shelf_id, product_id: product_id, stock: count})
    });

    const res = await req.json();

    if (res.error) {
        DisplayError(res.error);
    }
    ToggleNewShelfForm();
    UpdateShelvesTable();
}
UpdateShelvesTable();
