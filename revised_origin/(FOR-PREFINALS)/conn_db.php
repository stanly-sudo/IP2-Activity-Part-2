<?php
    $servername="localhost";
    $username_db="root";
    $password_db="";
    $db_name="ramendb";

    $conn = new mysqli($servername, $username_db, $password_db, $db_name);
    if(!$conn)
    {
        die("Connection failed" . $conn->connection_error);
    }
?>