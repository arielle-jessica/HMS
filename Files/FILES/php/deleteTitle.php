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
			print("<p>Insufficient privileges to delete books from catalogue.</p>");
		}
		else {
			include '../includes/aheader.html';

			if(isset($_POST["deleteTitleButton"])) {
				$dbook = $_POST['dbook'];
				$count = count($dbook);

				for($i = 0; $i < $count; $i++) {
					$sql = $mysqli -> prepare("DELETE FROM books WHERE callno=?");
					$sql -> bind_param('s', $dbook[$i]);
					$sql -> execute();
					if($sql -> errno)
						print("Delete query failed");
				}
				if($count == 1)
					print("<p>$count book removed from library catalogue.</p>");
				else
					print("<p>$count books removed from library catalogue.</p>");
			}
			else {
				$sql = "SELECT * FROM books";
				$result = $mysqli -> query($sql);
				if(!$result)
					print("<p>Select query failed</p>");
				else {
					if($result -> num_rows == 0)
						print("<p>There are no books in catalogue</p>");
					else {
						print("<h1>Select book(s) to remove from catalogue</h1>");
					?>

						<form name="deleteBooks" method="post" action="<?php $PHP_SELF?>">

					<?php
							print("<table><tr><th></th><th>Author</th><th>Title</th><th>Call Number</th></tr>\n");
							while($row = $result -> fetch_object()) {
								echo '<tr>';
								$callno = $row -> callno;
								print("<td><input type=\"checkbox\" name=\"dbook[]\" value=\"$callno\"></td>");
								echo '<td>'.$row -> author.'</td>';
								echo '<td>'.$row -> title.'</td>';
								echo '<td>'.$row -> callno.'</td>';
								echo '</tr>';
								print("\n");
							}
							print("</table><br />\n<input type=\"submit\" value=\"Delete selected books\" name=\"deleteTitleButton\"></form>");
					}
				}
			}
		}
    	include '../includes/footer.html';
	}
	$mysqli -> close();
?>
