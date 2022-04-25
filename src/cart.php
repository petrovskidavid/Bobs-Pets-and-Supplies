<!DOCTYPE html>
<html>
<head>
    <title>Your Shopping Cart</title>  
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
         * @file store.php
         * 
         * @brief This is the online store page.
         *        On this page customers can browse through the products and put items in their cart,
         *        as well as access their cart.
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

        // Stores the current carts total
        $order_total = 0;

        // Creates a return button to the store page.
		create_return_btn("./store.php", 1);

        // Infom customer this is the cart page
        echo "<h2 class=\"cart_msg\">Your Shopping Cart</h2>";

        // Prepares query to search for all the items in the customers cart
        $result = $pdo->prepare("SELECT * FROM Carts WHERE Username=?");
        $result->execute(array($_GET["Username"]));

        // Saves the list of items in the customers cart
        $items_in_cart = $result->fetchAll(PDO::FETCH_ASSOC);

        // Creates a table view of all the items, including their image, their name, quantity and price
        echo "<table class=\"cart_display\" cellpadding=10>";
        foreach($items_in_cart as $item){

            // Prepares query to get info on the product found in the customers cart
            $result = $pdo->prepare("SELECT * FROM Products WHERE ProductID=?");
            $result->execute(array($item["ProductID"]));

            // Saves the product info
            $product_info = $result->fetch(PDO::FETCH_ASSOC);

            // Display the product image
            echo "<tr><td><img src='".$product_info["ImgLink"]."' alt='".$product_info["Name"]." Product Image' height=150 width=150/></td>";
            // Display the product name
            echo "<td><b>".$product_info["Name"]."</b><br>";
            
            // Display if product is in stock, if 5 or less are in stock display a message informing the customer
            if($product_info["Quantity"] > 5)
            {

                echo "<p class=\"product_in_stock_cart\">In Stock<p>";
            }
            else
            {
                echo "<p class=\"product_low_stock_cart\">".$product_info["Quantity"]." left in stock! Order soon.</p>";
            }

            // Display the quantity in customers cart
            echo "<br>Quantity: <b>".$item["Amount"]."</b></td>";
            
            // Format the price to have 2 decimal places
            $price = number_format($product_info["Price"], 2);
            echo "<td><br><br><br><br>Price: <b>$".$price."</b></td></tr>";

            // Calculates the carts current total
            $order_total += $price * $item["Amount"];
        }

        // Puts the cart total at the bottom center of the table
        echo "<tr><td> </td><td class=\"total_cart\"><br>Cart Total: <b>$".$order_total."</b></td<td> </td></tr>";


        // Add btn to checkout at bottom whole row, then also add delete btn under every quantity
        echo "</table>";
    ?>
</body></html>
