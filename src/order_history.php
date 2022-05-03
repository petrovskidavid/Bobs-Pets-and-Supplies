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

        // Check if an order was just placed
        if(isset($_GET["new_order"]))
        {
            echo "<br><br><h4 style='text-align: center; font-size: 18px; color: green;'>Thank you for shopping with us! Your Order Number is <u>".$_GET["new_order"]."</u>.</h4>";
        }
        

        $result = $pdo->prepare("SELECT * FROM Orders WHERE Username=? AND (Status=2 or Status=3) ORDER BY OrderID DESC");
        $result->execute(array($_GET["Username"]));
        $rows = $result->fetchAll(PDO::FETCH_ASSOC);


        if(empty($rows))
        {
            echo "<h4 style='text-align: center; font-size: 25px;'>You have not made any orders at this time!</h4>";
        }
        else 
        {
        
            echo "<h4 style='text-align: center; font-size:25px;'>Your Orders!</h4><br>";
            echo "<table border=1 style=\"border: solid;\" class=\"orders\" cellpadding=10>";

            echo "<tr bgcolor=\"#8AA29E\">";
                echo "<th style='text-align:center'> Order Number </th>";
                echo "<th style='text-align:center'> Order Total </th>";
                echo "<th style='text-align:center'> Tracking Number </th>";
                echo "<th style='text-align:center'> Shipping Address </th>";
                echo "<th style='text-align:center'> Status </th>";
                echo "<th style='text-align:center'></th>";
            echo "</tr>";

            foreach($rows as $row)
            { 
                if($row['Status'] == 2)
                {
                    $stat="Processing";
                }
                else
                {
                    $stat="Shipped";
                }

                if($row["TrackingNum"] == NULL)
                {
                    $tracking_num = "Order Not Shipped";
                }
                else 
                {
                    $tracking_num = $row["TrackingNum"];
                }

            echo "<tr bgcolor=\"#FAFAFA\">";
            echo "<td style=\"text-align:center\">".$row["OrderID"]."</td>";
            echo "<td style=\"text-align:center\">$".number_format($row["Total"], 2)."</td>";
            echo "<td style=\"text-align:center\">".$tracking_num."</td>";
            echo "<td style=\"text-align:center\">".$row["Address"]."</td>";
            echo "<td style=\"text-align:center\">".$stat."</td>";
            echo "<td style=\"text-align:center\">";
            echo "<form action=\"./order_details.php\">";
            echo "<input type=\"hidden\" name=\"Username\" value=".$_GET["Username"]." />";
            echo "<input type=\"hidden\" name=\"OrderID\" value=".$row["OrderID"]." />";
            echo "<input type=\"submit\" name=\"submit\" value=\"View Order Details\"/> </form> </td>";
            echo "</tr>";
            }
            echo "</table>";
        }

    ?>
</body></html>
