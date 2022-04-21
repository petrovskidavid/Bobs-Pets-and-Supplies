<!DOCTYPE html>
<html>
<head>
    <title>Employee Home</title>  
    <link rel="stylesheet" type="text/css" href="../assets/css/body.css" />
    <link rel="stylesheet" type="text/css" href="../assets/css/header.css" />
    <link rel="stylesheet" type="text/css" href="../assets/css/login.css" />
    <link rel="stylesheet" type="text/css" href="../assets/css/button.css" />
</head>

<body>
    <?php
        /**
         * @file employee_home.php
         * 
         * @brief This is the employee home page.
         *        This is where they can access the orders they need to process.
         * 
         * @author David Petrovski
         * @author Isabelle Coletti
         * @author Amanda Zedwick
         * 
         * CSCI 466 - 1
         */

         
        include("header.php"); // Creates the header of the page
        include("secrets.php"); // Logs into the db
	include("functions.php"); // Gives the file with the login window creation function

	$sql="SELECT OrderID, TrackingNum, Address, Status FROM Orders WHERE Status='received'";

	$result = $pdo->query($sql);
	$result->setFetchMode(PDO::FETCH_ASSOC);

	echo "h2 text-align='center'> Orders </h2>";

	echo "<table border=1>";
	echo "<tr>";
		echo "<th style='text-align:center'> OrderID </th>";
		echo "<th style='text-align:center'> TrackingNum </th>";
		echo "<th style='text-align:center'> Address </th>";
		echo "<th style='text-align:center'> Status </th>";
		echo "<th style='text-align:center'> View Details </th>";
	echo "</tr>";
	
	while ($row = $result->fetch()):
		echo "<tr>";
			echo "<td style='text-align:center'> $row[OrderID] </td>";
			echo "<td style='text-align:center'> $row[TrackingNum] </tr>";
			echo "<td style='text-align:center'> $row[Address] </tr>";
			echo "<td style='text-align:center'> $row[Status] </tr>";
			echo   "<td style='text-align:center'>
				<form action='./src/order_details.php'>
					<input type='submit' name='submit' value='View Order Details'/> </form> </td>";
		echo "</tr>";
	endwhile

	echo "</table>";

?>



