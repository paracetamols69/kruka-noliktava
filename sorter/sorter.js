const shelves_table_body = document.querySelector("tbody#shelves");

async function GetAllShelves() {
    const req = await fetch("../api/get_all_shelves.php");
    const res = await req.json();

    return res;
}

async function DeleteShelve(id) {
    const req = await fetch("../api/delete_shelve.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ shelve_id: id })
    });

    const res = await req.json();
    console.log(res);

    UpdateTable();
}

function InsertShelvesTableRow(id, name, stock) {
    const row = document.createElement("tr");

    row.innerHTML = `
    <td>${id}</td>
    <td>${name}</td>
    <td>${stock}</td>
    <td>
        <button onclick="EditShelve(${id})">Edit</button>
        <button onclick="DeleteShelve(${id})">Delete</button>
    </td>
    `

    shelves_table_body.append(row);
}

async function UpdateTable() {
    shelves_table_body.innerHTML = "";
    const shelves = await GetAllShelves();
    
    shelves.forEach(shelve => {
        InsertShelvesTableRow(shelve.id, shelve.product_id, shelve.stock);
    });
}

UpdateTable();
