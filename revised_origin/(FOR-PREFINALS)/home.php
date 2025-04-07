<?php
    include_once("fetched_data_db.php");

    if($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['sign_out']))
    {
        include_once("conn_db.php");

        setcookie("username", "", time()+(86400 * 2), "/");
        session_unset();
        session_destroy();

        $conn->close();
        header("Location: index.php?message=Log out sucessful!");
    }
    else if($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['profile']))
    {
        $id=mysqli_real_escape_string($conn, $_GET['id']);

        $conn->close();
        header("Location: user_profile.php?&id=".$id);
    }
    else if($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['settings']))
    {
        $conn->close();
        header("Location: user_settings.php?&id=".$id);
    }
    else if($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['orders']))
    {
        $conn->close();
        header("Location: 404.php?&id=".$id);
    }
    else if($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['view_menu']))
    {
        echo "viewing";
    }
    else if($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['add_to_orders']))
    {
        // echo "added";
        $menuID = mysqli_real_escape_string($conn, $_POST['add_to_orders']);
        $id = mysqli_real_escape_string($conn, $_GET['id']);
        if(isset($id))
        {
            // echo "hello: ".$menuID;
            $sql11 = "SELECT id, first_name, last_name, address FROM users WHERE id='$id'";  
            $result11 = mysqli_query($conn, $sql11);
            if($result11->num_rows > 0)
            {
                while($row = $result11->fetch_assoc())
                {
                    $sql12 = "SELECT menuID, menu_price, menu_name FROM shop_menu WHERE menuID='$menuID'";  
                    $result12 = mysqli_query($conn, $sql12);
                    if($result12->num_rows > 0)
                    {
                        while($row2 = $result12->fetch_assoc())
                        {
                            //echo "<br>menuID: ".$row2['menuID'];
                            //echo "menu ID: ".$row2['menuID']."<br>menu name: ".$row2['menu_name']."<br> menu price: ".$row2['menu_price'];

                            $customer_id = $row['id'];
                            $customer_name = $row['first_name'] . " " . $row['last_name'];

                            $menuID = $row2['menuID'];
                            $menu_name = $row2['menu_name'];
                            $menu_price = $row2['menu_price'];

                            //echo "ID: ".$customer_id."<br>customer name: ".$customer_name."<br>menu name: ".$menu_name."<br>menu price: ¥".$menu_price;

                            $sql13 = "SELECT orderlistID, id, customer_name, menuID, menu_price FROM order_list WHERE id='$customer_id'AND menuID='$menuID'";
                            $result13 = mysqli_query($conn, $sql13);
                            if($result13->num_rows > 0)
                            {
                                // echo "<script> alert('Item already in list.') </script>";
                                $conn->close();
                                header("Location: home.php?message=Item already in list!&id=".$customer_id);
                            }
                            else
                            {
                                //echo "<script> alert('Item has been added.') </script>";
                                $sql14 = "INSERT INTO `order_list`(orderlistID, id, customer_name, menuID, menu_name, menu_price) VALUES (NULL,'$customer_id','$customer_name','$menuID','$menu_name','$menu_price')";
                                $result14 = mysqli_query($conn, $sql14);
                                if($result14->num_rows>0)
                                {
                                    $conn->close();
                                    header("Location: home.php?message=Item already in list!&id=".$customer_id);
                                } 
                                else
                                {
                                    $conn->close();
                                    header("Location: home.php?message=Item added sucessfully!&id=".$customer_id);
                                }
                            }

                        }
                    }
                }
            }
            {
                echo "";
            }
        }
        else
        {
            echo "";
        }
    }
    else if($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['order_now']))
    {
        echo "ordered";
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <link rel="icon" type="image/x-icon" href="assets/Logo.png">
    <title>Niram - Home</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

    <!-- my css -->
    <link rel="stylesheet" href="main.css">
    <style>
        span{ color: #3B3232; }
        .span_1{ font-family: Righteous; font-size: 1.75rem; }

        .card{ border: 1px solid #c3c3c3; background-color: #ffff; width: 20rem; height: 26rem; }
        .span_2{ font-family: Poppins-Regular; }

        .list{ border: 1px solid #c3c3c3; background-color: #ffff; width: 100%; height: 4rem; }

        .dropdown-item:hover{ background-color: #f3f3f3; }

        .b_1{ border: 1px solid #B35C4E; background-color: #B35C4E; width: 100%; height: 1.2rem; }
        .b_1:hover{ transition: 0.2s ease-in-out; border: 1px solid #dd8476; background-color: #dd8476; }

        .b_1_disabled{ border: 1px solid #c3c3c3; background-color: #c3c3c3; width: 100%; height: 1.2rem; }
        .b_1_disabled:hover{ transition: 0.2s ease-in-out; border: 1px solid #f3f3f3; background-color: #f3f3f3; }

        .my-nav{ position: sticky; z-index: 10; top:0; }

        .b_2{ height: 2rem; border-style:none; background: transparent; width: 100%; }
        .b_2:hover{ transition: 0.2s ease-in-out; background-color: #f3f3f3; }

        .sidebar-brand:hover{ text-decoration: none; }

        .MENU_NAME{
            display: -webkit-box;
            -webkit-line-clamp: 1;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .MENU_DESCRIPTION{
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        @media (min-width: 200px){ 
            .TITLE{ display: none; }
            .col_1{ display: none; }
            .COUNT{ display: none; }
            .CONTENT{ display: none; }
        }

        @media (min-width: 300px){ 
            .CONTENT{ display: flex; justify-content: center; }

            .NAME{ display: flex; }
            .PRICE{ display: none; }
        }

        @media (min-width: 490px){ 
            .TITLE{ display: flex; }

            .PRICE{ display: flex; }
        }

        @media (min-width: 650px){ 
            .COUNT{ display: flex; }
            .col_1{ display: flex; }

            .CONTENT{ justify-content: start; }
        }

    </style>

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light topbar mb-4 static-top shadow" style="background-color: #393431;">

                    <!-- Sidebar - Brand -->
                    <a class="sidebar-brand d-flex align-items-center justify-content-center">
                        <div class="sidebar-brand-icon">
                            <img src="assets/Logo.png" alt="..." width="25rem" height="25rem">
                        </div>
                        <div class="sidebar-brand-text mx-3"> <span style="font-family: Righteous; color: #D33939;"> NIRAM </span> </div>
                    </a>

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- Nav Item - User Information -->
                        <form method="POST">
                            <li class="nav-item dropdown no-arrow">
                                <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <span class="mr-2 d-none d-lg-inline text-gray-600 small"> <?php echo $first_name . " " . $last_name ?> </span>
                                    <img class="img-profile rounded-circle" src="<?php echo $profile_image ?>">
                                </a>
                                <!-- Dropdown - User Information -->
                                <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                    aria-labelledby="userDropdown">

                                    <!-- PROFILE -->
                                    <button type="submit" name="profile" class="b_2 d-flex justify-content-start align-items-center pl-4">
                                        <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                        Profile
                                    </button>
                                    <!-- SETTINGS -->
                                    <button type="submit" name="settings" class="b_2 d-flex justify-content-start align-items-center pl-4">
                                        <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                                        Settings
                                    </button>
                                    <!-- ORDERS -->
                                    <button type="submit" name="orders" class="b_2 d-flex justify-content-start align-items-center pl-4">
                                        <i class="fas fa-concierge-bell fa-sm fa-fw mr-2 text-gray-400"></i>
                                        Orders
                                    </button>
                                    <div class="dropdown-divider"></div>

                                    <!-- LOGOUT -->
                                    <button type="submit" name="sign_out" class="b_2 d-flex justify-content-start align-items-center pl-4">
                                        <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                        Logout
                                    </button>
                                </div>
                            </li>
                        </form>

                    </ul>

                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">
                    <div class="row d-flex flex-column">
            
                        <br>
            
                      <div class="col">
                        <form method="POST">
                        
                            <div class="col d-flex flex-row p-0">
                           
                                <div class="TITLE col-auto p-0 flex-row justify-content-center align-items-center"> 
                                    <span class="span_2"> NIRAM MENU </span> 
                                </div>
                
                                <div class="col_1 col"></div>
                
                                <div class="col-auto p-0 d-flex flex-row justify-content-center align-items-center"> 
                                    
                                    <div class="COUNT col h-100 justify-content-center align-items-center"> <span class="span_2" style="font-size: 1rem;"> Showing 1 out of <?php echo $pages3 ?> </span> </div>
            
                                    <div class="col-auto h-100 d-flex justify-content-center align-items-center" style="gap: 0.3rem;">
            
                                        <!-- very first -->
                                        <!-- <a href='...' class='border d-flex justify-content-center align-items-center' style='background-color: #f3f3f3; border-radius: 6px; width: 1.75rem; font-size: 1.5rem; text-align: center;' title='First'>
                                            <span> &laquo; </span>
                                        </a>   -->

                                        <?php
                                            $id=mysqli_real_escape_string($conn, $_GET['id']);
                                            if(isset($id))
                                            {
                                                echo"
                                                <a href='?page-nr=1&id=$id' class='border d-flex justify-content-center align-items-center' style='background-color: #f3f3f3; border-radius: 6px; width: 1.75rem; font-size: 1.5rem; text-align: center;' title='First'>
                                                    <span> &laquo; </span>
                                                </a>
                                                ";
                                            }
                                        ?>
               

                                        <!-- previous -->
                                        <!-- <a href='...' class='border d-flex justify-content-center align-items-center' style='background-color: #f3f3f3; border-radius: 6px; width: 1.75rem; font-size: 1.5rem; text-align: center;' title='Previous'>
                                            <span> &lsaquo; </span>
                                        </a>   -->
                                        <?php
                                            $id=mysqli_real_escape_string($conn, $_GET['id']);
                                            if(isset($id))
                                            {
                                                if(isset($_GET['page-nr']) && $_GET['page-nr'] > 1)
                                                {
                                                    $previous_page = intval($_GET['page-nr']) - 1;
                                                    echo "
                                                        <a href='?page-nr=$previous_page&id=$id' class='border d-flex justify-content-center align-items-center' style='background-color: #f3f3f3; border-radius: 6px; width: 1.75rem; font-size: 1.5rem; text-align: center;' title='Previous'>
                                                            <span> &lsaquo; </span>
                                                        </a>
                                                    ";
                                                }
                                                else
                                                {
                                                    echo "
                                                        <a class='border d-flex justify-content-center align-items-center' style='background-color: #f3f3f3; border-radius: 6px; width: 1.75rem; font-size: 1.5rem; text-align: center;' title='Previous'>
                                                            <span> &lsaquo; </span>
                                                        </a>
                                                    ";
                                                }   
                                            }
                                        ?>
                                        
            
                                        <!-- next -->
                                        <!-- <a href='...' class='border d-flex justify-content-center align-items-center' style='background-color: #f3f3f3; border-radius: 6px; width: 1.75rem; font-size: 1.5rem; text-align: center;' title='Next'>
                                            <span> &rsaquo; </span>
                                        </a>   -->
                                        <?php
                                            $id=mysqli_real_escape_string($conn, $_GET['id']);
                                            if(isset($id))
                                            {
                                                if(!isset($_GET['page-nr']))
                                                {
                                                    echo"
                                                        <a href='?page-nr=2&id=$id' class='border d-flex justify-content-center align-items-center' style='background-color: #f3f3f3; border-radius: 6px; width: 1.75rem; font-size: 1.5rem; text-align: center;' title='Next'>
                                                            <span> &rsaquo; </span>
                                                        </a> 
                                                    ";
                                                }
                                                else
                                                {
                                                    if($_GET['page-nr'] >= $pages3)
                                                    {
                                                        echo "
                                                            <a class='border d-flex justify-content-center align-items-center' style='background-color: #f3f3f3; border-radius: 6px; width: 1.75rem; font-size: 1.5rem; text-align: center;' title='Next'>
                                                                <span> &rsaquo; </span>
                                                            </a> 
                                                        ";
                                                    }
                                                    else
                                                    {
                                                        $next_page = intval($_GET['page-nr']) + 1;
                                        
                                                        echo "
                                                            <a href='?page-nr=$next_page&id=$id' class='border d-flex justify-content-center align-items-center' style='background-color: #f3f3f3; border-radius: 6px; width: 1.75rem; font-size: 1.5rem; text-align: center;' title='Next'>
                                                                <span> &rsaquo; </span>
                                                            </a>
                                                        ";
                                                    }
                                                }
                                            }
                                        ?>
                                        
        
                                        <!-- very end -->
                                        <!-- <a href='...' class='border d-flex justify-content-center align-items-center' style='background-color: #f3f3f3; border-radius: 6px; width: 1.75rem; font-size: 1.5rem; text-align: center;' title='Last'>
                                            <span> &raquo; </span>
                                        </a>   -->
                                        <?php
                                            $id=mysqli_real_escape_string($conn, $_GET['id']);
                                            if(isset($id))
                                            {
                                                echo"
                                                    <a href='?page-nr=$pages3&id=$id' class='border d-flex justify-content-center align-items-center' style='background-color: #f3f3f3; border-radius: 6px; width: 1.75rem; font-size: 1.5rem; text-align: center;' title='Last'>
                                                        <span> &raquo; </span>
                                                    </a>
                                                ";
                                            }
                                        ?>
                                        
            
                                    </div>
            
                                </div>
                
                            </div>
                            <hr>
                
                            <div class="CONTENT flex-wrap" style="gap: 1rem;">
                
                                <!-- 
                                <div class="card">
                                    <div class="col-auto d-flex justify-content-center align-items-center" style="height: 10rem;">
                                        <div class="col-auto d-flex justify-content-center align-items-center" style="border: 1px solid #F2E7C9; background-color: #F2E7C9; border-radius: 50%; width: 5rem; height: 5rem;">
                                            <img src="assets/ramen.png" alt="..." width="40rem" height="40rem">
                                        </div>
                                    </div>
                
                                    
                                    <div class="col-auto"> <span class="span_2"> <b> ¥ 297.08 </b> </span> </div>
                                    <div class="col-auto"> <span class="span_2" style="font-size: 0.8rem;"> Tonkotsu Ramen </span> </div>
                                    <div class="col-auto" style="height: 3rem;"> <span class="span_2" style="font-size: 0.8rem; color: #c3c3c3;"> Pork bone and soy milk broth </span> </div>
                
                                    <div class='col-auto d-flex justify-content-center align-items-center' style='height: 3rem;'>
                                        <button type='submit' name='add_to_orders' style='border: 1px solid #B35C4E; background-color: #B35C4E; width: 100%; height: 2rem; font-size: 0.8rem; color: white; font-weight: 500;'>
                                            <span style='font-family: Poppins-Medium; color: #ffff; font-size: 0.8rem;'> ADD TO ORDERS </span>
                                        </button>
                                    </div>
                                </div>  
                                -->
                                <!-- EDIBLE -->
                                <?php
                                    if($result9->num_rows > 0)
                                    {
                                        while($row = $result9->fetch_assoc())
                                        {
                                            if($row['menu_state']==="Available")
                                            {
                                                echo 
                                                " 
                                                    <div class='card'>
                                                        <div class='col-auto p-2 d-flex justify-content-center align-items-center' style='height: 15rem;'>
                                                            <div class='col p-0 h-100 d-flex justify-content-center align-items-center' style='border: 1px solid #ffff; background-color: #ffff;'>
                                                                <img src='assets/ramensample.png' alt='...' width='100%' height='100%' style='border-radius: 4px;'>
                                                            </div>
                                                        </div>
                                    
                                                        
                                                        <div class='col-auto d-flex flex-row'> 
                                                            <div class='col p-0 d-flex justify-content-start align-items-center'> 
                                                                <span class='span_2'> <b> ¥ ".$row['menu_price']." </b> </span>  
                                                            </div>
                                                            <div class='col-auto p-0 d-flex justify-content-end align-items-center'> 
                                                                <button type='submit' name='view_menu' value='".$row['menuID']."' style='border: 1px solid #ffff; background-color: #ffff; color: #D33939;'> view </button> 

                                                                <button title='Add to orders' type='submit' name='add_to_orders' value='".$row['menuID']."' class='col-auto d-flex justify-content-center align-items-center' style='border: 1px solid #ffff; background-color: #ffff; width: 0.8rem; height: 0.8rem;'>
                                                                    <img src='assets/bell3.png' alt='...' width='20rem' height='20rem'>
                                                                </button>
                                                            </div>
                                                        </div>

                                                        <div style='height: 1rem;'></div>
    
                                                        <div class='col-auto'> <span class='MENU_NAME span_2' style='font-size: 0.8rem;'> ".$row['menu_name']." </span> </div>
                                                        <div class='col-auto' style='height: 3rem;'> <span class='MENU_DESCRIPTION span_2' style='font-size: 0.8rem; color: #c3c3c3;'> ".$row['menu_description']." </span> </div>
                                    
                                                        <div class='col-auto d-flex justify-content-center align-items-center' style='height: 3rem;'>
                                                            <button title='Order now' type='submit' name='order_now' value='".$row['menuID']."' style='border: 1px solid #D33939; background-color: #D33939; width: 100%; height: 2rem; font-size: 0.8rem; color: white; font-weight: 500;'>
                                                                <span style='font-family: Poppins-Medium; color: #ffff; font-size: 0.8rem;'> ORDER NOW </span>
                                                            </button>
                                                        </div>
                                                    </div>  
                                                ";
                                            }
                                            else if($row['menu_state']==="Unavailable")
                                            {
                                                echo 
                                                " 
                                                    <div class='card'>
                                                        <div class='col-auto p-2 d-flex justify-content-center align-items-center' style='height: 15rem;'>
                                                            <div class='col p-0 h-100 d-flex justify-content-center align-items-center' style='border: 1px solid #ffff; background-color: #ffff;'>
                                                                <img src='assets/ramensample.png' alt='...' width='100%' height='100%' style='border-radius: 4px;'>
                                                            </div>
                                                        </div>
                                    
                                                        
                                                        <div class='col-auto d-flex flex-row'> 
                                                            <div class='col p-0 d-flex justify-content-start align-items-center'> 
                                                                <span class='span_2'> <b> ¥ ".$row['menu_price']." </b> </span>  
                                                            </div>

                                                            
                                                            <div class='col-auto p-0 d-flex justify-content-end align-items-center'> 
                                                                <button type='submit' name='view_menu' value='".$row['menuID']."' style='border: 1px solid #ffff; background-color: #ffff; color: #D33939;'> view </button> 

                                                                <button title='Unavailable' type='button' data-toggle='modal' data-target='#exampleModal' value='".$row['menuID']."' class='col-auto d-flex justify-content-center align-items-center' style='border: 1px solid #ffff; background-color: #ffff; width: 0.8rem; height: 0.8rem;'>
                                                                    <img src='assets/bell3.png' alt='...' width='20rem' height='20rem'>
                                                                </button>
                                                            </div>
                                                        </div>

                                                        <div style='height: 1rem;'></div>
    
                                                        <div class='col-auto'> <span class='MENU_NAME span_2' style='font-size: 0.8rem;'> ".$row['menu_name']." </span> </div>
                                                        <div class='col-auto' style='height: 3rem;'> <span class='MENU_DESCRIPTION span_2' style='font-size: 0.8rem; color: #c3c3c3;'> ".$row['menu_description']." </span> </div>
                                    
                                                        <div class='col-auto d-flex justify-content-center align-items-center' style='height: 3rem;'>
                                                            <button title='Unavailable' type='button' data-toggle='modal' data-target='#exampleModal' style='border: 1px solid  #F28B82; background-color: #F28B82; width: 100%; height: 2rem; font-size: 0.8rem; color: white; font-weight: 500;'>
                                                                <span style='font-family: Poppins-Medium; color: #D33939; font-size: 0.8rem;'> UNAVAILABLE </span>
                                                            </button>
                                                        </div>
                                                    </div>  
                                                ";
                                            }
                                        }
                                    }        
                                ?>

                            </div>
            
                        </form>
                      </div>
            
                      <br>
            
                      <div class="col">
                        <form method="POST">
                        
                            <div class="col d-flex flex-row p-0">
                           
                                <div class="TITLE col-auto p-0 flex-row justify-content-center align-items-center"> 
                                    <span class="span_2"> DRINKS </span> 
                                </div>
                
                                <div class="col"></div>
                                
                
                                <div class="col-auto h-100 d-flex justify-content-center align-items-center" style="gap: 0.3rem;">
            
                                <div class="COUNT col h-100 justify-content-center align-items-center"> <span class="span_2" style="font-size: 1rem;"> Showing 1 out of <?php echo $pages3 ?> </span> </div>

                                <!-- very first -->
                                <!-- <a href='...' class='border d-flex justify-content-center align-items-center' style='background-color: #f3f3f3; border-radius: 6px; width: 1.75rem; font-size: 1.5rem; text-align: center;' title='First'>
                                    <span> &laquo; </span>
                                </a>   -->

                                <?php
                                    $id=mysqli_real_escape_string($conn, $_GET['id']);
                                    if(isset($id))
                                    {
                                        echo"
                                        <a href='?page-nr=1&id=$id' class='border d-flex justify-content-center align-items-center' style='background-color: #f3f3f3; border-radius: 6px; width: 1.75rem; font-size: 1.5rem; text-align: center;' title='First'>
                                            <span> &laquo; </span>
                                        </a>
                                        ";
                                    }
                                ?>


                                <!-- previous -->
                                <!-- <a href='...' class='border d-flex justify-content-center align-items-center' style='background-color: #f3f3f3; border-radius: 6px; width: 1.75rem; font-size: 1.5rem; text-align: center;' title='Previous'>
                                    <span> &lsaquo; </span>
                                </a>   -->
                                <?php
                                    $id=mysqli_real_escape_string($conn, $_GET['id']);
                                    if(isset($id))
                                    {
                                        if(isset($_GET['page-nr']) && $_GET['page-nr'] > 1)
                                        {
                                            $previous_page = intval($_GET['page-nr']) - 1;
                                            echo "
                                                <a href='?page-nr=$previous_page&id=$id' class='border d-flex justify-content-center align-items-center' style='background-color: #f3f3f3; border-radius: 6px; width: 1.75rem; font-size: 1.5rem; text-align: center;' title='Previous'>
                                                    <span> &lsaquo; </span>
                                                </a>
                                            ";
                                        }
                                        else
                                        {
                                            echo "
                                                <a class='border d-flex justify-content-center align-items-center' style='background-color: #f3f3f3; border-radius: 6px; width: 1.75rem; font-size: 1.5rem; text-align: center;' title='Previous'>
                                                    <span> &lsaquo; </span>
                                                </a>
                                            ";
                                        }   
                                    }
                                ?>
                                

                                <!-- next -->
                                <!-- <a href='...' class='border d-flex justify-content-center align-items-center' style='background-color: #f3f3f3; border-radius: 6px; width: 1.75rem; font-size: 1.5rem; text-align: center;' title='Next'>
                                    <span> &rsaquo; </span>
                                </a>   -->
                                <?php
                                    $id=mysqli_real_escape_string($conn, $_GET['id']);
                                    if(isset($id))
                                    {
                                        if(!isset($_GET['page-nr']))
                                        {
                                            echo"
                                                <a href='?page-nr=2&id=$id' class='border d-flex justify-content-center align-items-center' style='background-color: #f3f3f3; border-radius: 6px; width: 1.75rem; font-size: 1.5rem; text-align: center;' title='Next'>
                                                    <span> &rsaquo; </span>
                                                </a> 
                                            ";
                                        }
                                        else
                                        {
                                            if($_GET['page-nr'] >= $pages4)
                                            {
                                                echo "
                                                    <a class='border d-flex justify-content-center align-items-center' style='background-color: #f3f3f3; border-radius: 6px; width: 1.75rem; font-size: 1.5rem; text-align: center;' title='Next'>
                                                        <span> &rsaquo; </span>
                                                    </a> 
                                                ";
                                            }
                                            else
                                            {
                                                $next_page = intval($_GET['page-nr']) + 1;
                                
                                                echo "
                                                    <a href='?page-nr=$next_page&id=$id' class='border d-flex justify-content-center align-items-center' style='background-color: #f3f3f3; border-radius: 6px; width: 1.75rem; font-size: 1.5rem; text-align: center;' title='Next'>
                                                        <span> &rsaquo; </span>
                                                    </a>
                                                ";
                                            }
                                        }
                                    }
                                ?>
                                

                                <!-- very end -->
                                <!-- <a href='...' class='border d-flex justify-content-center align-items-center' style='background-color: #f3f3f3; border-radius: 6px; width: 1.75rem; font-size: 1.5rem; text-align: center;' title='Last'>
                                    <span> &raquo; </span>
                                </a>   -->
                                <?php
                                    $id=mysqli_real_escape_string($conn, $_GET['id']);
                                    if(isset($id))
                                    {
                                        echo"
                                            <a href='?page-nr=$pages4&id=$id' class='border d-flex justify-content-center align-items-center' style='background-color: #f3f3f3; border-radius: 6px; width: 1.75rem; font-size: 1.5rem; text-align: center;' title='Last'>
                                                <span> &raquo; </span>
                                            </a>
                                        ";
                                    }
                                ?>
                                

                            </div>
                
                            </div>
                            <hr>
                
                            <div class="CONTENT flex-wrap" style="gap: 1rem;">
                
                                <!-- 
                                <div class="list p-0 d-flex flex-row">
                                    <div class="col-auto h-100 p-0 d-flex justify-content-center align-items-center" style="width: 4rem;">
                                        
                                        <div class="col-auto d-flex justify-content-center align-items-center" style="border: 1px solid #F2E7C9; background-color: #F2E7C9; border-radius: 50%; width: 2.5rem; height: 2.5rem;">
                                            <img src="assets/drink.png" alt="..." width="20rem" height="20rem">
                                        </div>
            
                                    </div>
            
                                    <div class="NAME col h-100 justify-content-start align-items-center">
                                        <span class="span_2"> Coca-cola </span>
                                    </div>
            
                                    <div class="PRICE col-auto h-100 justify-content-start align-items-center">  
                                        <span class="span_2"> <b> ¥ 297.08 </b> </span>
                                    </div>
            
                                    <div class='col-auto h-100 d-flex justify-content-center align-items-center' style='height: 3rem;'>
                                        <button type='submit' name='add_to_orders' style='border: 1px solid #B35C4E; background-color: #B35C4E; width: 100%; height: 2rem; font-size: 0.8rem; color: white; font-weight: 500;'>
                                            <span style='font-family: Poppins-Medium; color: #ffff; font-size: 0.8rem;'> ADD TO ORDERS </span>
                                        </button>
                                    </div>
            
                                </div> 
                                -->

                                <!-- DRINK -->
                                <?php
                                    if($result10->num_rows > 0)
                                    {
                                        while($row = $result10->fetch_assoc())
                                        {
                                            if($row['menu_type']==="Drink")
                                            {
                                                echo 
                                                " 
                                                    <div class='list p-0 d-flex flex-row'>
                                                        <div class='col-auto h-100 p-0 d-flex justify-content-center align-items-center' style='width: 4rem;'>
                                                            
                                                            <div class='col-auto d-flex justify-content-center align-items-center' style='border: 1px solid #F2E7C9; background-color: #F2E7C9; border-radius: 50%; width: 2.5rem; height: 2.5rem;'>
                                                                <img src='".$row['menu_image']."' alt='...' width='20rem' height='20rem'>
                                                            </div>
                                
                                                        </div>
                                
                                                        <div class='NAME col h-100 justify-content-start align-items-center'>
                                                            <span class='MENU_NAME span_2'> ".$row['menu_name']." </span>
                                                        </div>
                                
                                                        <div class='PRICE col-auto h-100 justify-content-start align-items-center'>  
                                                            <span class='span_2'> <b> ¥ ".$row['menu_price']." </b> </span>
                                                        </div>
                                
                                                        <div class='col-auto h-100 d-flex justify-content-center align-items-center' style='height: 3rem;'>
                                                            <button title='Add to orders' type='submit' name='add_to_orders' value='".$row['menuID']."' class='col-auto d-flex justify-content-center align-items-center' style='border: 1px solid #D33939; background-color: #D33939; width: 2rem; height: 2rem;'>
                                                                <img src='assets/bell.png' alt='...' width='20rem' height='20rem'>
                                                            </button>
                                                        </div>
                                                    </div> 
                                                ";
                                            }  
                                        }
                                    }    
                                ?>
                                
                            </div>
            
                        </form>
                      </div>
            
                    </div>
                  </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <br>

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Niram 2025</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <!-- <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="login.html">Logout</a>
                </div>
            </div>
        </div>
    </div> -->

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">We apologize for the inconvenience.</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            Item is currently unavailable.
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
        </div>
    </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="vendor/chart.js/Chart.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="js/demo/chart-area-demo.js"></script>
    <script src="js/demo/chart-pie-demo.js"></script>

</body>

</html>