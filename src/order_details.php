<!DOCTYPE html>
<html>
<head>
    <title>Order Details</title>  
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
         * @file order_details.php
         * 
         * @brief This is the page where order details can be seen both from employees and customers.
         *        The view depends on where the user is coming from.
         * 
         * @author David Petrovski
         * @author Isabelle Coletti
         * @author Amanda Zedwick
         * 
         * CSCI 466 - 1
         */

         
        include("functions.php"); // Gives the file with the login window creation function
        include("secrets.php"); // Logs into the db
        include("header.php"); // Creates the header of the page


        
        if(isset($_GET["EmpID"]))          // Checks if an employee is visiting the page
        {
            // Creates a return button to the orders page for employees
			create_return_btn("./orders.php", 2);

			// Store EmpID, OrderID for later
			$emp = $_GET["EmpID"];
			$ID = $_GET["OrderID"];

			// Check if notes were added
			if (isset($_POST['add_notes']))
			{
				// If so, save the notes
				$newNote = $_POST['Notes'];
				// Then update the Orders table with the notes
				$updateSQL = "UPDATE Orders SET Notes=? WHERE OrderID=?";
				$result = $pdo->prepare($updateSQL);
				$result->execute(array($newNote, $ID));
			}
			else	// Otherwise, no notes were added
			{
				$newNote = null;
			}
			
			// Check if the "Ship Order" button was clicked
			if(isset($_POST['ship_order']))
			{
				// Generate a random tracking number
				$trackNum = rand(10000000,99999999);
					
				// Updated the Orders table to change the status to shipped and put in the tracking number
				$updateSQL = "UPDATE Orders SET Status=3, TrackingNum=? WHERE OrderID=?";
				$result = $pdo->prepare($updateSQL);
				$result->execute(array($trackNum, $ID));

				// Redirect to the orders page
				header("Location: ./orders.php?EmpID=".$emp);
			}
			
			// Sql to view details of the order
			$sql = "SELECT * FROM Orders WHERE OrderID=?";
			$result = $pdo->prepare($sql);
			$result->execute(array($ID));

			$result = $result->fetchAll(PDO::FETCH_ASSOC);
			
			// Create a table to show the order information
			echo "<table class=\"orders\" style=\"top:60px;\" cellpadding=35>";
			echo "<tr><td>";
			echo "<table border=1 style=\"border: solid; top:100px;\" cellpadding=5>";

			echo "<tr bgcolor=\"#8AA29E\">";
				echo "<th style='text-align:center' colspan=7> Details on Order $ID </th>";
			echo "</tr>";

			echo "<tr bgcolor=\"#8AA29E\">";
				// Print labels for the table
				echo "<th style='text-align:center'> Assigned Employee </th>";
				echo "<th style='text-align:center'> Customer Name </th>";
				echo "<th style='text-align:center'> Customer Email </th>";
				echo "<th style='text-align:center'> Order Total </th>";
				echo "<th style='text-align:center'> Status </th>";
				echo "<th style='text-align:center'> Tracking Number </th>";
				echo "<th style='text-align:center'> Shipping Address </th>";
			echo "</tr>";

			foreach($result as $row)
			{
				// Save the status of the order
				$order_status = $row["Status"];
				// Ensure status of order is shown as a word, not a number
				if ($row['Status'] == 2)
				{
					$stat="<b>Processing</b>";
				}
				else if ($row['Status'] == 3)
				{
					$stat = "<p style=\"color:#049a89; font-weight:bold;\">Shipped</p>";
				}

				// Ensure something still shows if a tracking number isn't present
				if (isset($row['TrackingNum']))
				{
					$track = $row['TrackingNum'];
				}
				else
				{
					$track = "Order Not Shipped";
				} 

				// Get notes info
				if (isset($row['Notes']))
				{
					$notes = $row['Notes'];
				}
				else
				{
					$notes = "No notes have been added to this order yet.";
				}

				// Get the customer's name and email
				$customer_info = $pdo->prepare("SELECT Name, Email FROM Customers WHERE Username=?");
				$customer_info->execute(array($row["Username"]));

				$customer_info = $customer_info->fetch(PDO::FETCH_ASSOC);
				// Save the customer's name
				$customer_name = $customer_info["Name"];
				// Save the customer's email
				$customer_email = $customer_info["Email"];
?>
		
		<tr bgcolor="#FAFAFA">
			<td style="text-align:center"> 
			<?php 
				if($row["EmpID"] == NULL) // Checks if the order doesn't have an employee assigned to it
				{
					// Creates a form with a button for the employee to be able to choose the order to complete it
					echo "<form action=\"./orders.php\">";
													
					// Sends the EmpID to have for later
					echo "<input type=\"hidden\" name=\"EmpID\" value=".$_GET["EmpID"]." />";
													
					// Sends the OrderID of the order that the employee clicks on
					echo "<input type=\"hidden\" name=\"OrderID\" value=".$row["OrderID"]." />";

					// Creates the button to assign an order to the employee
					echo "<input type=\"submit\" name=\"assign_order\" value=\"Assign To Me\" class=\"assign_order_btn\"/>";
					echo "</form>";
				}
				else				      // Order has an assigned employee and it displays their name	
				{
					// Gets the assigned employee's name
					$result2 = $pdo->prepare("SELECT Name FROM Employees WHERE EmpID=?");
					$result2->execute(array($row['EmpID']));
													
					// Fetches the assigned employee's name
					$emp_name = $result2->fetch(PDO::FETCH_ASSOC);

					// Displays the assigned employee's name
					echo $emp_name["Name"];
				}
			?> 
			</td>
			<!-- Display the customer's information -->
			<td style="text-align:center"> <?php echo "$customer_name"; ?> </td>
			<td style="text-align:center"> <?php echo "$customer_email"; ?> </td>
			<td style="text-align:center"> <?php echo "$".number_format($row["Total"],2); ?> </td>
			<td style="text-align:center"> <?php echo "$stat"; ?> </td>
			<td style="text-align:center"> <?php echo "$track"; ?> </td>
			<td style="text-align:center"> <?php echo "$row[Address]"; ?> </td>
		</tr>

		<tr bgcolor="8AA29E">
			<!-- Display the contents of the order -->
			<th style="text-align:center" colspan=7>Items Ordered</th>
		</tr>

		<tr bgcolor="8AA29E">
			<th style="text-align:center" colspan=2> Product </th>
			<th style="text-align:center" colspan=3> Amount </th>
			<th style="text-align:center" colspan=2> Price Per Unit </th>

		</tr>

<?php

		// Get the information from the Carts table on the order
		$sql3 = "SELECT * FROM Carts WHERE OrderID=?";
		$result3 = $pdo->prepare($sql3);
		$result3->execute(array($ID));

		$result3 = $result3->fetchAll(PDO::FETCH_ASSOC);

		// Loop through every product in the cart
		foreach($result3 as $row3)
		{
			// Save the product ID
			$prodID = $row3['ProductID'];
	
			// Get product name from ID
			$sql4 = "SELECT Name, Price FROM Products WHERE ProductID=?";
			$result4 = $pdo->prepare($sql4);
			$result4->execute(array($prodID));

			$result4 = $result4->fetchAll(PDO::FETCH_ASSOC);

			foreach($result4 as $row4)
			{
				if (isset($row4['Name']))
				{
					// Save the product name
					$prodName = $row4['Name'];
				}

				if (isset($row4['Price']))
				{
					// Save the product price
					$price = $row4['Price'];
				}
			}
			echo "<tr bgcolor=#FAFAFA>";
			// Display the product information
			echo "<td style=text-align:center colspan=2> $prodName </td>";
			echo "<td style=text-align:center colspan=3> $row3[Amount] </td>";
			echo "<td style=text-align:center colspan=2> $".number_format($price, 2)."</td>";
			echo "</tr>";
		}

?>
		<tr bgcolor="8AA29E">
			<th style="text-align:center" colspan=7> Notes </th>
		</tr>

<?php 
			// Displaying order notes
			if (isset($row['Notes']))
			{
				echo "<tr bgcolor=#FAFAFA> <td style=text-align:center colspan=7> $row[Notes] </td> </tr>";
			}
			else
			{
				echo "<tr bgcolor=#FAFAFA> <td style=text-align:center colspan=7> No notes currently for this order. </td> </tr>";
			}

		echo "</table></td>"; 
		
		}

		// If there is an employee assigned to the order and if it is this employee and if the order isn't shipped
		if ($row["EmpID"] != NULL and $emp == $row["EmpID"] and $order_status == 2)
		{
?>
				<td>
				<form method="POST">

				<!-- Display an input box for notes to be added -->
					Enter Notes Below: <br><br>
					<textarea style="height:100px; width:500px; text-align:left; resize:none;" name="Notes" maxlength="255"></textarea>
					<br>
					<input type="submit" name="add_notes" value="Add Notes"/>
				</form>
				</td>
				<tr>
					<td colspan=2>
						<!-- Display a button for the employee to click to ship the order -->
						<form method="POST" style="text-align: center">
							<input type="hidden" name="EmpID" value="<?php echo $emp; ?>" />
							<input type="submit" name="ship_order" value="Ship Order" class="shipped_btn" />
						</form>
					</td>
				</tr>
			</table>

<?php
		}
	}
	
        else if (isset($_GET["Username"])) // Checks if a customer is visiting the page
        {
            // Creates a return button to the order history page for the customer
			create_return_btn("./order_history.php", 1);


			// Create stat variable for later
			$stat = "empty";

			// Store OrderID for later
			$ID = $_GET["OrderID"];
			
			// Sql to view details of the order
			$sql="SELECT * FROM Orders WHERE OrderID=?";
			$result = $pdo->prepare($sql);
			$result->execute(array($ID));

			$result = $result->fetchAll(PDO::FETCH_ASSOC);

			// Create a table to display the order information
			echo "<table border=1 style=\"border: solid; top:100px;\" class=\"orders\" cellpadding=5>";

			echo "<tr bgcolor=\"#8AA29E\">";
				echo "<th style='text-align:center' colspan=6> Details on Order $ID </th>";
			echo "</tr>";

			echo "<tr bgcolor=\"#8AA29E\">";
				// Display the customer's information
				echo "<th style='text-align:center'> Customer Name </th>";
				echo "<th style='text-align:center'> Order Total </th>";
				echo "<th style='text-align:center'> Status </th>";
				echo "<th style='text-align:center'> Tracking Number </th>";
				echo "<th style='text-align:center'> Shipping Address </th>";
			echo "</tr>";

			foreach($result as $row)
			{
				// Ensure status of order is shown as a word, not a number
				if ($row['Status'] == 2)
				{
					$stat="<b>Processing</b>";
				}
				else if ($row['Status'] == 3)
				{
					$stat = "<p style=\"color:green; font-weight:bold;\">Shipped</p>";
				}


				// Ensure something still shows if a tracking number isn't present
				if (isset($row['TrackingNum']))
				{
					$track = $row['TrackingNum'];
				}
				else
				{
					$track = "Order Not Shipped";
				} 
				
				// Get notes info
				if (isset($row['Notes']))
				{
					$notes = $row['Notes'];
				}
				else
				{
					$notes = "No notes have been added to this order yet.";
				}

				// Get the customer's name
				$customer_name = $pdo->prepare("SELECT Name FROM Customers WHERE Username=?");
				$customer_name->execute(array($row["Username"]));

				$customer_name = $customer_name->fetch(PDO::FETCH_ASSOC);
				// Save the customer's name
				$customer_name = $customer_name["Name"];
?>
		
		<tr bgcolor="#FAFAFA">
			<!-- Display the customer's information -->
			<td style="text-align:center"> <?php echo "$customer_name"; ?> </td>
			<td style="text-align:center"> <?php echo "$".number_format($row["Total"],2); ?> </td>
			<td style="text-align:center"> <?php echo "$stat"; ?> </td>
			<td style="text-align:center"> <?php echo "$track"; ?> </td>
			<td style="text-align:center"> <?php echo "$row[Address]"; ?> </td>
		</tr>

		<tr bgcolor="8AA29E">
			<!-- Display the contents of the order -->
			<th style="text-align:center" colspan=6> Info on Items Ordered </th>
		</tr>

		<tr bgcolor="8AA29E">
			<th style="text-align:center" colspan=2> Product </th>
			<th style="text-align:center" colspan=2> Amount </th>
			<th style="text-align:center" colspan=2> Price Per Unit </th>
		</tr>

<?php

		// Get the information from the Carts table on the order
		$sql3 = "SELECT * FROM Carts WHERE OrderID=?";
		$result3 = $pdo->prepare($sql3);
		$result3->execute(array($ID));

		$result3 = $result3->fetchAll(PDO::FETCH_ASSOC);

		foreach($result3 as $row3)
		{
			// Save the product ID
			$prodID = $row3['ProductID'];
			// Get product name from ID
			$sql4 = "SELECT Name, Price FROM Products WHERE ProductID=?";
			$result4 = $pdo->prepare($sql4);
			$result4->execute(array($prodID));

			$result4 = $result4->fetchAll(PDO::FETCH_ASSOC);

			foreach($result4 as $row4)
			{
				if (isset($row4['Name']))
				{
					// Save the product name
					$prodName = $row4['Name'];
				}
				else
				{
					$prodName = "Unknown";
				}

				if (isset($row4['Price']))
				{
					// Save the product price
					$price = $row4['Price'];
				}
				else
				{
					$price = "Unknown";
				}
			}
			echo "<tr bgcolor=#FAFAFA>";
			// Display the product information
			echo "<td style=text-align:center colspan=2> $prodName </td>";
			echo "<td style=text-align:center colspan=2> $row3[Amount] </td>";
			echo "<td style=text-align:Center colspan=2> $".number_format($price, 2)." </td>";
			echo "</tr>";
		}

?>


		<tr bgcolor="8AA29E">
			<th style="text-align:center" colspan=6> Notes </th>
		</tr>


<?php 
			// Displaying order notes
			if (isset($row['Notes']))
			{
				echo "<tr bgcolor=#FAFAFA> <td style=text-align:center colspan=6> $row[Notes] </td> </tr>";
			}
			else
			{
				echo "<tr bgcolor=#FAFAFA> <td style=text-align:center colspan=6> No notes currently for this order. </td> </tr>";
			}

		// End the table
		echo "</table>"; 

		} 

        } ?>
</body></html>