<?php
	//fetches food associated to household of logged in user (if visible)
	//sends data back to Android application

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
		//query for selecting food information associated with the household of user
		$query = "SELECT id, name, category, quantity_have, unit,entry_date,expiry_date,price,notes 
		FROM user,food 
		WHERE household_id = hID AND user.email = ? AND user.password = ? AND visible = 1 ORDER BY name, expiry_date";
		
		//user information recieved from application
		$email = $_POST['email'];
		$userPassword = $_POST['password'];
		$appPackage = $_POST['appPackage'];
		
		//run query on database
		$stmt = $conn->prepare($query);
		$stmt->bind_param("ss", $email, $userPassword);
		$stmt->execute();
		$stmt->bind_result($rID, $rName, $rCategory, $rQuantity_have, $rUnitofMeasurement, $rEntryDate, $rExpiryDate, $rImage, $rNotes);
		
		//if script run by Android application
		if($appPackage === "com.x10host.httpmyf00d.myf00d") {
			$preparedArray;
			//add each record to an array
			while($stmt->fetch()){
				$arrayRow = array ('id'=>$rID, 'name' => $rName, 'category' => $rCategory, 
				'quantity_have' => $rQuantity_have,'unit_of_measurement'=>$rUnitofMeasurement,
				'entry_date'=>$rEntryDate, 'expiry_date'=>$rExpiryDate, 'price'=>$rPrice, 'image'=>$rImage, 'notes'=>$rNotes);
				
				$preparedArray[] = $arrayRow;
			}
			//change array to a JSON array to be compatible with Android app
			$response = "{\"food\":" . json_encode($preparedArray) . "}";
			//send food information
			echo $response;
			exit();
		}
		$stmt->close();
	}
?>