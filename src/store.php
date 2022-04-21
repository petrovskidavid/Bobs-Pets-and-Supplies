<!DOCTYPE html>
<html>
<head>
    <title>Bob's Pets and Supplies</title>  
    <link rel="stylesheet" type="text/css" href="../assets/css/body.css" />
    <link rel="stylesheet" type="text/css" href="../assets/css/header.css" />
    <link rel="stylesheet" type="text/css" href="../assets/css/login.css" />
    <link rel="stylesheet" type="text/css" href="../assets/css/button.css" />
</head>

<body>
    <?php
        /**
         * @file store.php
         * 
         * @brief This is the online store page.
         *        On this page customers can browse through the products and put items in their cart,
         *        as well as access the their cart.
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


        echo "This is the store front.";
    ?>
</body></html>
