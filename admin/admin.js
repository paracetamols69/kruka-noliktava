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