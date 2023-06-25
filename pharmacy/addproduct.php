<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	// Retrieve the product data from the form
	$productName = $_POST['productName'];
	$productPrize = $_POST['productPrize'];
	$productQuantity = $_POST['productquantity'];
	$expiryDate = $_POST['expirydate'];

	// Create a connection to the MySQL database
	$servername = "localhost";
	$username = "root";
	$password = "";
	$dbname = "productex";

	$conn = new mysqli($servername, $username, $password, $dbname);

	// Check the connection
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	// Prepare and execute the SQL statement to insert the product data into the database
	$sql = "INSERT INTO extable (product_name, product_prize, product_quantity, expiry_date) VALUES ('$productName', '$productPrize', '$productQuantity', '$expiryDate')";

	if ($conn->query($sql) === TRUE) {
		echo "Product added successfully.";
	} else {
		echo "Error: " . $sql . "<br>" . $conn->error;
	}

	// Close the database connection
	$conn->close();
}
?>
