<!DOCTYPE html><html><head><title>Welcome!</title></head><body>
<?php
    /**
     * welcome.php
     * 
     * David Petrovski, Isabelle Coletti, Amanda Zedwick
     * 
     * CSCI 466 - 1
     */

    include("secrets.php") // Gives the file with the DB login credentials

    try{ // Tries to log into database.

        $dsn = "mysql:host=courses;dbname=$dbname";
        $pdo = new PDO($dsn, $username, $password);


    } catch(PDOexception $e){

        // Prints error message.
        echo "Connection to database failed: ".$e->getMessage();
    }
?>
</body></html>