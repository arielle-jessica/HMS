<?php
	session_start();
	include 'conn.php';

	if(empty($_SESSION['username']) || empty($_SESSION['password']))
		print("Access to database denied");
	else {
		$username = $_SESSION['username'];
		$password = $_SESSION['password'];
		$type = $_SESSION['type'];

		if($type != "admin") {
			include '../includes/uheader.html';
			print("<p>Insufficient privileges to add titles to catalogue</p>");      
		}
		else {
			include '../includes/aheader.html';

			if(isset($_POST["addTitleButton"])) {
				$Author = $_POST['Author'];
				$Title = $_POST['Title'];
				$CallNo = $_POST['CallNo'];

				$sql = $mysqli -> prepare("INSERT INTO books(author, title, callno) VALUES (?, ?, ?)");
				$sql -> bind_param('sss', $Author, $Title, $CallNo);
				$sql -> execute();

				if($sql -> errno)
					print("<p>Insert query failed</p>");
				else
					print("<p>$Title $CallNo by $Author added to library</p>");
			}
			else {
				include '../includes/addTitleForm.html';
				include '../includes/footer.html';
			}
		}
	}
	$mysqli -> close();
?>
