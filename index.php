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

        // Creates customer login form
        create_login_window(1);
    ?>
</body></html>
