


<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Medical Certificate</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;700&display=swap" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.22.4/dist/sweetalert2.all.min.js"></script>
        <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.22.4/dist/sweetalert2.min.css" rel="stylesheet">
        <link href="styles.css" rel="stylesheet" />
        <link rel="icon" type="image/x-icon" href="img/logo.ico" />
        <style>
        body { background: #fff; }
        .cert-container {
            max-width: 900px;
            margin: 30px auto;
            background: #fff;
            border: 1px solid #ccc;
            padding: 32px 40px 24px 40px;
            box-shadow: 0 2px 8px #0001;
        }
        .cert-header {
            border-bottom: 3px solid #d32f2f;
            margin-bottom: 10px;
            padding-bottom: 8px;
        }
        .cert-title {
            color: #d32f2f;
            font-weight: 700;
            font-size: 1.2rem;
        }
        .cert-main-title {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        .cert-section {
            margin-bottom: 1.2rem;
        }
        .cert-label {
            font-weight: 600;
        }
        .cert-signature {
            margin-top: 2.5rem;
            text-align: right;
        }
        .cert-footer {
            border-top: 2px solid #d32f2f;
            margin-top: 2rem;
            padding-top: 0.5rem;
            font-size: 0.95rem;
            color: #555;
        }
        .cert-logo {
            height: 60px;
        }
        /* Responsive adjustments */
        @media (max-width: 600px) {
            .cert-container {
                padding: 10px 2vw 10px 2vw;
            }
            .cert-header .col-2, .cert-header .col-8 {
                flex: 0 0 100%;
                max-width: 100%;
                margin-bottom: 10px;
            }
            .cert-header {
                flex-direction: column;
                text-align: center;
            }
            .cert-main-title {
                font-size: 1.3rem;
            }
            .cert-title {
                font-size: 1rem;
            }
            input[type="text"], input[type="number"], input[type="date"], select {
                width: 100% !important;
                min-width: 0 !important;
                display: block !important;
                margin-bottom: 8px;
            }
            .cert-signature {
                text-align: left;
                margin-top: 1.5rem;
            }
            .row.mb-2 > div {
                margin-bottom: 8px;
            }
        }
        @media print {
            .btn, .no-print { display: none !important; }
            .cert-container { box-shadow: none; border: none; }
        }
        </style>
    </head>
    <body>
   
        <header class="header">
            <div class="container">
                <div class="d-flex flex-column align-items-center justify-content-center text-center">
                    <div>
                        <img src="img/ispsc.png" alt="ISPSC Logo" width="100" height="100" class="me-3" />
                        <img class="bagong-pilipinas" src="img/bagong-pilipinas.png" alt="ISPSC Logo" width="120" height="120" class="me-3" />
                    </div>
                    <div>
                        <h1 class="ispsc-logo mb-0">REPUBLIC OF THE PHILIPPINES</h1>
                        <hr class="my-2 border-white" />
                        <h1 class="ispsc-logo mb-0">ILOCOS SUR POLYTECHNIC STATE COLLEGE</h1>
                        <h2 class="ispsc-logo mb-0">ILOCOS SUR, PHILIPPINES</h2>
                    </div>
                </div>
            </div>

                </header>

        <nav class="navbar navbar-expand-lg sticky-top">
            <div class="container">
                <button
                    class="navbar-toggler"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#navbarNav"
                    aria-controls="navbarNav"
                    aria-expanded="false"
                    aria-label="Toggle navigation"
                >
                    <i class="navbar-toggler-icon" id="menu"></i>
                </button>

                <div class="collapse navbar-collapse" id="navbarNav">
                    <!-- Left side -->
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link" href="./student_medical">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link "  href="./student_edit">Edit Health Info</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link"  href="./student_info">Edit Personal Info</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" style="color: yellow" aria-current="page"  href="./student_certificate">Issue Certificate</a>
                        </li>
        
                    </ul>

                    <!-- Right side (Logout) -->
                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link" href="index.php">
                                <i class="fa-solid fa-power-off"></i> Logout
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>


    <div class="cert-container">
        <div class="row align-items-center cert-header">
            <div class="col-2 text-center">
                <img src="img/ispsc.png" alt="ISPSC Logo" class="cert-logo">
            </div>
            <div class="col-8 text-center">
                <div style="font-size:1.1rem; font-weight:600;">Republic of the Philippines</div>
                <div style="font-size:1.2rem; font-weight:700;">ILOCOS SUR POLYTECHNIC STATE COLLEGE</div>
                <div style="font-size:1rem;">www.ispsc.edu.ph | ispscop@ispsc.edu.ph</div>
            </div>
            <div class="col-2 text-center">
                <img src="img/bagong-pilipinas.png" alt="Bagong Pilipinas" class="cert-logo">
            </div>
        </div>
        <div class="text-center cert-title">Health Services Unit</div>
        <div class="text-center cert-main-title">MEDICAL CERTIFICATE</div>
    <form id="certForm" method="POST" autocomplete="off">
        <input type="hidden" name="student_id" id="student_id">

            <div class="row mb-2">
                <div class="col-8"></div>
                <div class="col-4 text-end"><input type="date" class="form-control form-control-sm d-inline-block" name="cert_date" style="width: 70%; display:inline-block;"></div>
            </div>
            <div class="mb-2">TO WHOM IT MAY CONCERN:</div>
            <div class="mb-2">This is to certify that <b>MR./MS.</b> <input type="text" name="cert_name" class="form-control form-control-sm d-inline-block" style="width: 250px; display:inline-block;">,
                <input type="number" name="cert_age" class="form-control form-control-sm d-inline-block" style="width: 60px; display:inline-block;"> years old,
                <select name="cert_sex" class="form-select form-select-sm d-inline-block" style="width: 90px; display:inline-block;"><option>Male</option><option>Female</option></select>,
                <input type="number" name="cert_year" class="form-control form-control-sm d-inline-block" style="width: 60px; display:inline-block;"> year student of ISPSC – Santa Maria Campus was seen and examined by the undersigned with the following findings:
            </div>
            <div class="row mb-2">
                <div class="col-3"><b>BP:</b> <input type="text" name="cert_bp" class="form-control form-control-sm d-inline-block" style="width: 80px; display:inline-block;" value="0" disabled> mmHg</div>
                <div class="col-3"><b>HR:</b> <input type="text" name="cert_hr" class="form-control form-control-sm d-inline-block" style="width: 80px; display:inline-block;" value="0" disabled> bpm</div>
                <div class="col-3"><b>RR:</b> <input type="text" name="cert_rr" class="form-control form-control-sm d-inline-block" style="width: 80px; display:inline-block;" value="0" disabled> cpm</div>
                <div class="col-3"><b>T:</b> <input type="text" name="cert_temp" class="form-control form-control-sm d-inline-block" style="width: 80px; display:inline-block;" value="0" disabled> °C</div>
            </div>
            <div class="mb-2"><b>DIAGNOSIS:</b> <span style="text-decoration:underline; font-weight:600;">ESSENTIALLY NORMAL AT THE TIME OF EXAMINATION</span></div>
            <div class="mb-2">* Fully Vaccinated <select name="cert_vax" class="form-select form-select-sm d-inline-block" style="width: 120px; display:inline-block;"><option>WITH</option><option>WITHOUT</option></select> Booster Shot</div>
            <div class="mb-2"><b>REMARKS:</b> <span style="font-weight:600;">PHYSICALLY FIT</span></div>
            <div class="mb-2">This certificate is issued upon the request of <b>MR./MS.</b> <input type="text" name="cert_name" class="form-control form-control-sm d-inline-block" style="width: 250px; display:inline-block;"> for <input type="text" name="cert_purpose" class="form-control form-control-sm d-inline-block" style="width: 200px; display:inline-block;"> purposes and not valid for Medico – Legal.</div>
            <div class="cert-signature">
                <div style="font-weight:700;">LOU KHRISTINE DOLES RACCA, MD, MHA</div>
                <div>Medical Officer III</div>
                <div>Lic. No.: 144788</div>
            </div>
        </form>
        <div class="text-end mt-3 no-print">
            <button type="submit" form="certForm" class="btn btn-success"><i class="fa fa-paper-plane"></i> Submit Certificate</button>
        </div>
    </div>
    <?php include 'footer.php'; ?>
 
    <script src="assets/js/student_cert.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>

