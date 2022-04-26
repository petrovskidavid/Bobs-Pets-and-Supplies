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
         * @file cart.php
         * 
         * @brief This is the page where the customer can view their cart and start checkout process.
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


        // Creates a return button to the store page.
		create_return_btn("./store.php", 1, "Continue Shopping");

        // Infom customer this is the cart page
        echo "<h2 class=\"cart_msg\">Your Shopping Cart</h2>";

        // Prepares query to search for all the items in the customers cart
        $result = $pdo->prepare("SELECT * FROM Carts WHERE Username=?");
        $result->execute(array($_GET["Username"]));

        // Saves the list of items in the customers cart
        $items_in_cart = $result->fetchAll(PDO::FETCH_ASSOC);

        // Checks if the cart is not empty to then display the products in the cart, otherwise it notifies the customer that the cart is empty
        if(!empty($items_in_cart))
        {
            // Resets counter values
            $order_total = 0;
            $number_of_items = 0;

            // Creates a table view of all the items, including their image, their name, quantity and price
            echo "<table class=\"cart_display\">";
            // Used to organize the view
            echo "<td><table cellpadding=10>";
            foreach($items_in_cart as $item){   

                // Prepares query to get info on the product found in the customers cart
                $result = $pdo->prepare("SELECT * FROM Products WHERE ProductID=?");
                $result->execute(array($item["ProductID"]));

                // Saves the product info
                $product_info = $result->fetch(PDO::FETCH_ASSOC);

                // Checks if the update or remove buttons were clicked to update what is displayed
                if(isset($_GET["remove_items"]) or isset($_GET["update_qty"]))
                {
                    header("Location: cart.php?Username=".$_GET["Username"]);
                }

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

                // Creates form to update number of items of the selected product
                echo "<form>";

                // Sends the username so that it is saved
                echo "<input type=\"hidden\" name=\"Username\" value=".$_GET["Username"]." />";

                // Sends the productID of the item
                echo "<input type=\"hidden\" name=\"ProductID\" value=".$product_info["ProductID"]." />";
                
                // Display the quantity in customers cart in the input textbox
                echo "Quantity: ";
                echo "<input type=\"number\" name=\"amount\" min='0' max=".$product_info["Quantity"]." value=".$item["Amount"]." style=\"height: 15px;\"/><br><br>";

                // Display update and remove buttons
                echo "<input type=\"submit\" name=\"remove_items\" value=\"Remove\" class=\"remove_items_btn\" /> ";
                echo "<input type=\"submit\" name=\"update_qty\" value=\"Update\">";
             
                echo "</form>";
                
                // Format the price to have 2 decimal places
                $price = number_format($product_info["Price"], 2);
                echo "<td><br>Price: <b>$".$price."</b></td></tr>";

                // Increments the number of items in the cart
                $number_of_items += $item["Amount"];
                
                // Calculates the carts current total
                $order_total += $price * $item["Amount"];
            }
            echo "</table>";
            // Puts the cart total at the bottom center of the table
            echo "<td class=\"total_cart\"><br>Cart Total (".$number_of_items." items): <b>$".number_format($order_total, 2)."</b>";

            // Creates form for checkout button
            echo "<form action=\"checkout.php\" >";
            echo "<input type=\"hidden\" name=\"Username\" value=".$_GET["Username"]." /><br>";
            echo "<input type=\"submit\" name=\"checkout\" value=\"Checkout\" class=\"checkout_btn\" >";
            echo "</form>";

            // Add btn to checkout at bottom whole row, then also add delete btn under every quantity
            echo "</table>";
        }
        else 
        {
            echo "<h3 class=\"cart_msg\">Your cart is empty!</h2>";
        }

        // Checks if the Update button was clicked
        if(isset($_GET["update_qty"]))
        {
            // Update the row in the table with the new amount
            $result = $pdo->prepare("UPDATE Carts SET Amount=? WHERE ProductID=? AND Username=?");
            $success = $result->execute(array($_GET["amount"], $_GET["ProductID"], $_GET["Username"])); 
        }
        else if(isset($_GET["remove_items"]))
        {
            // Remove the row in the table with the selected item
            $result = $pdo->prepare("DELETE FROM Carts WHERE ProductID=? AND Username=?");
            $success = $result->execute(array($_GET["ProductID"], $_GET["Username"])); 
        }
    ?>
</body></html>
