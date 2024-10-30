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
        if (isset($_POST["addPharmacyButton"])) {
            $pharmacy_id = $_POST['pharmacy_id'];
            $pharmacy_name = $_POST['pharmacy_name'];

            $sql = $mysqli->prepare("INSERT INTO pharmacy(pharmacy_id, pharmacy_name) VALUES (?, ?)");
            $sql->bind_param('ss', $pharmacy_id, $pharmacy_name);
            $sql->execute();

            if ($sql->errno) {
                print("<p>Insert query failed</p>");
            } else {
                print("<p>Pharmacy with ID $pharmacy_id and Name $pharmacy_name added to pharmacies list</p>");
            }
        } else {
            include '../includes/addPharmacyForm.html';
            // Include any additional files or content as needed
        }
    } else {
        print("<p>Insufficient privileges to add pharmacies</p>");
    }
}

$mysqli->close();
?>
