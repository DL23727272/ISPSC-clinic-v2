<?php
session_start();
require_once __DIR__ . './backend/db_connection.php';

// ✅ Allow only admin or super admin roles
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['admin', 'super_admin'])) {
    header("Location: ./admin_login");
    exit;
}

// ✅ Only allow POST requests for deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $certId = intval($_POST['id']); // sanitize input

    // Prepare delete statement
    $sql = "DELETE FROM student_certificates WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $certId);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Certificate deleted successfully.";
    } else {
        $_SESSION['error'] = "Failed to delete certificate.";
    }

    $stmt->close();
    $conn->close();

    // Redirect back to certificate_info
    header("Location: ./certificate_info");
    exit;
} else {
    // If accessed directly, redirect
    header("Location: ./certificate_info");
    exit;
}
