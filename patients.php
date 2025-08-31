<?php
session_start();
require_once './backend/admin_patients.php';
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Patients Record</title>
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
    <link rel="stylesheet" href="assets/css/admin.css">

   <style>
        /* Filters/Search */
        .filters {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 20px;
        }
        .filters input[type=text],
        .filters select {
            padding: 8px 12px;
            border-radius: 6px;
            border: 1px solid #ccc;
            font-size: 14px;
            outline: none;
            transition: all 0.3s ease;
        }
        .filters input[type=text]:focus,
        .filters select:focus {
            border-color: var(--secondary-color);
            box-shadow: 0 0 5px rgba(52,152,219,0.3);
        }
        .filters button {
            padding: 8px 16px;
            border: none;
            background: var(--secondary-color);
            color: #fff;
            border-radius: 6px;
            cursor: pointer;
            transition: background 0.3s ease;
        }
        .filters button:hover {
            background: #2980b9;
        }

       /* Table with grid lines */
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
    </style>
</head>
<body>



<!-- Edit Student Modal -->
<div class="modal fade" id="editStudentModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Student Information</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="editStudentBody">
        <!-- Form will be loaded here via AJAX -->
      </div>
    </div>
  </div>
</div>


<!-- Edit Employee Modal -->
<div class="modal fade" id="editEmployeeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Employee Information</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="editEmployeeBody">
                <!-- Form will be loaded here via AJAX -->
            </div>
        </div>
    </div>
</div>








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
            <h2>Patient Records</h2>
        
                <form method="GET" class="filters row g-2 align-items-center">

                

                    <!-- Search input -->
                    <div class="col-auto">
                        <input type="text" 
                            name="search" 
                            class="form-control" 
                            placeholder="Search by Name or ID" 
                            value="<?= htmlspecialchars($search); ?>">
                    </div>

                    <!-- Type selector: Student or Employee -->
                    <div class="col-auto" style="min-width: 150px; max-width: 200px;">
                        <select name="type" class="form-select">
                            <option value="employees" <?= (!isset($_GET['type']) || $_GET['type']=='employees') ? 'selected' : '' ?>>Employees</option>
                            <option value="students" <?= (isset($_GET['type']) && $_GET['type']=='students') ? 'selected' : '' ?>>Students</option>
                        </select>
                    </div>

                <!-- Campus filter for super admins -->
                    <?php if ($role === 'super_admin'): ?>
                        <div class="col-auto" style="min-width: 180px; max-width: 220px;">
                            <select id="campusFilter" name="campus" class="form-select">
                                <option value="">-- Select Campus --</option>
                                <!-- Options will be populated by JS -->
                            </select>
                        </div>
                    <?php else: ?>

                        <input type="hidden" name="campus" value="<?= htmlspecialchars($userCampus); ?>">
                        <div class="col-auto" style="min-width: 180px; max-width: 220px;">
                            <span class="badge bg-primary p-2 d-block text-truncate"><?= htmlspecialchars($userCampus); ?></span>
                        </div>
                    <?php endif; ?>


                    <!-- Submit button -->
                    <div class="col-auto">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i> Search
                        </button>
                    </div>

                </form>






                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th><?php echo ($type === 'students') ? 'Student ID' : 'Employee ID'; ?></th>
                            <th>Name</th>
                            <th>Campus</th>
                            <th>Sex</th>
                            <th>Age</th>
                            <th>Email</th>
                            <th>Phone</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if ($result && $result->num_rows > 0): ?>
                        <?php while($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['id_value']); ?></td>
                                <td><?= htmlspecialchars($row['full_name']); ?></td>
                                <td><?= htmlspecialchars($row['campus']); ?></td>
                                <td><?= htmlspecialchars($row['sex']); ?></td>
                                <td><?= htmlspecialchars($row['age']); ?></td>
                                <td><?= htmlspecialchars($row['email']); ?></td>
                                <td><?= htmlspecialchars($row['phone_number']); ?></td>
                                <?php if($type === 'students'): ?>
                                    <td>
                                        <button class="btn btn-sm btn-warning edit-student-btn" data-student-id="<?= $row['id_value']; ?>">
                                            <i class="fas fa-edit"></i> Edit
                                        </button>
                                    </td>
                                <?php elseif($type === 'employees'): ?>
                                    <td>
                                        <button class="btn btn-sm btn-warning edit-employee-btn" data-employee-id="<?= $row['id_value']; ?>">
                                            <i class="fas fa-edit"></i> Edit
                                        </button>
                                    </td>
                                <?php endif; ?>

                                
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="8" class="text-center">No records found.</td></tr>
                    <?php endif; ?>
                    </tbody>

                </table>


            </div>
        </main>
</div>

<script>
// Make sidebar menu items toggle active
document.querySelectorAll('.menu-item').forEach(item => {
    item.addEventListener('click', function(e) {
        e.preventDefault();
        document.querySelectorAll('.menu-item').forEach(i => i.classList.remove('active'));
        this.classList.add('active');
        window.location.href = this.href; // navigate manually
    });
});

