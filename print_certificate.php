<?php
session_start();
require_once __DIR__ . './backend/db_connection.php';

// Check if user is authorized
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['admin', 'superadmin', 'super_admin'])) {
    header("Location: admin_login.php");
    exit;
}

$cert_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($cert_id <= 0) {
    echo "Invalid certificate ID.";
    exit;
}

// Fetch certificate data
$sql = "SELECT * FROM student_certificates WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $cert_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Certificate not found.";
    exit;
}

$cert = $result->fetch_assoc();
$date_issued = date('F j, Y', strtotime($cert['created_at']));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medical Certificate - <?= htmlspecialchars($cert['name']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f8f9fa;
            padding: 20px;
        }
        
        .print-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        
        .print-header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #2c3e50;
            padding-bottom: 20px;
        }
        
        .print-logo {
            width: 80px;
            margin-bottom: 15px;
        }
        
        .print-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 5px;
        }
        
        .print-main-title {
            font-size: 2.2rem;
            font-weight: 700;
            color: #3498db;
            margin: 20px 0;
        }
        
        .print-actions {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .cert-content {
            line-height: 1.8;
        }
        
        .vital-signs, .additional-info {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        
        .signature-section {
            margin-top: 50px;
            display: flex;
            justify-content: space-between;
            align-items: end;
        }
        
        .signature-line {
            border-bottom: 2px solid #000;
            width: 250px;
            margin-bottom: 10px;
        }
          .cert-signature {
            margin-top: 2.5rem;
            text-align: right;
        }
        
        @media print {
            body {
                background: white !important;
                padding: 0 !important;
            }
            
            .print-container {
                box-shadow: none !important;
                border-radius: 0 !important;
                padding: 20px !important;
                margin: 0 !important;
                max-width: none !important;
            }
            
            .print-actions {
                display: none !important;
            }
            
            .no-print {
                display: none !important;
            }
        }
    </style>
</head>
<body>
    <div class="print-container">
        <div class="print-actions no-print">
            <button class="btn btn-success btn-lg me-3" onclick="window.print()">
                <i class="fas fa-print"></i> Print Certificate
            </button>
            <button class="btn btn-secondary btn-lg" onclick="window.close()">
                <i class="fas fa-times"></i> Close
            </button>
        </div>
        
        <div class="print-header">
            <div class="cert-container mb-4">
                <div class="row align-items-center cert-header">
                    <div class="col-2 text-center">
                        <img src="img/ispsc.png" alt="ISPSC Logo" class="cert-logo" 
                            style="max-width: 80px; height: auto;">
                    </div>
                    <div class="col-8 text-center">
                        <div style="font-size:1.1rem; font-weight:600;">Republic of the Philippines</div>
                        <div style="font-size:1.2rem; font-weight:700;">ILOCOS SUR POLYTECHNIC STATE COLLEGE</div>
                        <div style="font-size:1rem;">www.ispsc.edu.ph | ispscop@ispsc.edu.ph</div>
                    </div>
                    <div class="col-2 text-center">
                        <img src="img/bagong-pilipinas.png" alt="Bagong Pilipinas" class="cert-logo" 
                            style="max-width: 80px; height: auto;">
                    </div>
                </div>

                <div class="text-center cert-title mt-2" style="font-size:1rem; font-weight:600;">
                    Health Services Unit
                </div>
                <div class="text-center cert-main-title" style="font-size:2rem; font-weight:700; margin-top:10px;">
                    MEDICAL CERTIFICATE
                </div>
            </div>

        </div>

       <div class="cert-content">
    <div class="row mb-2">
        <div class="col-8"></div>
        <div class="col-4 text-end">
            <span style="font-weight:500;"><?= htmlspecialchars(date('F j, Y', strtotime($cert['created_at']))) ?></span>
        </div>
    </div>

    <div class="mb-2">TO WHOM IT MAY CONCERN:</div>

    <div class="mb-2">
        This is to certify that <b>MR./MS.</b> 
        <span style="font-weight:500; text-decoration:underline;"><?= htmlspecialchars($cert['name']) ?></span>, 
        <span style="font-weight:500;"><?= htmlspecialchars($cert['age']) ?></span> years old, 
        <span style="font-weight:500;"><?= htmlspecialchars($cert['sex']) ?></span>, 
        <span style="font-weight:500;"><?= htmlspecialchars($cert['year']) ?></span> year student of ISPSC – Santa Maria Campus was seen and examined by the undersigned with the following findings:
    </div>

    <div class="row mb-2">
        <div class="col-3"><b>BP:</b> <span><?= htmlspecialchars($cert['bp']) ?></span> mmHg</div>
        <div class="col-3"><b>HR:</b> <span><?= htmlspecialchars($cert['hr']) ?></span> bpm</div>
        <div class="col-3"><b>RR:</b> <span><?= htmlspecialchars($cert['rr']) ?></span> cpm</div>
        <div class="col-3"><b>T:</b> <span><?= htmlspecialchars($cert['temp']) ?></span> °C</div>
    </div>

    <div class="mb-2"><b>DIAGNOSIS:</b> 
        <span style="text-decoration:underline; font-weight:600;">
            <?= !empty($cert['diagnosis']) ? htmlspecialchars($cert['diagnosis']) : 'ESSENTIALLY NORMAL AT THE TIME OF EXAMINATION' ?>
        </span>
    </div>

    <div class="mb-2">* Fully Vaccinated 
        <span style="font-weight:500;"><?= !empty($cert['vax']) ? htmlspecialchars($cert['vax']) : 'NOT SPECIFIED' ?></span> Booster Shot
    </div>

    <div class="mb-2"><b>REMARKS:</b> 
        <span style="font-weight:600;"><?= !empty($cert['remarks']) ? htmlspecialchars($cert['remarks']) : 'PHYSICALLY FIT' ?></span>
    </div>

    <div class="mb-2">
        This certificate is issued upon the request of <b>MR./MS.</b> 
        <span style="font-weight:500; text-decoration:underline;"><?= htmlspecialchars($cert['name']) ?></span> 
        for <span style="font-weight:500;"><?= htmlspecialchars($cert['purpose']) ?></span> purposes and not valid for Medico – Legal.
    </div>

    <div class="cert-signature mt-5 ">
        <div style="font-weight:700;">LOU KHRISTINE DOLES RACCA, MD, MHA</div>
        <div>Medical Officer III</div>
        <div>Lic. No.: 144788</div>
    </div>
</div>




    </div>

    <script>
        // Auto-focus for better printing experience
        window.addEventListener('load', function() {
            // Optional: Auto-print when page loads (uncomment if desired)
            // setTimeout(() => window.print(), 500);
        });
        
        // Handle keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            if (e.ctrlKey && e.key === 'p') {
                e.preventDefault();
                window.print();
            }
        });
    </script>
</body>
</html>
