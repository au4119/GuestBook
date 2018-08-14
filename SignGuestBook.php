<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"/>
<title>Sign Guest Book</title>
</head>
<body>
<?php
if (empty($_POST['first_name']) || empty($_POST['last_name']))
	echo "<p>You must enter your first and last name! Click your browser's Back button to return to the Guest Book form.</p>";
else {
	$DBConnect = @mysqli_connect("localhost", "root", "mgs314");
	if ($DBConnect === FALSE)
		echo "<p>Unable to connect to the database server.</p>"
			. "<p>Error code " . mysqli_errno()
			. ": " . mysqli_error() . "</p>";
	else {
		$DBName = "guestbook";
		if (!@mysqli_select_db($DBName, $DBConnect)) {
			$SQLString = "CREATE DATABASE $DBName";
			$QueryResult = @mysqli_query($SQLstring, $DBConnect);
			if ($QueryResult === FALSE)
				echo "<p>Unable to execute the query.</p>" . "<p>Error code " . mysqli_errno($DBConnect) . 
					": " . mysqli_error($DBConnect) . "</p>";
			else
				echo "<p>You are the first visitor!</p>";
		}
		mysqli_select_db($DBName, $DBConnect);
		$TableName = "visitors";
		$SQLstring = "SHOW TABLES LIKE '$TableName'";
		$QueryResult = @mysqli_query($SQLstring, $DBConnect);
		if (mysqli_num_rows($QueryResult) == 0) {
			$SQLString = "CREATE TABLE $TableName (countID SMALLINT NOT NULL AUTO_INCREMENT PRIMARY KEY, last_name VARCHAR(40), first_name VARCHAR(40))";
			$QueryResult = @mysqli_query($SQLstring, $DBConnect);
			if ($QueryResult===FALSE)
				echo "<p>Unable to create the table.</p>" . "<p>Error code " . mysqli_errno($DBConnect) . ": " . mysqli_error($DBConnect) . "</p>";
			$LastName = stripslashes($_POST['last_name']);
			$FirstName = stripslashes($_POST['first_name']);
			$SQLString = "INSERT INTO $TableName VALUES(NULL, '$LastName', '$FirstName')";
			$QueryResult = @mysqli_query($SQLstring, $DBConnect);
			if ($QueryResult === FALSE)
				echo "<p>Unable to execute the query.</p>" . "<p>Error code " . mysqli_errno($DBConnect) . ": " . mysqli_error($DBConnect) . "</p>";
			else
				echo "<h1>Thank you for signing our guest book!</h1>";
		}
		mysqli_close($DBConnect);
	}
}
?>
</body>
</html>