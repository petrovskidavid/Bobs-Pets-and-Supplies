<!DOCTYPE html>
<html>
<head>
    <title>Customer Sign Up</title>  
    <link rel="stylesheet" type="text/css" href="../assets/css/body.css" />
    <link rel="stylesheet" type="text/css" href="../assets/css/header.css" />
    <link rel="stylesheet" type="text/css" href="../assets/css/login.css" />
    <link rel="stylesheet" type="text/css" href="../assets/css/button.css" />
</head>

<body>
    <?php
        /**
         * @file customer_signup.php
         * 
         * @brief This is the customer sign up page.
         *        Customers create their accounts on this page and then will get redirected
         *        to the normal store page after sucessful account creation.
         * 
         * @author David Petrovski
         * @author Isabelle Coletti
         * @author Amanda Zedwick
         * 
         * CSCI 466 - 1
         */

         
        include("header.php"); // Creates the header of the page
        include("secrets.php"); // Logs into the db
        include("functions.php"); // Gives the file with the login window creation function

        // Creates a signup form for future customers
        create_login_window(3);
    ?>
</body>
</html>