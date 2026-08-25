<?php

include "db.php";
include "auth.php";
$userId = require_login();

header("Content-Type: application/json");

// This week (last 7 days including today)
$stmt = $conn->prepare("
    SELECT IFNULL(SUM(total), 0) AS total
    FROM sales
    WHERE user_id = ?
    AND DATE(sale_date) >= DATE_SUB(CURDATE(), INTERVAL 6 DAY)
");
$stmt->bind_param("i", $userId);
$stmt->execute();
$weekThis = (float)$stmt->get_result()->fetch_assoc()["total"];
$stmt->close();

// Previous 7 days
$stmt = $conn->prepare("
    SELECT IFNULL(SUM(total), 0) AS total
    FROM sales
    WHERE user_id = ?
    AND DATE(sale_date) >= DATE_SUB(CURDATE(), INTERVAL 13 DAY)
    AND DATE(sale_date) < DATE_SUB(CURDATE(), INTERVAL 6 DAY)
");
$stmt->bind_param("i", $userId);
$stmt->execute();
$weekLast = (float)$stmt->get_result()->fetch_assoc()["total"];
$stmt->close();

$weekChangePct = null;
if ($weekLast > 0) {
    $weekChangePct = round((($weekThis - $weekLast) / $weekLast) * 100, 1);
} elseif ($weekThis > 0) {
    $weekChangePct = 100;
}

// Stock health
$stock = ["in" => 0, "low" => 0, "out" => 0];

$stmt = $conn->prepare("SELECT stock FROM products WHERE user_id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $s = (int)$row["stock"];
    if ($s <= 0) {
        $stock["out"]++;
    } elseif ($s <= 5) {
        $stock["low"]++;
    } else {
        $stock["in"]++;
    }
}
$stmt->close();

// Top products by quantity
$topProducts = [];
$stmt = $conn->prepare("
    SELECT
        products.name AS name,
        IFNULL(SUM(sales.quantity), 0) AS quantity,
        IFNULL(SUM(sales.total), 0) AS revenue
    FROM sales
    INNER JOIN products ON sales.product_id = products.id
    WHERE sales.user_id = ?
    GROUP BY products.id, products.name
    ORDER BY quantity DESC
    LIMIT 5
");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $topProducts[] = [
        "name" => $row["name"],
        "quantity" => (int)$row["quantity"],
        "revenue" => (float)$row["revenue"]
    ];
}
$stmt->close();

// Daily revenue last 7 days
$dailyMap = [];
$stmt = $conn->prepare("
    SELECT DATE(sale_date) AS day, IFNULL(SUM(total), 0) AS total
    FROM sales
    WHERE user_id = ?
    AND DATE(sale_date) >= DATE_SUB(CURDATE(), INTERVAL 6 DAY)
    GROUP BY DATE(sale_date)
    ORDER BY day ASC
");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $dailyMap[$row["day"]] = (float)$row["total"];
}
$stmt->close();

$daily = [];
for ($i = 6; $i >= 0; $i--) {
    $day = date("Y-m-d", strtotime("-{$i} days"));
    $daily[] = [
        "date" => $day,
        "label" => date("D j", strtotime($day)),
        "total" => $dailyMap[$day] ?? 0
    ];
}

echo json_encode([
    "weekThis" => $weekThis,
    "weekLast" => $weekLast,
    "weekChangePct" => $weekChangePct,
    "stock" => $stock,
    "topProducts" => $topProducts,
    "daily" => $daily
]);

$conn->close();
?>
