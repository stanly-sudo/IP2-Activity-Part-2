<?php
    include_once("conn_db.php");

    // * revising and organizing fetched data *

    /* start of fetching logged user */  //* LOGGED USER
        $id = mysqli_real_escape_string($conn, $_GET['id']);
        if(isset($id))
        {
            $profile_image="";
            $first_name="";
            $last_name="";
            $username="";
            $age=0;
            $address="";
            $role="";

            $sql = "SELECT id, profile_image, first_name, last_name, username, age, address, role FROM users WHERE id='$id'";
            $result = mysqli_query($conn, $sql);

            if($result->num_rows > 0)
            {
                while($row = $result->fetch_assoc())
                {
                    $profile_image=$row['profile_image'];
                    $first_name=$row['first_name'];
                    $last_name=$row['last_name'];
                    $username=$row['username'];
                    $age=$row['age'];
                    $address=$row['address'];
                    $role=$row['role'];
                }
            }
            else
            {
                $conn->close();
                header("Location: index.php?message=Something went wrong.&id=".$id);
            }
        }
        else
        {
            $conn->close();
            header("Location: index.php?message=Something went wrong.&id=".$id);
        }
    /* end of fetching logged user */ 


    
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

        $sql2 = "SELECT id, profile_image, first_name, last_name, username, age, address, role FROM users LIMIT $start, $rows_per_page";
        $result2 = mysqli_query($conn, $sql2);
    /* end of fetching to display list of users in a table */



    /* start of fetching to display list of users in a table */  //* DISPLAY MENU TABLE
        $start2 = 0;
        $rows_per_page2 = 10;

        $records2 = mysqli_query($conn, "SELECT * FROM shop_menu");

        $nr_of_rows2 = mysqli_num_rows($records2);

        $pages2 = ceil($nr_of_rows2 / $rows_per_page2);

        if(isset($_GET['page-nr'])) 
        {
            $page2 = intval($_GET['page-nr']) - 1; 
            if($page2 < 0) 
            {
                $page2 = 0;
            }
            $start2 = $page2 * $rows_per_page2;
        }

        $sql6 = "SELECT menuID, menu_image, menu_price, menu_name, menu_description, menu_type, menu_state FROM shop_menu LIMIT $start2, $rows_per_page2";
        $result6 = mysqli_query($conn, $sql6);
    /* end of fetching to display list of users in a table */

    

    /* start of fetching to display edible menu in home */  //* DISPLAY  EDIBLE MENU HOME
        $start3 = 0;
        $rows_per_page3 = 6;

        $records3 = mysqli_query($conn, "SELECT * FROM shop_menu WHERE menu_type='Edible'");

        $nr_of_rows3 = mysqli_num_rows($records3);

        $pages3 = ceil($nr_of_rows3 / $rows_per_page3);

        if(isset($_GET['page-nr'])) 
        {
            $page3 = intval($_GET['page-nr']) - 1; 
            if($page3 < 0) 
            {
                $page3 = 0;
            }
            $start3 = $page3 * $rows_per_page3;
        }

        $sql9 = "SELECT menuID, menu_image, menu_price, menu_name, menu_description, menu_type, menu_state FROM shop_menu WHERE menu_type='Edible' LIMIT $start3, $rows_per_page3";
        $result9 = mysqli_query($conn, $sql9);
    /* end of fetching to display edible menu in home */



    /* start of fetching to display drink menu in home */  //* DISPLAY  DRINK MENU HOME
        $start4 = 0;
        $rows_per_page4 = 6;

        $records4 = mysqli_query($conn, "SELECT * FROM shop_menu WHERE menu_type='Drink'");

        $nr_of_rows4 = mysqli_num_rows($records4);

        $pages4 = ceil($nr_of_rows3 / $rows_per_page4);

        if(isset($_GET['page-nr'])) 
        {
            $page4 = intval($_GET['page-nr']) - 1; 
            if($page4 < 0) 
            {
                $page4 = 0;
            }
            $start4 = $page4 * $rows_per_page4;
        }

        $sql10 = "SELECT menuID, menu_image, menu_price, menu_name, menu_description, menu_type, menu_state FROM shop_menu WHERE menu_type='Drink' LIMIT $start3, $rows_per_page3";
        $result10 = mysqli_query($conn, $sql10);
    /* end of fetching to display drink menu in home */
?>