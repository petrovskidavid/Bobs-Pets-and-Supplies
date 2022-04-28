<!DOCTYPE html>
<html>
<head>
    <title>Order Details</title>  
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
         * @file order_details.php
         * 
         * @brief This is the page where order details can be seen both from employees and customers.
         *        The view depends on where the user is coming from
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


        
        if(isset($_GET["EmpID"]))          // Checks if an employee is visiting the page
        {
            // Creates a return button to the orders page for employees
		    create_return_btn("./orders.php", 2);
        
            // This can be removed
            echo "Test: Employee";
        }
        else if (isset($_GET["Username"])) // Checks if a customer is visiting the page
        {
            // Creates a return button to the order history page for the customer
            create_return_btn("./order_history.php", 1);

            // This can be removed
            echo "Test: Customer";
        } 
        

       //testing... 

    ?>
</body></html>
