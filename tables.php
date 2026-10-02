<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submissions Admin Panel</title>
    <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="assets/img/favicon.png">
  <title>
    Soft UI Dashboard 3 by Creative Tim
  </title>
  <!--     Fonts and icons     -->
  <link href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700,800" rel="stylesheet" />
  <!-- Nucleo Icons -->
  <link href="https://demos.creative-tim.com/soft-ui-dashboardassets/css/nucleo-icons.css" rel="stylesheet" />
  <link href="https://demos.creative-tim.com/soft-ui-dashboardassets/css/nucleo-svg.css" rel="stylesheet" />
  <!-- Font Awesome Icons -->
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <!-- CSS Files -->
  <link id="pagestyle" href="assets/css/soft-ui-dashboard.css?v=1.1.0" rel="stylesheet" />
  <!-- Nepcha Analytics (nepcha.com) -->
  <!-- Nepcha is a easy-to-use web analytics. No cookies and fully compliant with GDPR, CCPA and PECR. -->
  <script defer data-site="YOUR_DOMAIN_HERE" src="https://api.nepcha.com/js/nepcha-analytics.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f5f5f5;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f8f9fa;
            font-weight: bold;
        }

        tr:hover {
            background-color: #f9f9f9;
        }
    </style></head>
    <?php include 'left.php'; ?>
<!-- End Sidebar -->
  <!-- Navbar -->
  <?php include 'top.php'; ?>
<body>
    
<div class="container">
        <h1>Submissions Admin Panel</h1>
        <table>
            <thead>
                <tr>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Email</th>
                    <th>Phone Number</th>
                    <th>Description</th>
                    <th>Date Submitted</th>
                </tr>
            </thead>
            <tbody id="submissionsBody">
                <!-- Data will be populated here -->
            </tbody>
        </table>
    </div>

    <!-- Firebase SDK -->
    <script src="https://www.gstatic.com/firebasejs/9.22.0/firebase-app-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/9.22.0/firebase-database-compat.js"></script>

    <script>
        // Initialize Firebase
        const firebaseConfig = {
            apiKey: "AIzaSyBZucOkcX5uNu-tejhXpGgHnh8deRNwDno",
            authDomain: "admindashboard-ebb97.firebaseapp.com",
            databaseURL: "https://admindashboard-ebb97-default-rtdb.firebaseio.com",
            projectId: "admindashboard-ebb97",
            storageBucket: "admindashboard-ebb97.firebasestorage.app",
            messagingSenderId: "839931753288",
            appId: "1:839931753288:web:0eb5241df4c0b02aba797d",
            measurementId: "G-R3X1RSR1JD"
        };

        // Initialize Firebase
        const app = firebase.initializeApp(firebaseConfig);
        const db = firebase.database();
        const submissionsRef = db.ref('submissions');

        // Add loading state
        let initialLoad = true;

        submissionsRef.on('value', (snapshot) => {
            const submissions = snapshot.val();
            const tbody = document.getElementById('submissionsBody');
            tbody.innerHTML = '';

            if (submissions) {
                Object.entries(submissions).forEach(([key, submission]) => {
                    // Add null checks for nested properties
                    const submissionData = submission || {};
                    tbody.innerHTML += `
                        <tr>
                            <td>${submissionData.firstName || 'N/A'}</td>
                            <td>${submissionData.lastName || 'N/A'}</td>
                            <td>${submissionData.email || 'N/A'}</td>
                            <td>${submissionData.phoneNumber || 'N/A'}</td>
                            <td>${submissionData.description || 'N/A'}</td>
                            <td>${
                                submissionData.createdAt ? 
                                new Date(submissionData.createdAt).toLocaleString() : 
                                'N/A'
                            }</td>
                        </tr>
                    `;
                });
            } else if (initialLoad) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="6" style="text-align: center;">
                            No submissions found
                        </td>
                    </tr>
                `;
            }
            initialLoad = false;
        }, (error) => {
            console.error("Database error:", error);
            document.getElementById('submissionsBody').innerHTML = `
                <tr>
                    <td colspan="6" style="text-align: center; color: red;">
                        Error loading data: ${error.message}
                    </td>
                </tr>
            `;
        });
    </script>
</body>
</html>