<?php
session_start();

require_once './backend/db_connection.php';

// Make sure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: admin_login.php");
    exit;
}
// Handle search, campus, and type filter
$search = isset($_GET['search']) ? $_GET['search'] : '';
$campus = isset($_GET['campus']) ? $_GET['campus'] : '';
$type   = isset($_GET['type']) ? $_GET['type'] : 'student'; // default student
$role = isset($_SESSION['role']) ? $_SESSION['role'] : null;
// Check if logged-in user is admin and restrict campus
if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
    // Assume you store admin campus in session
    $adminCampus = $_SESSION['campus'];  
}

// Build base query
if ($type === 'employee') {
    $sql = "SELECT ehi.id, ehi.employee_id AS person_id, ehi.created_at, e.first_name, e.last_name, e.campus
            FROM employee_health_info ehi
            LEFT JOIN employees e ON ehi.employee_id = e.employee_id
            WHERE 1";
} else {
    $sql = "SELECT shi.id, shi.student_id AS person_id, shi.created_at, s.first_name, s.last_name, s.campus
            FROM student_health_info shi
            LEFT JOIN students s ON shi.student_id = s.student_id
            WHERE 1";
}

// Apply search
if(!empty($search)) {
    $search = $conn->real_escape_string($search);

    if ($type === 'employee') {
        $sql .= " AND (ehi.employee_id LIKE '%$search%' 
                   OR e.first_name LIKE '%$search%' 
                   OR e.last_name LIKE '%$search%')";
    } else {
        $sql .= " AND (shi.student_id LIKE '%$search%' 
                   OR s.first_name LIKE '%$search%' 
                   OR s.last_name LIKE '%$search%')";
    }
}


// Restrict campus for admin
if (isset($adminCampus)) {
    $sql .= " AND campus = '".$conn->real_escape_string($adminCampus)."'";
} elseif(!empty($campus)) {
    // If not admin, use campus filter from dropdown
    $campus = $conn->real_escape_string($campus);
    $sql .= " AND campus = '$campus'";
}

$sql .= " ORDER BY campus ASC, id DESC";
$result = $conn->query($sql);

// Fetch campuses depending on type (but limit if admin)
if ($type === 'employee') {
    if (isset($adminCampus)) {
        $campus_result = $conn->query("SELECT DISTINCT campus FROM employees WHERE campus = '".$conn->real_escape_string($adminCampus)."' ORDER BY campus ASC");
    } else {
        $campus_result = $conn->query("SELECT DISTINCT campus FROM employees ORDER BY campus ASC");
    }
} else {
    if (isset($adminCampus)) {
        $campus_result = $conn->query("SELECT DISTINCT campus FROM students WHERE campus = '".$conn->real_escape_string($adminCampus)."' ORDER BY campus ASC");
    } else {
        $campus_result = $conn->query("SELECT DISTINCT campus FROM students ORDER BY campus ASC");
    }
}
?>