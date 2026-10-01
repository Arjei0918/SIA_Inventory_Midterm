<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

```
<title>Inventory Management System</title>

<style>
    * {
        box-sizing: border-box;
    }

    body {
        margin: 0;
        font-family: Arial, sans-serif;
        background: #f4f6f8;
    }

    .header {
        background: #1f2937;
        color: white;
        padding: 20px 40px;
    }

    .header h1 {
        margin: 0;
    }

    .container {
        width: 90%;
        max-width: 1200px;
        margin: 30px auto;
    }

    .cards {
        display: flex;
        gap: 20px;
        margin-bottom: 30px;
    }

    .card {
        background: white;
        padding: 20px;
        border-radius: 10px;
        flex: 1;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .card h3 {
        margin-top: 0;
        color: #555;
    }

    .card p {
        font-size: 28px;
        font-weight: bold;
        margin-bottom: 0;
    }

    .table-container {
        background: white;
        padding: 25px;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .top-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        gap: 10px;
    }

    .top-bar h2 {
        margin: 0;
    }

    .top-buttons {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    button,
    .history-button {
        cursor: pointer;
        border: none;
        border-radius: 6px;
        color: white;
        padding: 9px 14px;
        text-decoration: none;
        font-size: 14px;
    }

    .add-button {
        background: #2563eb;
    }

    .add-button:hover {
        background: #1d4ed8;
    }

    .stock-in-button {
        background: #16a34a;
    }

    .stock-in-button:hover {
        background: #15803d;
    }

    .stock-out-button {
        background: #ea580c;
    }

    .stock-out-button:hover {
        background: #c2410c;
    }

    .history-button {
        background: #7c3aed;
    }

    .history-button:hover {
        background: #6d28d9;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th,
    td {
        padding: 14px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }

    th {
        background: #f1f1f1;
    }

    .stock-low {
        color: red;
        font-weight: bold;
    }

    .stock-good {
        color: green;
        font-weight: bold;
    }

    .loading {
        text-align: center;
        padding: 30px;
    }

    .edit-button {
        background: #f59e0b;
        margin-right: 5px;
    }

    .edit-button:hover {
        background: #d97706;
    }

    .delete-button {
        background: #dc2626;
    }

    .delete-button:hover {
        background: #b91c1c;
    }

    .modal {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.5);
        justify-content: center;
        align-items: center;
        z-index: 1000;
    }

    .modal-content {
        background: white;
        width: 400px;
        max-width: 90%;
        padding: 25px;
        border-radius: 10px;
    }

    .modal-content h2 {
        margin-top: 0;
        margin-bottom: 20px;
    }

    .modal-content label {
        display: block;
        margin-top: 12px;
        margin-bottom: 5px;
        font-weight: bold;
    }

    .modal-content input,
    .modal-content select {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        font-size: 14px;
    }

    .form-buttons {
        display: flex;
        gap: 10px;
        margin-top: 20px;
    }

    .form-buttons button {
        flex: 1;
        padding: 10px;
    }

    .submit-button {
        background: #16a34a;
    }

    .submit-button:hover {
        background: #15803d;
    }

    .stock-out-submit {
        background: #ea580c;
    }

    .stock-out-submit:hover {
        background: #c2410c;
    }

    .cancel-button {
        background: #dc2626;
    }

    .cancel-button:hover {
        background: #b91c1c;
    }

    .current-stock {
        margin-top: 10px;
        padding: 10px;
        background: #f1f5f9;
        border-radius: 5px;
    }

    @media (max-width: 700px) {
        .cards {
            flex-direction: column;
        }

        .top-bar {
            flex-direction: column;
            align-items: stretch;
        }

        .top-buttons {
            flex-wrap: wrap;
        }

        table {
            font-size: 12px;
        }

        th,
        td {
            padding: 8px;
        }
    }
</style>
```

</head>

<body>

<div class="header">
    <h1>Inventory Management System</h1>
    <p>Enterprise Integration System</p>
</div>

<div class="container">

