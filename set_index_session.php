<?php
    session_start();
    setcookie("user", "Guest", time()+(86400 * 2), "/");

    $index = $_COOKIE['user'];
    if($index === "Guest")
    {
      echo "";
    }
    else if($index === "User")
    {
      header("Location: login.php?message=Browsing+as+user.");
      exit();
    }
    else
    {
      if($index !== "Guest")
      {
        echo "";
      }
      else if($index !== "User")
      {
        header("Location: login.php?message=Browsing+as+user.");
        exit();
      }
    }
?>