<?php
session_start();
require_once __DIR__ . './backend/db_connection.php';

// Only allow admins/superadmins
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['admin', 'superadmin', 'super_admin'])) {
    header("Location: ./admin_login");
    exit;
}

// Get certificate ID
$certId = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($certId <= 0) {
    header("Location: ./certificate_info");
    exit;
}

// Fetch certificate details
$stmt = $conn->prepare("SELECT id, name, age, sex, year, purpose, bp, hr, rr, temp, vax FROM student_certificates WHERE id = ?");
$stmt->bind_param("i", $certId);
$stmt->execute();
$result = $stmt->get_result();
$cert = $result->fetch_assoc();
$stmt->close();

if (!$cert) {
    $_SESSION['error'] = "Certificate not found.";
    header("Location: ./certificate_info");
    exit;
}

// Handle update submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name    = trim($_POST['name']);
    $age     = intval($_POST['age']);
    $sex     = trim($_POST['sex']);
    $year    = trim($_POST['year']);
    $purpose = trim($_POST['purpose']);
    $bp      = trim($_POST['bp']);
    $hr      = trim($_POST['hr']);
    $rr      = trim($_POST['rr']);
    $temp    = trim($_POST['temp']);
    $vax     = trim($_POST['vax']);

    $stmt = $conn->prepare("UPDATE student_certificates 
        SET name=?, age=?, sex=?, year=?, purpose=?, bp=?, hr=?, rr=?, temp=?, vax=? 
        WHERE id=?");
    $stmt->bind_param("sissssssssi", $name, $age, $sex, $year, $purpose, $bp, $hr, $rr, $temp, $vax, $certId);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Certificate updated successfully.";
    } else {
        $_SESSION['error'] = "Failed to update certificate.";
    }

    $stmt->close();
    header("Location: ./certificate_info");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Certificate</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
  <div class="card shadow-sm">
    <div class="card-header bg-warning text-dark">
      <h4 class="mb-0">Edit Certificate</h4>
    </div>
    <div class="card-body">
      <form method="POST">
        <div class="mb-3">
          <label class="form-label">Name</label>
          <input type="text" name="name" class="form-control" required value="<?= htmlspecialchars($cert['name']) ?>">
        </div>
        <div class="mb-3">
          <label class="form-label">Age</label>
          <input type="number" name="age" class="form-control" required value="<?= htmlspecialchars($cert['age']) ?>">
        </div>
        <div class="mb-3">
          <label class="form-label">Sex</label>
          <select name="sex" class="form-select" required>
            <option value="Male" <?= $cert['sex'] === 'Male' ? 'selected' : '' ?>>Male</option>
            <option value="Female" <?= $cert['sex'] === 'Female' ? 'selected' : '' ?>>Female</option>
          </select>
        </div>
        <div class="mb-3">
          <label class="form-label">Year</label>
          <input type="text" name="year" class="form-control" required value="<?= htmlspecialchars($cert['year']) ?>">
        </div>
        <div class="mb-3">
          <label class="form-label">Purpose</label>
          <input type="text" name="purpose" class="form-control" required value="<?= htmlspecialchars($cert['purpose']) ?>">
        </div>
        <div class="mb-3">
          <label class="form-label">BP</label>
          <input type="text" name="bp" class="form-control" value="<?= htmlspecialchars($cert['bp']) ?>">
        </div>
        <div class="mb-3">
          <label class="form-label">HR</label>
          <input type="text" name="hr" class="form-control" value="<?= htmlspecialchars($cert['hr']) ?>">
        </div>
        <div class="mb-3">
          <label class="form-label">RR</label>
          <input type="text" name="rr" class="form-control" value="<?= htmlspecialchars($cert['rr']) ?>">
        </div>
        <div class="mb-3">
          <label class="form-label">Temperature</label>
          <input type="text" name="temp" class="form-control" value="<?= htmlspecialchars($cert['temp']) ?>">
        </div>
        <div class="mb-3">
          <label class="form-label">Vaccination</label>
          <input type="text" name="vax" class="form-control" value="<?= htmlspecialchars($cert['vax']) ?>">
        </div>
        <button type="submit" class="btn btn-warning">Update</button>
        <a href="certificate_info.php" class="btn btn-secondary">Cancel</a>
      </form>
    </div>
  </div>
</div>
</body>
</html>
