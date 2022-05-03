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
         *        The view depends on where the user is coming from
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

			//store EmpID, OrderID for later
			$emp = $_GET["EmpID"];
			$ID = $_GET["OrderID"];

			if (isset($_POST['add_notes']))
			{
				$newNote = $_POST['Notes'];
				$updateSQL = "UPDATE Orders SET Notes=? WHERE OrderID=?";
				$result = $pdo->prepare($updateSQL);
				$result->execute(array($newNote, $ID));
			}
			else
			{
				$newNote = null;
			}
			
			if(isset($_POST['ship_order']))
			{
				$trackNum = rand(10000000,99999999);
					
				$updateSQL = "UPDATE Orders SET Status=3, TrackingNum=? WHERE OrderID=?";
				$result = $pdo->prepare($updateSQL);
				$result->execute(array($trackNum, $ID));

				header("Location: ./orders.php?EmpID=".$emp);
			}
			
			//sql to view details of the order
			$sql="SELECT * FROM Orders WHERE OrderID='$ID'";

			$result = $pdo->query($sql);
			$result = $result->fetchAll(PDO::FETCH_ASSOC);
			
			echo "<table class=\"orders\" style=\"top:60px;\" cellpadding=35>";
			echo "<tr><td>";
			echo "<table border=1 style=\"border: solid; top:100px;\" cellpadding=5>";

			echo "<tr bgcolor=\"#8AA29E\">";
				echo "<th style='text-align:center' colspan=7> Details on Order $ID </th>";
			echo "</tr>";

			echo "<tr bgcolor=\"#8AA29E\">";
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
				$order_status = $row["Status"];
				//ensure status of order is shown as a word, not a number
				if ($row['Status'] == 2)
				{
					$stat = "Processing";
				}
				else if ($row['Status'] == 3)
				{
					$stat = "Shipped";
				}

				//ensure something still shows if a tracking number isn't present
				if (isset($row['TrackingNum']))
				{
					$track = $row['TrackingNum'];
				}
				else
				{
					$track = "Order Not Shipped";
				} 

				//get the ID of the employee assigned to the order
				$employee = $row['EmpID'];
				
				//get the assigned employee's name (display ID if no name set)
				$sql2 = "SELECT Name FROM Employees WHERE EmpID='$employee'";

				$result2 = $pdo->query($sql2);
				$result2 = $result2->fetch(PDO::FETCH_ASSOC);
				

				//get notes info
				if (isset($row['Notes']))
				{
					$notes = $row['Notes'];
				}
				else
				{
					$notes = "No notes have been added to this order yet.";
				}

				$customer_info = $pdo->prepare("SELECT Name, Email FROM Customers WHERE Username=?");
				$customer_info->execute(array($row["Username"]));

				$customer_info = $customer_info->fetch(PDO::FETCH_ASSOC);
				$customer_name = $customer_info["Name"];
				$customer_email = $customer_info["Email"];
?>
		
		<tr bgcolor="#FAFAFA">
			<td style="text-align:center"> <?php echo $result2['Name']; ?> </td>
			<td style="text-align:center"> <?php echo "$customer_name"; ?> </td>
			<td style="text-align:center"> <?php echo "$customer_email"; ?> </td>
			<td style="text-align:center"> <?php echo "$".number_format($row["Total"],2); ?> </td>
			<td style="text-align:center"> <?php echo "$stat"; ?> </td>
			<td style="text-align:center"> <?php echo "$track"; ?> </td>
			<td style="text-align:center"> <?php echo "$row[Address]"; ?> </td>
		</tr>

		<tr bgcolor="8AA29E">
			<th style="text-align:center" colspan=7>Items Ordered</th>
		</tr>

		<tr bgcolor="8AA29E">
			<th style="text-align:center" colspan=2> Product </th>
			<th style="text-align:center" colspan=3> Amount </th>
			<th style="text-align:center" colspan=2> Price Per Unit </th>

		</tr>

