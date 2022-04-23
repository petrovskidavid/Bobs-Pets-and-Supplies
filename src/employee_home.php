<!DOCTYPE html>
<html>
<head>
    <title>Employee Home</title>  
    <link rel="stylesheet" type="text/css" href="../assets/css/body.css" />
    <link rel="stylesheet" type="text/css" href="../assets/css/header.css" />
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
        
        // Prepares query to get employees name
        $result = $pdo->prepare("SELECT Name FROM Employees WHERE EmpID=?");
        $result->execute(array($_GET["EmpID"]));

        // Saves employees name
        $row = $result->fetch(PDO::FETCH_ASSOC);
        $emp_name = $row["Name"];
        
        // Displays welcome message
        echo "<br><h3 class=\"welcome_msg\">Welcome back ".$emp_name."! Choose which page you want to access bellow.";

        // Puts buttons in table and in the same row to center them in the page.
        echo "<br><br><table class=\"emp_options\" cellpadding=20 >";
        echo "<td>";
        // Creates button that redirects employee to the Orders page
        echo "<form action=\"./view_orders.php\" >";

        // Sends the employees EmpID so that it is saved for later use
        echo "<input type=\"hidden\" name=\"EmpID\" value=".$_GET["EmpID"]." />";
        echo "<input type=\"submit\" name=\"submit\" value=\"View Orders\" class=\"view_order_btn\" />";
        echo "</form>";
        echo "</td>";


        echo "<td>";
        // Creates button that redirects employee to the Inventory page
        echo "<form action=\"./inventory.php\" >";

        // Sends the employees EmpID so that it is saved for later use
        echo "<input type=\"hidden\" name=\"EmpID\" value=".$_GET["EmpID"]." />";
        echo "<input type=\"submit\" name=\"submit\" value=\"View Inventory\" class=\"view_inventory_btn\" />";
        echo "</form>";

        echo "</td>";

        echo "</table>";
?>

</body></html>
