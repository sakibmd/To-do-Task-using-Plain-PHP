<?php 
include_once "config.php"; 
include_once "tasks.php";

$connection = mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);
if(!$connection){
	throw new Exception("Not Connected");
}
else{
	echo "connected";

	//create a record
	//echo mysqli_query($connection, "INSERT INTO task (task,date) VALUES ('Do Something More','2019-11-12')");

	$result = mysqli_query($connection, "SELECT * FROM task");
	while($data = mysqli_fetch_assoc($result)){
		echo "<pre>";
		print_r($data);
		echo "</pre>";
	}



	mysqli_close($connection); //connection off
}


















?>