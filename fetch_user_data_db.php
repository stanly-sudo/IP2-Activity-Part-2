<?php

    include_once("conn_db.php");

    /* start of fetching user data *///*FETCHING USER DATA
        $id=mysqli_real_escape_string($conn, $_GET['id']);
        if(isset($id))
        {
            $first_name="";
            $last_name="";
            $username="";
            $age=0;
            $email="";
            $role="";

            $sql = "SELECT id, first_name, last_name, username, email, age, role FROM users WHERE id='$id'";
            $result = mysqli_query($conn, $sql);

            if(mysqli_num_rows($result) > 0)
            {
                while($row = $result->fetch_assoc())
                {
                    $first_name=$row['first_name'];
                    $last_name=$row['last_name'];
                    $username=$row['username'];
                    $age=$row['age'];
                    $email=$row['email'];
                    $role=$row['role'];
                }
            }
        }
    /* end of fetching user data */

    /* start of fetching to display list of users in a table */  //* DISPLAY USERS TABLE
    $start = 0;
    $rows_per_page = 10;

    $records = mysqli_query($conn, "SELECT * FROM users");

    $nr_of_rows = mysqli_num_rows($records);

    $pages = ceil($nr_of_rows / $rows_per_page);

    if(isset($_GET['page-nr'])) 
    {
        $page = intval($_GET['page-nr']) - 1; 
        if($page < 0) 
        {
            $page = 0;
        }
        $start = $page * $rows_per_page;
    }

    $sql2 = "SELECT id, first_name, last_name, username, age, role FROM users LIMIT $start, $rows_per_page";
    $result2 = mysqli_query($conn, $sql2);
    /* end of fetching to display list of users in a table */


    /* start of fetching to display list of files of user in a user files */  //* DISPLAY FILE IN USER FILES
    $start2 = 0;
    $rows_per_page2 = 10;

    $records2 = mysqli_query($conn, "SELECT * FROM cloud");

    $nr_of_rows2 = mysqli_num_rows($records2);

    $pages = ceil($nr_of_rows2 / $rows_per_page2);

    if(isset($_GET['page-nr'])) 
    {
        $page2 = intval($_GET['page-nr']) - 1; 
        if($page2 < 0) 
        {
            $page2 = 0;
        }
        $start2 = $page2 * $rows_per_page2;
    }

    $sql3 = "SELECT cloudID, id, file, file_name, file_ext FROM cloud LIMIT $start2, $rows_per_page2";
    $result3 = mysqli_query($conn, $sql3);
    /* end of fetching to display list of files of user in a user files */



    /* start of fetching to display list of files of user in a cloud table */  //* DISPLAY FILES IN CLOUD TABLE FILES
    $start3 = 0;
    $rows_per_page3 = 10;

    $records3 = mysqli_query($conn, "SELECT * FROM cloud");

    $nr_of_rows3 = mysqli_num_rows($records3);

    $pages = ceil($nr_of_rows3 / $rows_per_page3);

    if(isset($_GET['page-nr'])) 
    {
        $page2 = intval($_GET['page-nr']) - 1; 
        if($page2 < 0) 
        {
            $page2 = 0;
        }
        $start2 = $page2 * $rows_per_page2;
    }

    $sql3 = "SELECT cloudID, id, email, file, file_name, file_ext, file_size, file_size_unit, file_hash FROM cloud LIMIT $start3, $rows_per_page3";
    $result3 = mysqli_query($conn, $sql3);
    /* end of fetching to display list of files of user in a cloud table */

?>