<?php session_start(); /* Start session to save username/EmpID */ ?>
<!DOCTYPE html>
<html>
<head>
    <title>Orders</title>  
    <link rel="stylesheet" type="text/css" href="../assets/css/body.css" />
    <link rel="stylesheet" type="text/css" href="../assets/css/header.css" />
    <link rel="stylesheet" type="text/css" href="../assets/css/button.css" />
	<script>
    if(window.history.replaceState){
        window.history.replaceState(null, null, window.location.href);
    }
    </script>
</head>

<body>
    <?php
        /**
         * @file orders.php
         * 
         * @brief This is the orders page where employees can see the orders they
		 * 		  need to process and all the other orders that were processed and shipped.
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

		// Creates a return button to the employee home page.
		create_return_btn("./employee_home.php", 2);

		// Checks if an employee wants to view order details
		if(isset($_POST["view_order"]))
        {
			// Saves order ID for the order details page and the type of view
            $_SESSION["view_orderID"] = $_POST["OrderID"];
            $_SESSION["type_of_view"] = "customer_view";

			// Redirects to the order details page
			header("Location: order_details.php");
        }

		// Checks if an employee clicked the assign order button
		if(isset($_POST["assign_order"]))
		{
			// Prepares query to assign the employee to the order they chose
			$rows = $pdo->prepare("UPDATE Orders SET EmpID=? WHERE OrderID=?");

			// Execute query
            $rows->execute(array($_SESSION["EmpID"], $_POST["OrderID"]));

			echo "<h4 style='position: absolute; left: 50%; top: 140px; transform: translate(-50%, 0); font-size: 18px; color: #049a89;'>You successfully assigned Order Number <u>".$_POST["OrderID"]."</u> to yourself.</h4>";
		}

		// Check if the "Ship Order" button was clicked
		if(isset($_POST['ship_order']))
		{
			// Generate a random tracking number
			$trackNum = rand(10000000,99999999);
							
			// Updated the Orders table to change the status to shipped and put in the tracking number
			$updateSQL = "UPDATE Orders SET Status=3, TrackingNum=? WHERE OrderID=?";
			$result = $pdo->prepare($updateSQL);
			$result->execute(array($trackNum, $_POST["OrderID"]));
			echo "<h4 style='position: absolute; left: 50%; top: 140px; transform: translate(-50%, 0); font-size: 18px; color: #049a89;'>You successfully shipped Order Number <u>".$_POST["OrderID"]."</u>.</h4>";
		}

		// Get all information for all orders that are processing or shipped
		$sql="SELECT * FROM Orders WHERE (Status='2' OR Status='3')";
		$result = $pdo->query($sql);

		$result = $result->fetchAll(PDO::FETCH_ASSOC); 
	?>

	<h2 style="text-align:center">Bob's Pets and Supplies Orders</h2>

<?php

	// Additional table that is used to display both tables in center of page
	echo "<table class=\"orders\" cellpadding=35>";
	echo "<tr><td>";
	if(empty($result))
	{
		echo "<h4 align=center>There are no orders to process at this time.</h4>";
	}
	else
	{
		// All orders table
		echo "<table border=1 style=\"border: solid;\" cellpadding=5>";

		// Display labels for order table
		echo "<tr bgcolor=\"#8AA29E\">";
			echo "<th style='text-align:center' colspan=6> All Orders </th>";
		echo "</tr>";

		echo "<tr bgcolor=\"#8AA29E\">";
			echo "<th style='text-align:center'> OrderID </th>";
			echo "<th style='text-align:center'> Assigned Employee </th>";
			echo "<th style='text-align:center'> TrackingNum </th>";
			echo "<th style='text-align:center'> Shipping Address </th>";
			echo "<th style='text-align:center'> Status </th>";
			echo "<th style='text-align:center'></th>";
		echo "</tr>";

		// Loop through every order
		foreach($result as $row)
		{ 
			// Check the status of the order
			if ($row['Status'] == 2)
			{
				$stat="<b>Processing</b>";
			}
			else
			{
				$stat = "<b style=\"color:#049a89;\">Shipped</b>";
			} 
			
?>
		<tr bgcolor="#FAFAFA">
			<!-- Print order information -->
			<td style="text-align:center"> <?php echo "$row[OrderID]"; ?> </td>
			<td style="text-align:center"> 
			<?php 
				if($row["EmpID"] == NULL) // Checks if the order doesn't have an employee assigned to it
				{
					// Creates a form with a button for the employee to be able to choose the order to complete it
					echo "<form method=POST>";

					// Sends the OrderID of the Order that the employee clicks on
					echo "<input type=\"hidden\" name=\"OrderID\" value=".$row["OrderID"]." />";

					// Creates the button to assign an order to the employee
					echo "<input type=\"submit\" name=\"assign_order\" value=\"Assign To Me\" class=\"assign_order_btn\"/>";
					echo "</form>";
				}
				else				      // Order has an assigned employee and it displays their name	
				{
					// Gets the assigned employee's name
					$result1 = $pdo->prepare("SELECT Name FROM Employees WHERE EmpID=?");
					$result1->execute(array($row['EmpID']));
													
					// Fetches the assigned employee's name
					$emp_name = $result1->fetch(PDO::FETCH_ASSOC);

					// Displays the assigned employee's name
					echo $emp_name["Name"];
				}
			?> </td>
			<!-- Print the rest of the order information -->
			<td style="text-align:center"> <?php echo "$row[TrackingNum]"; ?> </td>
			<td style="text-align:center"> <?php echo "$row[Address]"; ?> </td>
			<td style="text-align:center"> <?php echo "$stat"; ?> </td>
			<td style="text-align:center"> <form method="POST"> <input type="submit" name="view_order" value="View Order Details"/><input type="hidden" name="OrderID" value=<?php echo $row["OrderID"]; ?> /><input type="hidden" name="employee_view" /></form> </td>
		</tr>
<?php   }
		// End the table
	    echo "</table>";
	}
	echo "</td></tr>";

	// Get the order ID, tracking number, and address for all processing orders for the current employee logged in
	$sql2="SELECT OrderID, TrackingNum, Address FROM Orders WHERE Status='2' AND EmpID=?";
	$result2 = $pdo->prepare($sql2);
	$result2->execute(array($_SESSION['EmpID']));

	$result2 = $result2->fetchAll(PDO::FETCH_ASSOC);

	echo "<tr><td align=center>";
	// Checks if the employee has any orders to process, if not it prints a message
	if(empty($result2))
	{
		echo "<h4>You don't have any orders to process at the moment. Congrats!</h4>";
	}
	else
	{
		// Make employee's assigned orders table
		echo "<table border=1 style=\"border: solid;\" cellpadding=5>";

		// Display labels for employee's orders table
		echo "<tr bgcolor=\"#8AA29E\">";
			echo "<th style='text-align:center' colspan=5> Your Unprocessed Orders </th>";
		echo "</tr>";

		echo "<tr bgcolor=\"#8AA29E\">";
			echo "<th style='text-align:center'> OrderID </th>";
			echo "<th style='text-align:center'> TrackingNum </th>";
			echo "<th style='text-align:center'> Shipping Address </th>";
			echo "<th style='text-align:center'></th>";
		echo "</tr>";

		// Loop through every order assigned to the current employee logged in
		foreach($result2 as $row2)
		{ ?> 
			<!-- Display order information -->
			<tr bgcolor="#FAFAFA">
				<td style="text-align:center"> <?php echo "$row2[OrderID]"; ?> </td>
				<td style="text-align:center"> <?php echo "$row2[TrackingNum]"; ?> </td>
				<td style="text-align:center"> <?php echo "$row2[Address]"; ?> </td>
				<td style="text-align:center"> <form method="POST"> <input type="submit" name="view_order" value="View Order Details"/><input type="hidden" name="OrderID" value=<?php echo $row2["OrderID"]; ?> /><input type="hidden" name="employee_view" /></form> </td>
			</tr>
	<?php   
		}
		// End the employee's assgined orders table
		echo "</table>";
	}
	
	// End the formatting table
	echo "</td></tr></table>";

?>
</body>
</html>