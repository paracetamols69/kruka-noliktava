const toast_container = document.getElementById("toast-container");
const overlay_container = document.getElementById("overlay-container");

async function DisplayError(msg) {
    const toast = document.createElement("div");
    toast.className = "toast";
    toast.innerHTML = msg;

    toast_container.appendChild(toast);

    await new Promise(r => setTimeout(r, 5000));

    toast.remove();
}

async function DisplaySuccess(msg) {
    const toast = document.createElement("div");
    toast.className = "toast good";
    toast.innerHTML = msg;

    toast_container.appendChild(toast);
    
    await new Promise(r => setTimeout(r, 5000));

    toast.remove();
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
        case "shelves-table-container":
            await UpdateShelvesTable();
            break;
    }
}