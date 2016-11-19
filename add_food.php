<?php
	//get food information from application
	//adds food information as record to database
	
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
		//insert new record with data sent from android app
		$query = "INSERT INTO food (hID, name, category, quantity_have, unit, expiry_date, price, notes) 
		VALUES ((SELECT household_id FROM user WHERE email = ? AND password = ?),?,?,?,?,?,?,?)";
		
		//data sent from android app
		$email = $_POST["email"];
		$userPassword = $_POST["password"];
		$name = $_POST["name"];
		$category = $_POST["category"];
		$quantity_have = $_POST["quantity_have"];
		$unit = $_POST["unit"];
		$expiry_date = $_POST["expiry_date"];
		$price = $_POST["price"];
		$notes = $_POST["notes"];
		$appPackage = $_POST["appPackage"];
		
		$stmt = $conn->prepare($query);
		$stmt->bind_param("sssssssss", $email, $userPassword, $name, $category,$quantity_have,$unit,$expiry_date,$price,$notes);
		
		if (!$stmt->execute()){ //query failed to execute
			echo "repeat_id"; //tell app that query failed
		}
		if ($appPackage === "com.x10host.httpmyf00d.myf00d"){
			echo $stmt->insert_id; //give app food id of recently added food
			exit();
		}
	}
?>