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

        // Saves the customer's username
        $username = $_GET["Username"];

        // Saves the current order total
        $order_total = $_GET["Total"];

        // Set the shipping cost
        $shipping = 14.99;

        // Check if the order subtotal is over $200
        if($order_total >= 200)
        {
            // If so, give the customer free shipping
            $shipping = 0;
            echo "<p class='shipping'><u>Your order total is over $200 so you qualify for free shipping!</u></p>";
        }
        else    // If the subtotal is not over $200,
        {
            // Display how much more they need to spend to earn free shipping
            $balance = 200 - $order_total;
            echo "<p class='shipping'><u>Spend $" .number_format($balance, 2). " more to qualify for free shipping!</u></p>";
        }

        // Create a table to show the order breakdown
        echo "<table class='checkout_table' border=0>";
        echo "<tr>";
        // Show the order subtotal with 2 decimal places
        echo "<td><p class='order_breakdown'>Order Subtotal: </p></td>";
        echo "<td class='order_details'><b>$".number_format($order_total, 2)."</b></td>";
        echo "</tr>";

        echo "<tr>";
        // Show the shipping cost with 2 decimal places
        echo "<td><p class='order_breakdown'>Shipping: </p></td>";
        echo "<td class='order_details'><b>$".number_format($shipping, 2)."</b></td>";
        echo "</tr>";

        echo "<tr>";
        echo "<td><p class='order_breakdown'>Tax: </p></td>";
        // Calculate the order tax with a tax rate of 5.09%
        $tax = ($shipping + $order_total) * .0509;
        $tax = number_format($tax, 2);
        // Show the order tax with 2 decimal places
        echo "<td class='order_details'><b>$".$tax."</b></td>";
        echo "</tr>";

        echo "<tr>";
        echo "<td><p class='order_breakdown'>Order Total: </p></td>";
        // Calculate the order total
        $total = $order_total + $shipping + $tax;
        // Show the order total with 2 decimal places
        echo "<td class='order_details'><b>$".number_format($total, 2)."</b></td>";
        echo "</tr>";
        // End the breakdown table
        echo "</table>";
        echo "<br/><br/><br/>";

        // Create a form for the shipping and billing information that redirects to the same checkout page
        echo "<form method='POST' action='checkout.php?Username=".$_GET["Username"]."&Total=".$_GET["Total"]."' class='checkout_details'>";
        echo "<p style='font-size: 18px;'>Please enter your information below to complete your purchase.</p>";
        echo "<br/>";
        echo "<p class='billing_information'>Shipping Address:</p>";
        // Input box for the shipping information with max length of 255 characters
        echo "<input type='text' maxlength='255' class='shipping_address' name='addr'>";
        echo "<p class='billing_information'>Credit Card Number:</p>";
        // Input box for the credit card number needing 16 numeric digits
        echo "<input type='text' pattern='[0-9]*' maxlength='16' minlength='16' title='Please enter numeric digits only.' class='card_number' name='cardnum'>";
        echo "<p class='billing_information'>Security Code (CVV):</p>";
        // Input box for the security code needing 3 numeric digits
        echo "<input type='text' pattern='[0-9]*' minlength='3' maxlength='3' title='Please enter numeric digits only.' class='card_number' name='cvv'>";

        echo "<br/><br/>";
        // Create a table to format 2 text boxes next to each other
        echo "<table class='exp_table'>";
        echo "<tr>";
        echo "<td class='exp_month_lbl'>";
        echo "Expiration Month:<br><br>";
        // Input box for the expiration month of the card needing 2 numeric digits
        echo "<input type='text' pattern='[0-9]*' minlength='2' maxlength='2' title='Please enter numeric digits only.' class='exp_month_field' name='month'>";
        echo "</td>";
        echo "<td class='exp_year_lbl'>";
        echo "Expiration Year:<br><br>";
        // Input box for the expiration year of the card needing 4 numeric digits
        echo "<input type='text' pattern='[0-9]*' minlength='4' maxlength='4' title='Please enter numeric digits only.' class='exp_year_field' name='year'>";
        echo "</td>";
        // End the table
        echo "</table>";
        // Submit button for the form
        echo "<input type='submit' class='purchase_button' name='purchase' value='Confirm Purchase'>";
        // Sends the order total to the checkout page if an error occurs
        echo "<input type='hidden' name='Total' value='$order_total' />";
        // Sends the order total to the checkout page if an error occurs
        echo "<input type='hidden' name='Username' value='$username' />";
        // End the form
        echo "</form>";

        // Check if the "Confirm Purchase" button was clicked
        if(isset($_POST['purchase']))
        {
            // Check if all the fields in the form were filled in
            if(($_POST['addr']) and ($_POST['cardnum']) and ($_POST['cvv']) and ($_POST['month']) and ($_POST['year']))
            {
                // Check if the month is greater than 12
                if($_POST['month'] > 12)
                {
                    // If so, print error message and exit
                    echo "<p class='month_error'>Invalid month.</p>";
                    exit;
                }
                else if($_POST['year'] < 2022)      // Check if the year is less than current year
                {
                    // If so, print error message and exit
                    echo "<p class='year_error'>Invalid year.</p>";
                    exit;
                }

                // Save the customer's address
                $address = $_POST["addr"];

                // Get the order ID for the purchase
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
                
                // Choose a random employee to assign the order to
                $result = $pdo->query("SELECT EmpID FROM Employees ORDER BY RAND() LIMIT 1");
                $emp_to_assign = $result->fetch(PDO::FETCH_ASSOC);
                $emp_to_assign = $emp_to_assign["EmpID"];
                
                // Assign the employee to the order
                $rows = $pdo->prepare("UPDATE Orders SET EmpID=? WHERE OrderID=?");
                $rows->execute(array($emp_to_assign, $orderID));

                // Get the amounts of each product in the order
                $rows = $pdo->prepare("SELECT ProductID, Amount FROM Carts WHERE OrderID=?");
                $rows->execute(array($orderID));
                $results = $rows->fetchAll(PDO::FETCH_ASSOC);
                // Loop through every product in the order
                foreach($results as $result)
                {
                    // Save the product ID
                    $product_ID = $result["ProductID"];
                    // Save the amount
                    $amount_purchased = $result["Amount"];

                    // Get the current quantity in stock of the product
                    $get_amount = $pdo->prepare("SELECT Quantity FROM Products WHERE ProductID=?");
                    $get_amount->execute(array($product_ID));
                    $amt = $get_amount->fetch(PDO::FETCH_ASSOC);
                    // Save the current quantity
                    $old_amount = $amt["Quantity"];

                    // Calculate the new amount in stock after the purchase
                    $updated_amount = $old_amount - $amount_purchased;
                    // Update the product's quantity in the Products table
                    $prepare_product = $pdo->prepare("UPDATE Products SET Quantity=? WHERE ProductID=?");
                    $prepare_product->execute(array($updated_amount, $product_ID));
                }

                // If everything above was successful, redirect to the order history page
                header("Location: order_history.php?Username=".$_GET["Username"]."&new_order=".$orderID);
            } 
            else    // If not all 4 fields were filled,
            {
                // Print error message when redirecting back to the checkout page
                echo "<p class='purchase_error'><b>Could not complete purchase. Please fill in all fields and try again.</b></p>";
            }
        }
    ?>
</body></html>
