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

        echo "<table class=\"product_view_table\" cellpadding=25>";
        // Begin a new data cell
        echo "<td>";
        // Display the product image and a new line
        echo "<img src='$addr' alt='$name Product Image' height=400 width=400/>";
        echo "</td>";

        echo "<td class='product_info_view'>";
        echo "<form method='POST' action=\"cart.php?Username=".$_GET["Username"]."\" >";
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


    ?>
</body></html>
