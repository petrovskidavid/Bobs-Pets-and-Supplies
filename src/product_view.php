<!DOCTYPE html>
<html>
<head>
    <title>Bob's Pets and Supplies</title>  
    <link rel="stylesheet" type="text/css" href="../assets/css/body.css" />
    <link rel="stylesheet" type="text/css" href="../assets/css/header.css" />
    <link rel="stylesheet" type="text/css" href="../assets/css/button.css" />
    <script>
    if(window.history.replaceState){
        window.history.replaceState(null, null, window.location.href);
    }
    </script>
</head>

<body>
    <?php
        /**
         * @file product_view.php
         * 
         * @brief This is the page where the customer can view more info on a selected product.
         *        They can also add the product to their cart and redirect back to the store.
         * 
         * @author David Petrovski
         * @author Isabelle Coletti
         * @author Amanda Zedwick
         * 
         * CSCI 466 - 1
         */

         
        include("functions.php"); // Gives the file with the login window creation function
        include("secrets.php"); // Logs into the db
        include("header.php"); // Creates the header of the page

        // Creates a return button to the store page.
		create_return_btn("./store.php", 1);

        if(isset($_POST["add_to_cart"]))
        {
            header("Location: cart.php?Username=".$_GET["Username"]);
        }
        // Get the product name, ID, Description and image source link from Products table
        $result = $pdo->prepare("SELECT * from Products WHERE ProductID=?");
        $result->execute(array($_GET["ProductID"]));
        $product = $result->fetch(PDO::FETCH_ASSOC);

        // Save the image source link 
        $addr = $product["ImgLink"];
        // Save the product name
        $name = $product["Name"];
        // Save the product description
        $desc = $product["Description"];
        // Save the product ID 
        $ID = $product["ProductID"];
        // Save the quantity in stock
        $quantity = $product["Quantity"];
        // Save the price of the product
        $price = $product["Price"];
        // Format the price to have 2 decimal places
        $price = number_format($price, 2);

        echo "<table class=\"product_view_table\" cellpadding=20>";
        // Begin a new data cell
        echo "<td>";
        // Display the product image and a new line
        echo "<img src='$addr' alt='$name Product Image' height=400 width=400/>";
        echo "</td>";

        echo "<td class='product_info_view'>";
        echo "<form method='POST'>";
        // Display the product name
        echo "<p class='product_name_view'>$name</p>";
        // Display the product description
        echo "<p>$desc</p><br><br>";
        // Dispay quantity in stock, green font if more than 5 otherwise red
        if($quantity > 5){
            echo "<p class='product_in_stock_cart'>Quantity in stock: <b>".$quantity."</b></p>";
        } else {
            echo "<p class='product_low_stock_cart'>Quantity in stock: <b>".$quantity."</b></p>";
        }
        // Display the product price
        echo "<p>Price: <b>$".$price."</b></p>";
        // Display a number textbox for the user to enter an amount to add to cart
        echo "<input type='number' name='amount' min='1' max='$quantity' value='1' style='height:30px; font-size:18px;'/> ";
        // Display an "Add To Cart" button
        echo "<input type='submit' name='add_to_cart' value='Add To Cart' class='add_to_cart_btn'\/>";
        // Add a hidden attribute to send the product ID when button is submitted
        echo "<input type='hidden' name='ProductID' value='$ID' />";
        // End the form
        echo "</form></td>";


        // Check if an "Add To Cart" button was clicked
        if(isset($_POST["add_to_cart"]))
        {
            // Used to indicate the amount requested exceed what is in the cart
            $amountExceeded = false;
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

                // Get the order ID for the new order
                $result = $pdo->prepare("SELECT OrderID FROM Orders WHERE Username=?");
                $result->execute(array($username));
                $row = $result->fetch(PDO::FETCH_ASSOC);

                // Save the order ID
                $orderID = $row["OrderID"];
            }
            else
            {
                // Stores the already existing unprocessed order's ID
                $orderID = $row["OrderID"];
            }
            
            // Check if the product is already in their cart
            $result = $pdo->prepare("SELECT Amount FROM Carts WHERE ProductID=? AND OrderID=?");
            $result->execute(array($productID, $orderID));
            $row = $result->fetch(PDO::FETCH_ASSOC);
            
            // If not,
            if(empty($row))
            {
                // Insert a new row into the cart table with the product ID, order ID, username, and amount
                $result = $pdo->prepare("INSERT INTO Carts VALUES(?, ?, ?, ?)");
                $success = $result->execute(array($orderID, $productID, $username, $amount));   
            }
            else    // If the product is already in their cart,
            {

                // Prepares query to get quantity in stock of the selected product
                $result = $pdo->prepare("SELECT Quantity FROM Products WHERE ProductID=?");
                $result->execute(array($productID));

                // Saves the quantity in stock
                $quantity = $result->fetch(PDO::FETCH_ASSOC);
                $quantity = $quantity["Quantity"];

                // Get the amount currently in the cart
                $previousAmt = $row["Amount"];
                // Add the previous amount to the new amount
                $newAmount = $amount + $previousAmt;

                // Checks if the customers current request to add is higher than what is in stock and updated to hold the current maximum value
                if($newAmount > $quantity){

                    $newAmount = $quantity;

                    // Used to indicate that the amount requested exceeded what we have in stock
                    $amountExceeded = true;
                }

                // Update the row in the table with the new amount
                $result = $pdo->prepare("UPDATE Carts SET Amount=? WHERE ProductID=? AND OrderID=?");
                $success = $result->execute(array($newAmount, $productID, $orderID));   
            }
           
            // If the insert/update did not fail,
            if($success)
            {
                // Get the name of the product they added to their cart
                $result = $pdo->prepare("SELECT Name FROM Products WHERE ProductID=?");
                $result->execute(array($productID));
                $row = $result->fetch(PDO::FETCH_ASSOC);

                // Save the name of the product
                $name = $row["Name"];

                // Checks if the amount requested exceeds what we have in stock
                if($amountExceeded){

                    // Prints message letting the user know their requsted amount exceed what was in stock, so the amount in their cart is the maximum avaliable
                    echo "<p class=\"err_added_to_cart\">The requested amount is too large. Your cart was updated to have the maximum amount we have in stock!";
                    
                }
                else
                {
                    // Print a message letting the user know they added the product to their cart
                    echo "<p class='succ_added_to_cart'>Successfully added to cart!</p>"; 
                    
                }
                
            }
            else    // Otherwise, if the insert/update failed,
            {
                // Print an error message and let the user know
                echo "<p class='err_added_to_cart'>An error occurred. Please try again.</p>";
            }
        
        }
    ?>
</body></html>
