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
         * @file employee_login.php
         * 
         * @brief This is the employee login page.
         *        Employees get redirected from the home page to this page and are able to log in
         *        to their employee account to then get redirected to the employee page.
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

        // Creates employee login form
        create_login_window(2);
    ?>
</body>
</html>