```
<div class="cards">

    <div class="card">
        <h3>Total Products</h3>
        <p id="totalProducts">0</p>
    </div>

    <div class="card">
        <h3>Total Stock</h3>
        <p id="totalStock">0</p>
    </div>

    <div class="card">
        <h3>Low Stock</h3>
        <p id="lowStock">0</p>
    </div>

</div>

<div class="table-container">

    <div class="top-bar">

        <h2>Products</h2>

        <div class="top-buttons">

            <button
                class="stock-in-button"
                onclick="openStockModal('IN')">
                + Stock In
            </button>

            <button
                class="stock-out-button"
                onclick="openStockModal('OUT')">
                - Stock Out
            </button>

            <a
                href="movements.php"
                class="history-button">
                Stock History
            </a>

            <button
                class="add-button"
                onclick="openAddProduct()">
                + Add Product
            </button>

        </div>

    </div>

    <div id="loading" class="loading">
        Loading products...
    </div>

    <table id="productTable" style="display:none;">

        <thead>
            <tr>
                <th>ID</th>
                <th>Product Name</th>
                <th>Category</th>
                <th>Price</th>
                <th>Stock</th>
                <th>Actions</th>
            </tr>
        </thead>

        <tbody id="productBody"></tbody>

    </table>

</div>
```

</div>

<!-- ADD PRODUCT MODAL -->

<div id="productModal" class="modal">

```
<div class="modal-content">

    <h2>Add Product</h2>

    <form id="productForm">

        <label for="product_name">Product Name</label>

        <input
            type="text"
            id="product_name"
            placeholder="Enter product name"
            required>

        <label for="category">Category</label>

        <input
            type="text"
            id="category"
            placeholder="Enter category"
            required>

        <label for="price">Price</label>

        <input
            type="number"
            id="price"
            placeholder="Enter price"
            step="0.01"
            min="0"
            required>

        <label for="stock">Stock</label>

        <input
            type="number"
            id="stock"
            placeholder="Enter stock quantity"
            min="0"
            required>

        <div class="form-buttons">

            <button
                type="submit"
                class="submit-button">
                Add Product
            </button>

            <button
                type="button"
                class="cancel-button"
                onclick="closeAddProduct()">
                Cancel
            </button>

        </div>

    </form>

</div>
```

</div>

<!-- STOCK MOVEMENT MODAL -->

<div id="stockModal" class="modal">

```
<div class="modal-content">

    <h2 id="stockModalTitle">
        Stock In
    </h2>

    <form id="stockForm">

        <label for="stockProduct">
            Product
        </label>

        <select id="stockProduct" required>

            <option value="">
                Select a product
            </option>

        </select>

        <div id="currentStock" class="current-stock">
            Current Stock: -
        </div>

        <label for="movementQuantity">
            Quantity
        </label>

        <input
            type="number"
            id="movementQuantity"
            min="1"
            placeholder="Enter quantity"
            required>

        <label for="remarks">
            Remarks
        </label>

        <input
            type="text"
            id="remarks"
            placeholder="Optional">

        <div class="form-buttons">

            <button
                type="submit"
                id="stockSubmitButton"
                class="submit-button">
                Stock In
            </button>

            <button
                type="button"
                class="cancel-button"
                onclick="closeStockModal()">
                Cancel
            </button>

        </div>

    </form>

</div>
```

</div>

