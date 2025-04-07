<?php 
    session_start();
    $isUser = $_COOKIE['user'];

    if($isUser === "Guest")
    {
        header("Location: index.php?message=You+are+a+guest.");
        exit();
    }
?>