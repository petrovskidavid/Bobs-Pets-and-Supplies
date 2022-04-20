<!DOCTYPE html>
<html>
<head>
    <title>Welcome to Bob's Pets and Supplies</title>  
    <link rel="stylesheet" type="text/css" href="./assets/css/body.css" />
    <link rel="stylesheet" type="text/css" href="./assets/css/header.css" />
    <link rel="stylesheet" type="text/css" href="./assets/css/login.css" />
    <link rel="stylesheet" type="text/css" href="./assets/css/button.css" />
    <script>
    if(window.history.replaceState){
        window.history.replaceState(null, null, window.location.href);
    }
    </script>
</head>

<body>
    <?php
        /**
         * @file index.php
         * 
         * @brief This is the home page of the website.
         *        Customers can choose to log in or create an account on this page, or if they are
         *        an employee they can get redirected to the employee login page.
         * 
         * @author David Petrovski
         * @author Isabelle Coletti
         * @author Amanda Zedwick
         * 
         * CSCI 466 - 1
         */

         
        include("./src/header.php"); // Creates the header of the page
        include("./src/secrets.php"); // Logs into the db
        include("./src/functions.php"); // Gives the file with the login window creation function


        // Creates customer login form
        create_login_window(1);

        // Checks if the login button was clicked
        if(isset($_POST["login"]))
        {

            // Checks if any of the login fields were left blank, otherwise prints error message.
            if($_POST["Username"] != NULL and $_POST["Password"] != NULL)
            {

                // Checks if the login credentials are valid
                $result = check_credentials(1, $_POST, $pdo);
                
                if($result) // If valid redirects to the store page
                {
                    // Redirects to store page, and puts Username in GET method to use on the store page
                    header("Location: ./src/store.php?Username=".$_POST["Username"]); 
                }
                else        // Otherwise prints an error message
                {
                    echo "<p class=\"login_error\">Incorrect Username or Password.</p>";
                }
            }
            else
            {
                echo "<p class=\"login_error\">Enter both Username and Password.</p>";
            }
        } 
    ?>
</body></html>
