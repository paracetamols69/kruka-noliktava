const users_table_body = document.querySelector("tbody#users");

async function GetAllUsers() {
    const req = await fetch("../api/get_all_users.php");
    const res = await req.json();

    return res;
}

function InsertUsersTableRow(id, username, role, created_at) {
    const row = document.createElement("tr");

    row.innerHTML = `
    <td>${id}</td>
    <td>${username}</td>
    <td>${role}</td>
    <td>${created_at}</td>
    <td>
        <button onclick="EditUser(${id})">Edit</button>
        <button onclick="DeleteUser(${id}")>Delete</button>
    </td>
    `

    users_table_body.append(row);
}

async function UpdateTable() {
    const users = await GetAllUsers();

    users.forEach(user => {
        InsertUsersTableRow(user.id, user.username, user.role, user.created_at);
    });
}

UpdateTable();

