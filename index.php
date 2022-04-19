<!DOCTYPE html>
<html>
<head>
    <title>Welcome!</title>  
    <link rel="stylesheet" type="text/css" href="./assets/css/body.css" />
    <link rel="stylesheet" type="text/css" href="./assets/css/header.css" />
</head>

<body>
    <?php
        /**
         * welcome.php
         * 
         * David Petrovski, Isabelle Coletti, Amanda Zedwick
         * 
         * CSCI 466 - 1
         */

        include("./src/header.php"); // Gives the file with the header for the top of the page
        include("./src/secrets.php"); // Gives the file with the DB login credentials

        try
        { // Tries to log into database

            $dsn = "mysql:host=courses;dbname=".$username;
            $pdo = new PDO($dsn, $username, $password);


        }
        catch(PDOexception $e)
        { // Otherwise catch error message

            // Prints error message
            echo "Connection to database failed: ".$e->getMessage();
        }
    ?>
</body></html>
