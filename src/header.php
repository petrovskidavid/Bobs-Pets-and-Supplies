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
            
            // Prepares query to get the total number of items that the customer has in their cart
            $result = $pdo->prepare("SELECT SUM(Amount) FROM Carts WHERE Username=?");
            $result->execute(array($_GET["Username"]));

            // Fetches the row
            $num_items = $result->fetch(PDO::FETCH_ASSOC);
            
            // Saves the number
            $num_items = $num_items["SUM(Amount)"];

            // Checks if NULL was saved and updated items number to correct value
            if($num_items == NULL){
                $num_items = 0;
            }
    
            // Displays cart icon and sends user to the carts page and saves their username for later use
            echo "<a href=\"cart.php?Username=".$_GET["Username"]."\" class=\"num_items\" ><img src=\"../assets/img/cart.png\" alt=\"Cart icon\" class=\"cart_icon\" height=50 width=50 />".$num_items."</a>";
            
        }

        // Checks if we are NOT in the employee login or customer sing up pages, to display a logout button
        if (!(substr($_SERVER['PHP_SELF'], -19)  == "customer_signup.php" or substr($_SERVER['PHP_SELF'], -18)  == "employee_login.php"))
        {
            // Check to see if we are not in the store or product view page to make sure the cart icon doesn't display
            if(!(substr($_SERVER['PHP_SELF'], -9)  == "store.php" or substr($_SERVER['PHP_SELF'], -16)  == "product_view.php")){
                echo "<div class=\"header_right\">";
            }

            // Updates action link to accomodate index.phps location in the repo 
            echo "<form action=$home_link method=\"POST\">";
            
            // Puts the text and creates a submit button to logout
            echo "<input type=\"submit\" value=\"Logout\" class=\"logout\" />";
            echo "</form>";
        }
        echo "</div>";
    }

    // Prints the name of the store
    echo "<h2><a href=$home_link>Bob's Pets and Supplies</a></h2>";
    
    echo "</header>";
?>