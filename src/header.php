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
        
        // Updates src link to accomodate index.phps location in the repo
        echo "<img src=\"./assets/img/logo.png\" alt=\"Bob's Pets and Supplies Logo\" class=\"logo\" />";
        
        // Updates action link to accomodate index.phps location in the repo 
        echo "<form action=\"./src/employee_login.php\" >";
        
        // Puts the text and creates a submit button
        echo "<input type=\"submit\" value=\"Employee Login\" class=\"e_login\" />";
        echo "</form>";

    } else {                                               // Otherwise we are in the src directory
        
        // Updates src link to accomodate the files location in the repo
        echo "<img src=\"../assets/img/logo.png\" alt=\"Bob's Pets and Supplies Logo\" class=\"logo\" />";
        
    }

    // Prints the name of the store
    echo "<h2>Bob's Pets and Supplies</h2>";
    
    echo "</header>";
?>
