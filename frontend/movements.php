<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Stock Movement History</title>

    <style>
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

        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .back-button {
            background: #2563eb;
            color: white;
            padding: 10px 16px;
            text-decoration: none;
            border-radius: 6px;
        }

        .table-container {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            overflow-x: auto;
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

        .stock-in {
            color: green;
            font-weight: bold;
        }

        .stock-out {
            color: red;
            font-weight: bold;
        }

        .loading {
            text-align: center;
            padding: 30px;
        }
    </style>
</head>

<body>

    <div class="header">
        <h1>Stock Movement History</h1>
        <p>Inventory Management System</p>
    </div>

    <div class="container">

        <div class="top-bar">
            <h2>Stock Movements</h2>

            <a
                href="index.php"
                class="back-button"
            >
                ← Back to Inventory
            </a>
        </div>

        <div class="table-container">

            <div id="loading" class="loading">
                Loading stock movements...
            </div>

            <table id="movementTable" style="display:none;">

                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Product</th>
                        <th>Movement</th>
                        <th>Quantity</th>
                        <th>Remarks</th>
                        <th>Date</th>
                    </tr>
                </thead>

                <tbody id="movementBody"></tbody>

            </table>

        </div>

    </div>

    <script>

        const API_URL = "../backend/stock.php";

        async function loadMovements() {

            try {

                const response = await fetch(API_URL);

                const movements = await response.json();

                const movementBody =
                    document.getElementById("movementBody");

                movementBody.innerHTML = "";

                movements.forEach(movement => {

                    const row =
                        document.createElement("tr");

                    const movementType =
                        movement.movement_type;

                    const movementClass =
                        movementType === "IN"
                        ? "stock-in"
                        : "stock-out";

                    const movementText =
                        movementType === "IN"
                        ? "Stock In"
                        : "Stock Out";

                    row.innerHTML = `

                        <td>${movement.id}</td>

                        <td>${movement.product_name}</td>

                        <td class="${movementClass}">
                            ${movementText}
                        </td>

                        <td>${movement.quantity}</td>

                        <td>
                            ${movement.remarks || "-"}
                        </td>

                        <td>
                            ${movement.created_at}
                        </td>

                    `;

                    movementBody.appendChild(row);

                });

                document.getElementById("loading")
                    .style.display = "none";

                document.getElementById("movementTable")
                    .style.display = "table";

            }

            catch (error) {

                console.error(error);

                document.getElementById("loading")
                    .textContent =
                    "Failed to load stock movements.";

            }

        }

        loadMovements();

    </script>

</body>
</html>