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
        
        // Puts the text and creates a submit button
        echo "<input type=\"submit\" value=\"Employee Login\" class=\"e_login\" />";
        echo "</form>";

    } else {                                               // Otherwise we are in the src directory
        
        $home_link = "../";

        // Adds return link to the home of the website relative to the current location
        echo "<a href=$home_link>";

        // Updates src link to accomodate the files location in the repo
        echo "<img src=\"../assets/img/logo.png\" alt=\"Bob's Pets and Supplies Logo\" class=\"logo\" />";  
    }

    
    // Prints the name of the store
    echo "<h2><a href=$home_link>Bob's Pets and Supplies</a></h2>";
    
    echo "</header>";
?>