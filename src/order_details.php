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


		//check if something was just resubmitted for order processing
		if (isset($_POST['Status']))
		{
			//update order if it's been changed
			if (isset($_POST['Status']))
			{
				$newStat = $_POST['Status'];

				if (isset($_POST['Notes']))
				{
					$newNote = $_POST['Notes'];
				}
				else
				{
					$newNote = null;
				}

				if ($newStat == "2")
				{
					$trackNum = null;
				}
				else if ($newStat == "3")
				{
					$trackNum = rand(10000000,99999999);
				}

				$updateSQL = "UPDATE Orders SET Status='$newStat', TrackingNum='$trackNum', Notes='$newNote' WHERE OrderID='$ID'";
				$pdo->query($updateSQL);

			}
			echo "<h3 align=center> Order Updated Successfully </h3>";
 
		}

		//create stat variable for later
		$stat = "empty";
		
		//sql to view details of the order
		$sql="SELECT * FROM Orders WHERE OrderID='$ID'";

		$result = $pdo->query($sql);
		$result = $result->fetchAll(PDO::FETCH_ASSOC);

		echo "<table border=1 style=\"border: solid;\" cellpadding=5 align=right>";

		echo "<tr bgcolor=\"#8AA29E\">";
			echo "<th style='text-align:center' colspan=6> Details on Order: $ID </th>";
		echo "</tr>";

		echo "<tr bgcolor=\"#8AA29E\">";
			echo "<th style='text-align:center'> Assigned Employee </th>";
			echo "<th style='text-align:center'> Customer Username </th>";
			echo "<th style='text-align:center'> Order Total </th>";
			echo "<th style='text-align:center'> Status </th>";
			echo "<th style='text-align:center'> Tracking Number </th>";
			echo "<th style='text-align:center'> Address </th>";
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
			else
			{
				$stat = "Unknown";
			}

			//ensure something still shows if a tracking number isn't present
			if (isset($row['TrackingNum']))
			{
				$track = $row['TrackingNum'];
			}
			else
			{
				$track = "Order not Shipped";
			} 
			
			//get the assigned employee's name (display ID if no name set)
			$sql2 = "SELECT Name FROM Employees WHERE EmpID='$emp'";

			$result2 = $pdo->query($sql2);
			$result2 = $result2->fetchAll(PDO::FETCH_ASSOC);

			if (isset($result2['Name']))
			{
				$Name = $result2['Name'];
			}
			else
			{
				$Name = $row['EmpID'];
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
			


?>
		
		<tr bgcolor="#FAFAFA">
			<td style="text-align:center"> <?php echo "$Name"; ?> </td>
			<td style="text-align:center"> <?php echo "$row[Username]"; ?> </td>
			<td style="text-align:center"> <?php echo "$$row[Total]"; ?> </td>
			<td style="text-align:center"> <?php echo "$stat"; ?> </td>
			<td style="text-align:center"> <?php echo "$track"; ?> </td>
			<td style="text-align:center"> <?php echo "$row[Address]"; ?> </td>
		</tr>

		<tr bgcolor="8AA29E">
			<th style="text-align:center" colspan=6> Items Ordered </th>
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
			echo "<td style=text-align:center colspan=2> $$price </td>";
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

		} ?>
		
		<br><br><br><br>
	
		<form method="POST">

			Enter Notes Below: <br>
			<input type="text" name="Notes" style="height:100px; width:548px; text-align:center" maxlength="255"> <br><br>
			<select name="Status" id="Status">
				<option value="2"> Processing </option>
				<option value="3"> Shipped </option>
			</select>
			<input type="submit" name="submit" value="Update Order"/>
		</form>

<?php
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

		echo "<table border=1 style=\"border: solid;\" cellpadding=5 align=right>";

		echo "<tr bgcolor=\"#8AA29E\">";
			echo "<th style='text-align:center' colspan=6> Details on Order: $ID </th>";
		echo "</tr>";

		echo "<tr bgcolor=\"#8AA29E\">";
			echo "<th style='text-align:center'> Assigned Employee </th>";
			echo "<th style='text-align:center'> Customer Username </th>";
			echo "<th style='text-align:center'> Order Total </th>";
			echo "<th style='text-align:center'> Status </th>";
			echo "<th style='text-align:center'> Tracking Number </th>";
			echo "<th style='text-align:center'> Address </th>";
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
			else
			{
				$stat = "Unknown";
			}

			//ensure something still shows if a tracking number isn't present
			if (isset($row['TrackingNum']))
			{
				$track = $row['TrackingNum'];
			}
			else
			{
				$track = "Order not Shipped";
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
			


?>
		
		<tr bgcolor="#FAFAFA">
			<td style="text-align:center"> <?php echo "$row[EmpID]"; ?> </td>
			<td style="text-align:center"> <?php echo "$row[Username]"; ?> </td>
			<td style="text-align:center"> <?php echo "$$row[Total]"; ?> </td>
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
			echo "<td style=text-align:Center colspan=2> $$price </td>";
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
