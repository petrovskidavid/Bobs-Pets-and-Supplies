<?php
    
    // Creates the header 
    echo "<header>";

    if (substr($_SERVER['PHP_SELF'], -9)  == "index.php"){ // Checks if we are in the index.php
        
        // Updates src link to accomodate index.phps location in the repo
        echo "<img src=\"./assets/img/logo.png\" alt=\"Bob's Pets and Supplies Logo\" class=\"logo\" />";
        // Updates href link to accomodate index.phps location in the repo
        echo "<a href=\"./src/employee_login.php\">"; 

    } else {                                               // Otherwise we are in the src directory
        
        // Updates src link to accomodate the files location in the repo
        echo "<img src=\"../assets/img/logo.png\" alt=\"Bob's Pets and Supplies Logo\" class=\"logo\" />";
        // Updates href link to accomodate the files location in the repo
        echo "<a href=\"../src/employee_login.php\">";
    }

    // Puts the text and creates a div so we can manipualte how it looks
    echo "<div class=\"e_login\">Employee Login</div>";
    echo "</a>";

    // Prints the name of the store
    echo "<h2>Bob's Pets and Supplies</h2>";
    
    echo "</header>";
?>
