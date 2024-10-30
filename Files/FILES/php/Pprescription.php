<?php
session_start();
include 'conn.php';

if (empty($_SESSION['username']) || empty($_SESSION['password'])) {
    print("Access to the database denied");
} else {
    $username = $_SESSION['username'];
    $password = $_SESSION['password'];
    $type = $_SESSION['type'];

    if ($type == "patient") {
        // Fetch the patient name based on the logged-in username
        $patientNameQuery = $mysqli->prepare("SELECT patient_name FROM patients WHERE patient_name = ?");
        $patientNameQuery->bind_param('s', $username);
        $patientNameQuery->execute();
        $patientNameResult = $patientNameQuery->get_result();
        $patientNameRow = $patientNameResult->fetch_assoc();
        $patientName = $patientNameRow['patient_name'];

        // Fetch and display prescriptions associated with the logged-in patient
        $prescriptionQuery = $mysqli->prepare("SELECT * FROM prescriptions WHERE patient_name = ?");
        $prescriptionQuery->bind_param('s', $patientName);
        $prescriptionQuery->execute();
        $prescriptionResult = $prescriptionQuery->get_result();

        // Display prescription details here
        if ($prescriptionResult->num_rows > 0) {
            // Include the HTML content with the matching styling
            ?>

            <!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>My Prescriptions</title>
                <style>
                    body {
                        font-family: 'Arial', sans-serif;
                        background-color: #f4f4f4;
                        margin: 0;
                        padding: 0;
                    }

                    h2 {
                        text-align: center;
                        color: #333;
                    }

                    form {
                        max-width: 600px;
                        margin: 20px auto;
                        background-color: #fff;
                        padding: 20px;
                        border-radius: 8px;
                        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                    }

                    p {
                        margin-bottom: 8px;
                        color: #555;
                    }

                    strong {
                        color: #333;
                    }

                    .prescription {
                        background-color: #b695b6; /* Purple background color */
                        color: #fff; /* White text color */
                        padding: 10px;
                        border-radius: 4px;
                        margin-bottom: 16px;
                    }

                    hr {
                        border: 1px solid #ccc;
                        margin-bottom: 16px;
                    }
                </style>
            </head>
            <body>

                <form>

                    <h2>My Prescriptions</h2>

                    <?php
                    while ($prescriptionRow = $prescriptionResult->fetch_assoc()) {
                        echo "<div class='prescription'>";
                        echo "<p><strong>Medication:</strong> {$prescriptionRow['medication']}</p>";
                        echo "<p><strong>Instructions:</strong> {$prescriptionRow['instructions']}</p>";
                        echo "<p><strong>Pharmacy Name:</strong> {$prescriptionRow['pharmacy_name']}</p>";
                        echo "</div>";
                        echo "<hr>"; // Add a horizontal line for better separation between prescriptions
                    }
                    ?>

                </form>

            </body>
            </html>

            <?php
        } else {
            echo "<p>No prescriptions available.</p>";
        }

        $prescriptionQuery->close();
        $patientNameQuery->close();

        // Include HTML content here
        include '../includes/footer.html';
    } else {
        print("<p>Login as a patient</p>");
    }
}

$mysqli->close();
?>

