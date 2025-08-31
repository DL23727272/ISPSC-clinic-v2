<?php
require_once './backend/admin_dashboard.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
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

            <div class="info-cards">

                <!-- Total Health Info -->
                <div class="info-card">
                    <div class="card-icon yellow"><i class="fas fa-file-alt"></i></div>
                    <div class="card-content">
                        <h3>Total Health Info Entries</h3>
                        <div class="count"><?php echo $total_health_info; ?></div>
                    </div>
                </div>

                <!-- This Month Students -->
                <div class="info-card">
                    <div class="card-icon purple"><i class="fas fa-chart-bar"></i></div>
                    <div class="card-content">
                        <h3>This Month registration</h3>
                        <div class="count"><?php echo $this_month_students; ?></div>
                    </div>
                </div>

                <!-- Total Users -->
                <div class="info-card">
                    <div class="card-icon red"><i class="fas fa-user-shield"></i></div>
                    <div class="card-content">
                        <h3>Total Admin</h3>
                        <div class="count"><?php echo $total_users; ?></div>
                    </div>
                </div>
            </div>

            <hr>

            <div class="info-cards">
                <!-- Total Students -->
                <div class="info-card">
                    <div class="card-icon blue"><i class="fas fa-users"></i></div>
                    <div class="card-content">
                        <h3>Total Students</h3>
                        <div class="count"><?php echo $total_students; ?></div>
                    </div>
                </div>

                <!-- Male Students -->
                <div class="info-card">
                    <div class="card-icon" style="background-color:#3498db;">
                        <i class="fas fa-male"></i>
                    </div>
                    <div class="card-content">
                        <h3>Male Students</h3>
                        <div class="count"><?php echo $male_students; ?></div>
                    </div>
                </div>

                <!-- Female Students -->
                <div class="info-card">
                    <div class="card-icon" style="background-color:#e91e63;">
                        <i class="fas fa-female"></i>
                    </div>
                    <div class="card-content">
                        <h3>Female Students</h3>
                        <div class="count"><?php echo $female_students; ?></div>
                    </div>
                </div>
                 <!-- Campus Cards (Students) -->
                <?php foreach($student_campus_counts as $campus => $count): ?>
                <div class="info-card">
                    <div class="card-icon campus"><i class="fas fa-school"></i></div>
                    <div class="card-content">
                        <h3><?php echo htmlspecialchars($campus); ?> Students</h3>
                        <div class="count"><?php echo $count; ?></div>
                    </div>
                </div>
                <?php endforeach; ?>

                

            </div>

            <hr>

            <div class="info-cards">
                <!-- Total Employees -->
                <div class="info-card">
                    <div class="card-icon blue"><i class="fas fa-users"></i></div>
                    <div class="card-content">
                        <h3>Total Employees</h3>
                        <div class="count"><?php echo $total_employees; ?></div>
                    </div>
                </div>

                <!-- Male Employees -->
                <div class="info-card">
                    <div class="card-icon" style="background-color:#3498db;">
                        <i class="fas fa-male"></i>
                    </div>
                    <div class="card-content">
                        <h3>Male Employees</h3>
                        <div class="count"><?php echo $male_employees; ?></div>
                    </div>
                </div>

                <!-- Female Employees -->
                <div class="info-card">
                    <div class="card-icon" style="background-color:#e91e63;">
                        <i class="fas fa-female"></i>
                    </div>
                    <div class="card-content">
                        <h3>Female Employees</h3>
                        <div class="count"><?php echo $female_employees; ?></div>
                    </div>
                </div>

                <!-- Campus Cards (Employees) -->
                <?php foreach($employee_campus_counts as $campus => $count): ?>
                <div class="info-card">
                    <div class="card-icon campus"><i class="fas fa-school"></i></div>
                    <div class="card-content">
                        <h3><?php echo htmlspecialchars($campus); ?> Employees</h3>
                        <div class="count"><?php echo $count; ?></div>
                    </div>
                </div>
                <?php endforeach; ?>

                

            </div>
            <hr>

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
    setTimeout(() => { window.location.href = './admin_login'; }, 2000);
}
document.getElementById('user-info-btn').addEventListener('click', handleLogout);
document.getElementById('logout-icon').addEventListener('click', handleLogout);
</script>
</body>
</html>
