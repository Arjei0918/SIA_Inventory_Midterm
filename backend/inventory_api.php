<?php

require_once "db.php";

header("Content-Type: application/json");

$sql = "
    SELECT
        id,
        product_name,
        category,
        price,
        stock
    FROM products
    ORDER BY id DESC
";

$result = $conn->query($sql);

if (!$result) {

    echo json_encode([
        "success" => false,
        "message" => "Failed to retrieve inventory."
    ]);

    exit;
}

$products = [];

while ($row = $result->fetch_assoc()) {

    $products[] = [
        "id" => (int)$row["id"],
        "product_name" => $row["product_name"],
        "category" => $row["category"],
        "price" => (float)$row["price"],
        "stock" => (int)$row["stock"],
        "available" => ((int)$row["stock"] > 0)
    ];

}

echo json_encode([
    "success" => true,
    "system" => "Inventory Management System",
    "data" => $products
]);

?>