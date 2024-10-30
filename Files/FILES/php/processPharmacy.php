<?php
session_start();
include 'conn.php'; // Include your database connection file

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['addPharmacyButton'])) {
    // Validate and sanitize form data (add more validation as needed)
    $pharmacy_id = $_POST['pharmacy_id'];
    $pharmacy_name = $_POST['pharmacy_name'];

    // Check if the pharmacy ID already exists
    $checkIfExists = $mysqli->prepare("SELECT * FROM pharmacy WHERE pharmacy_id = ?");
    $checkIfExists->bind_param('s', $pharmacy_id);
    $checkIfExists->execute();
    $checkResult = $checkIfExists->get_result();

    if ($checkResult->num_rows > 0) {
        print("<p>Pharmacy ID already exists. Choose a different ID.</p>");
    } else {
        // Insert pharmacy data into the 'pharmacy' table
        $insertPharmacy = $mysqli->prepare("INSERT INTO pharmacy(pharmacy_id, pharmacy_name) VALUES (?, ?)");
        $insertPharmacy->bind_param('ss', $pharmacy_id, $pharmacy_name);
        $insertPharmacy->execute();

        if($insertPharmacy-> errno)
					print("<p>Insert query failed</p>");
				else
					print("<p>Pharmacy Added</p>");
			}
			else {
				include '../includes/addPharmacyForm.html';
				include '../includes/footer.html';
			}
		}
	} 


$mysqli->close();
?>