<script>

    /*
     * CORRECT API URLS
     */

    const API_URL =
        "../backend/products.php";

    const STOCK_API_URL =
        "../backend/stock.php";


    let products = [];

    let currentMovementType = "IN";


    // ==============================
    // LOAD PRODUCTS
    // ==============================

    async function loadProducts() {

        const loading =
            document.getElementById("loading");

        try {

            loading.textContent =
                "Loading products...";

            const response =
                await fetch(API_URL);

            if (!response.ok) {
                throw new Error(
                    "HTTP error: " + response.status
                );
            }

            const result =
                await response.json();

            /*
             * Your PHP API returns the array directly.
             * It does NOT return { data: [...] }.
             */

            products =
                Array.isArray(result)
                    ? result
                    : (result.data || []);


            const productBody =
                document.getElementById("productBody");

            const totalProducts =
                document.getElementById("totalProducts");

            const totalStock =
                document.getElementById("totalStock");

            const lowStock =
                document.getElementById("lowStock");


            productBody.innerHTML = "";


            let stockTotal = 0;

            let lowStockTotal = 0;


            products.forEach(product => {

                const stock =
                    parseInt(product.stock) || 0;


                stockTotal += stock;


                if (stock <= 10) {
                    lowStockTotal++;
                }


                const row =
                    document.createElement("tr");


                row.innerHTML = `

                    <td>${product.id}</td>

                    <td>${product.product_name}</td>

                    <td>${product.category}</td>

                    <td>
                        &#8369;${parseFloat(product.price).toFixed(2)}
                    </td>

                    <td class="${
                        stock <= 10
                            ? "stock-low"
                            : "stock-good"
                    }">
                        ${stock}
                    </td>

                    <td>

                        <button
                            class="edit-button"
                            onclick="editProduct(${product.id})">
                            Edit
                        </button>

                        <button
                            class="delete-button"
                            onclick="deleteProduct(${product.id})">
                            Delete
                        </button>

                    </td>

                `;


                productBody.appendChild(row);

            });


            totalProducts.textContent =
                products.length;

            totalStock.textContent =
                stockTotal;

            lowStock.textContent =
                lowStockTotal;


            loading.style.display =
                "none";

            document.getElementById("productTable")
                .style.display = "table";


            loadProductOptions();

        }

        catch (error) {

            console.error(
                "Product loading error:",
                error
            );

            loading.textContent =
                "Failed to load products. Check the PHP API.";

        }

    }


    // ==============================
    // PRODUCT OPTIONS
    // ==============================

    function loadProductOptions() {

        const select =
            document.getElementById("stockProduct");


        select.innerHTML = `
            <option value="">
                Select a product
            </option>
        `;


        products.forEach(product => {

            const option =
                document.createElement("option");


            option.value =
                product.id;


            option.textContent =
                product.product_name +
                " (Stock: " +
                product.stock +
                ")";


            select.appendChild(option);

        });

    }


    // ==============================
    // ADD PRODUCT
    // ==============================

    function openAddProduct() {

        document.getElementById("productModal")
            .style.display = "flex";

    }


    function closeAddProduct() {

        document.getElementById("productModal")
            .style.display = "none";

        document.getElementById("productForm")
            .reset();

    }


    document.getElementById("productForm")
        .addEventListener(
            "submit",
            async function(event) {

                event.preventDefault();


                const product = {

                    product_name:
                        document.getElementById(
                            "product_name"
                        ).value.trim(),

                    category:
                        document.getElementById(
                            "category"
                        ).value.trim(),

                    price:
                        parseFloat(
                            document.getElementById(
                                "price"
                            ).value
                        ),

                    stock:
                        parseInt(
                            document.getElementById(
                                "stock"
                            ).value
                        )

                };


                try {

                    /*
                     * IMPORTANT:
                     * No /${id} here.
                     * POST goes directly to products.php.
                     */

                    const response =
                        await fetch(API_URL, {

                            method: "POST",

                            headers: {
                                "Content-Type":
                                    "application/json"
                            },

                            body:
                                JSON.stringify(product)

                        });


                    const result =
                        await response.json();


                    if (response.ok) {

                        alert(
                            "Product added successfully!"
                        );

                        closeAddProduct();

                        loadProducts();

                    }

                    else {

                        alert(
                            result.detail ||
                            result.message ||
                            result.title ||
                            "Failed to add product."
                        );

                    }

                }

                catch (error) {

                    console.error(error);

                    alert(
                        "Something went wrong while adding the product."
                    );

                }

            }
        );


    // ==============================
    // EDIT PRODUCT
    // ==============================

    async function editProduct(id) {

        const product =
            products.find(
                p =>
                    String(p.id) === String(id)
            );


        if (!product) {

            alert("Product not found.");

            return;

        }


        const productName =
            prompt(
                "Enter new product name:",
                product.product_name
            );


        if (productName === null) {
            return;
        }


        const category =
            prompt(
                "Enter new category:",
                product.category
            );


        if (category === null) {
            return;
        }


        const price =
            prompt(
                "Enter new price:",
                product.price
            );


        if (price === null) {
            return;
        }


        const stock =
            prompt(
                "Enter new stock:",
                product.stock
            );


        if (stock === null) {
            return;
        }


        try {

            const response =
                await fetch(
                    `${API_URL}?id=${id}`,
                    {

                        method: "PUT",

                        headers: {
                            "Content-Type":
                                "application/json"
                        },

                        body:
                            JSON.stringify({

                                product_name:
                                    productName,

                                category:
                                    category,

                                price:
                                    parseFloat(price),

                                stock:
                                    parseInt(stock)

                            })

                    }
                );


            const result =
                await response.json();


            if (response.ok) {

                alert(
                    "Product updated successfully!"
                );

                loadProducts();

            }

            else {

                alert(
                    result.detail ||
                    result.message ||
                    result.title ||
                    "Failed to update product."
                );

            }

        }

        catch (error) {

            console.error(error);

            alert(
                "Failed to update product."
            );

        }

    }


    // ==============================
    // DELETE PRODUCT
    // ==============================

    async function deleteProduct(id) {

        const confirmDelete =
            confirm(
                "Are you sure you want to delete this product?"
            );


        if (!confirmDelete) {
            return;
        }


        try {

            const response =
                await fetch(
                    `${API_URL}?id=${id}`,
                    {

                        method: "DELETE"

                    }
                );


            const result =
                await response.json();


            if (response.ok) {

                alert(
                    "Product deleted successfully!"
                );

                loadProducts();

            }

            else {

                alert(
                    result.detail ||
                    result.message ||
                    result.title ||
                    "Failed to delete product."
                );

            }

        }

        catch (error) {

            console.error(error);

            alert(
                "Failed to delete product."
            );

        }

    }


    // ==============================
    // STOCK IN / STOCK OUT
    // ==============================

    function openStockModal(type) {

        currentMovementType =
            type;


        const modal =
            document.getElementById(
                "stockModal"
            );


        const title =
            document.getElementById(
                "stockModalTitle"
            );


        const button =
            document.getElementById(
                "stockSubmitButton"
            );


        if (type === "IN") {

            title.textContent =
                "Stock In";

            button.textContent =
                "Stock In";

            button.className =
                "submit-button";

        }

        else {

            title.textContent =
                "Stock Out";

            button.textContent =
                "Stock Out";

            button.className =
                "stock-out-submit";

        }


        document.getElementById(
            "stockForm"
        ).reset();


        document.getElementById(
            "currentStock"
        ).textContent =
            "Current Stock: -";


        modal.style.display =
            "flex";

    }


    function closeStockModal() {

        document.getElementById(
            "stockModal"
        ).style.display =
            "none";


        document.getElementById(
            "stockForm"
        ).reset();

    }


    // ==============================
    // SHOW CURRENT STOCK
    // ==============================

    document.getElementById(
        "stockProduct"
    ).addEventListener(
        "change",
        function() {

            const selectedId =
                this.value;


            const product =
                products.find(
                    p =>
                        String(p.id) ===
                        String(selectedId)
                );


            if (product) {

                document.getElementById(
                    "currentStock"
                ).textContent =
                    "Current Stock: " +
                    product.stock;

            }

            else {

                document.getElementById(
                    "currentStock"
                ).textContent =
                    "Current Stock: -";

            }

        }
    );


    // ==============================
    // STOCK MOVEMENT
    // ==============================

    document.getElementById(
        "stockForm"
    ).addEventListener(
        "submit",
        async function(event) {

            event.preventDefault();


            const productId =
                document.getElementById(
                    "stockProduct"
                ).value;


            const quantity =
                parseInt(
                    document.getElementById(
                        "movementQuantity"
                    ).value
                );


            const remarks =
                document.getElementById(
                    "remarks"
                ).value;


            if (!productId) {

                alert(
                    "Please select a product."
                );

                return;

            }


            if (!quantity || quantity <= 0) {

                alert(
                    "Quantity must be greater than zero."
                );

                return;

            }


            try {

                const response =
                    await fetch(
                        STOCK_API_URL,
                        {

                            method: "POST",

                            headers: {
                                "Content-Type":
                                    "application/json"
                            },

                            body:
                                JSON.stringify({

                                    product_id:
                                        parseInt(
                                            productId
                                        ),

                                    movement_type:
                                        currentMovementType,

                                    quantity:
                                        quantity,

                                    remarks:
                                        remarks

                                })

                        }
                    );


                const result =
                    await response.json();


                if (response.ok) {

                    alert(
                        "Stock movement recorded successfully!"
                    );

                    closeStockModal();

                    loadProducts();

                }

                else {

                    alert(
                        result.detail ||
                        result.message ||
                        result.title ||
                        "Failed to update stock."
                    );

                }

            }

            catch (error) {

                console.error(error);

                alert(
                    "Something went wrong while updating stock."
                );

            }

        }
    );


    // ==============================
    // START APPLICATION
    // ==============================

    loadProducts();

</script>

</body>
</html>
