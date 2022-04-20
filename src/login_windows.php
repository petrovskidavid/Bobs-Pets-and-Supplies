<?php
    /**
     * login_windows.php
     * 
     * David Petrovski, Isabelle Coletti, Amanda Zedwick
     * 
     * CSCI 466 - 1
     */

    
    /**
     * @brief Creates a form for the specified type of login or sign up window, with the
     *        required fields.
     * 
     * @param $type Type of login/signup window to create.
     *              1 -> Creates a customer login window.
     *              2 -> Creates an employee login window.
     *              3 -> Creates a signup window for a new customer.
     */
    function create_login_window($type){

        // Creates form for customer login
        echo "<form method=\"POST\" class=\"login_window\">";

        if($type == 1){        // Creates customer login window

            // Display a messsage
            echo "<h2>Hello Dear Customer!<br>Log In to shop on our online store<br></h2>";

            // Creates field for username input
            echo "<label for=\"Username\">Username: </label><br>";
            echo "<input type=\"text\" name=\"Username\" /><br><br>";

            // Creates a field for password input
            echo "<label for=\"Password\">Password: </label><br>";
            echo "<input type=\"Password\" name=\"Password\" /><br><br><br>";

            // Creates login button
            echo "<input class=\"login_btn\" type=\"submit\" name=\"login\" value=\"Log In\" /><br>";

            // Adds link to make an account
            echo "<br><p>Don't have an account? <a href=\"./src/customer_signup.php\">Sign up here</a></p>";
        
        } else if($type == 2){ // Creates employee login window
            
            // Display a messsage
            echo "<h2>Welcome back!<br>Log In to process orders<br></h2>";

            // Creates field for employee ID input
            echo "<label for=\"EmpID\">Employee ID: </label><br>";
            echo "<input type=\"text\" name=\"EmpID\" /><br><br>";

            // Creates a field for password input
            echo "<label for=\"Password\">Password: </label><br>";
            echo "<input type=\"Password\" name=\"Password\" /><br><br><br>";

            // Creates login button
            echo "<input class=\"login_btn\" type=\"submit\" name=\"login\" value=\"Log In\" /><br>";
        
        } else if($type == 3){ // Creates customer sign up window

            // Display a messsage
            echo "<h2>Hello Future Customer!<br>Please fill in the fields to create an account<br></h2>";

            // Creates field for name input
            echo "<label for=\"Name\">Name: </label><br>";
            echo "<input type=\"text\" name=\"Name\" /><br><br>";

            // Creates field for email input
            echo "<label for=\"Email\">Email: </label><br>";
            echo "<input type=\"text\" name=\"Email\" /><br><br>";

            // Creates field for username input
            echo "<label for=\"Username\">Username: </label><br>";
            echo "<input type=\"text\" name=\"Username\" /><br><br>";

            // Creates a field for creating a password
            echo "<label for=\"Password\">Password: </label><br>";
            echo "<input type=\"Password\" name=\"Password\" /><br><br>";

            // Creates a field for verifying the password
            echo "<label for=\"Password\">Confirm Password: </label><br>";
            echo "<input type=\"Password\" name=\"Confirmed_Password\" /><br><br><br>";

            // Creates sign up button
            echo "<input class=\"login_btn\" type=\"submit\" name=\"login\" value=\"Sign Up\" /><br>";
        }

        echo "</form>";
    }
?>
