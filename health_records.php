<?php
require_once './backend/admin_health_records.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Health Records</title>
        <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
      rel="stylesheet"
    />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link rel="icon" type="image/x-icon" href="img/logo.ico" />
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="assets/css/admin.css">
    <style>

          table {
            width: 100%;
            border-collapse: collapse;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0,0,0,0.05);
            background: #fff;
            border: 1px solid #ccc; /* outer border */
        }
        table thead {
            background: var(--secondary-color);
            color: #fff;
        }
        table th, table td {
            padding: 12px 10px;
            text-align: left;
            font-size: 14px;
            border-bottom: 1px solid #ddd; /* horizontal grid lines */
            border-right: 1px solid #ddd;  /* vertical grid lines */
        }
        table th:last-child, table td:last-child {
            border-right: none; /* remove right border on last column */
        }
        table tbody tr:nth-child(even) {
            background: #f9f9f9;
        }
        table tbody tr:hover {
            background: #e8f0fe;
        }
        table th:first-child, table td:first-child {
            border-left: 1px solid #ddd; /* left border for first column */
        }
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --employee-color: #27ae60;
            --admin-color: #e74c3c;
            --warning-color: #f39c12;
            --purple-color: #9b59b6;
            --light-gray: #ecf0f1;
            --dark-gray: #7f8c8d;
            --white: #ffffff;
            --box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            --sidebar-width: 280px;
        }
       
    </style>
