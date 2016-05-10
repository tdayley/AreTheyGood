<?php include('variables/private_variables.php') ?>

<?php
	try {
		$conn = new PDO("mysql:host=$serverName;dbname=$databaseName", $username, $password);
		// set the PDO error mode to exception
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}
	catch(PDOException $e)
	{
		echo "<div>Connection failed: " . $e->getMessage() . "</div><div>Error code: " . $e->getCode() . "</div>";
    	die();
	}
?>