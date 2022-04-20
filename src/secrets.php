<?php
    /**
     * secrets.php
     * 
     * David Petrovski, Isabelle Coletti, Amanda Zedwick
     * CSCI 466 - 1
     */

    // Insert your login to MariaDB here
    $db_username = ""; // Username
    $db_password = ""; // Password

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