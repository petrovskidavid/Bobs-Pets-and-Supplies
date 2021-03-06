<?php session_start(); /* Start session to save username/EmpID */ ?>
<!DOCTYPE html>
<html>
<head>
    <title>Employee Login</title>  
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
         * @file employee_login.php
         * 
         * @brief This is the employee login page.
         *        Employees get redirected from the home page to this page and are able to log in
         *        to their employee account to then get redirected to the employee page.
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


        // Creates employee login form
        create_login_window(2);

        // Checks if the login button was clicked
        if(isset($_POST["login"]))
        {

            // Checks if any of the login fields were left blank, otherwise prints error message
            if($_POST["EmpID"] != NULL and $_POST["Password"] != NULL)
            {

                // Checks if the login credentials are found
                $result = check_credentials(2, $_POST, $pdo);
                
                if($result) // If found redirects to the employee home page
                {
                    // Saves EmpID into session varaible
                    $_SESSION["EmpID"] = $_POST["EmpID"];

                    // Redirects to employee home page
                    header("Location: employee_home.php"); 
                }
                else        // Otherwise prints an error message
                {
                    echo "<p class=\"login_error\">Incorrect Employee ID or Password.</p>";
                }
            }
            else
            {
                echo "<p class=\"login_error\">Enter both Employee ID and Password.</p>";
            }
        } 
    ?>
</body>
</html>