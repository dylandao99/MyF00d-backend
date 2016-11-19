<?php
	//gets user registration information from Android application
	//adds new record to "user" table in database
	
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
		//add user credentials as a record to the "user" table
		$query = "INSERT INTO user (email, password, first_name) VALUES (?, ?, ?)";
	
		//user information recieved from Android app
		$email = $_POST["email"];
		$userPassword = $_POST["password"];
		$first_name = $_POST["first_name"];
		$appPackage = $_POST["appPackage"];
		
		//execute query
		$stmt = $conn->prepare($query);
		$stmt->bind_param("sss", $email, $userPassword, $first_name);
		
		if (!$stmt->execute()){//if adding user fails (email already registered)
			echo "400";
		}
		if ($appPackage === "com.x10host.httpmyf00d.myf00d"){
			exit();
		}
		$stmt->close();
	}
	
	$conn->close();
?>