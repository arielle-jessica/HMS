<?php
session_start();
include 'conn.php';

/*
    if($mysqli->connect_errno)
        echo 'not connected to database: '.$mysqli->connect_error;
    else
        echo 'connected';
*/

if (empty($_POST['username']) || empty($_POST['password'])) {
    header('Location: ../index.html');
} else {
    if (isset($_POST['login'])) {

        $username = $_POST['username'];
        $password = $_POST['password'];
        $password = md5($password);

        $_SESSION['username'] = $username;
        $_SESSION['password'] = $password;

        $sql = $mysqli->prepare("SELECT * FROM admin WHERE username=? AND password=?");
        $sql->bind_param('ss', $username, $password);
        $sql->execute();
        $result = $sql->get_result();

        if ($result->num_rows == 1) {
            $_SESSION['type'] = "admin";
            include '../includes/aheader.html';
            print("<p>Welcome $username</p>");
            include '../includes/footer.html';
        } else {
            // Check if the user exists in patients table
            $sql = $mysqli->prepare("SELECT * FROM patients WHERE patient_name=? AND password=?");
            $sql->bind_param('ss', $username, $password);
            $sql->execute();
            $result = $sql->get_result();

            if ($result->num_rows == 1) {
                $_SESSION['type'] = "patient";
                include '../includes/pheader.html';
                print("<p>Welcome $username</p>");
                include '../includes/footer.html';
            } else {
                // Check if the user exists in doctors table
                $sql = $mysqli->prepare("SELECT * FROM doctor WHERE LastName=? AND Password=?");
                $sql->bind_param('ss', $username, $password);
                $sql->execute();
                $result = $sql->get_result();

                if ($result->num_rows == 1) {
                    $_SESSION['type'] = "doctor";
                    include '../includes/dheader.html';
                    print("<p>Welcome $username</p>");
                    include '../includes/footer.html';
                } else {
                    // Check if the user exists in pharmacies table
                    $sql = $mysqli->prepare("SELECT * FROM pharmacy WHERE pharmacy_name=? AND password=?");
                    $sql->bind_param('ss', $username, $password);
                    $sql->execute();
                    $result = $sql->get_result();

                    if ($result->num_rows == 1) {
                        $_SESSION['type'] = "pharmacy";
                        include '../includes/phheader.html';
                        print("<p>Welcome $username</p>");
                        include '../includes/footer.html';
                    } else {
                        header('Location: ../index.html');
                    }
                }
            }
        }
    } else if (isset($_POST['signup'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $password = md5($password);
        $role = $_POST['role'];

        if ($role === "patient") {

            $sql = $mysqli->prepare("INSERT INTO patients(patient_name,password) VALUES(?, ?)");
            $sql->bind_param('ss', $username, $password);
            $sql->execute();

            header('Location: ../includes/pheader.html');
        } elseif ($role === "doctor") {

            $sql = $mysqli->prepare("INSERT INTO doctor(LastName, Password) VALUES(?, ?)");
            $sql->bind_param('ss', $username, $password);
            $sql->execute();

            header('Location: ../includes/dheader.html');
        } elseif ($role === "pharmacy") {

            $sql = $mysqli->prepare("INSERT INTO pharmacy(pharmacy_name, password) VALUES(?, ?)");
            $sql->bind_param('ss', $username, $password);
            $sql->execute();

            header('Location: ../includes/phheader.html');
        } else {

            header('Location: ../index.html');
            exit();
        }

        $mysqli->close();
    }
}
?>