</head>
<body>
<div class="dashboard-container">
    <header class="header">
        <div class="header-left">
            <div class="logo">
                <i class="fas fa-chart-bar"></i>
                <span>ISPSC CLINICA</span>
            </div>
            <span class="header-title">
                 Admin Dashboard
                <?php if (isset($_SESSION['campus'])): ?>
                    - <?= htmlspecialchars($_SESSION['campus']); ?>
                <?php endif; ?> Campus
            </span>
        </div>
        <div class="header-right">
            <button class="user-info" id="user-info-btn">
                <span>
                    <?= isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : "Admin User"; ?>
                </span>
            </button>
            <i class="fas fa-sign-out-alt logout-btn" id="logout-icon"></i>
        </div>
    </header>

    <div class="logout-message" id="logout-message"><i class="fas fa-check-circle"></i><span>Successfully logged out! Redirecting...</span></div>

   <?php $currentPage = basename($_SERVER['PHP_SELF']); ?>
    <nav class="sidebar">
        <div class="sidebar-menu">
            <a href="./dashboard" class="menu-item <?= ($currentPage == './dashboard') ? 'active' : '' ?>">
                <i class="fas fa-tachometer-alt"></i><span>Dashboard</span>
            </a>
            <a href="./patients" class="menu-item <?= ($currentPage == './patients') ? 'active' : '' ?>">
                <i class="fas fa-users"></i><span>Patient Informations</span>
            </a>
            <a href="./health_records" class="menu-item <?= ($currentPage == './health_records') ? 'active' : '' ?>">
                <i class="fas fa-clipboard-list"></i><span>Health Informations</span>
            </a>
            <a href="./reports" class="menu-item <?= ($currentPage == './reports') ? 'active' : '' ?>">
                <i class="fas fa-chart-line"></i><span>Reports & Analytics</span>
            </a>
            <!-- <a href="#" class="menu-item <?= ($currentPage == 'settings.php') ? 'active' : '' ?>">
                <i class="fas fa-cog"></i><span>Settings</span>
            </a> -->
        </div>
    </nav>

        <main class="main-content mt-5">
            <div class="container mt-5">
                <h2 class="mb-4">Student Health Information Records</h2>

                <form class="row g-3 mb-3" method="get">
                    <div class="col-md-3">
                        <input type="text" name="search" class="form-control" 
                            placeholder="Search by ID or Name" 
                            value="<?= htmlspecialchars($search) ?>">
                    </div>

                    <div class="col-md-3">
                        <select name="type" class="form-select" onchange="this.form.submit()">
                            <option value="student" <?= $type === 'student' ? 'selected' : '' ?>>Students</option>
                            <option value="employee" <?= $type === 'employee' ? 'selected' : '' ?>>Employees</option>
                        </select>
                    </div>


                    
                    <div class="col-md-3">
                        <?php if ($role === 'super_admin'): ?>
                            <!-- Super Admin: Can select from all campuses -->
                            <select name="campus" class="form-select" onchange="this.form.submit()">
                                <option value="">All Campuses</option>
                                <?php while($c = $campus_result->fetch_assoc()): ?>
                                    <option value="<?= htmlspecialchars($c['campus']) ?>" 
                                            <?= $campus == $c['campus'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($c['campus']) ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        <?php else: ?>
                            <!-- Campus Admin: Show their campus (disabled) -->
                            <select class="form-select" disabled>
                                <option selected><?= htmlspecialchars($adminCampus) ?></option>
                            </select>
                            <!-- Pass their campus as hidden field so it's still submitted -->
                            <input type="hidden" name="campus" value="<?= htmlspecialchars($adminCampus) ?>">
                        <?php endif; ?>
                    </div>




                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-filter"></i> Filter</button>
                        <a href="health_records.php" class="btn btn-secondary">Reset</a>
                    </div>
                </form>


                <table class="table table-bordered table-striped">
                <thead>
                        <tr>
                            <th><?= $type === 'employee' ? 'Employee ID' : 'Student ID' ?></th>
                            <th>Name</th>
                            <th>Campus</th>
                            <th>Date Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if($result->num_rows > 0): ?>
                            <?php while($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?= htmlspecialchars($row['person_id']) ?></td>
                                    <td><?= htmlspecialchars($row['first_name'] . ' ' . $row['last_name']) ?></td>
                                    <td><?= htmlspecialchars($row['campus']) ?></td>
                                    <td><?= htmlspecialchars($row['created_at']) ?></td>
                                    <td>
                                        
                                        <?php if($type === 'student'): ?>
                                            <a href="./backend/pdf.php?student_id=<?= $row['person_id'] ?>&type=<?= $type ?>" target="_blank" class="btn btn-sm btn-success">Print</a>
                                            <a href="./backend/edit_health_info?id=<?= $row['id'] ?>&type=<?= $type ?>" class="btn btn-sm btn-primary">Edit</a>
                                            <!-- <a href="delete_health.php?id=<?= $row['id'] ?>&type=<?= $type ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this record?')">Delete</a> -->
                                        <?php elseif($type === 'employee'): ?>
                                            <a href="./backend/pdf.php?id=<?= $row['id'] ?>&type=<?= $type ?>" target="_blank" class="btn btn-sm btn-success">Print</a>
                                            <a href="./backend/edit_employee_health_info?id=<?= $row['id'] ?>&type=<?= $type ?>" class="btn btn-sm btn-primary">Edit</a>
                                            <!-- <a href="delete_health.php?id=<?= $row['id'] ?>&type=<?= $type ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this record?')">Delete</a> -->
                                        <?php endif; ?>
                                    </td>

                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="5" class="text-center">No records found.</td></tr>
                        <?php endif; ?>
                    </tbody>


                </table>
            </div>
        </main>

</div>

<script>
document.querySelectorAll('.menu-item').forEach(item => {
    item.addEventListener('click', function(e) {
        e.preventDefault();
        document.querySelectorAll('.menu-item').forEach(i => i.classList.remove('active'));
        this.classList.add('active');
        window.location.href = this.href; // navigate manually
    });
});


function handleLogout() {
    const logoutMessage = document.getElementById('logout-message');
    logoutMessage.classList.add('show');
    setTimeout(() => { window.location.href = 'admin_login.php'; }, 2000);
}
document.getElementById('user-info-btn').addEventListener('click', handleLogout);
document.getElementById('logout-icon').addEventListener('click', handleLogout);
</script>
</body>
</html>
