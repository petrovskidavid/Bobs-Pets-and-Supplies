<!DOCTYPE html>
<html>
<head>
    <title>Customer Sign Up</title>  
    <link rel="stylesheet" type="text/css" href="../assets/css/body.css" />
    <link rel="stylesheet" type="text/css" href="../assets/css/header.css" />
    <link rel="stylesheet" type="text/css" href="../assets/css/login.css" />
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
         * @file customer_signup.php
         * 
         * @brief This is the customer sign up page.
         *        Customers create their accounts on this page and then will get redirected
         *        to the normal store page after sucessful account creation.
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

        // Creates a signup form for future customers
        create_login_window(3);

        // Checks if the login button was clicked
        if(isset($_POST["login"]))
        {

            // Checks if any of fields were left blank, otherwise prints error message
            if($_POST["Name"] != NULL and $_POST["Email"] != NULL and $_POST["Username"] != NULL and $_POST["Password"] != NULL and $_POST["Confirmed_Password"] != NULL)
            {

                // Checks if the Password and Confirmed Password fields match, otherwise prints error message
                if($_POST["Password"] == $_POST["Confirmed_Password"])
                {

                    // Checks if the credentials are found
                    $result = check_credentials(3, $_POST, $pdo);
                
                    if($result == false) // If not found redirects to the employee home page
                    {

                        // Prepares the query to add a new customer to the Customers table
                        $result = $pdo->prepare("INSERT INTO Customers VALUES (?, ?, ?, ?)");

                        // Executes the query and checks if it the query was successful 
                        if($result->execute(array($_POST["Username"], $_POST["Password"], $_POST["Name"], $_POST["Email"]))){

                            // Redirects to store page, and puts Username in GET method to use on the store page
                            header("Location: store.php?Username=".$_POST["Username"]); 
                    
                        } else {
                            echo "<p class=\"login_error\"'>Something went wrong! Make sure your information is correct, and try again.</p>";
                        }
                    }
                    else        // Otherwise prints an error message
                    {
                    echo "<p class=\"login_error\">Account already exists.</p>";
                    } 
                }
                else
                {
                    echo "<p class=\"login_error\">Passwords don't match.</p>";
                }
            }
            else
            {
                echo "<p class=\"login_error\">Enter values for all of the fields to create an account.</p>";
            }
        } 
    ?>
</body>
</html>