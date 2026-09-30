<?php

require_once "db.php";

header("Content-Type: application/json");

// Allow Swagger and other clients to access the API
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

// Handle preflight requests
if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") {
    exit;
}

$method = $_SERVER["REQUEST_METHOD"];


/* =========================
   GET - Get all products
   ========================= */

if ($method === "GET") {

    $result = $conn->query("SELECT * FROM products ORDER BY id DESC");

    if (!$result) {
        http_response_code(500);

        echo json_encode([
            "success" => false,
            "message" => "Unable to get products."
        ]);

        exit;
    }

    $products = [];

    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }

    echo json_encode($products);
}


/* =========================
   POST - Add a product
   ========================= */

elseif ($method === "POST") {

    $data = json_decode(file_get_contents("php://input"), true);

    if (
        !isset($data["product_name"]) ||
        !isset($data["category"]) ||
        !isset($data["price"]) ||
        !isset($data["stock"])
    ) {

        http_response_code(400);

        echo json_encode([
            "success" => false,
            "message" => "All fields are required."
        ]);

        exit;
    }

    $product_name = $data["product_name"];
    $category = $data["category"];
    $price = $data["price"];
    $stock = $data["stock"];

    $stmt = $conn->prepare(
        "INSERT INTO products (product_name, category, price, stock)
         VALUES (?, ?, ?, ?)"
    );

    $stmt->bind_param(
        "ssdi",
        $product_name,
        $category,
        $price,
        $stock
    );

    if ($stmt->execute()) {

        echo json_encode([
            "success" => true,
            "message" => "Product added successfully."
        ]);

    } else {

        http_response_code(500);

        echo json_encode([
            "success" => false,
            "message" => "Unable to add product."
        ]);
    }

    $stmt->close();
}


/* =========================
   PUT - Update a product
   ========================= */

elseif ($method === "PUT") {

    $data = json_decode(file_get_contents("php://input"), true);

    if (
        !isset($data["id"]) ||
        !isset($data["product_name"]) ||
        !isset($data["category"]) ||
        !isset($data["price"]) ||
        !isset($data["stock"])
    ) {

        http_response_code(400);

        echo json_encode([
            "success" => false,
            "message" => "All fields are required."
        ]);

        exit;
    }

    $id = $data["id"];
    $product_name = $data["product_name"];
    $category = $data["category"];
    $price = $data["price"];
    $stock = $data["stock"];

    $stmt = $conn->prepare(
        "UPDATE products
         SET product_name = ?,
             category = ?,
             price = ?,
             stock = ?
         WHERE id = ?"
    );

    $stmt->bind_param(
        "ssdii",
        $product_name,
        $category,
        $price,
        $stock,
        $id
    );

    if ($stmt->execute()) {

        echo json_encode([
            "success" => true,
            "message" => "Product updated successfully."
        ]);

    } else {

        http_response_code(500);

        echo json_encode([
            "success" => false,
            "message" => "Unable to update product."
        ]);
    }

    $stmt->close();
}


/* =========================
   DELETE - Delete a product
   ========================= */

elseif ($method === "DELETE") {

    // Swagger sends the ID as ?id=5
    $id = $_GET["id"] ?? null;

    if (!$id || !is_numeric($id)) {

        http_response_code(400);

        echo json_encode([
            "success" => false,
            "message" => "Product ID is required."
        ]);

        exit;
    }

    $id = (int) $id;

    $stmt = $conn->prepare(
        "DELETE FROM products WHERE id = ?"
    );

    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {

        if ($stmt->affected_rows > 0) {

            echo json_encode([
                "success" => true,
                "message" => "Product deleted successfully."
            ]);

        } else {

            http_response_code(404);

            echo json_encode([
                "success" => false,
                "message" => "Product not found."
            ]);
        }

    } else {

        http_response_code(500);

        echo json_encode([
            "success" => false,
            "message" => "Unable to delete product."
        ]);
    }

    $stmt->close();
}


/* =========================
   Invalid request
   ========================= */

else {

    http_response_code(405);

    echo json_encode([
        "success" => false,
        "message" => "Invalid request method."
    ]);
}

?>