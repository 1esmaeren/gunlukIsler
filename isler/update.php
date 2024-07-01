<?php
require 'db.php';

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['id'], $data['column'], $data['value'])) {
    $id = $data['id'];
    $column = $data['column'];
    $value = $data['value'];

    $sql = "UPDATE isler_tb SET $column = :value WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['value' => $value, 'id' => $id]);

    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false]);
}
?>