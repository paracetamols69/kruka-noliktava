async function LoadDashboardData() {
    const req = await fetch("../api/reports/get_dashboard.php");
    const data = await req.json();

    if (data.error) {
        console.error(data.error);
        return;
    }

    document.getElementById("stat-products").textContent = data.stats.total_products;
    document.getElementById("stat-shelves").textContent = data.stats.total_shelves;
    document.getElementById("stat-orders").textContent = data.stats.total_orders;


    const emptyShelvesList = document.getElementById("dash-empty-shelves");
    emptyShelvesList.innerHTML = "";
    if (data.empty_shelves.length === 0) {
        emptyShelvesList.innerHTML = "<li>No empty shelves.</li>";
    } else {
        data.empty_shelves.forEach(shelf => {
            emptyShelvesList.innerHTML += `<li>Shelf ID: ${shelf.id}</li>`;
        });
    }


    const lowShelvesList = document.getElementById("dash-low-shelves");
    lowShelvesList.innerHTML = "";
    if (data.low_shelves.length === 0) {
        lowShelvesList.innerHTML = "<li>No shelves with low stock.</li>";
    } else {
        data.low_shelves.forEach(shelf => {
            lowShelvesList.innerHTML += `<li>Shelf ID: ${shelf.id} (Left: ${shelf.stock} pcs.)</li>`;
        });
    }


    const outProductsList = document.getElementById("dash-out-products");
    outProductsList.innerHTML = "";
    if (data.out_of_stock_products.length === 0) {
        outProductsList.innerHTML = "<li>All products are available.</li>";
    } else {
        data.out_of_stock_products.forEach(prod => {
            outProductsList.innerHTML += `<li>${prod.product_name} (ID: ${prod.id})</li>`;
        });
    }


    const lowProductsList = document.getElementById("dash-low-products");
    lowProductsList.innerHTML = "";
    if (data.low_stock_products.length === 0) {
        lowProductsList.innerHTML = "<li>No products with low stock.</li>";
    } else {
        data.low_stock_products.forEach(prod => {
            lowProductsList.innerHTML += `<li>${prod.product_name}: ${prod.stock} gab.</li>`;
        });
    }

    const topProductsTable = document.getElementById("dash-top-products");
    topProductsTable.innerHTML = "";
    if (data.top_products.length === 0) {
        topProductsTable.innerHTML = "<tr><td colspan='2' style='text-align:center; padding:10px;'>Datu nav</td></tr>";
    } else {
        data.top_products.forEach(item => {
            topProductsTable.innerHTML += `
                <tr>
                    <td>${item.product_name}</td>
                    <td>${item.total_sold} pcs.</td>
                </tr>
            `;
        });
    }
}

document.addEventListener("DOMContentLoaded", () => {
    LoadDashboardData();
    
    const dashBtn = document.getElementById("dashboard-button");
    if (dashBtn) {
        dashBtn.addEventListener("click", LoadDashboardData);
    }
});