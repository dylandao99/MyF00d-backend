<?php
	//Registers or joins user to a household
	//Sets user table "household_id" foreign key to match that of the
	//household specified by a code and a password

	//SQL database login informations
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
		session_start();
		
		//get input
		$code = $_POST['code'];
		$hPassword = $_POST['password'];
		$email = $_POST['email'];
	
		
		//if accessed from MyF00d application
		if ($_POST['appPackage'] === "com.x10host.httpmyf00d.myf00d"){
				if ($_POST['register'] === "true"){ //if registering household
					$_POST['register'] = true;
				} else {//if joining household
					$_POST['register'] = false;
				}
			}

		if ($_POST["register"]){//if registering household
			
			//create new household record with code and password entered by user
			$query = "INSERT INTO household (code, password) VALUES (?, ?)";
			
			//run query
			$stmt = $conn->prepare($query);
			
			//get query result
			$stmt->bind_param("ss", $code, $hPassword);
			if ($stmt->execute() === false){//if query failed to execute (SHOULD NOT HAPPEN)
					echo "400";
					exit();
				}
			$stmt->close();
		}
		
		//join user to household
		//get family id #
		$query = "SELECT id FROM household WHERE code = \"$code\" AND password = \"$hPassword\"";
		
		//run query on database
		$rawResult = $conn->query($query);
		if ($rawResult->num_rows === 0){//household does not exist
			echo "401";
			exit();
		} else { //household specified by code and password exists
			//get household id
			$array = $rawResult->fetch_array(MYSQLI_NUM);
			if ($_POST['appPackage'] === "com.x10host.httpmyf00d.myf00d"){
				//set user "household_id" foreign key to fetched household ID
				$query = "UPDATE user SET household_id=? WHERE email = ?";
			
				$stmt = $conn->prepare($query);
				$stmt->bind_param("is", $array[0], $email);
				$stmt->execute();
				$stmt->close();
				exit();
			}
		}
	}
	$conn->close();
?>