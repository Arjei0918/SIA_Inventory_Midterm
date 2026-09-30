<?php

require_once "db.php";

header("Content-Type: application/json");

$method = $_SERVER["REQUEST_METHOD"];



if ($method === "GET") {

    $sql = "
        SELECT
            stock_movements.id,
            stock_movements.product_id,
            products.product_name,
            stock_movements.movement_type,
            stock_movements.quantity,
            stock_movements.remarks,
            stock_movements.created_at
        FROM stock_movements
        INNER JOIN products
            ON stock_movements.product_id = products.id
        ORDER BY stock_movements.id DESC
    ";

    $result = $conn->query($sql);

    $movements = [];

    while ($row = $result->fetch_assoc()) {
        $movements[] = $row;
    }

    echo json_encode($movements);

}



elseif ($method === "POST") {

    $data = json_decode(
        file_get_contents("php://input"),
        true
    );


    if (
        !isset($data["product_id"]) ||
        !isset($data["movement_type"]) ||
        !isset($data["quantity"])
    ) {

        echo json_encode([
            "success" => false,
            "message" => "Product, movement type, and quantity are required."
        ]);

        exit;
    }


    $product_id = (int)$data["product_id"];
    $movement_type = $data["movement_type"];
    $quantity = (int)$data["quantity"];
    $remarks = $data["remarks"] ?? "";


    if ($quantity <= 0) {

        echo json_encode([
            "success" => false,
            "message" => "Quantity must be greater than zero."
        ]);

        exit;
    }


    if (
        $movement_type !== "IN" &&
        $movement_type !== "OUT"
    ) {

        echo json_encode([
            "success" => false,
            "message" => "Invalid movement type."
        ]);

        exit;
    }


    $check = $conn->prepare(
        "SELECT stock FROM products WHERE id = ?"
    );

    $check->bind_param(
        "i",
        $product_id
    );

    $check->execute();

    $result = $check->get_result();


    if ($result->num_rows === 0) {

        echo json_encode([
            "success" => false,
            "message" => "Product not found."
        ]);

        exit;
    }


    $product = $result->fetch_assoc();

    $currentStock = (int)$product["stock"];


    // CALCULATE NEW STOCK

    if ($movement_type === "IN") {

        $newStock =
            $currentStock + $quantity;

    } else {

        if ($quantity > $currentStock) {

            echo json_encode([
                "success" => false,
                "message" => "Not enough stock available."
            ]);

            exit;
        }

        $newStock =
            $currentStock - $quantity;
    }


    // UPDATE PRODUCT STOCK

    $update = $conn->prepare(
        "UPDATE products SET stock = ? WHERE id = ?"
    );

    $update->bind_param(
        "ii",
        $newStock,
        $product_id
    );


    if (!$update->execute()) {

        echo json_encode([
            "success" => false,
            "message" => "Failed to update stock."
        ]);

        exit;
    }



    $stmt = $conn->prepare(
        "INSERT INTO stock_movements
        (product_id, movement_type, quantity, remarks)
        VALUES (?, ?, ?, ?)"
    );


    $stmt->bind_param(
        "isis",
        $product_id,
        $movement_type,
        $quantity,
        $remarks
    );


    if ($stmt->execute()) {

        echo json_encode([
            "success" => true,
            "message" => "Stock updated successfully.",
            "new_stock" => $newStock
        ]);

    } else {

        echo json_encode([
            "success" => false,
            "message" => "Stock updated but movement was not recorded."
        ]);

    }

}


else {

    echo json_encode([
        "success" => false,
        "message" => "Invalid request method."
    ]);

}

?>