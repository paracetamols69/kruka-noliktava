const users_table_body = document.querySelector("tbody#users");

async function GetAllUsers() {
    const req = await fetch("../api/users/get_all_users.php");
    const res = await req.json();
    if (res.error) {
        DisplayError(res.error);
    }
    else if (res.success) {
        DisplaySuccess(res.success);
    }
    return res;
}

async function DeleteUser(id) {
    const req = await fetch("../api/users/delete_user.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ user_id: id })
    });

    const res = await req.json();
    if (res.error) {
        DisplayError(res.error);
    }
    else if (res.success) {
        DisplaySuccess(res.success);
    }

    UpdateUsersTable();
}

function InsertUsersTableRow(id, username, role, created_at) {
    const row = document.createElement("tr");

    row.innerHTML = `
    <td>${id}</td>
    <td>${username}</td>
    <td>${role}</td>
    <td>${created_at}</td>
    <td>
        <button onclick="ToggleEditUserForm(${id}, ${role})">Edit</button>
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


const current_admin_id = document.getElementById("current-admin-id").value;

function InsertUsersTableRow(id, username, role, created_at) {
    const row = document.createElement("tr");

    const isSelf = (id == current_admin_id);

    row.innerHTML = `
    <td>${id}</td>
    <td>${username}</td>
    <td>${role}</td>
    <td>${created_at}</td>
    <td>
        ${isSelf ? '' : `<button onclick="ToggleEditUserForm(${id}, ${role})">Edit</button>`}
        <button onclick="DeleteUser(${id})">Delete</button>
    </td>
    `;

    users_table_body.append(row);
}

function ToggleEditUserForm(id = null, role = null) {
    const container = document.getElementById("overlay-container");
    const form = document.getElementById("edit-user-form");
    
    if (form.classList.contains("active")) {
        container.classList.remove("active");
        form.classList.remove("active");
    } else {
        if (id == current_admin_id) {
            return;
        }

        document.getElementById("edit-user-id").value = id;
        document.getElementById("edit-user-role").value = role;
        
        container.classList.add("active");
        form.classList.add("active");
    }
}

async function SaveUserRole() {
    const id = document.getElementById("edit-user-id").value;
    const newRole = document.getElementById("edit-user-role").value;

    await fetch("../api/users/update_user.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ user_id: id, role: newRole })
    });

    ToggleEditUserForm();
    UpdateUsersTable();
}