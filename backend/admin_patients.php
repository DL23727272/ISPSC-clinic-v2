<?php
require_once './backend/db_connection.php';

// Make sure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: admin_login.php");
    exit;
}

$role = $_SESSION['role'];       // "admin" or "super_admin"
$userCampus = $_SESSION['campus']; // Campus from users table

// Fetch campuses only if superadmin
$campuses = [];
if ($role === 'super_admin') {
    $result = $conn->query("SELECT DISTINCT campus FROM employees ORDER BY campus ASC");
    while($row = $result->fetch_assoc()){
        $campuses[] = $row['campus'];
    }
} else {
    // Campus admin only sees their campus
    $campuses[] = $userCampus;
}

// Get search, campus, and type filter values
$search = isset($_GET['search']) ? $_GET['search'] : '';
$campus_filter = isset($_GET['campus']) ? $_GET['campus'] : '';
$type = isset($_GET['type']) ? $_GET['type'] : 'employees'; // default to employees

// Restrict campus filter for admin
if ($role === 'admin') {
    $campus_filter = $userCampus;
}

// Determine table and fields based on type
if ($type === 'students') {
    $table = 'students';
    $idField = 'student_id';
    $nameField = "CONCAT(last_name, ', ', first_name)";
    $extraFields = 'middle_name, suffix, age, campus, department, course, year, major, semester, course_year, birthdate, sex, permanent_address, phone_number, civil_status, religion, contact_person, contact_address, contact_no, email, created_at';
} else {
    $table = 'employees';
    $idField = 'employee_id';
    $nameField = "CONCAT(last_name, ', ', first_name)";
    $extraFields = 'middle_name, suffix, age, campus, birthdate, sex, permanent_address, phone_number, civil_status, religion, contact_person, contact_address, contact_no, email, created_at';
}

// Build query
$sql = "SELECT id, $idField AS id_value, $nameField AS full_name, $extraFields
        FROM $table
        WHERE 1";

// Apply search filter
if ($search != '') {
    $search_esc = $conn->real_escape_string($search);
    $sql .= " AND ($nameField LIKE '%$search_esc%' OR $idField LIKE '%$search_esc%')";
}

// Apply campus filter
if ($campus_filter != '') {
    $campus_esc = $conn->real_escape_string($campus_filter);
    $sql .= " AND campus = '$campus_esc'";
}

$sql .= " ORDER BY $nameField ASC";
$result = $conn->query($sql);
?>
