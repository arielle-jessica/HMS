<?php
	session_start();
	include 'conn.php';

	if(empty($_SESSION['username']) || empty($_SESSION['password']))
		print("Access to database denied");
	else {
		$username = $_SESSION['username'];
		$password = $_SESSION['password'];
		$type = $_SESSION['type'];

		if($type == "admin") {
			include '../includes/aheader.html';
		}
		if($type == "user") {
			include '../includes/uheader.html';
		}

		if(isset($_POST["searchButton"])) {
			$keyword = $_POST['keyword'];
			
            $sql = $mysqli->prepare("SELECT * FROM prescriptions WHERE patient_name LIKE ?");
			
			$keyword = '%'.$keyword.'%';
			$sql -> bind_param('s', $keyword);
			$sql -> execute();
			$result = $sql -> get_result();

			if (!$result) {
			    print("<p>Select query failed</p>");
			} else {
			    echo '<!DOCTYPE html>';
			    echo '<html lang="en">';
			    echo '<head>';
			    echo '<meta charset="UTF-8">';
			    echo '<meta name="viewport" content="width=device-width, initial-scale=1.0">';
			    echo '<title>Prescription Search Results</title>';
			    echo '<style>';
			    // Adjust the CSS styling to match the prescription form
			    echo 'body { font-family: "Arial", sans-serif; background-color: #f4f4f4; margin: 0; padding: 0; }';
			    echo 'h1 { text-align: center; color: #333; }';
			    echo 'table { width: 80%; margin: 20px auto; background-color: #fff; border-radius: 8px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); }';
			    echo 'th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }';
			    echo 'th { background-color: #b695b6; color: white; }';
			    echo '</style>';
			    echo '</head>';
			    echo '<body>';
			    echo '<h1>Prescription</h1>';
			    echo '<table>';
			    echo '<tr><th>Medication</th><th>Instructions</th></tr>';

			    while ($row = $result->fetch_object()) {
			        echo '<tr>';
			        echo '<td>' . $row->medication . '</td>';
			        echo '<td>' . $row->instructions . '</td>';
			        echo '</tr>';
			    }

			    echo '</table>';
			    echo '</body>';
			    echo '</html>';
			}
		} else {
		    include '../includes/searchForm.html';
		    include '../includes/footer.html';
		}
	}

	$mysqli->close();
?>

