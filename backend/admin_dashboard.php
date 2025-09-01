<?php
session_start();
require_once './backend/db_connection.php';

// Make sure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: admin_login.php");
    exit;
}

$role = $_SESSION['role'];       // "admin" or "superadmin"
$userCampus = $_SESSION['campus']; // Campus from users table

// Initialize counts
$total_students = 0;
$today_visits = 0;
$total_health_info = 0;
$total_users = 0;
$this_month_students = 0;
$campus_counts = [];
// Total Admin
$result = $conn->query("SELECT COUNT(*) FROM users");
if ($row = $result->fetch_row()) $total_users = $row[0];

// ---------------- Students ----------------
if ($role === 'super_admin') {
    // No campus restriction
    $result = $conn->query("SELECT COUNT(*) FROM students");
    if ($row = $result->fetch_row()) $total_students = $row[0];

    $student_campus_counts = [];
    $result = $conn->query("SELECT campus, COUNT(*) AS count FROM students GROUP BY campus");
    while ($row = $result->fetch_assoc()) {
        $student_campus_counts[$row['campus']] = $row['count'];
    }

    $result = $conn->query("SELECT COUNT(*) FROM student_health_info");
    if ($row = $result->fetch_row()) $total_health_info = $row[0];

    $result = $conn->query("SELECT COUNT(*) FROM students WHERE MONTH(created_at) = MONTH(CURRENT_DATE()) AND YEAR(created_at) = YEAR(CURRENT_DATE())");
    if ($row = $result->fetch_row()) $this_month_students = $row[0];

    $result = $conn->query("SELECT COUNT(*) FROM student_health_info WHERE DATE(created_at) = CURRENT_DATE()");
    if ($row = $result->fetch_row()) $today_visits = $row[0];

    $result = $conn->query("SELECT COUNT(*) FROM students WHERE sex = 'Male'");
    $male_students = $result->fetch_row()[0];

    $result = $conn->query("SELECT COUNT(*) FROM students WHERE sex = 'Female'");
    $female_students = $result->fetch_row()[0];

} else {
    // Admin restricted to their campus
    $stmt = $conn->prepare("SELECT COUNT(*) FROM students WHERE campus = ?");
    $stmt->bind_param("s", $userCampus);
    $stmt->execute();
    $stmt->bind_result($total_students);
    $stmt->fetch();
    $stmt->close();

    $stmt = $conn->prepare("SELECT COUNT(*) FROM student_health_info sh
                            JOIN students s ON sh.student_id = s.id
                            WHERE s.campus = ?");
    $stmt->bind_param("s", $userCampus);
    $stmt->execute();
    $stmt->bind_result($total_health_info);
    $stmt->fetch();
    $stmt->close();

    $stmt = $conn->prepare("SELECT COUNT(*) FROM students WHERE campus = ? 
                            AND MONTH(created_at) = MONTH(CURRENT_DATE()) 
                            AND YEAR(created_at) = YEAR(CURRENT_DATE())");
    $stmt->bind_param("s", $userCampus);
    $stmt->execute();
    $stmt->bind_result($this_month_students);
    $stmt->fetch();
    $stmt->close();

    $stmt = $conn->prepare("SELECT COUNT(*) FROM student_health_info sh 
                            JOIN students s ON sh.student_id = s.id
                            WHERE s.campus = ? AND DATE(sh.created_at) = CURRENT_DATE()");
    $stmt->bind_param("s", $userCampus);
    $stmt->execute();
    $stmt->bind_result($today_visits);
    $stmt->fetch();
    $stmt->close();

    $stmt = $conn->prepare("SELECT COUNT(*) FROM students WHERE campus = ? AND sex = 'Male'");
    $stmt->bind_param("s", $userCampus);
    $stmt->execute();
    $stmt->bind_result($male_students);
    $stmt->fetch();
    $stmt->close();

    $stmt = $conn->prepare("SELECT COUNT(*) FROM students WHERE campus = ? AND sex = 'Female'");
    $stmt->bind_param("s", $userCampus);
    $stmt->execute();
    $stmt->bind_result($female_students);
    $stmt->fetch();
    $stmt->close();

    // Only show the admin’s campus in the cards
    $student_campus_counts = [$userCampus => $total_students];
}

// ---------------- Employees ----------------
if ($role === 'super_admin') {
    $result = $conn->query("SELECT COUNT(*) FROM employees");
    $total_employees = $result->fetch_row()[0];

    $result = $conn->query("SELECT COUNT(*) FROM employees WHERE sex = 'Male'");
    $male_employees = $result->fetch_row()[0];

    $result = $conn->query("SELECT COUNT(*) FROM employees WHERE sex = 'Female'");
    $female_employees = $result->fetch_row()[0];

    $employee_campus_counts = [];
    $result = $conn->query("SELECT campus, COUNT(*) AS count FROM employees GROUP BY campus");
    while ($row = $result->fetch_assoc()) {
        $employee_campus_counts[$row['campus']] = $row['count'];
    }
} else {
    $stmt = $conn->prepare("SELECT COUNT(*) FROM employees WHERE campus = ?");
    $stmt->bind_param("s", $userCampus);
    $stmt->execute();
    $stmt->bind_result($total_employees);
    $stmt->fetch();
    $stmt->close();

    $stmt = $conn->prepare("SELECT COUNT(*) FROM employees WHERE campus = ? AND sex = 'Male'");
    $stmt->bind_param("s", $userCampus);
    $stmt->execute();
    $stmt->bind_result($male_employees);
    $stmt->fetch();
    $stmt->close();

    $stmt = $conn->prepare("SELECT COUNT(*) FROM employees WHERE campus = ? AND sex = 'Female'");
    $stmt->bind_param("s", $userCampus);
    $stmt->execute();
    $stmt->bind_result($female_employees);
    $stmt->fetch();
    $stmt->close();

    $employee_campus_counts = [$userCampus => $total_employees];
}
?>