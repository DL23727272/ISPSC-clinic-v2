<?php
header('Content-Type: application/json');
require_once __DIR__ . '/db_connection.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['cert_name'] ?? '');
    $age = intval($_POST['cert_age'] ?? 0);
    $sex = trim($_POST['cert_sex'] ?? '');
    $year = trim($_POST['cert_year'] ?? '');
    $purpose = trim($_POST['cert_purpose'] ?? '');
    $bp = trim($_POST['cert_bp'] ?? '');
    $hr = trim($_POST['cert_hr'] ?? '');
    $rr = trim($_POST['cert_rr'] ?? '');
    $temp = trim($_POST['cert_temp'] ?? '');
    $vax = trim($_POST['cert_vax'] ?? '');
    $cert_date = $_POST['cert_date'] ?? date('Y-m-d');
    $user_id = trim($_POST['student_id'] ?? '');

    if ($name && $age && $sex && $year && $purpose) {
        $stmt = $conn->prepare("INSERT INTO student_certificates 
            (name, age, sex, year, purpose, bp, hr, rr, temp, vax, user_id, cert_date) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sissssssssss", 
            $name, $age, $sex, $year, $purpose, $bp, $hr, $rr, $temp, $vax, $user_id, $cert_date);

        if ($stmt->execute()) {
            echo json_encode(["status" => "success", "message" => "Your certificate has been submitted."]);
        } else {
            echo json_encode(["status" => "error", "message" => "Database error."]);
        }
        $stmt->close();
    } else {
        echo json_encode(["status" => "error", "message" => "Please fill in all required fields."]);
    }
}
