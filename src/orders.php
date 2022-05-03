<!DOCTYPE html>
<html>
<head>
    <title>Orders</title>  
    <link rel="stylesheet" type="text/css" href="../assets/css/body.css" />
    <link rel="stylesheet" type="text/css" href="../assets/css/header.css" />
    <link rel="stylesheet" type="text/css" href="../assets/css/button.css" />
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

		$sql="SELECT * FROM Orders WHERE (Status='2' OR Status='3')";

		$result = $pdo->query($sql);
		$result = $result->fetchAll(PDO::FETCH_ASSOC); 

		// Creates a return button to the employee home page.
		create_return_btn("./employee_home.php", 2);

		// Checks if an employee clicked the assign order button
		if(isset($_GET["assign_order"]))
		{
			// Prepares query to assign the employee to the order they chose
			$rows = $pdo->prepare("UPDATE Orders SET EmpID=? WHERE OrderID=?");

			// Execute query
            $rows->execute(array($_GET["EmpID"], $_GET["OrderID"]));

			// Refresh the screen to have the order move to the employees table of their assigned orders
			header("Location: ./orders.php?EmpID=".$_GET["EmpID"]."&assigned");
		}
	?>

	<h2 style="text-align:center">Bob's Pets and Supplies Orders</h2>

<?php

	// Additional table that is used to display both tables in center of page
	echo "<table class=\"orders\" cellpadding=35>";
	echo "<td>";
	if(empty($result))
	{
		echo "<h4>There are no orders to process at this time.</h4>";
	}
	else
	{

		// All Orders table
		echo "<table border=1 style=\"border: solid;\" cellpadding=5>";

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

		foreach($result as $row)
		{ 
			if ($row['Status'] == 2)
			{
				$stat="<b>Processing</b>";
			}
			else
			{
				$stat = "<p style=\"color:#049a89; font-weight:bold;\">Shipped</p>";
			} 
			
?>
		<tr bgcolor="#FAFAFA">
			<td style="text-align:center"> <?php echo "$row[OrderID]"; ?> </td>
			<td style="text-align:center"> <?php 
												if($row["EmpID"] == NULL) // Checks if the order doesn't have an employee assigned to it
												{
													// Creates a form with a button for the employee to be able to choose the order to complete it
													echo "<form>";
													
													// Sends the EmpID to have for later
													echo "<input type=\"hidden\" name=\"EmpID\" value=".$_GET["EmpID"]." />";
													
													// Sends the OrderID of the Order that the employee clicks on
													echo "<input type=\"hidden\" name=\"OrderID\" value=".$row["OrderID"]." />";

													// Creates the button to assign an order to the employee
													echo "<input type=\"submit\" name=\"assign_order\" value=\"Assign To Me\" class=\"assign_order_btn\"/>";
													echo "</form>";
												}
												else				      // Order has an assigned employee and it displays their name	
												{
													// Gets the assigned employees name
													$result1 = $pdo->prepare("SELECT Name FROM Employees WHERE EmpID=?");
													$result1->execute(array($row['EmpID']));
													
													// Fetches the assigned employees name
													$emp_name = $result1->fetch(PDO::FETCH_ASSOC);

													// Displays the assigned employees name
													echo $emp_name["Name"];
												}
			?> </td>
			<td style="text-align:center"> <?php echo "$row[TrackingNum]"; ?> </td>
			<td style="text-align:center"> <?php echo "$row[Address]"; ?> </td>
			<td style="text-align:center"> <?php echo "$stat"; ?> </td>
			<td style="text-align:center"> <form action="./order_details.php"> <input type="submit" name="submit" value="View Order Details"/> <input type="hidden" name="EmpID" value=<?php echo $_GET["EmpID"]; ?> /> <input type="hidden" name="OrderID" value=<?php echo $row["OrderID"]; ?> ></form> </td>
		</tr>
<?php   }
	    echo "</table>";
	}
	echo "</td>";

	$sql2="SELECT OrderID, TrackingNum, Address FROM Orders WHERE Status='2' AND EmpID=?";

	$result2 = $pdo->prepare($sql2);
	$result2->execute(array($_GET['EmpID']));
	$result2 = $result2->fetchAll(PDO::FETCH_ASSOC);

	echo "<td>";
	// Checks if the employee has any orders to process, if not it prints a message
	if(empty($result2))
	{
		echo "<h4>You don't have any orders to process at the moment. Congrats!</h4>";
	}
	else
	{
		// Employees Orders table
		echo "<table border=1 style=\"border: solid;\" cellpadding=5>";

		echo "<tr bgcolor=\"#8AA29E\">";
			echo "<th style='text-align:center' colspan=5> Your Unprocessed Orders </th>";
		echo "</tr>";

		echo "<tr bgcolor=\"#8AA29E\">";
			echo "<th style='text-align:center'> OrderID </th>";
			echo "<th style='text-align:center'> TrackingNum </th>";
			echo "<th style='text-align:center'> Shipping Address </th>";
			echo "<th style='text-align:center'></th>";
		echo "</tr>";

		foreach($result2 as $row2)
		{ ?> 
			<tr bgcolor="#FAFAFA">
				<td style="text-align:center"> <?php echo "$row2[OrderID]"; ?> </td>
				<td style="text-align:center"> <?php echo "$row2[TrackingNum]"; ?> </td>
				<td style="text-align:center"> <?php echo "$row2[Address]"; ?> </td>
				<td style="text-align:center"> <form action="./order_details.php"> <input type="submit" name="submit" value="View Order Details"/> <input type="hidden" name="EmpID" value=<?php echo $_GET["EmpID"]; ?> /> <input type="hidden" name="OrderID" value=<?php echo $row2["OrderID"]; ?> /> </form> </td>
			</tr>
	<?php   }
		echo "</table>";
	}
		
	echo "</td></table>";

?>
</body>
</html>




