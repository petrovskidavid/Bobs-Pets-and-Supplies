<?php
    /**
     * @file secrets.php
     * 
     * @brief Tries to connect to the specified database.
     *        Prints an error on failure and it doesn't doesn't anything else of the page.
     * 
     * @author David Petrovski
     * @author Isabelle Coletti
     * @author Amanda Zedwick
     * 
     * CSCI 466 - 1
     */

    
    // Insert your login to MariaDB here
    $db_username = "z1894079"; // Username
    $db_password = "2002Feb21"; // Password

    try
    { // Tries to log into database

        $dsn = "mysql:host=courses;dbname=".$db_username; // Creates DSN to connect to db.
        $pdo = new PDO($dsn, $db_username, $db_password); // Establishes connection to db.
    
    }
    catch(PDOexception $e)
    { // Otherwise catch error message

        // Prints error message
        echo "<h2 style=\"text-align: center; position: absolute; top: 35%; left: 50%; transform: translate(-50%, 0);\">Connection to database failed: ".$e->getMessage().". Try again later.</h2>";
        exit;
    }
?>