<?php

		$sql3 = "SELECT * FROM Carts WHERE OrderID='$ID'";
		
		$result3 = $pdo->query($sql3);
		$result3 = $result3->fetchAll(PDO::FETCH_ASSOC);

		foreach($result3 as $row3)
		{
			//get product name from id
			$prodID = $row3['ProductID'];
	
			$sql4 = "SELECT Name, Price FROM Products WHERE ProductID=$prodID";

			$result4 = $pdo->query($sql4);
			$result4 = $result4->fetchAll(PDO::FETCH_ASSOC);

			foreach($result4 as $row4)
			{
				if (isset($row4['Name']))
				{
					$prodName = $row4['Name'];
				}

				if (isset($row4['Price']))
				{
					$price = $row4['Price'];
				}
			}
			echo "<tr bgcolor=#FAFAFA>";
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
			//displaying order notes
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

		//if the employee is assigned to the order...
		if ($emp == $employee and $order_status == 2)
		{
?>
				<td>
				<form method="POST">

					Enter Notes Below: <br><br>
					<textarea style="height:100px; width:500px; text-align:left; resize:none;" name="Notes" maxlength="255"></textarea>
					<br>
					<input type="submit" name="add_notes" value="Add Notes"/>
				</form>
				</td>
				<tr>
					<td colspan=2>
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


			//create stat variable for later
			$stat = "empty";

			//store OrderID for later
			$ID = $_GET["OrderID"];
			
			//sql to view details of the order
			$sql="SELECT * FROM Orders WHERE OrderID='$ID'";

			$result = $pdo->query($sql);
			$result = $result->fetchAll(PDO::FETCH_ASSOC);

			echo "<table border=1 style=\"border: solid; top:100px;\" class=\"orders\" cellpadding=5>";

			echo "<tr bgcolor=\"#8AA29E\">";
				echo "<th style='text-align:center' colspan=6> Details on Order $ID </th>";
			echo "</tr>";

			echo "<tr bgcolor=\"#8AA29E\">";
				echo "<th style='text-align:center'> Customer Name </th>";
				echo "<th style='text-align:center'> Order Total </th>";
				echo "<th style='text-align:center'> Status </th>";
				echo "<th style='text-align:center'> Tracking Number </th>";
				echo "<th style='text-align:center'> Shipping Address </th>";
			echo "</tr>";

			foreach($result as $row)
			{
				//ensure status of order is shown as a word, not a number
				if ($row['Status'] == 2)
				{
					$stat = "Processing";
				}
				else if ($row['Status'] == 3)
				{
					$stat = "Shipped";
				}


				//ensure something still shows if a tracking number isn't present
				if (isset($row['TrackingNum']))
				{
					$track = $row['TrackingNum'];
				}
				else
				{
					$track = "Order Not Shipped";
				} 
				
				//get notes info

				if (isset($row['Notes']))
				{
					$notes = $row['Notes'];
				}
				else
				{
					$notes = "No notes have been added to this order yet.";
				}

				$customer_name = $pdo->prepare("SELECT Name FROM Customers WHERE Username=?");
				$customer_name->execute(array($row["Username"]));

				$customer_name = $customer_name->fetch(PDO::FETCH_ASSOC);
				$customer_name = $customer_name["Name"];
?>
		
		<tr bgcolor="#FAFAFA">
			<td style="text-align:center"> <?php echo "$customer_name"; ?> </td>
			<td style="text-align:center"> <?php echo "$".number_format($row["Total"],2); ?> </td>
			<td style="text-align:center"> <?php echo "$stat"; ?> </td>
			<td style="text-align:center"> <?php echo "$track"; ?> </td>
			<td style="text-align:center"> <?php echo "$row[Address]"; ?> </td>
		</tr>

		<tr bgcolor="8AA29E">
			<th style="text-align:center" colspan=6> Info on Items Ordered </th>
		</tr>

		<tr bgcolor="8AA29E">
			<th style="text-align:center" colspan=2> Product </th>
			<th style="text-align:center" colspan=2> Amount </th>
			<th style="text-align:center" colspan=2> Price Per Unit </th>
		</tr>

<?php

		$sql3 = "SELECT * FROM Carts WHERE OrderID='$ID'";
		
		$result3 = $pdo->query($sql3);
		$result3 = $result3->fetchAll(PDO::FETCH_ASSOC);

		foreach($result3 as $row3)
		{
			//get product name from id
			$prodID = $row3['ProductID'];
	
			$sql4 = "SELECT Name, Price FROM Products WHERE ProductID=$prodID";

			$result4 = $pdo->query($sql4);
			$result4 = $result4->fetchAll(PDO::FETCH_ASSOC);

			foreach($result4 as $row4)
			{
				if (isset($row4['Name']))
				{
					$prodName = $row4['Name'];
				}
				else
				{
					$prodName = "Unknown";
				}

				if (isset($row4['Price']))
				{
					$price = $row4['Price'];
				}
				else
				{
					$price = "Unknown";
				}
			}
			echo "<tr bgcolor=#FAFAFA>";
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
			//displaying order notes
			if (isset($row['Notes']))
			{
				echo "<tr bgcolor=#FAFAFA> <td style=text-align:center colspan=6> $row[Notes] </td> </tr>";
			}
			else
			{
				echo "<tr bgcolor=#FAFAFA> <td style=text-align:center colspan=6> No notes currently for this order. </td> </tr>";
			}

		echo "</table>"; 

		} 

        } ?>
</body></html>