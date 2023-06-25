<!DOCTYPE html>
<html>
<head>
    <title>Pharmacy Management System - Requests</title>
    <style>
		body {
			background-image: url("wallpaper.jpg");
			background-size: cover;
			background-position: center;
			background-repeat: no-repeat;
			padding: 250px;
			font-family: Arial, sans-serif;
		}

		header {
			background-color: #53040f;
			color: #FFFFFF;
			display:flexbox;
			justify-content: space-between;
			align-items: center;
			padding: 10px;
			text-align: center;
			position: fixed;
			top: 0;
			left: 0;
			right: 0;
		}

		.logo {
			font-size: 24px;
			font-weight: bold;
		}

		nav {
			display: flex;
			justify-content: center;
			align-items: center;
			padding: 10px;
			border-radius: 200px;
			background-color: #FFFFFF;
			box-shadow: 0px 0px 10px 0px rgba(0, 0, 0, 0.5);
			margin-bottom: 20px;
		}

		nav a {
			color: #333333;
			text-decoration: none;
			font-size: 16px;
			margin: 0 10px;
			padding: 10px;
			border-radius: 20px;
			transition: all 0.3s ease;
		}

		nav a:hover {
			background-color: #53040f;
			color: #FFFFFF;
		}

		section {
			display: flex;
			flex-wrap: wrap;
			justify-content: center;
			align-items: center;
			margin: 20px;
		}

		.box {
  background-color: #FFFFFF;
  border-radius: 50%;
  box-shadow: 0px 0px 10px 0px rgba(0, 0, 0, 0.5);
  padding: 20px;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  position: relative;
  overflow: hidden;
  width: 190px;
  height: 190px;
  margin: 20px;
}


		.box::before {
			content: "";
			position: absolute;
			top: -100px;
			left: -100px;
			width: 200px;
			height: 200px;
			background-color: rgba(0, 0, 0, 0.05);
			border-radius: 50%;
			transform: rotate(45deg);
			z-index: -1;
		}

		h2 {
			font-size: 24px;
			margin-top: 0;
			margin-bottom: 10px;
		}

		p {
			font-size: 16px;
			margin-top: 0;
			margin-bottom: 20px;
		}

		button {
			background-color: #333;
			color: white;
			padding: 10px 16px;
			border: none;
			border-radius: 10px;
			cursor: pointer;
			font-size: 16px;
			margin-top: 20px;
		}

		.stats {
			display: flex;
			justify-content: center;
			align-items: center;
			color: #000000;
			padding: 20px;
			margin: 20px;
			border-radius: 10px;
			box-shadow: 0px 0px 10px 0px rgba(0, 0, 0, 0.5);
		}

		.stats div {
			margin: 0 20px;
			flex: 1;
			text-align: center;
		}

		.stats h3 {
			margin-top: 0;
			font-size: 24px;
		}

		.stats p {
			margin-bottom: 0;
			font-size: 16px;
		}
	</style>
    <script>
        // JavaScript code

        function handleDone(patientId) {
            // Send an AJAX request to the server to handle the action
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    // Handle the server response if needed
                    console.log(this.responseText);

                    // Remove the table row from the page
                    var row = document.getElementById("row-" + patientId);
                    row.parentNode.removeChild(row);
                }
            };
            xhttp.open("GET", "handle_request.php?action=done&patient_id=" + patientId, true);
            xhttp.send();
        }
    </script>
</head>
<body>
    <h2>Requests</h2>
    <table>
        <thead>
            <tr>
                <th>Patient ID</th>
                <th>Patient Name</th>
                <th>Prescription</th>
                <th>Medicines</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // PHP code

            // Establish a connection to the database (replace with your database credentials)
            $host = 'localhost';
            $dbName = 'patients';
            $username = 'root';
            $password = '';

            try {
                $pdo = new PDO("mysql:host=$host;dbname=$dbName", $username, $password);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                // Retrieve patient data from the "patients" table
                $stmt = $pdo->query('SELECT patient_id, patient_name, prescription, medicines FROM patients');
                $patients = $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                echo 'Connection failed: ' . $e->getMessage();
                die();
            }

            foreach ($patients as $patient) {
                echo '<tr id="row-' . $patient['patient_id'] . '">';
                echo '<td>' . $patient['patient_id'] . '</td>';
                echo '<td>' . $patient['patient_name'] . '</td>';
                echo '<td>' . $patient['prescription'] . '</td>';
                echo '<td>' . $patient['medicines'] . '</td>';
                echo '<td class="status-cell">Pending</td>';
                echo '<td class="action-cell">';
                echo '<button onclick="handleDone(' . $patient['patient_id'] . ')">Done</button>';
                echo '</td>';
                echo '</tr>';
            }
            ?>
        </tbody>
    </table>

    <?php
    // Handle the AJAX requests and perform database operations

    // Establish a connection to the database (replace with your database credentials)
    $host = 'localhost';
    $dbName = 'patients';
    $username = 'root';
    $password = '';

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbName", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Get the action and patient ID from the AJAX request
        $action = isset($_GET['action']) ? $_GET['action'] : '';
        $patientId = isset($_GET['patient_id']) ? $_GET['patient_id'] : '';

        // Handle the action based on the request
        switch ($action) {
            case 'done':
                deleteRequest($pdo, $patientId);
                break;
            default:
                // Invalid action
                break;
        }
    } catch (PDOException $e) {
        echo 'Connection failed: ' . $e->getMessage();
        die();
    }

    function deleteRequest($pdo, $patientId) {
        // Perform necessary database operations for deleting the request
        // For example, you can delete the record from the "patients" table
        $stmt = $pdo->prepare('DELETE FROM patients WHERE patient_id = :patientId');
        $stmt->bindParam(':patientId', $patientId);
        $stmt->execute();
    }

    // You can provide a response if needed
    // For example, you can return a success message
    ?>
</body>
</html>
