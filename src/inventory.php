<!DOCTYPE html>
<html>
<head>
    <title>Inventory</title>  
    <link rel="stylesheet" type="text/css" href="../assets/css/body.css" />
    <link rel="stylesheet" type="text/css" href="../assets/css/header.css" />
    <link rel="stylesheet" type="text/css" href="../assets/css/button.css" />
</head>

<body>
    <?php
        /**
         * @file inventory.php
         * 
         * @brief This is the inventory page where employees can view their inventory
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

		$sql="SELECT ProductID, Name, Price, Quantity FROM Products";

		$result = $pdo->query($sql);
		$result->setFetchMode(PDO::FETCH_ASSOC); 


		echo "<form action=\"./employee_home.php\" >";
		echo "<input type=\"hidden\" name=\"EmpID\" value=".$_GET["EmpID"]." />";
        echo "<input type=\"submit\" name=\"submit\" value=\"Back\" class=\"return_btn\" />";
        echo "</form>";
	?>

	<h2 style="text-align:center"> Inventory </h2>

	<table border=1" cellpadding=15 class="product_table">
		<tr>
			<th style="text-align:center"> ProductID </th>
			<th style="text-align:center"> Name </th>
			<th style="text-align:center"> Price </th>
			<th style="text-align:center"> Quantity </th>
			<th style="text-align:center"> In Stock </th>
		</tr>

		<?php foreach($result as $row)
		{ ?>
			<tr>
				<td style="text-align:center"> <?php echo "$row[ProductID]"; ?> </td>
				<td style="text-align:center"> <?php echo "$row[Name]"; ?> </td>
				<td style="text-align:center"> $<?php echo "$row[Price]"; ?> </td>
				<td style="text-align:center"> <?php echo "$row[Quantity]"; ?> </td>
				<td style="text-align:center"> 
				<?php
					// Checks if the quantity is 0 then it is out of stock and display it in red
					if($row["Quantity"] == 0){
						echo "<div style=\"color:red; font-weight:bold;\"> No </div>";
					} else {
						echo "<div style=\"color:#049a89; font-weight:bold;\">Yes</div>";
					}
				?>
				</td>
				
			</tr>
		<?php 	} ?>
	</table>


</body>
</html>




