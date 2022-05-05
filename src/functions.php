<?php
    /**
     * @file functions.php
     * 
     * @brief Holds functions created by authors to be reused on other pages of the store.
     * 
     * @author David Petrovski
     * @author Isabelle Coletti
     * @author Amanda Zedwick
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
        echo "<br><br><br><form method=\"POST\" class=\"login_window\">";

        if($type == 1){        // Creates customer login window

            // Display a messsage
            echo "<h2>Hello Dear Customer!<br>Log In to shop on our online store<br></h2>";

            // Creates field for username input
            echo "<label for=\"Username\">Username: </label><br>";
            echo "<input type=\"text\" name=\"Username\" maxlength=\"15\"/><br><br>";

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
            echo "<input type=\"text\" name=\"Name\" maxlength=\"255\"/><br><br>";

            // Creates field for email input
            echo "<label for=\"Email\">Email: </label><br>";
            echo "<input type=\"email\" name=\"Email\" maxlength=\"255\" '/><br><br>";

            // Creates field for username input
            echo "<label for=\"Username\">Username: </label><br>";
            echo "<input type=\"text\" name=\"Username\" maxlength=\"15\" pattern=\"\\S*$\" title=\"Avoid using spaces in your username.\" /><br><br>";

            // Creates a field for creating a password
            echo "<label for=\"Password\">Password: </label><br>";
            echo "<input type=\"Password\" name=\"Password\" minlength=\"8\" /><br><br>";

            // Creates a field for verifying the password
            echo "<label for=\"Password\">Confirm Password: </label><br>";
            echo "<input type=\"Password\" name=\"Confirmed_Password\" maxlength=\"8\" /><br><br><br>";

            // Creates sign up button
            echo "<input class=\"login_btn\" type=\"submit\" name=\"login\" value=\"Sign Up\" /><br>";
        }

        echo "</form>";
    }


    /**
     * @brief Checks if there is a Username/EmpID/Email and Password in the database 
     *        that matches the one provided by the user.
     *      
     * @param $type Type of login to be processed.
     *              1 -> Customer login request.
     *              2 -> Employee login request.  
     *              3 -> Customer sign up request.
     * @param $POST Data from the POST method receieved once a form is submitted. 
     * @param $pdo PDO object.  
     * 
     * @return True If Username/EmpID and Password are found in the database. Or if
     *              Email is found in the database if user is creating an account.
     * @return False Otherwise.        
     */
    function check_credentials($type, $POST, $pdo){

        
        if($type == 1 or $type == 3) // Checks if a customer is login in or creating an account
        {
            $table = "Customers"; // Sets to search Customers table in the db
            $first_field = "Username"; // Looks for the Username in the $_POST
        }
        else if($type == 2)          // Checks if an employee is login in
        {
            $table = "Employees"; // Sets to search Employees table in the db
            $first_field = "EmpID"; // Looks for the EmpID in the $_POST 
        }

        // Selects every row from the specified table
        $result = $pdo->query("SELECT * FROM ".$table);

        // Fetches the found data
        $rows = $result->fetchAll(PDO::FETCH_ASSOC);
                
        // Looks through every row in the data retrieved
        foreach($rows as $row)
        {
           
            if($type == 3) // Checks if the user is creating an account
            {
                // Checks if the username or email already exists
                if($row[$first_field] == $POST[$first_field] or $row["Email"] == $POST["Email"])
                {
                    return true;
                }
            }
            else           // Otherwise it searches for a matching Username/EmpID and Password
            {

                // add code that convers password to the sql hashed password, also add code that makes a password hashed when a user creates an account

                // Checks for a Username/EmpID and Password that matches
                if($row[$first_field] == $POST[$first_field] and $row["Password"])
                {

                    // Prepares query to get hashed version of password from POST
                    $hashed_pw = $pdo->prepare("SELECT Password(?)");
                    $hashed_pw->execute(array($POST["Password"]));

                    // Fetches the password
                    $hashed_pw = $hashed_pw->fetch(PDO::FETCH_ASSOC);

                    // Checks if the hashed password matches the one from the db for the current user
                    if($row["Password"] == $hashed_pw["Password('".$POST["Password"]."')"])
                    {
                      return true;  
                    }
                }
            }
        }

        return false;
    }


    /**
     * @brief Creates a button that returns one page back provided by the first param, and it 
     *        sends the Username/EmpID through the GET method.
     * 
     * @param $dest Destination when button clicked.
     * @param $usr_type Type of user that is using the page.
     *                  1 -> Customer
     *                  2 -> Employee
     * @param $btn_value The text inside the button (Default = 'Back')
     */
    function create_return_btn($dest, $type, $btn_value = "Back"){

        // Checks if the user that is using the button is a Customer or Employee
        if($type == 1)
        {
            $ID = "Username";
        } 
        else 
        {
            $ID = "EmpID";
        }

        // Creates a form for the button with the given destination
		echo "<form action=".$dest." >";

        // Creates the button
        echo "<input type=\"submit\" name=\"submit\" value=\"".$btn_value."\" class=\"return_btn\" />";
        echo "</form>";
    }
?>
