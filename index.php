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

        try
        { // Tries to log into database

            $dsn = "mysql:host=courses;dbname=".$username;
            $pdo = new PDO($dsn, $username, $password);
            
            echo "<div class=\"login_window\">";
            echo "<form method=\"POST\">";
            echo "<label for=\"username\">Username: </label><br>";
            echo "<input type=\"text\" name=\"username\" /><br><br>";
            echo "<label for=\"password\">Password: </label><br>";
            echo "<input type=\"text\" name=\"password\" /><br><br><br>";
            echo "<input class=\"login_btn\" type=\"submit\" name=\"login\" value=\"Log In\" />";
            echo "</form></div>";
            
        }
        catch(PDOexception $e)
        { // Otherwise catch error message

            // Prints error message
            echo "Connection to database failed: ".$e->getMessage();
        }

        
    ?>
</body></html>
