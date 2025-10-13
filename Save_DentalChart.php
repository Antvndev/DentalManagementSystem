<?php
include 'config.php';
header('Content-Type: application/json');

// Parse incoming JSON
$input = json_decode(file_get_contents('php://input'), true);

if (!$input || !isset($input['patient_id']) || !isset($input['chart'])) {
    echo json_encode(['success' => false, 'error' => 'Invalid data']);
    exit;
}

$patient_id = intval($input['patient_id']);
$chart = $input['chart'];

// Clear existing data
$stmt = $conn->prepare("DELETE FROM dental_chart WHERE patient_id = ?");
$stmt->bind_param("i", $patient_id);
$stmt->execute();
$stmt->close();

// Insert new records
$stmt = $conn->prepare("INSERT INTO dental_chart (patient_id, tooth_number, `condition`) VALUES (?, ?, ?)");

foreach ($chart as $item) {
    $tooth = intval($item['tooth']);
    $condition = $item['condition'];
    $stmt->bind_param("iis", $patient_id, $tooth, $condition);
    $stmt->execute();
}

$stmt->close();

echo json_encode(['success' => true]);
