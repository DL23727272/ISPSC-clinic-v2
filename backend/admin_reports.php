<?php
require_once './backend/db_connection.php';
session_start();

$role = $_SESSION['role'];
$userCampus = $_SESSION['campus'] ?? null;

// Restrict data if campus admin
$campusCondition = "";
if ($role !== 'super_admin' && $userCampus) {
    $campusCondition = " WHERE campus = '" . $conn->real_escape_string($userCampus) . "'";
}

// --- Students Count ---
$studentCount = $conn->query("SELECT COUNT(*) as total FROM students $campusCondition")->fetch_assoc()['total'];

// --- Employees Count ---
$employeeCount = $conn->query("SELECT COUNT(*) as total FROM employees $campusCondition")->fetch_assoc()['total'];

// --- Common Diseases (students + employees) ---
$diseases = ['hypertension','diabetes','asthma','cancer','tuberculosis','heart_disease'];

$diseaseStats = [];
$studentStats = [];
$employeeStats = [];

foreach ($diseases as $d) {
    $studentQ = $conn->query("SELECT COUNT(*) as c FROM student_health_info 
                              INNER JOIN students s ON s.student_id=student_health_info.student_id 
                              WHERE $d=1 " . ($campusCondition ? "AND s.campus='".$conn->real_escape_string($userCampus)."'" : ""));
    $employeeQ = $conn->query("SELECT COUNT(*) as c FROM employee_health_info 
                               INNER JOIN employees e ON e.employee_id=employee_health_info.employee_id 
                               WHERE $d=1 " . ($campusCondition ? "AND e.campus='".$conn->real_escape_string($userCampus)."'" : ""));
    
    $studentStats[$d] = $studentQ->fetch_assoc()['c'];
    $employeeStats[$d] = $employeeQ->fetch_assoc()['c'];
}


// --- Total Health Inputs (students + employees) ---
$totalHealthInputs = $conn->query("
    SELECT (
        (SELECT COUNT(*) FROM student_health_info sh
         INNER JOIN students s ON s.student_id=sh.student_id
         " . ($campusCondition ? "WHERE s.campus='".$conn->real_escape_string($userCampus)."'" : "") . ")
        +
        (SELECT COUNT(*) FROM employee_health_info eh
         INNER JOIN employees e ON e.employee_id=eh.employee_id
         " . ($campusCondition ? "WHERE e.campus='".$conn->real_escape_string($userCampus)."'" : "") . ")
    ) as total
")->fetch_assoc()['total'];

// --- Per Campus Counts (Students + Employees) ---
$campuses = ['MAIN CAMPUS','SANTA MARIA','NARVACAN','SANTIAGO','TAGUDIN','CANDON','CERVANTES'];

// Students
$studentsByCampus = [];
$result = $conn->query("
    SELECT campus, sex, COUNT(*) as total 
    FROM students
    GROUP BY campus, sex
");
while ($row = $result->fetch_assoc()) {
    $campus = strtoupper($row['campus']);
    $gender = strtolower($row['sex']);
    $studentsByCampus[$campus][$gender] = $row['total'];
    $studentsByCampus[$campus]['total'] = 
        ($studentsByCampus[$campus]['total'] ?? 0) + $row['total'];
}

// Employees
$employeesByCampus = [];
$result = $conn->query("
    SELECT campus, sex, COUNT(*) as total 
    FROM employees
    GROUP BY campus, sex
");
while ($row = $result->fetch_assoc()) {
    $campus = strtoupper($row['campus']);
    $gender = strtolower($row['sex']);
    $employeesByCampus[$campus][$gender] = $row['total'];
    $employeesByCampus[$campus]['total'] = 
        ($employeesByCampus[$campus]['total'] ?? 0) + $row['total'];
}

?>