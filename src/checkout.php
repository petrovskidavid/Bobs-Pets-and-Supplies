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
            echo "<p class='shipping'>Spend $" .$balance. " more to qualify for free shipping!</p>";
        }

        echo "<table class='checkout_table' border=0>";
        echo "<tr>";
        echo "<td><p class='order_breakdown'>Order Subtotal: </p></td>";
        echo "<td class='order_details'>$".$order_total."</td>";
        echo "</tr>";

        echo "<tr>";
        echo "<td><p class='order_breakdown'>Shipping: </p></td>";
        $shipping = number_format($shipping, 2);
        echo "<td class='order_details'>$".$shipping."</td>";
        echo "</tr>";

        echo "<tr>";
        echo "<td><p class='order_breakdown'>Tax: </p></td>";
        $tax = ($shipping + $order_total) * .0509;
        $tax = number_format($tax, 2);
        echo "<td class='order_details'>$".$tax."</td>";
        echo "</tr>";

        echo "<tr>";
        echo "<td><p class='order_breakdown'>Order Total: </p></td>";
        $total = $order_total + $shipping + $tax;
        $total = number_format($total, 2);
        echo "<td class='order_details'>$".$total."</td>";
        echo "</tr>";
        echo "</table>";

        echo "<form class='checkout_details'>";
        echo "<p>Please enter your information below to complete the checkout process.</p>";
        echo "<p>Shipping Address:</p>";
        echo "<input type='text' maxlength='255' class='shipping_address'>";
        echo "<p>Credit Card Number:</p>";
        echo "<input type='text' maxlength='16' min='0' class='card_number'>";
        echo "<p>Security Code (CCV):</p>";
        echo "<input type='text' pattern='[0-9]*' minlength=3 maxlength=3 title='asdsad' class='card_number'>";
        echo "<input type='submit' name='test' value='TEST' />";
        echo "</form>";
        /* Make sure to delete the order from the Cart after checkout is processed, assign an employee to the order, update the status of the order and also update
           the products quantity after the order is sucesfully checked out.
        */
    ?>
</body></html>
