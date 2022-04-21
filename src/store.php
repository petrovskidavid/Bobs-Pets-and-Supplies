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


        //echo "This is the store front.";
        $result = $pdo->query("SELECT ImgLink from Products;");
        $links = $result->fetchAll(PDO::FETCH_ASSOC);
        $count = 0;
        foreach($links as $link)
        {
            $addr = $link["ImgLink"];
            //echo $link["ImgLink"];
            //echo "$link['ImgLink']";
            //echo "<img src='$link['ImgLink']' />";
            echo "<img src='$addr' class='product_img' height=250 width=250/>";
            $count++;
            if($count == 5)
            {
                echo "<br/>";
                $count = 0;
            }
        }
    ?>
</body></html>
