<?php


//Get the unique user ID of the user that has just registered.
include 'config/database.php';
//Create a "unique" token.
$token = bin2hex(openssl_random_pseudo_bytes(16));
$email = '';
//Construct the URL.
$url = "http://127.0.0.1:8080/camagru/verify.php?email=$email&token=$token";
 
//Build the HTML for the link.
$link = '<a href="' . $url . '">' . $url . '</a>';
 
//Send the email containing the $link above.
//Make sure that our query string parameters exist.
if(isset($_GET['token']) && isset($_GET['email']))
{
    $token = trim($_GET['token']);
    $email = trim($_GET['email']);

    $conn = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
    
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email AND token = :token");
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':token', $token);
    $stmt->execute();
    $result = $stmt->fetch();
    
    if($result)
    {   
        
            $isEmailConfirmed = 1;
          
            $sql = "UPDATE users SET isEmailConfirmed = 1 WHERE email = '$email'";  
            $stmt = $conn->prepare($sql);
           
            $stmt->execute();
        header('location: login.php');
    } 
    else{
        //Token is not valid.
        echo 'Check your emails.';
    }
    $conn = null;
}
?>