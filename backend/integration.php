<?php

require_once "db.php";

header("Content-Type: application/json");

$action = $_GET["action"] ?? "";

if ($action === "products") {

    $sql = "
        SELECT
            id,
            product_name,
            category,
            price,
            stock
        FROM products
        ORDER BY product_name ASC
    ";

    $result = $conn->query($sql);

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
        "source" => "Inventory Management System",
        "data" => $products
    ]);

    exit;
}


if ($action === "stock") {

    $sql = "
        SELECT
            id,
            product_name,
            stock
        FROM products
        ORDER BY product_name ASC
    ";

    $result = $conn->query($sql);

    $stock = [];

    while ($row = $result->fetch_assoc()) {

        $stock[] = [
            "product_id" => (int)$row["id"],
            "product_name" => $row["product_name"],
            "stock" => (int)$row["stock"]
        ];

    }

    echo json_encode([
        "success" => true,
        "source" => "Inventory Management System",
        "data" => $stock
    ]);

    exit;
}


echo json_encode([
    "success" => false,
    "message" => "Invalid action."
]);

?>