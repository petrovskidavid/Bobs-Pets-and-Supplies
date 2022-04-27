<?php
    /**
     * @file header.php
     * 
     * @brief Creates the page header for all of the stores pages.
     * 
     * @author David Petrovski
     * @author Isabelle Coletti
     * @author Amanda Zedwick
     * 
     * CSCI 466 - 1
     */


    // Creates the header 
    echo "<header>";

    if (substr($_SERVER['PHP_SELF'], -9)  == "index.php"){ // Checks if we are in the index.php
        
        $home_link = "./";
        // Adds return link to the home of the website relative to the current location
        echo "<a href=$home_link>"; 

        // Updates src link to accomodate index.phps location in the repo
        echo "<img src=\"./assets/img/logo.png\" alt=\"Bob's Pets and Supplies Logo\" class=\"logo\" /></a>";
        
        // Updates action link to accomodate index.phps location in the repo 
        echo "<form action=\"./src/employee_login.php\" >";
        
        // Puts the text and creates a submit button for the employee to login
        echo "<input type=\"submit\" value=\"Employee Login\" class=\"e_login\" />";
        echo "</form>";

    } else {                                               // Otherwise we are in the src directory
        
        $home_link = "../";

        // Adds return link to the home of the website relative to the current location
        echo "<a href=$home_link>";

        // Updates src link to accomodate the files location in the repo
        echo "<img src=\"../assets/img/logo.png\" alt=\"Bob's Pets and Supplies Logo\" class=\"logo\" /></a>"; 

        // Checks if we are in the store or product view page to display the cart
        if(substr($_SERVER['PHP_SELF'], -9)  == "store.php" or substr($_SERVER['PHP_SELF'], -16)  == "product_view.php")
        {

            echo "<div class=\"header_right_w_cart\">";
            
            // Prepares query to search for an existing cart
            $result = $pdo->prepare("SELECT OrderID FROM Orders WHERE Username=? AND Status=1");
            $result->execute(array($_GET["Username"]));

            // Fetch an order ID if avaliable
            $orderID = $result->fetch(PDO::FETCH_ASSOC);

            // Checks if there is a current cart for the customer
            if(!empty($orderID["OrderID"])){

                // Prepares query to get the total number of items that the customer has in their cart
                $result = $pdo->prepare("SELECT SUM(Amount) FROM Carts WHERE Username=? AND OrderID=?");
                $result->execute(array($_GET["Username"], $orderID["OrderID"]));

                // Fetches the row
                $num_items = $result->fetch(PDO::FETCH_ASSOC);
                
                // Saves the number
                $num_items = $num_items["SUM(Amount)"];
            }
            else 
            {
                $num_items = 0;    
            }
    
            // Displays cart icon and sends user to the carts page and saves their username for later use
            echo "<a href=\"cart.php?Username=".$_GET["Username"]."\" class=\"num_items\" ><img src=\"../assets/img/cart.png\" alt=\"Cart icon\" class=\"cart_icon\" height=50 width=50 />".$num_items."</a>";
            
        }

        // Checks if we are NOT in the employee login or customer sing up pages, to display a logout button
        if (!(substr($_SERVER['PHP_SELF'], -19)  == "customer_signup.php" or substr($_SERVER['PHP_SELF'], -18)  == "employee_login.php"))
        {
            
            if(substr($_SERVER['PHP_SELF'], -8)  == "cart.php")                                                                       // Check to see if we are in the cart page
            {

                // Space the buttons correctly in the cart page
                echo "<div class=\"header_right_w_order\">";

        
            }
            else if (!(substr($_SERVER['PHP_SELF'], -9)  == "store.php" or substr($_SERVER['PHP_SELF'], -16)  == "product_view.php")) // Check to see if we are in any other page where there will be a logout button
            {

                // Space the logout button correctly for the rest of the pages it appears on alone
                echo "<div class=\"header_right\">";
            }
            
            // Check to see if we are in the store, product or cart page to add the order history button to the left of the logout button
            if(substr($_SERVER['PHP_SELF'], -9)  == "store.php" or substr($_SERVER['PHP_SELF'], -16)  == "product_view.php" or substr($_SERVER['PHP_SELF'], -8)  == "cart.php"){
                
                // Creates a table to have the buttons appear next to each other each in their own cell
                echo "<table><td>";

                // Creates a form that sends user to their order history page
                echo "<form action=\"order_history.php\">";
                echo "<input type=\"submit\" value=\"Order History\" class=\"order_history\" />";
                echo "<input type=\"hidden\" name=\"Username\" value=".$_GET["Username"]." />";
                echo "</form></td>";

                echo "<td>";
            }

            // Updates action link to accomodate index.phps location in the repo 
            echo "<form action=$home_link method=\"POST\">";
            
            // Puts the text and creates a submit button to logout
            echo "<input type=\"submit\" value=\"Logout\" class=\"logout\" />";
            echo "</form>";

            // Check to see if we are in the store, product or cart page to add the order history button AGAIN to close the table and cell for layout purposes
            if(substr($_SERVER['PHP_SELF'], -9)  == "store.php" or substr($_SERVER['PHP_SELF'], -16)  == "product_view.php" or substr($_SERVER['PHP_SELF'], -8)  == "cart.php"){
                echo "</td></table>";
            }
        }
        echo "</div>";
    }

    // Prints the name of the store
    echo "<h2><a href=$home_link>Bob's Pets and Supplies</a></h2>";
    
    echo "</header>";
?>