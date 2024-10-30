<?php
session_start();
include 'conn.php';

if (empty($_SESSION['username']) || empty($_SESSION['password'])) {
    print("Access to database denied");
} else {
    $username = $_SESSION['username'];
    $password = $_SESSION['password'];
    $type = $_SESSION['type'];

    if ($type == "doctor" || $type == "admin") {
        if (isset($_POST["addPatientButton"])) {
            $patientName = $_POST['patient_name'];
            $dob = $_POST['dob'];
            $medicalCardNumber = $_POST['medical_card_number'];

            $sql = $mysqli->prepare("INSERT INTO patients(patient_name, dob, medical_card_number) VALUES (?, ?, ?)");
            $sql->bind_param('sss', $patientName, $dob, $medicalCardNumber);
            $sql->execute();
 
            if ($sql->errno) {
                print("<p>Insert query failed</p>");
            } else {
                print("<p>$patientName with Medical Card Number $medicalCardNumber added to patients list</p>");
            }
        } else {
            include '../includes/addPatientForm.html';
            include '../includes/footer.html';
        }
    } else {
        print("<p>Insufficient privileges to add patients</p>");
    }
}





$mysqli->close();
?>
