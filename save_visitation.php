<?php
include 'config.php';
header('Content-Type: application/json; charset=utf-8');

// Disable output buffering issues
ob_start();

try {
    $input = json_decode(file_get_contents('php://input'), true);
    if (!$input || !isset($input['patient_id'])) {
        throw new Exception('Invalid input');
    }

    $patient_id     = intval($input['patient_id']);
    $visit_date     = !empty($input['visit_date']) ? $input['visit_date'] : date('Y-m-d');
    $tooth_number   = $input['tooth_number'] ?? null;
    $diagnosis      = $input['diagnosis'] ?? null;
    $treatment      = $input['treatment'] ?? null;
    $procedure_used = $input['procedure_used'] ?? null;
    $notes          = $input['notes'] ?? null;

    $conn->set_charset('utf8mb4');

    $stmt = $conn->prepare("
        INSERT INTO visitations
        (patient_id, visit_date, tooth_number, diagnosis, treatment, procedure_used, notes)
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");

    if (!$stmt) {
        throw new Exception($conn->error);
    }

    $stmt->bind_param(
        "issssss",
        $patient_id,
        $visit_date,
        $tooth_number,
        $diagnosis,
        $treatment,
        $procedure_used,
        $notes
    );

    if (!$stmt->execute()) {
        throw new Exception($stmt->error);
    }

    echo json_encode(['success' => true, 'id' => $stmt->insert_id]);

    $stmt->close();
    $conn->close();
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}

// Clear any extra output and exit
ob_end_flush();
exit;
?>
