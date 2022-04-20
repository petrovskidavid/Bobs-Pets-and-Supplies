<!DOCTYPE html>
<html>
<head>
    <title>Welcome!</title>  
    <link rel="stylesheet" type="text/css" href="./assets/css/body.css" />
    <link rel="stylesheet" type="text/css" href="./assets/css/header.css" />
    <link rel="stylesheet" type="text/css" href="./assets/css/login.css" />
    <link rel="stylesheet" type="text/css" href="./assets/css/button.css" />
</head>

<body>
    <?php
        /**
         * index.php
         * 
         * David Petrovski, Isabelle Coletti, Amanda Zedwick
         * 
         * CSCI 466 - 1
         */

        include("./src/header.php"); // Gives the file with the header for the top of the page
        include("./src/secrets.php"); // Gives the file with the DB login credentials
        include("./src/login_windows.php"); // Gives the file with the login window creation function

        try
        { // Tries to log into database

            $dsn = "mysql:host=courses;dbname=".$username; // Creates DSN to connect to db.
            $pdo = new PDO($dsn, $username, $password); // Establishes connection to db.
            
            // Creates customer login form
            create_login_window(1);
            
        }
        catch(PDOexception $e)
        { // Otherwise catch error message

            // Prints error message
            echo "Connection to database failed: ".$e->getMessage();
        }

        
    ?>
</body></html>
