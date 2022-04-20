<!DOCTYPE html>
<html>
<head>
    <title>Employee Login</title>  
    <link rel="stylesheet" type="text/css" href="../assets/css/body.css" />
    <link rel="stylesheet" type="text/css" href="../assets/css/header.css" />
    <link rel="stylesheet" type="text/css" href="../assets/css/login.css" />
    <link rel="stylesheet" type="text/css" href="../assets/css/button.css" />
</head>

<body>
    <?php
        /**
         * customer_signup.php
         * 
         * David Petrovski, Isabelle Coletti, Amanda Zedwick
         * 
         * CSCI 466 - 1
         */

        include("header.php"); // Creates the header of the page
        include("secrets.php"); // Logs into the db
        include("login_windows.php"); // Gives the file with the login window creation function

        // Creates a signup form for future customers
        create_login_window(3);
    ?>
</body>
</html>