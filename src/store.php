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
        $result = $pdo->query("SELECT ImgLink, Name, ProductID from Products;");
        $links = $result->fetchAll(PDO::FETCH_ASSOC);
        $count = 0;
        $nameArr = [];
        $nameCount = 0;
        echo "<table>";
        foreach($links as $link)
        {
            $addr = $link["ImgLink"];
            $name = $link["Name"];
            $ID = $link["ProductID"];
        

            if($count == 0)
            {
                echo "<tr>";
            }
            echo "<td>";
            echo "<form method='POST'>";
            echo "<img src='$addr' class='product_img' height=250 width=250/><br>";
            echo "<p class='product_name'>$name</p>";
            echo "<input type='number' name='amount' min='1' value='1' style='height:25px'/>";
            echo " ";
            echo "<input type='submit' name='add_to_cart' value='Add To Cart' \/>";
            echo "<input type='hidden' name='ProductID' value='$ID' />";
            echo "</form>";
            echo "</td>";
            $count++;
            if($count == 5)
            {
                echo "</tr>";
                $count = 0;
            }

        }
        echo "</table>";

        if(isset($_POST["add_to_cart"]))
        {
            $product_id = $_POST["ProductID"];
            $amount = $_POST["amount"];
            $username = $_GET['Username'];

            // Checks if the customer has any current or past orders in the Carts table
            $result = $pdo->query("SELECT OrderID FROM Orders WHERE Username=".$username." AND Status=1");

            // Fetches the data.
            $orderID = $result->fetch(PDO::FETCH_ASSOC);

            if(empty($orderID)) // If no OrderID add new order to the Orders table and Carts
            {
                
                $pdo->query("INSERT INTO Orders (Username) VALUES(".$username.")"); 
                
                // Select OrderID from Orders Where Username = $username

                //$result = $pdo->prepare("INSERT INTO Carts")
                
                //$pre = $pdo->prepare("INSERT INTO Carts VALUES(?, ?, ?, ?)");
            }
            else
            {
                //$pre = $pdo->prepare("INSERT INTO Carts VALUES(?, ?, ?, ?)");
            }


            
        }
    
        
    ?>
</body></html>
