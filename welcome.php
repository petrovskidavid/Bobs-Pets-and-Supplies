<!DOCTYPE html>
<html>
<head>
    <title>Welcome!</title>
    <style>   
        header 
        {
            text-align: left;           /* Alligns text in the header to the left */
            background: #3bbfa0;        /* Background color of the header element */
            padding: 12px;              /* Creates a space around the header to make it distinct */
            font-family: Comic Sans MS; /* Font for the text in the header */

            /* Creates a solid border with a width of 3 pixels and beige color */
            border: 3px;                
            border-style: solid;        
            border-color: beige; 
        }

        .logo
        {
            float: left;        /* Makes the logo go to the left of the page */
            margin-right: 20px; /* Gives space to the right for the text */
            width: 100px;       /* Width of the logo */
            height: 100px;      /* Height of the logo */
        }

        body 
        {
            background: #f7c27c; /* Background color of the whole page */
            margin: 0;           /* Makes the header go to the top of the screen */
        }
    </style>
</head>

<body>
<img src="assets/img/logo.png" alt="Bob's Pets and Supplies Logo" class="logo">
<header>
    <h2>Bob's Pets and Supplies</h2>
</header>
<?php
    /**
     * welcome.php
     * 
     * David Petrovski, Isabelle Coletti, Amanda Zedwick
     * 
     * CSCI 466 - 1
     */

    include("secrets.php"); // Gives the file with the DB login credentials

    try
    { // Tries to log into database

        $dsn = "mysql:host=courses;dbname=".$username;
        $pdo = new PDO($dsn, $username, $password);


    }
    catch(PDOexception $e)
    { // Otherwise catch error message

        // Prints error message
        echo "Connection to database failed: ".$e->getMessage();
    }
?>
</body></html>
