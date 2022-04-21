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
?>

<form action="./scr/view_orders.php">
	<input type="submit" name="submit" value="View Orders"/>
</form>

<form action="./scr/view_inventory.php">
	<input type="submit" name="submit" value="View Inventory"/>
</form>


</body></html>
