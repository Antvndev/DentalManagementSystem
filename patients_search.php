<?php
// patients_search.php
include 'config.php';

// Return JSON
header('Content-Type: application/json; charset=utf-8');

// Ensure proper charset on the connection
if (method_exists($conn, 'set_charset')) {
    $conn->set_charset('utf8mb4');
}

$q = isset($_GET['q']) ? trim($_GET['q']) : '';

try {
    if ($q === '') {
        // Return all (or limited)
        $sql = "SELECT id, first_name, middle_name, last_name, gender, age, phone, egn
                FROM patients
                ORDER BY last_name
                LIMIT 1000";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
    } else {
        // Add EGN search capability
        $param = '%' . $q . '%';
        $sql = "SELECT id, first_name, middle_name, last_name, gender, age, phone, egn
                FROM patients
                WHERE CONCAT_WS(' ', first_name, middle_name, last_name) LIKE ?
                   OR first_name LIKE ?
                   OR last_name LIKE ?
                   OR egn LIKE ?
                ORDER BY last_name
                LIMIT 500";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ssss', $param, $param, $param, $param);
        $stmt->execute();
    }

    $res = $stmt->get_result();
    $rows = [];
    while ($row = $res->fetch_assoc()) {
        $rows[] = $row;
    }

    echo json_encode($rows, JSON_UNESCAPED_UNICODE);
} catch (Exception $e) {
    // On error, return empty array (don’t leak DB errors)
    echo json_encode([]);
}
?>
