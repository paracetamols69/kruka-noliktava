const users_table_body = document.querySelector("tbody#users");

async function GetAllUsers() {
    const req = await fetch("../api/get_all_users.php");
    const res = await req.json();
    if (res.error) {
        DisplayError(res.error);
    }
    return res;
}

async function DeleteUser(id) {
    const req = await fetch("../api/delete_user.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ user_id: id })
    });

    const res = await req.json();
    if (res.error) {
        DisplayError(res.error);
    }

    UpdateUsersTable();
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

async function UpdateUsersTable() {
    users_table_body.innerHTML = "";
    const users = await GetAllUsers();
    
    users.forEach(user => {
        InsertUsersTableRow(user.id, user.username, user.role, user.created_at);
    });
}