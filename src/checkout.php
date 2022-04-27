<!DOCTYPE html>
<html>
<head>
    <title>Checkout</title>  
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
         * @file checkout.php
         * 
         * @brief This is the checkout page where the customer can purchase their items.
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


        // Creates a return button to the cart page.
		create_return_btn("./cart.php", 1);

        // Saves the customers usrename
        $username = $_GET["Username"];

        // Saves the current order total
        $order_total = $_GET["Total"];
        $shipping = 14.99;
        if($order_total >= 200)
        {
            $shipping = 0;
            echo "<p class='shipping'>Your order total is over $200 so you qualify for free shipping!</p>";
        }
        else
        {
            $balance = 200 - $order_total;
            echo "<p class='shipping'>Spend $" .number_format($balance, 2). " more to qualify for free shipping!</p>";
        }

        echo "<table class='checkout_table' border=0>";
        echo "<tr>";
        echo "<td><p class='order_breakdown'>Order Subtotal: </p></td>";
        echo "<td class='order_details'><b>$".number_format($order_total, 2)."</b></td>";
        echo "</tr>";

        echo "<tr>";
        echo "<td><p class='order_breakdown'>Shipping: </p></td>";
        echo "<td class='order_details'><b>$".number_format($shipping, 2)."</b></td>";
        echo "</tr>";

        echo "<tr>";
        echo "<td><p class='order_breakdown'>Tax: </p></td>";
        $tax = ($shipping + $order_total) * .0509;
        $tax = number_format($tax, 2);
        echo "<td class='order_details'><b>$".$tax."</b></td>";
        echo "</tr>";

        echo "<tr>";
        echo "<td><p class='order_breakdown'>Order Total: </p></td>";
        $total = $order_total + $shipping + $tax;
        echo "<td class='order_details'><b>$".number_format($total, 2)."</b></td>";
        echo "</tr>";
        echo "</table>";

        echo "<form method='POST' action='checkout.php?Username=".$_GET["Username"]."&Total=".$_GET["Total"]."' class='checkout_details'>";
        echo "<p>Please enter your information below to complete your purchase.</p>";
        echo "<p>Shipping Address:</p>";
        echo "<input type='text' maxlength='255' class='shipping_address' name='addr'>";
        echo "<p>Credit Card Number:</p>";
        echo "<input type='text' pattern='[0-9]*' maxlength='16' minlength='16' title='Please enter numeric digits only.' class='card_number' name='cardnum'>";
        echo "<p>Security Code (CCV):</p>";
        echo "<input type='text' pattern='[0-9]*' minlength='3' maxlength='3' title='Please enter numeric digits only.' class='card_number' name='ccv'>";

        echo "<br/><br/>";
        echo "<table class='exp_table'>";
        echo "<tr>";
        echo "<td class='exp_month'>";
        echo "Expiration Month:";
        echo "</td>";
        echo "<td>";
        echo "<input type='text' pattern='[0-9]*' minlength='2' maxlength='2' title='Please enter numeric digits only.' class='expiration_date_left' name='month'>";
        echo "</td>";
        echo "<td class='exp_year'>";
        echo "Expiration Year:";
        echo "</td>";
        echo "<td>";
        echo "<input type='text' pattern='[0-9]*' minlength='4' maxlength='4' title='Please enter numeric digits only.' class='expiration_date_right' name='year'>";
        echo "</td>";
        echo "</table>";
        echo "<input type='submit' class='purchase_button' name='purchase' value='Confirm Purchase'>";
        echo "<input type='hidden' name='Total' value='$order_total' />";
        echo "<input type='hidden' name='Username' value='$username' />";
        echo "</form>";

        /* Make sure to delete the order from the Cart after checkout is processed, assign an employee to the order, update the status of the order and also update
           the products quantity after the order is sucesfully checked out.
        */

        if(isset($_POST['purchase']))
        {
            if(($_POST['addr']) and ($_POST['cardnum']) and ($_POST['ccv']) and ($_POST['month']) and ($_POST['year']))
            {
                if($_POST['month'] > 12)
                {
                    echo "<p class='month_error'>Invalid month.</p>";
                    exit;
                }
                else if($_POST['year'] < 2022)
                {
                    echo "<p class='year_error'>Invalid year.</p>";
                    exit;
                }

                $address = $_POST["addr"];
                // Get the order ID
                $rows = $pdo->prepare("SELECT OrderID FROM Orders WHERE Username=? AND Status=1");
                $rows->execute(array($username));
                $row = $rows->fetch(PDO::FETCH_ASSOC);
                // Save the order ID
                $orderID = $row["OrderID"];

                // Add the address and total to the order
                $rows = $pdo->prepare("UPDATE Orders SET Address=?, Total=? WHERE OrderID=?");
                $rows->execute(array($address, $total, $orderID));

                // Set the order status to received 
                $rows = $pdo->prepare("UPDATE Orders SET Status=2 WHERE OrderID=?");
                $rows->execute(array($orderID));
                
                // Choose an employee to assign the order to
                $result = $pdo->query("SELECT EmpID FROM Employees ORDER BY RAND() LIMIT 1");
                $emp_to_assign = $result->fetch(PDO::FETCH_ASSOC);
                $emp_to_assign = $emp_to_assign["EmpID"];
                
                // Assign the employee to the order
                $rows = $pdo->prepare("UPDATE Orders SET EmpID=? WHERE OrderID=?");
                $rows->execute(array($emp_to_assign, $orderID));

                // Update the product quantities
                $rows = $pdo->prepare("SELECT ProductID, Amount FROM Carts WHERE OrderID=?");
                $rows->execute(array($orderID));
                $results = $rows->fetchAll(PDO::FETCH_ASSOC);
                foreach($results as $result)
                {
                    $product_ID = $result["ProductID"];
                    $amount_purchased = $result["Amount"];

                    $get_amount = $pdo->prepare("SELECT Quantity FROM Products WHERE ProductID=?");
                    $get_amount->execute(array($product_ID));
                    $amt = $get_amount->fetch(PDO::FETCH_ASSOC);
                    $old_amount = $amt["Quantity"];

                    $updated_amount = $old_amount - $amount_purchased;
                    $prepare_product = $pdo->prepare("UPDATE Products SET Quantity=? WHERE ProductID=?");
                    $prepare_product->execute(array($updated_amount, $product_ID));
                }

                // // Remove the order from the Carts table
                // $rows = $pdo->prepare("DELETE FROM Carts WHERE OrderID=?");
                // $rows->execute(array($orderID));

                header("Location: order_history.php??Username=".$_GET["Username"]);
            } 
            else
            {
                echo "<p class='purchase_error'>Please fill in all fields.</p>";
            }
        }
    ?>
</body></html>
