<?php
session_start();
include 'conn.php'; // Include your database connection file

if (empty($_SESSION['username']) || empty($_SESSION['password'])) {
    print("Access to database denied");
} else {
    $username = $_SESSION['username'];
    $password = $_SESSION['password'];
    $type = $_SESSION['type'];

    if ($type !== "doctor" && $type !== "admin") {
        print("Insufficient privileges to create a prescription");
    } else {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['createPrescription'])) {
            // Validate and sanitize form data (add more validation as needed)
            $patient_name = $_POST['patient_name'];
            $medication = $_POST['medication'];
            $instructions = $_POST['instructions'];

            // Insert prescription data into the 'prescriptions' table
            $sql = $mysqli->prepare("INSERT INTO prescriptions(patient_name, medication, instructions) VALUES (?, ?, ?)");
            $sql->bind_param('sss', $patient_name, $medication, $instructions);
            $sql->execute();

            if ($sql->errno) {
                print("<p>Insert query failed</p>");
            } else {
                print("<p>Prescription for Patient $patient_name added successfully</p>");
            }
        } else {
            // This part was missing in your code
            include '../includes/PrescriptionForm.html';
            // Button to link to the process prescription form
            echo '<a href="processPrescription.php"><button type="button">Process Prescription</button></a>';
            include '../includes/footer.html';
        }
    }
}

$mysqli->close();
?>

