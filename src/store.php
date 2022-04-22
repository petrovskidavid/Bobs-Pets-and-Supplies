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


        // Get the product name, ID, and image source link from Products table
        $result = $pdo->query("SELECT ImgLink, Name, ProductID from Products;");
        $links = $result->fetchAll(PDO::FETCH_ASSOC);

        // Create a counter to display 5 rows of products
        $count = 0;

        // Create a table
        echo "<table>";
        // Loop through every product in the table
        foreach($links as $link)
        {
            // Save the image source link 
            $addr = $link["ImgLink"];
            // Save the product name
            $name = $link["Name"];
            // Save the product ID 
            $ID = $link["ProductID"];

            // Check if we are in the beginning of the table
            if($count == 0)
            {
                // If so, begin a new row
                echo "<tr>";
            }

            // Begin a new data cell
            echo "<td>";
            // Create a new form with POST method
            echo "<form method='POST'>";
            // Display the product image and a new line
            echo "<img src='$addr' class='product_img' alt='$name Product Image' height=250 width=250/><br>";
            // Display the product name
            echo "<p class='product_name'>$name</p>";
            // Display a number textbox for the user to enter an amount to add to cart
            echo "<input type='number' name='amount' min='1' value='1' style='height:25px'/>";
            echo " ";
            // Display an "Add To Cart" button
            echo "<input type='submit' name='add_to_cart' value='Add To Cart' \/>";
            // Add a hidden attribute to send the product ID when button is submitted
            echo "<input type='hidden' name='ProductID' value='$ID' />";
            // End the form
            echo "</form>";
            // End the data cell
            echo "</td>";

            // Increment the counter
            $count++;

            // If 5 products have been displayed,
            if($count == 5)
            {
                // End the row
                echo "</tr>";
                // Reset the count to 0
                $count = 0;
            }

        }
        // End the table
        echo "</table>";

        // Check if an "Add To Cart" button was clicked
        if(isset($_POST["add_to_cart"]))
        {
            // Save the product ID
            $productID = $_POST["ProductID"];
            // Save the amount to add to cart
            $amount = $_POST["amount"];
            // Save the username
            $username = $_GET['Username'];
            
            // Checks if the customer has any current or past orders in the Carts table
            $result = $pdo->prepare("SELECT OrderID FROM Orders WHERE Username=? AND Status=1");
            $result->execute(array($username));

            $row = $result->fetch(PDO::FETCH_ASSOC);
            
            // If no OrderID is found with Status of 1 then new order is created
            if(empty($row)) 
            {
                // Create a new order for the user with default values except for username
                $result = $pdo->prepare("INSERT INTO Orders (Username) VALUES(?)"); 
                $result->execute(array($username));

                // Select OrderID from Orders Where Username = $username
                $result = $pdo->prepare("SELECT OrderID FROM Orders WHERE Username=?");
                $result->execute(array($username));

                // Gets the new orders OrderID and stores it
                $row = $result->fetch(PDO::FETCH_ASSOC);
                $orderID = $row["OrderID"];
            }
            else
            {
                // Stores the already existing unprocessed order OrderID
                $orderID = $row["OrderID"];
            }
           
            // Adds the chosen product to the correct customers cart
            $result = $pdo->prepare("INSERT INTO Carts VALUES(?, ?, ?, ?)");
            $result->execute(array($orderID, $productID, $username, $amount));   
        }
    
        
    ?>
</body></html>
