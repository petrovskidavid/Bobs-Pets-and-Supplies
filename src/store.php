<?php session_start(); /* Start session to save username/EmpID */ ?>
<!DOCTYPE html>
<html>
<head>
    <title>Bob's Pets and Supplies</title>  
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
         * @file store.php
         * 
         * @brief This is the online store page.
         *        On this page customers can browse through the products and put items in their cart,
         *        as well as access their cart.
         * 
         * @author David Petrovski
         * @author Isabelle Coletti
         * @author Amanda Zedwick
         * 
         * CSCI 466 - 1
         */

        include("secrets.php"); // Logs into the db
        include("functions.php"); // Gives the file with the login window creation function
        include("header.php"); // Creates the header of the page

        // Prepares query to get customers name
        $result = $pdo->prepare("SELECT Name FROM Customers WHERE Username=?");
        $result->execute(array($_SESSION["Username"]));

        // Saves customers name
        $row = $result->fetch(PDO::FETCH_ASSOC);
        $customer_name = $row["Name"];
        
        // Displays welcome message
        echo "<br><h3 class=\"welcome_msg\">Welcome back ".$customer_name."! Happy shopping!";

        // Get the product name, ID, and image source link from Products table
        $result = $pdo->query("SELECT * from Products;");
        $links = $result->fetchAll(PDO::FETCH_ASSOC);

        // Create a counter to display 5 rows of products
        $count = 0;

        // Add space for cart messages
        echo "<br/><br/><br>";
        // Create a table
        echo "<table class=\"product_table_store\" cellpadding=20>";
        // Loop through every product in the table
        foreach($links as $link)
        {
            // Save the image source link 
            $addr = $link["ImgLink"];
            // Save the product name
            $name = $link["Name"];
            // Save the product ID 
            $ID = $link["ProductID"];
            // Save the quantity in stock
            $quantity = $link["Quantity"];
            // Save the price of the product
            $price = $link["Price"];
            // Format the price to have 2 decimal places
            $price = number_format($price, 2);

            // Make sure the product is in stock
            if($quantity > 0)
            {
                 // Check if we are in the beginning of the table
                if($count == 0)
                {
                   // If so, begin a new row
                   echo "<tr>";
                }

                // Begin a new data cell
                echo "<td class=\"product_view_link\">";
                // Create link for every product to redirect to its product_view page
                echo "<a href=\"product_view.php?ProductID=".$ID."\">";
                // Display the product image and a new line
                echo "<img src='$addr' alt='$name Product Image' height=250 width=250/><br>";
                // Display the product name
                echo "<p class='product_name_store'>$name</p>";
                // Display the product price
                echo "<p class='price_lbl_store'>Price: <b>$".$price."</b></p>";
                echo "</a>";
                // End the data cell
                echo "</td>";

                // Increment the counter
                $count++;

                // If 4 products have been displayed,
                if($count == 4)
                {
                    // End the row
                    echo "</tr>";
                    // Reset the count to 0
                    $count = 0;
                }

            }
           
        }
        // End the table
        echo "</table>";

    
    ?>
</body></html>
