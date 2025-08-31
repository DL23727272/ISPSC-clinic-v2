<?php
require_once './backend/admin_reports.php';

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reports Dashboard</title>
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
    <link rel="stylesheet" href="./assets/css/admin.css">
    <style>
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
            <a href="dashboard.php" class="menu-item <?= ($currentPage == 'dashboard.php') ? 'active' : '' ?>">
                <i class="fas fa-tachometer-alt"></i><span>Dashboard</span>
            </a>
            <a href="patients.php" class="menu-item <?= ($currentPage == 'patients.php') ? 'active' : '' ?>">
                <i class="fas fa-users"></i><span>Patient Informations</span>
            </a>
            <a href="health_records.php" class="menu-item <?= ($currentPage == 'health_records.php') ? 'active' : '' ?>">
                <i class="fas fa-clipboard-list"></i><span>Health Informations</span>
            </a>
            <a href="reports.php" class="menu-item <?= ($currentPage == 'reports.php') ? 'active' : '' ?>">
                <i class="fas fa-chart-line"></i><span>Reports & Analytics</span>
            </a>
            <!-- <a href="#" class="menu-item <?= ($currentPage == 'settings.php') ? 'active' : '' ?>">
                <i class="fas fa-cog"></i><span>Settings</span>
            </a> -->
        </div>
    </nav>

    <main class="main-content mt-5">
        <div class="container mt-5">

        

           <div class="container mt-4">
                <h2>Reports & Analytics <?= ($role !== 'super_admin') ? "- ".htmlspecialchars($userCampus) : ""; ?></h2>

                 <div class="info-cards">

                    <!-- Total Health Info -->
                    <div class="info-card">
                        <div class="card-icon yellow"><i class="fas fa-file-alt"></i></div>
                        <div class="card-content">
                        <h5>Total Health Inputs</h5>
                            <h2><?= $totalHealthInputs ?></h2>
                        </div>
                    </div>
                    <div class="info-card">
                        <div class="card-icon blue"><i class="fas fa-users"></i></div>
                        <div class="card-content">
                        <h5>Students</h5>
                            <h2><?= $studentCount ?></h2>
                        </div>
                    </div>
                    <div class="info-card">
                        <div class="card-icon purple"><i class="fas fa-users"></i></div>
                        <div class="card-content">
                            <h5>Employees</h5>
                                <h2><?= $employeeCount ?></h2>
                        </div>
                    </div>
                 </div>
                  
                 <hr class="mt-5">
                

                

                <!-- Disease Prevalence Chart -->
                <canvas id="diseaseChart" height="120"></canvas>
            </div>


            <hr class="mt-5">
             <div class="container">

                <!-- Students Row -->
                <h3 class="mb-3">Students</h3>
                    <div class="info-cards">
                            <div class="info-card">
                                <div class="card-icon red"><i class="fas fa-users"></i></div>
                                <div class="card-content">
                            <h4>Main Campus</h4>
                                <p>Total: <?= $studentsByCampus['MAIN CAMPUS']['total'] ?? 0 ?></p>
                                <p><i class="fas fa-male"></i> Male: <?= $studentsByCampus['MAIN CAMPUS']['male'] ?? 0 ?></p>
                                <p><i class="fas fa-female"></i> Female: <?= $studentsByCampus['MAIN CAMPUS']['female'] ?? 0 ?></p>
                                </div>
                            </div>
                            <div class="info-card">
                                <div class="card-icon yellow"><i class="fas fa-users"></i></div>
                                <div class="card-content">
                                <h4>Santa Maria</h4>
                                        <p>Total: <?= $studentsByCampus['SANTA MARIA']['total'] ?? 0 ?></p>
                                        <p><i class="fas fa-male"></i> Male: <?= $studentsByCampus['SANTA MARIA']['male'] ?? 0 ?></p>
                                        <p><i class="fas fa-female"></i> Female: <?= $studentsByCampus['SANTA MARIA']['female'] ?? 0 ?></p>
                                </div>
                            </div>

                            <div class="info-card">
                                <div class="card-icon blue"><i class="fas fa-users"></i></div>
                                <div class="card-content">
                                <h4>Narvacan</h4>
                                        <p>Total: <?= $studentsByCampus['NARVACAN']['total'] ?? 0 ?></p>
                                        <p><i class="fas fa-male"></i> Male: <?= $studentsByCampus['NARVACAN']['male'] ?? 0 ?></p>
                                        <p><i class="fas fa-female"></i> Female: <?= $studentsByCampus['NARVACAN']['female'] ?? 0 ?></p>
                                </div>
                            </div>
                    </div>
                    <div class="info-cards mt-3">
                        <div class="info-card">
                            <div class="card-icon green"><i class="fas fa-users"></i></div>
                            <div class="card-content">
                        <h4>Santiago</h4>
                                    <p>Total: <?= $studentsByCampus['SANTIAGO']['total'] ?? 0 ?></p>
                                    <p><i class="fas fa-male"></i> Male: <?= $studentsByCampus['SANTIAGO']['male'] ?? 0 ?></p>
                                    <p><i class="fas fa-female"></i> Female: <?= $studentsByCampus['SANTIAGO']['female'] ?? 0 ?></p>
                            </div>
                        </div>
                        <div class="info-card">
                            <div class="card-icon red"><i class="fas fa-users"></i></div>
                            <div class="card-content">
                            <h4>Tagudin</h4>
                                    <p>Total: <?= $studentsByCampus['TAGUDIN']['total'] ?? 0 ?></p>
                                    <p><i class="fas fa-male"></i> Male: <?= $studentsByCampus['TAGUDIN']['male'] ?? 0 ?></p>
                                    <p><i class="fas fa-female"></i> Female: <?= $studentsByCampus['TAGUDIN']['female'] ?? 0 ?></p>
                            </div>
                        </div>

                        <div class="info-card">
                            <div class="card-icon purple"><i class="fas fa-users"></i></div>
                            <div class="card-content">
                            <h4>Candon</h4>
                                    <p>Total: <?= $studentsByCampus['CANDON']['total'] ?? 0 ?></p>
                                    <p><i class="fas fa-male"></i> Male: <?= $studentsByCampus['CANDON']['male'] ?? 0 ?></p>
                                    <p><i class="fas fa-female"></i> Female: <?= $studentsByCampus['CANDON']['female'] ?? 0 ?></p>
                            </div>
                        </div>
                        <div class="info-card">
                            <div class="card-icon yellow"><i class="fas fa-users"></i></div>
                            <div class="card-content">
                            <h4>Cervantes</h4>
                                    <p>Total: <?= $studentsByCampus['CERVANTES']['total'] ?? 0 ?></p>
                                    <p><i class="fas fa-male"></i> Male: <?= $studentsByCampus['CERVANTES']['male'] ?? 0 ?></p>
                                    <p><i class="fas fa-female"></i> Female: <?= $studentsByCampus['CERVANTES']['female'] ?? 0 ?></p>
                            </div>
                        </div>
                    </div>


                    <hr class="mt-5">
                   

                <!-- Employees Row -->
                <h3 class="mb-3 mt-5">Employees</h3>
                    <div class="info-cards">
                            <div class="info-card">
                                <div class="card-icon blue"><i class="fas fa-user-tie"></i></div>
                                <div class="card-content">
                                <h4>Main Campus</h4>
                                    <p>Total: <?= $employeesByCampus['MAIN CAMPUS']['total'] ?? 0 ?></p>
                                    <p><i class="fas fa-male"></i> Male: <?= $employeesByCampus['MAIN CAMPUS']['male'] ?? 0 ?></p>
                                    <p><i class="fas fa-female"></i> Female: <?= $employeesByCampus['MAIN CAMPUS']['female'] ?? 0 ?></p>
                                </div>
                            </div>
                            <div class="info-card">
                                <div class="card-icon blue"><i class="fas fa-user-tie"></i></div>
                                <div class="card-content">
                                <h4>Santa Maria</h4>
                                    <p>Total: <?= $employeesByCampus['SANTA MARIA']['total'] ?? 0 ?></p>
                                    <p><i class="fas fa-male"></i> Male: <?= $employeesByCampus['SANTA MARIA']['male'] ?? 0 ?></p>
                                    <p><i class="fas fa-female"></i> Female: <?= $employeesByCampus['SANTA MARIA']['female'] ?? 0 ?></p>
                                </div>
                            </div>
                            

                            <div class="info-card">
                                <div class="card-icon green"><i class="fas fa-user-tie"></i></div>
                                <div class="card-content">
                                    <h4>Narvacan</h4>
                                    <p>Total: <?= $employeesByCampus['NARVACAN']['total'] ?? 0 ?></p>
                                    <p><i class="fas fa-male"></i> Male: <?= $employeesByCampus['NARVACAN']['male'] ?? 0 ?></p>
                                    <p><i class="fas fa-female"></i> Female: <?= $employeesByCampus['NARVACAN']['female'] ?? 0 ?></p>
                                </div>
                            </div>
                    </div>
                    <div class="info-cards mt-3">
                            <div class="info-card">
                                <div class="card-icon red"><i class="fas fa-user-tie"></i></div>
                                <div class="card-content">
                                    <h4>Santiago</h4>
                                    <p>Total: <?= $employeesByCampus['SANTIAGO']['total'] ?? 0 ?></p>
                                    <p><i class="fas fa-male"></i> Male: <?= $employeesByCampus['SANTIAGO']['male'] ?? 0 ?></p>
                                    <p><i class="fas fa-female"></i> Female: <?= $employeesByCampus['SANTIAGO']['female'] ?? 0 ?></p>
                                </div>
                            </div>
                            <div class="info-card">
                                <div class="card-icon blue"><i class="fas fa-user-tie"></i></div>
                                <div class="card-content">
                                <h4>Tagudin</h4>
                                    <p>Total: <?= $employeesByCampus['TAGUDIN']['total'] ?? 0 ?></p>
                                    <p><i class="fas fa-male"></i> Male: <?= $employeesByCampus['TAGUDIN']['male'] ?? 0 ?></p>
                                    <p><i class="fas fa-female"></i> Female: <?= $employeesByCampus['TAGUDIN']['female'] ?? 0 ?></p>
                                </div>
                            </div>
                            

                            <div class="info-card">
                                <div class="card-icon green"><i class="fas fa-user-tie"></i></div>
                                <div class="card-content">
                                <h4>Candon</h4>
                                    <p>Total: <?= $employeesByCampus['CANDON']['total'] ?? 0 ?></p>
                                    <p><i class="fas fa-male"></i> Male: <?= $employeesByCampus['CANDON']['male'] ?? 0 ?></p>
                                    <p><i class="fas fa-female"></i> Female: <?= $employeesByCampus['CANDON']['female'] ?? 0 ?></p>
                                </div>
                            </div>
                            <div class="info-card">
                                <div class="card-icon green"><i class="fas fa-user-tie"></i></div>
                                <div class="card-content">
                                    <h4>Cervantes</h4>
                                    <p>Total: <?= $employeesByCampus['CERVANTES']['total'] ?? 0 ?></p>
                                    <p><i class="fas fa-male"></i> Male: <?= $employeesByCampus['CERVANTES']['male'] ?? 0 ?></p>
                                    <p><i class="fas fa-female"></i> Female: <?= $employeesByCampus['CERVANTES']['female'] ?? 0 ?></p>
                                </div>
                            </div>
                    </div>


            </div>




        </div>
    </main>
</div>


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('diseaseChart');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: <?= json_encode(array_keys($studentStats)) ?>,
            datasets: [
                {
                    label: 'Students',
                    data: <?= json_encode(array_values($studentStats)) ?>,
                    borderColor: 'rgba(54, 162, 235, 1)',
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    tension: 0.3,
                    fill: false,
                    pointBackgroundColor: 'rgba(54, 162, 235, 1)',
                    pointRadius: 5
                },
                {
                    label: 'Employees',
                    data: <?= json_encode(array_values($employeeStats)) ?>,
                    borderColor: 'rgba(255, 99, 132, 1)',
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    tension: 0.3,
                    fill: false,
                    pointBackgroundColor: 'rgba(255, 99, 132, 1)',
                    pointRadius: 5
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'top' },
                title: { display: true, text: 'Disease Prevalence (Students vs Employees)' }
            },
            scales: {
                y: { beginAtZero: true }
            }
        }
    });


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
