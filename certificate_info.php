<?php
// Only allow admin/superadmin
session_start();
require_once __DIR__ . './backend/db_connection.php';
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['admin', 'superadmin', 'super_admin'])) {
    header("Location: admin_login.php");
    exit;
}

// Fetch certificate submissions
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$where = '';
$params = [];
if ($search !== '') {
    $where = "WHERE name LIKE ?";
    $params[] = "%$search%";
    $sql = "SELECT id, name, age, sex, year, created_at, purpose, bp, hr, rr, temp, vax FROM student_certificates $where ORDER BY created_at DESC LIMIT 100";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $params[0]);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $sql = "SELECT id, name, age, sex, year, created_at, purpose, bp, hr, rr, temp, vax FROM student_certificates ORDER BY created_at DESC LIMIT 100";
    $result = $conn->query($sql);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Certificate Info</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="icon" type="image/x-icon" href="img/logo.ico" />
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
        * {margin:0;padding:0;box-sizing:border-box;}
        body {font-family:'Roboto',sans-serif;background-color:var(--light-gray);color:var(--primary-color);}
        .dashboard-container {display:flex;min-height:100vh;}
        .header {position:fixed;top:0;left:0;right:0;height:70px;background:var(--white);border-bottom:1px solid #e0e0e0;display:flex;align-items:center;justify-content:space-between;padding:0 2rem;z-index:1000;box-shadow:var(--box-shadow);}
        .header-left {display:flex;align-items:center;gap:1rem;}
        .logo {display:flex;align-items:center;gap:0.5rem;font-size:1.25rem;font-weight:700;color:var(--secondary-color);}
        .logo i {font-size:1.5rem;}
        .header-title {font-size:1.1rem;color:var(--dark-gray);font-weight:400;}
        .header-right {display:flex;align-items:center;gap:1rem;}
        .user-info {display:flex;align-items:center;gap:0.5rem;color:var(--dark-gray);font-size:0.95rem;cursor:pointer;padding:0.5rem 0.75rem;border-radius:6px;transition:all 0.3s ease;border:none;background:transparent;font-family:'Roboto',sans-serif;}
        .user-info:hover {background-color:var(--light-gray);color:var(--primary-color);}
        .logout-btn {color:var(--dark-gray);font-size:1.1rem;cursor:pointer;transition:color 0.3s ease;}
        .logout-btn:hover {color:var(--admin-color);}
        .logout-message {position:fixed;top:90px;right:2rem;background:#27ae60;color:#fff;padding:1rem 1.5rem;border-radius:8px;box-shadow:var(--box-shadow);font-size:0.95rem;font-weight:500;display:none;z-index:1001;animation:slideIn 0.3s ease;}
        .logout-message.show {display:flex;align-items:center;gap:0.5rem;}
        @keyframes slideIn {from{transform:translateX(100%);opacity:0;}to{transform:translateX(0);opacity:1;}}
        .sidebar {width:var(--sidebar-width);background:var(--white);border-right:1px solid #e0e0e0;padding-top:70px;position:fixed;height:100vh;overflow-y:auto;}
        .sidebar-menu {padding:2rem 0;}
        .menu-item {display:flex;align-items:center;gap:1rem;padding:1rem 2rem;color:var(--dark-gray);text-decoration:none;font-size:0.95rem;font-weight:500;transition:all 0.3s ease;border-left:3px solid transparent;}
        .menu-item:hover {background-color:var(--light-gray);color:var(--primary-color);}
        .menu-item.active {background-color:var(--secondary-color);color:#fff;border-left-color:var(--secondary-color);}
        .menu-item i {font-size:1.1rem;width:20px;}
        .main-content {flex:1;margin-left:var(--sidebar-width);padding-top:70px;padding:70px 2rem 2rem 2rem;}
        .table th, .table td { vertical-align: middle; }
        /* Modern table style */
        .table {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 2px 12px #0001;
            overflow: hidden;
        }
        .table thead {
            background: #f8f9fa;
            font-weight: 700;
        }
        .table th, .table td {
            border: none !important;
        }
        .table-striped > tbody > tr:nth-of-type(odd) {
            background-color: #f6fafd;
        }
        .table-bordered {
            border: none !important;
        }
        .table-responsive {
            border-radius: 18px;
            box-shadow: 0 2px 12px #0001;
            background: #fff;
            padding: 0.5rem 0.5rem 0 0.5rem;
        }
        /* Filter bar style */
        .filter-bar {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-bottom: 1.2rem;
        }
        .filter-bar input,
        .filter-bar select {
            border-radius: 12px !important;
            box-shadow: none;
        }
        .filter-bar .btn {
            border-radius: 10px !important;
            font-weight: 500;
        }
        /* Action buttons style */
        .action-btn {
            border-radius: 8px !important;
            font-weight: 500;
            min-width: 70px;
            margin-right: 2px;
        }
        .action-btn.view { background: #00cfff; color: #fff; }
        .action-btn.print { background: #2ecc71; color: #fff; }
        .action-btn.edit { background: #2980ef; color: #fff; }
        .action-btn.delete { background: #e74c3c; color: #fff; }
        .action-btn:last-child { margin-right: 0; }
        @media(max-width:768px){.sidebar{transform:translateX(-100%);transition:transform 0.3s ease;}.main-content{margin-left:0;padding:70px 1rem 2rem 1rem;}.header{padding:0 1rem;}.user-info span{display:none;}.logout-message{right:1rem;left:1rem;}}
        @media(max-width: 900px) {
            .sidebar { position: fixed; left: 0; top: 0; height: 100vh; z-index: 2000; width: var(--sidebar-width); background: var(--white); border-right: 1px solid #e0e0e0; transform: translateX(-100%); transition: transform 0.3s ease; box-shadow: var(--box-shadow); }
            .sidebar.open { transform: translateX(0); }
            .main-content { margin-left: 0; padding: 70px 1rem 2rem 1rem; }
            .header { padding: 0 1rem; }
            .user-info span { display: none; }
            .logout-message { right: 1rem; left: 1rem; }
            .burger-menu { display: inline-block !important; }
        }
        .burger-menu { display: none; }

        /* Add these styles for the certificate modal */
        .cert-container { 
            max-width: 900px; 
            margin: 2rem auto; 
            background: #fff; 
            padding: 2rem 2.5rem; 
            border-radius: 18px; 
            box-shadow: 0 2px 12px #0001; 
        }
        .cert-header { margin-bottom: 1.5rem; }
        .cert-logo { width: 70px; }
        .cert-title { font-size: 1.1rem; font-weight: 600; margin-bottom: 0.5rem; }
        .cert-main-title { font-size: 2rem; font-weight: 700; margin-bottom: 1.2rem; }
        .cert-signature { margin-top: 2rem; text-align: right; }
        .cert-label { font-weight: 500; }
        .cert-value { font-weight: 400; }
        .diagnosis { font-weight: 700; text-decoration: underline; }
        .remarks { font-weight: 700; }
        @media (max-width: 900px) {
            .cert-container { padding: 1rem; }
            .cert-logo { width: 50px; }
        }
        /* Modal body padding for certificate */
        #modalCertContent {
            background: #f7f9fa;
            padding: 0;
        }
    </style>
</head>
<body>
<div class="dashboard-container">
    <header class="header">
        <div class="header-left">
            <!-- Burger menu for mobile -->
            <button class="burger-menu d-md-none" id="burger-menu" aria-label="Open sidebar" style="background:none;border:none;outline:none;cursor:pointer;margin-right:1rem;font-size:1.7rem;color:var(--secondary-color);">
                <i class="fas fa-bars"></i>
            </button>
            <div class="logo">
                <i class="fas fa-chart-bar"></i>
                <span>ISPSC CLINICA</span>
            </div>
            <span class="header-title">
                 Certificate Info
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
    <nav class="sidebar" id="sidebar">
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
            <a href="certificate_info.php" class="menu-item <?= ($currentPage == 'certificate_info.php') ? 'active' : '' ?>">
                <i class="fas fa-certificate"></i><span>Certificate Info</span>
            </a>
        </div>
    </nav>
    <main class="main-content">
        <div class="container mt-5">
            <h2 class="mb-4">Submitted Medical Certificates</h2>
            <form method="get" class="mb-3 d-flex" style="max-width:400px;">
                <input type="text" name="search" class="form-control me-2" placeholder="Search by name..." value="<?= htmlspecialchars($search) ?>">
                <button type="submit" class="btn btn-primary">Search</button>
            </form>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Age</th>
                        <th>Sex</th>
                        <th>Year</th>
                        <th>Purpose</th>
                        <th>BP</th>
                        <th>HR</th>
                        <th>RR</th>
                        <th>Temp</th>
                        <th>Vax</th>
                        <th>Date Submitted</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php if ($result && $result->num_rows > 0): ?>
                    <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= !empty($row['id']) ? htmlspecialchars($row['id']) : "N/A"; ?></td>
                            <td><?= htmlspecialchars($row['name']) ?></td>
                            <td><?= htmlspecialchars($row['age']) ?></td>
                            <td><?= htmlspecialchars($row['sex']) ?></td>
                            <td><?= htmlspecialchars($row['year']) ?></td>
                            <td><?= htmlspecialchars($row['purpose']) ?></td>
                            <td><?= isset($row['bp']) ? htmlspecialchars($row['bp']) : '' ?></td>
                            <td><?= isset($row['hr']) ? htmlspecialchars($row['hr']) : '' ?></td>
                            <td><?= isset($row['rr']) ? htmlspecialchars($row['rr']) : '' ?></td>
                            <td><?= isset($row['temp']) ? htmlspecialchars($row['temp']) : '' ?></td>
                            <td><?= isset($row['vax']) ? htmlspecialchars($row['vax']) : '' ?></td>
                            <td><?= htmlspecialchars($row['created_at']) ?></td>
                            <td class="action-btns">
                                <button class="btn btn-info btn-sm view-cert-btn" data-id="<?= $row['id'] ?>" title="View"><i class="fas fa-eye"></i></button>
                                <a href="print_certificate.php?id=<?= $row['id'] ?>" class="btn btn-success btn-sm" title="Print" target="_blank"><i class="fas fa-print"></i></a>
                                <a href="edit_certificate.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm" title="Edit"><i class="fas fa-edit"></i></a>
                                <form method="post" action="delete_certificate.php" style="display:inline;" 
                                    onsubmit="return confirm('Are you sure you want to delete this certificate?');">
                                    <input type="hidden" name="id" value="<?= htmlspecialchars($row['id']) ?>">
                                    <button type="submit" class="btn btn-danger btn-sm" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="13" class="text-center">No certificates found.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>
</div>

<!-- Modal for viewing certificate -->
<div class="modal fade" id="viewCertModal" tabindex="-1" aria-labelledby="viewCertLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered">
    <div class="modal-content" style="border-radius:18px;">
      <div class="modal-header" style="border-bottom:none;">
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="modalCertContent" style="padding:0;">
        <!-- Certificate HTML will be loaded here -->
      </div>
    </div>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>

$(document).on('click', '.view-cert-btn', function() {
    var certId = $(this).data('id');
    $('#modalCertContent').html('<div class="text-center p-5"><i class="fas fa-spinner fa-spin fa-2x"></i><p>Loading...</p></div>');
    $.get('view_certificate.php', { id: certId, modal: 1 }, function(data) {
        $('#modalCertContent').html(data);
        var modal = new bootstrap.Modal(document.getElementById('viewCertModal'));
        modal.show();
    });
});

</script>
</body>
</html>