// Logout handler
function handleLogout() {
    const logoutMessage = document.getElementById('logout-message');
    logoutMessage.classList.add('show');
    setTimeout(() => { window.location.href = 'admin_login.php'; }, 2000);
}
document.getElementById('user-info-btn').addEventListener('click', handleLogout);
document.getElementById('logout-icon').addEventListener('click', handleLogout);





const data = {
  "MAIN CAMPUS": { departments: { "College of Teacher Education": { courses: ["Bachelor of Secondary Education","Bachelor of Elementary Education","Bachelor of Culture and the Arts Education","Bachelor of Physical Education"], majors: ["Science","English","Mathematics","Filipino","Social Studies","None"] }, "College of Arts and Sciences": { courses: ["Bachelor of Arts in English Language","Bachelor of Arts in Political Science","Bachelor of Science in Computer Science","Bachelor of Science in Midwifery","Bachelor of Science in Nursing"], majors: ["None"] }, "College of Business Education": { courses: ["Bachelor of Science in Business Administration","Bachelor of Science in Office Administration"], majors: ["Financial Management","Human Resources Development Management","None"] }, "School of Criminal Justice Education": { courses: ["Bachelor of Science in Criminology"], majors: ["None"] } } },
  "SANTA MARIA": { departments: { "College of Agriculture, Forestry, Engineering and Development Communication": { courses: ["Bachelor of Science in Agriculture","Bachelor of Science in Agricultural and Biosystems Engineering","Bachelor of Science in Forestry","Bachelor of Science in Agroforestry","Bachelor in Agricultural Technology","Bachelor of Science in Development Communication"], majors: ["Agronomy","Animal Husbandry","Horticulture","Post Harvest Technology","Agribusiness Management & Entrepreneurship","None"] }, "College of Teacher Education": { courses: ["Bachelor of Elementary Education","Bachelor of Secondary Education","Bachelor of Technology and Livelihood Education"], majors: ["Science","English","Mathematics","Filipino","Social Studies","Home Economics","Agro-Fishery Arts","None"] }, "College of Computing Studies": { courses: ["Bachelor of Science in Information Technology","Bachelor of Science in Information Systems"], majors: ["Web Development and Mobile Track","Networking and Cybersecurity Track","Business and Data Analytics","None"] }, "College of Business, Management and Entrepreneurship": { courses: ["Bachelor of Science in Hospitality Management"], majors: ["None"] } } },
  "NARVACAN": { departments: { "Fisheries Department": { courses: ["Bachelor of Science in Fisheries"], majors: ["None"] }, "Department of Teacher Education": { courses: ["Bachelor of Technology and Livelihood Education","Bachelor of Physical Education","Bachelor of Technical Vocational Teacher Education"], majors: ["Agri-fishery Arts","Home Economics","Fish Processing","Aquaculture","None"] } } },
  "SANTIAGO": { departments: { "Industrial Technology Department": { courses: ["Bachelor of Science in Industrial Technology","Bachelor of Science in Mechatronics Technology"], majors: ["Automotive Technology","Electrical Technology","Electronics Technology","Food Technology","Apparel Technology","None"] }, "Teacher Education Department": { courses: ["Bachelor in Technical-Vocational Teacher Education"], majors: ["Automotive Technology","Electrical Technology","Electronics Technology","Food and Service Management","Garments, Fashion, and Design"] } } },
  "TAGUDIN": { departments: { "College of Arts and Sciences": { courses: ["Bachelor of Science in Mathematics","Bachelor of Arts in Psychology","Bachelor of Arts in Social Science","Bachelor of Arts in English Language","Bachelor of Science in Information Technology","Bachelor of Science in Public Administration"], majors: ["Web Development and Mobile Track","None"] }, "College of Business, Management and Entrepreneurship": { courses: ["Bachelor of Science in Business Administration","Bachelor of Science in Entrepreneurship"], majors: ["Human Resource Development Management","Marketing Management","Financial Management","None"] }, "College of Teacher Education": { courses: ["Bachelor of Secondary Education","Bachelor of Elementary Education","Bachelor of Physical Education"], majors: ["Science","English","Mathematics","Filipino","Social Studies","None"] } } },
  "CANDON": { departments: { "College of Business and Hospitality Management": { courses: ["Bachelor of Science in Hospitality Management","Bachelor of Science in Tourism Management"], majors: ["None"] }, "College of Computing Studies": { courses: ["Bachelor of Science in Information Technology"], majors: ["None"] }, "College of Teacher Education": { courses: ["Bachelor of Secondary Education"], majors: ["Filipino"] } } },
  "CERVANTES": { departments: { "College of Arts and Science": { courses: [" Bachelor of Science in Information Technology","Bachelor of Science in Criminology"], majors: ["Web and Mobile Application","None"] }, "College of Teacher Education": { courses: ["Bachelor of Secondary Education","Bachelor of Elementary Education","Bachelor of Technology and Livelihood Education","Bachelor of Technical-Vocational Teacher Education"], majors: ["None","Mathematics","English","Science","Technology and Livelihood Education","Agri-Fishery Arts","Home Economics","Food Service Management","Agricultural Crops Production"] } } }
};

