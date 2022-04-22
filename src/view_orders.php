<!DOCTYPE html>
<html>
<head>
    <title>Orders</title>  
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

	$sql="SELECT OrderID, TrackingNum, Address, Status FROM Orders WHERE (Status='2' OR Status='3')";

	$result = $pdo->query($sql);
	$result->setFetchMode(PDO::FETCH_ASSOC); ?>

	<h2 style="text-align:center"> Orders </h2>

<?php
	echo "<table border=1 width='50%' style='float:left'>";

	echo "<tr>";
		echo "<th style='text-align:center' colspan=5> All Unprocessed Orders </th>";
	echo "</tr>";

	echo "<tr>";
		echo "<th style='text-align:center'> OrderID </th>";
		echo "<th style='text-align:center'> TrackingNum </th>";
		echo "<th style='text-align:center'> Address </th>";
		echo "<th style='text-align:center'> Status </th>";
		echo "<th style='text-align:center'> View Details </th>";
	echo "</tr>";

	foreach($result as $row)
	{ 
		if ($row['Status'] == 2)
		{
			$stat="Received";
		}
		else
		{
			$stat="Shipped";
		} ?>
		<tr>
			<td style="text-align:center"> <?php echo "$row[OrderID]" ?> </td>
			<td style="text-align:center"> <?php echo "$row[TrackingNum]" ?> </td>
			<td style="text-align:center"> <?php echo "$row[Address]" ?> </td>
			<td style="text-align:center"> <?php echo "$stat" ?> </td>
			<td style="text-align:center"> <form action="./order_details.php"> <input type="submit" name="submit" value="View Order Details"/> </form> </td>
		</tr>
<?php   }
	echo "</table>";

	$sql2="SELECT OrderID, TrackingNum, Address, Status FROM Orders WHERE Status='2' AND EmpID='admin'";

	$result2 = $pdo->query($sql2);
	$result2->setFetchMode(PDO::FETCH_ASSOC);

	echo "<table border=1 width='50%' style='float:right'>";

	echo "<tr>";
		echo "<th style='text-align:center' colspan=5> Your Unprocessed Orders </th>";
	echo "</tr>";

	echo "<tr>";
		echo "<th style='text-align:center'> OrderID </th>";
		echo "<th style='text-align:center'> TrackingNum </th>";
		echo "<th style='text-align:center'> Address </th>";
		echo "<th style='text-align:center'> Status </th>";
		echo "<th style='text-align:center'> View Details </th>";
	echo "</tr>";

	foreach($result2 as $row2)
	{ ?> 
		<tr>
			<td style="text-align:center"> <?php echo "$row2[OrderID]"; ?> </td>
			<td style="text-align:center"> <?php echo "$row2[TrackingNum]"; ?> </td>
			<td style="text-align:center"> <?php echo "$row2[Address]"; ?> </td>
			<td style="text-align:center"> Received </td>
			<td style="text-align:center"> <form action="./order_details.php"> <input type="submit" name="submit" value="View Order Details"/> <input type="hidden" name="OrderID" value="<?php echo "$row2[OrderID]" ?>"/> </form> </td>
		</tr>
<?php   }
	echo "</table>";
?>

</body>
</html>




