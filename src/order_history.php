<?php session_start(); /* Start session to save username/EmpID */ ?>
<!DOCTYPE html>
<html>
<head>
    <title>Your Orders</title>  
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
         * @file order_history.php
         * 
         * @brief This is the page where the customer can view their order history.
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

        // Creates a return button to the store page.
		create_return_btn("./store.php", 1, "Continue Shopping");

        // Checks if the customer wants to view order details
        if(isset($_POST["view_order"]))
        {
            // Saves order ID for the order details page and the type of view
            $_SESSION["view_orderID"] = $_POST["OrderID"];
            $_SESSION["type_of_view"] = "customer_view";

            // Redirects to the order details page
            header("Location: order_details.php");
        }

        // Check if an order was just placed
        if(isset($_GET["new_order"]))
        {
            // If so, print a message for the order to confirm placement
            echo "<h4 style='position: absolute; left: 50%; top: 165px; transform: translate(-50%, 0); font-size: 18px; color: #049a89;'>Thank you for shopping with us! Your Order Number is <u>".$_SESSION["new_order"]."</u>.</h4>";
        }
        
        // Get the order information for all the customer's orders
        $result = $pdo->prepare("SELECT * FROM Orders WHERE Username=? AND (Status=2 or Status=3) ORDER BY OrderID DESC");
        $result->execute(array($_SESSION["Username"]));
        $rows = $result->fetchAll(PDO::FETCH_ASSOC);

        // Get the sum of the totals of all the customer's orders
        $result2 = $pdo->prepare("SELECT SUM(Total) FROM Orders WHERE Username=?");
        $result2->execute(array($_SESSION["Username"]));
        $rows2 = $result2->fetch(PDO::FETCH_ASSOC);
        // Save the sum of all the totals
        $totalToDate = $rows2["SUM(Total)"];

        // If there are no orders,
        if(empty($rows))
        {
            // Print a message saying there are no orders
            echo "<h4 style='text-align: center; font-size: 25px;'>You have not made any orders at this time!</h4>";
        }
        else        // Otherwise, there are orders
        {
            echo "<h4 style='text-align: center; font-size:25px;'>Your Orders!</h4>";

            // Make a table to display all orders
            echo "<br><br><table border=1 style=\"border: solid;\" class=\"orders\" cellpadding=10>";

            // Display labels for table
            echo "<tr bgcolor=\"#8AA29E\">";
                echo "<th style='text-align:center'> Order Number </th>";
                echo "<th style='text-align:center'> Order Total </th>";
                echo "<th style='text-align:center'> Tracking Number </th>";
                echo "<th style='text-align:center'> Shipping Address </th>";
                echo "<th style='text-align:center'> Status </th>";
                echo "<th style='text-align:center'></th>";
            echo "</tr>";

            // Loop through every order
            foreach($rows as $row)
            { 
                // Check the status of the order
                if($row['Status'] == 2)
                {
                    $stat="<b>Processing</b>";
                }
                else
                {
                    $stat = "<b style=\"color:#049a89;\">Shipped</b>";
                }

                // Check if there is a tracking number
                if($row["TrackingNum"] == NULL)
                {
                    $tracking_num = "Order Not Shipped";
                }
                else 
                {
                    $tracking_num = $row["TrackingNum"];
                }

                
            // Display the order information with correct formatting for numbers
            echo "<tr bgcolor=\"#FAFAFA\">";
            echo "<td style=\"text-align:center\">".$row["OrderID"]."</td>";
            echo "<td style=\"text-align:center\">$".number_format($row["Total"], 2)."</td>";
            echo "<td style=\"text-align:center\">".$tracking_num."</td>";
            echo "<td style=\"text-align:center\">".$row["Address"]."</td>";
            echo "<td style=\"text-align:center\">".$stat."</td>";
            echo "<td style=\"text-align:center\">";

            // Creat form to display a button to view individual order details
            echo "<form method=POST>";
            echo "<input type=\"hidden\" name=\"OrderID\" value=".$row["OrderID"]." />";
            // Button to view individual order details
            echo "<input type=\"submit\" name=\"view_order\" value=\"View Order Details\"/> </form> </td>";
            echo "</tr>";
            }
            // End the table
            echo "</table>";

            // Print sum of all totals
            echo "<br><p class=TotalToDate>The total of all your orders is <b>$".number_format($totalToDate,2)."</b>.</p>";
        }

    ?>
</body></html>
