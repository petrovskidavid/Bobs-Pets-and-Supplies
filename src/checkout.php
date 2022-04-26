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


        /* Make sure to delete the order from the Cart after checkout is processed, assign an employee to the order, update the status of the order and also update
           the products quantity after the order is sucesfully checked out.
        */
    ?>
</body></html>
