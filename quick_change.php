<?php
	//changes the quantity of a food item specified by food ID
	//user household must match food household to occur
	
	//SQL database login information
	$servername = "localhost";
	$username = "myf00dx2_admin";
	$password = "Defasecur3password";
	$dbname = "myf00dx2_food";

	// Create connection
	$conn = new mysqli($servername, $username, $password, $dbname);

	// Check connection
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} else {
		//query that changes food quantity by amount sent by application if user household matches food household
		$query = 'UPDATE food SET quantity_have = quantity_have - ? 
		WHERE id = ? AND hID = (SELECT household_id FROM user WHERE email = ? and password = ?)';
		
		//user credentials sent from app
		$email = $_POST["email"];
		$userPassword = $_POST["password"];
		
		//food & change information sent from app
		$change = $_POST["change"];
		$id = $_POST["id"];
		
		//run query
		$stmt = $conn->prepare($query);
		$stmt->bind_param("ssss", $change, $id, $email, $userPassword);
		if (!$stmt->execute()){ //user household and food household don't match, food doesn't exist
			echo "400";
		}
		$stmt->close;
		if ($appPackage === "com.x10host.httpmyf00d.myf00d"){
			exit();
		}
	}
?>