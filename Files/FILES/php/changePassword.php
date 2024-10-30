<?php
	session_start();
	include 'conn.php';

	
                    
                

		if(isset($_POST["changePasswordButton"])) {
			$oldpassword = md5($_POST['oldpassword']);
			$newpassword = md5($_POST['newpassword']);

			if($oldpassword != $password)
				print("<p>Old password does not match</p>");
			else {
				$sql = $mysqli -> prepare("UPDATE $table SET Password=? WHERE LastName=?");
				$sql -> bind_param('ss', $newpassword, $username);
				$sql -> execute();

				if($sql -> errno)
					print("<p>Query failed</p>");
				else {
					print("<p>Password successfully changed</p>");
					$_SESSION['password'] = $newpassword;
				}
			}
		}
		else {
			include '../includes/changePasswordForm.html';
			include '../includes/footer.html';
		}
	
	$mysqli -> close();
?>
