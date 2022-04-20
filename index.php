<!DOCTYPE html>
<html>
<head>
    <title>Welcome to Bob's Pets and Supplies</title>  
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

        include("./src/header.php"); // Creates the header of the page
        include("./src/secrets.php"); // Logs into the db
        include("./src/login_windows.php"); // Gives the file with the login window creation function

        // Creates customer login form
        create_login_window(1);
    ?>
</body></html>
