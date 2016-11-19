<?php
	//sets "visible" field of designated food item to false
	//prevents food item from being fetched by Android app
	//effectively "deletes" food for the user
	
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
		//query that sets "visible" boolean field of a food item specified by its ID to false ONLY if user credentials/households correlate
		$query = 'UPDATE food SET visible = 0 WHERE id = ? AND hID = (SELECT household_id FROM user WHERE email = ? and password = ?)';
		
		//user information from application
		$email = $_POST["email"];
		$userPassword = $_POST["password"];
		
		//food ID from application
		$id = $_POST["id"];
		
		//run query
		$stmt = $conn->prepare($query);
		$stmt->bind_param("sss", $id, $email, $userPassword);
		if (!$stmt->execute()){ //if query fails, food does not exist, user household doesn't match food household
			echo "400";
		}
		$stmt->close;
		if ($appPackage === "com.x10host.httpmyf00d.myf00d"){
			exit();
		}
	}
?>