// Edit Student Modal JS
document.querySelectorAll('.edit-student-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const studentId = this.dataset.studentId;
        fetch('./backend/edit_student_form.php?student_id=' + studentId)
        .then(res => res.text())
        .then(html => {
            document.getElementById('editStudentBody').innerHTML = html;
            const modal = new bootstrap.Modal(document.getElementById('editStudentModal'));
            modal.show();

            // Cascading dropdowns
            const campusSelect = document.getElementById('campus');
            const departmentSelect = document.getElementById('department');
            const courseSelect = document.getElementById('course');
            const majorSelect = document.getElementById('major');

            function populateDepartments() {
                const selectedCampus = campusSelect.value;
                departmentSelect.innerHTML = '<option value="">SELECT DEPARTMENT</option>';
                courseSelect.innerHTML = '<option value="">SELECT COURSE</option>';
                majorSelect.innerHTML = '<option value="">SELECT MAJOR</option>';

                if(data[selectedCampus]){
                    Object.keys(data[selectedCampus].departments).forEach(dep => {
                        const option = document.createElement('option');
                        option.value = dep;
                        option.textContent = dep;
                        departmentSelect.appendChild(option);
                    });
                    const currentDep = departmentSelect.getAttribute('data-current');
                    if(currentDep) departmentSelect.value = currentDep;
                    populateCoursesAndMajors();
                }
            }

            function populateCoursesAndMajors() {
                const selectedCampus = campusSelect.value;
                const selectedDepartment = departmentSelect.value;
                courseSelect.innerHTML = '<option value="">SELECT COURSE</option>';
                majorSelect.innerHTML = '<option value="">SELECT MAJOR</option>';

                if(data[selectedCampus] && data[selectedCampus].departments[selectedDepartment]){
                    const {courses, majors} = data[selectedCampus].departments[selectedDepartment];
                    courses.forEach(c => {
                        const option = document.createElement('option');
                        option.value = c;
                        option.textContent = c;
                        courseSelect.appendChild(option);
                    });
                    majors.forEach(m => {
                        const option = document.createElement('option');
                        option.value = m;
                        option.textContent = m;
                        majorSelect.appendChild(option);
                    });

                    const currentCourse = courseSelect.getAttribute('data-current');
                    const currentMajor = majorSelect.getAttribute('data-current');
                    if(currentCourse) courseSelect.value = currentCourse;
                    if(currentMajor) majorSelect.value = currentMajor;
                }
            }

            function populateCampuses() {
                const currentCampus = campusSelect.getAttribute('data-current');
                campusSelect.innerHTML = '<option value="">SELECT CAMPUS</option>';
                Object.keys(data).forEach(campus => {
                    const option = document.createElement('option');
                    option.value = campus;
                    option.textContent = campus;
                    campusSelect.appendChild(option);
                });
                if(currentCampus) campusSelect.value = currentCampus;
                populateDepartments();
            }

            populateCampuses();
            campusSelect.addEventListener('change', populateDepartments);
            departmentSelect.addEventListener('change', populateCoursesAndMajors);

            // Form submission
            document.getElementById('editStudentForm').addEventListener('submit', function(e){
                e.preventDefault();
                const formData = new FormData(this);
                fetch('./backend/update_student.php', {method:'POST', body:formData})
                .then(res => res.json())
                .then(data => {
                    if(data.success){
                        Swal.fire({icon:'success', title:'Updated!', text:'Student information updated.', timer:2000, showConfirmButton:false})
                        .then(() => location.reload());
                    } else {
                        Swal.fire({icon:'error', title:'Error!', text:data.message});
                    }
                });
            });
        });
    });
});


document.querySelectorAll('.edit-employee-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const employeeId = this.dataset.employeeId;
        fetch('./backend/edit_employee_form.php?employee_id=' + employeeId)
        .then(res => res.text())
        .then(html => {
            document.getElementById('editEmployeeBody').innerHTML = html;
            const modal = new bootstrap.Modal(document.getElementById('editEmployeeModal'));
            modal.show();

            // Form submission
            document.getElementById('editEmployeeForm').addEventListener('submit', function(e){
                e.preventDefault();
                const formData = new FormData(this);
                fetch('./backend/update_employee.php', {method:'POST', body:formData})
                .then(res => res.json())
                .then(data => {
                    if(data.success){
                        Swal.fire({icon:'success', title:'Updated!', text:'Employee information updated.', timer:2000, showConfirmButton:false})
                        .then(() => location.reload());
                    } else {
                        Swal.fire({icon:'error', title:'Error!', text:data.message});
                    }
                });
            });
        });
    });
});

    const campusFilter = document.getElementById('campusFilter');
    if(campusFilter){
        Object.keys(data).forEach(campus => {
            const option = document.createElement('option');
            option.value = campus;
            option.textContent = campus;
            campusFilter.appendChild(option);
        });

        // Optionally, preselect current campus if you want
        const currentCampus = "<?= htmlspecialchars($campus_filter); ?>"; // from PHP GET
        if(currentCampus) campusFilter.value = currentCampus;

        // Submit form when changed
        campusFilter.addEventListener('change', () => {
            campusFilter.form.submit();
        });
    }


</script>
</body>
</html>
