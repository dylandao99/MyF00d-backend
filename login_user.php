<?php
//checks user input login credentials with database
//if match found, gives user associated information with account

	//SQL Database login information
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
		//get user input
		$email = $_POST["email"];
		$userPassword = $_POST["password"];
		$appPackage = $_POST["appPackage"];
		
		//gets Household ID, First Name if there is a Username-Password match in the database
		$query = "SELECT household_id, first_name FROM user WHERE email = \"$email\" AND password = \"$userPassword\"";
		
		//get result from Query run on database
		$rawResult = $conn->query($query);
		
		//if user not registered or wrong credentials
		if ($rawResult->num_rows === 0){
			if($appPackage === "com.x10host.httpmyf00d.myf00d") {
				echo 400;
				exit();
			}
			
		} else {
			//convert query result to array
			$array = $rawResult->fetch_array(MYSQLI_NUM);
			//define what each data value is
			$hID = $array[0];
			$first_name = $array[1];
			
			if($appPackage === "com.x10host.httpmyf00d.myf00d") { //if accessed via MyF00d android application
				//send user data to app
				$user_data = array ('email' => $email, 'password' => $userPassword, 'fName' => $first_name, 'hID' => $hID);
				echo json_encode($user_data); 
				exit();
			}
		}
	}
	$conn->close();
?>