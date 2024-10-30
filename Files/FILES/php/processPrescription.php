<?php
session_start();
include 'conn.php'; // Include your database connection file

if (empty($_SESSION['username']) || empty($_SESSION['password'])) {
    print("Access to the database denied");
} else {
    $username = $_SESSION['username'];
    $password = $_SESSION['password'];
    $type = $_SESSION['type'];

    if ($type == "patient") {
        print("Insufficient privileges to create a prescription");
    } else {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['createPrescription'])) {
            // Validate and sanitize form data (add more validation as needed)
            $patient_name = $_POST['patient_name'];
            $medication = $_POST['medication'];
            $instructions = $_POST['instructions'];
            $pharmacy_name = $_POST['pharmacy_name']; // Correct variable name
            
            // Check if the pharmacy is missing
            if (empty($pharmacy_name)) {
                // Handle the case where pharmacy_name is missing (add your logic here)
                print("<p>Pharmacy name is missing. Please add a pharmacy.</p>");
            } else {
                // Retrieve pharmacy ID based on the provided pharmacy name
                $pharmacy_query = $mysqli->prepare("SELECT pharmacy_id FROM pharmacy WHERE pharmacy_name = ?");
                $pharmacy_query->bind_param('s', $pharmacy_name);
                $pharmacy_query->execute();
                $pharmacy_query->bind_result($pharmacy_id);
                $pharmacy_query->fetch();
                $pharmacy_query->close();


  
                       
 $sql = $mysqli->prepare("INSERT INTO prescriptions(patient_name, medication, instructions, pharmacy_name) VALUES (?, ?, ?, ?)");
            $sql->bind_param('ssss', $patient_name, $medication, $instructions, $pharmacy_name);
            $sql->execute();

                    if ($sql->errno) {
                        print("<p>Insert query failed</p>");
                    } else {
                        print("<p>Prescription for Patient $patient_name sent to Pharmacy $pharmacy_name successfully</p>");
                    }
                }
            }
        }
    }


$mysqli->close();
?>

