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
        $conn->close();
        header("Location: admin_profile.php?&id=".$id);
    }
    else if($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['settings']))
    {
        $conn->close();
        header("Location: admin_settings.php?&id=".$id);
    }
    else if($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['home']))
    {
        $id = mysqli_real_escape_string($conn, $_GET['id']);
        if(isset($id))
        {
            $conn->close();
            header("Location: dashboard.php?&id=".$id);
        }
    }
    else if($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['cancel_editing']))
    {
        $userID = mysqli_real_escape_string($conn, $_GET['userID']);
        $conn->close();
        header("Location: view_user_db.php?&id=".$id."&userID=".$userID);
    }
    else if($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['add_menu']))
    {
        $price = $_POST['menu_price'];
        $name = $_POST['menu_name'];
        $description = $_POST['menu_description'];
        $type = $_POST['menu_type'];

        if(!empty($price) && !empty($name) && !empty($description) && $type !== "0")
        {
            if(!is_double($price) && !is_numeric($price))
            {
                echo "<script> alert('Price must be a number.') </script>";
            }
            else
            {
                
                if(is_numeric($name))
                {
                    echo "<script> alert('Name cannot start as a number.') </script>";
                }
                else
                {
                    if(is_numeric($description))
                    {
                        echo "<script> alert('Description cannot start as a number.') </script>";
                    }
                    else
                    {
                        // echo "<script> alert('added') </script>";

                        $sql = "SELECT menu_name FROM shop_menu WHERE menu_name='$name'";
                        $result = mysqli_query($conn, $sql);
                        $count_rows = mysqli_fetch_assoc($result);

                        if($count_rows > 0)
                        {
                            echo "<script> alert('This item is already in the menu.') </script>";
                        }
                        else
                        {
                            // echo "<script> alert('added.') </script>";
                            
                            if($type==="Edible")
                            {
                                $menu_image = "assets/ramen.png";

                                $sql2 = "INSERT INTO `shop_menu`(menuID, menu_image, menu_price, menu_name, menu_description, menu_type, menu_state) VALUES (NULL,'$menu_image','$price','$name','$description','$type', 'Available')";
                                $result2 = mysqli_query($conn, $sql2);
    
                                if($result2===false)
                                {
                                    echo"<script> alert('Database query failed.') </script>";
                                }
    
                                $conn->close();
                                header("Location: add_menu.php?message=Menu has been added successfully!&id=".$id);
                            }
                            else
                            {
                                $menu_image = "assets/drink.png";

                                $sql2 = "INSERT INTO `shop_menu`(menuID, menu_image, menu_price, menu_name, menu_description, menu_type, menu_state) VALUES (NULL,'$menu_image','$price','$name','$description','$type', 'Available')";
                                $result2 = mysqli_query($conn, $sql2);
    
                                if($result2===false)
                                {
                                    echo"<script> alert('Database query failed.') </script>";
                                }
    
                                $conn->close();
                                header("Location: add_menu.php?message=Menu has been added successfully!&id=".$id);
                            }
                        }
                    }
                }
            }
        }
        else
        {
            echo "<script> alert('Please fill in the required fields.') </script>";
        }
 
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
    <title>Niram - Add menu</title>

    <!-- Custom fonts for this template -->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

    <!-- Custom styles for this page -->
    <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

    <!-- my css -->
    <style>

        .my-nav{ background-color: #D33939; }

        @font-face{ font-family: "Poppins-Regular"; src: url(assets/Poppins-Regular.ttf); } 
        @font-face{ font-family: "Poppins-Medium"; src: url(assets/Poppins-Medium.ttf); } 
        @font-face{ font-family: "Righteous"; src: url(assets/Righteous-Regular.ttf); } 

        .my-nav{ background-color: #D33939; }

        .dropdown-item:hover{ background-color: #f3f3f3; }

        .b_1{ border: 1px solid #B35C4E; background-color: #B35C4E; width: 100%; height: 1.2rem; }
        .b_1:hover{ transition: 0.2s ease-in-out; border: 1px solid #dd8476; background-color: #dd8476; }

        .b_1_disabled{ border: 1px solid #c3c3c3; background-color: #c3c3c3; width: 100%; height: 1.2rem; }
        .b_1_disabled:hover{ transition: 0.2s ease-in-out; border: 1px solid #f3f3f3; background-color: #f3f3f3; }

        .my-nav{ position: sticky; z-index: 10; top:0; }

        .b_2{ height: 2rem; border-style:none; background: transparent; width: 100%; }
        .b_2:hover{ transition: 0.2s ease-in-out; background-color: #f3f3f3; }

        .sidebar-brand:hover{ text-decoration: none; }

        input::-webkit-file-upload-button{ border-style: none; background-color: #B35C4E; width: 8rem; height: 100%; font-size: 0.8rem; color: #ffff; cursor: pointer; }

        input:focus 
        {
            border: none;
            outline: none;
        }
        select:focus 
        {
            border: none;
            outline: none;
        }

        .INPUT{ background-color: #ffff; height: 2.5rem; }

        .INPUT > input{ padding-left: 1rem; border-style: none; outline-style: none; background: transparent; width: 100%; height: 100%; }
        .INPUT > button{ border-style: none; background-color: #B35C4E; width: 3rem; }

        @media (min-width: 100px){ 
            .HEADLINE{ display: none; }
            .CONTENT{ display:none; }
        }

        @media (min-width: 300px){ 
            .HEADLINE{ display: flex; }
            .CONTENT{ display:flex; }
        }
    </style>

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="my-nav navbar-nav sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
                <div class="sidebar-brand-icon">
                    <img src="assets/Logo.png" alt="..." width="25rem" height="25rem">
                </div>
                <div class="sidebar-brand-text mx-3"> <span style="font-family: Righteous;"> NIRAM </span> </div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item">
                <!-- <a class="nav-link" href="index.html">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a> -->
                <?php
                    $id=mysqli_real_escape_string($conn, $_GET['id']);
                    if(isset($id))
                    {
                        echo"
                            <a class='nav-link' href='dashboard.php?&id=$id'>
                                <i class='fas fa-fw fa-tachometer-alt'></i>
                                <span>Dashboard</span>
                            </a>
                        ";
                    }
                ?>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Creation
            </div>

            <!-- Nav Item - Utilities Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities"
                    aria-expanded="true" aria-controls="collapseUtilities">
                    <i class="fas fa-fw fa-wrench"></i>
                    <span>Utilities</span>
                </a>
                <div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities"
                    data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">ADD NEW:</h6>
                        <?php $id=mysqli_real_escape_string($conn, $_GET['id']); if(isset($id)){ echo"<a class='collapse-item' href='add_user.php?&id=$id'>User</a>"; } ?>
                        <?php $id=mysqli_real_escape_string($conn, $_GET['id']); if(isset($id)){ echo"<a class='collapse-item' href='add_menu.php?&id=$id'>Menu</a>"; } ?>
                    </div>
                </div>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Tables
            </div>

            <!-- Nav Item - Menus -->
            <li class="nav-item">
                <!-- <a class="nav-link" href="userstable.html">
                    <i class="fas fa-fw fa-table"></i>
                    <span>Users</span>
                </a> -->
                <?php
                    $id=mysqli_real_escape_string($conn, $_GET['id']);
                    if(isset($id))
                    {
                        echo"
                            <a class='nav-link active' href='userstable.php?&id=$id'>
                                <i class='fas fa-fw fa-table'></i>
                                <span>Users</span>
                            </a>
                        ";
                    }
                ?>
            </li>

            <!-- Nav Item - Menus -->
            <li class="nav-item active">
                <!-- <a class="nav-link" href="menustable.html">
                    <i class="fas fa-fw fa-table"></i>
                    <span>Menus</span></a> -->
                <?php
                    $id=mysqli_real_escape_string($conn, $_GET['id']);
                    if(isset($id))
                    {
                        echo"
                            <a class='nav-link' href='menustable.php?&id=$id'>
                                <i class='fas fa-fw fa-table'></i>
                                <span>Menus</span>
                            </a>
                        ";
                    }
                ?>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light topbar mb-4 static-top shadow" style="background-color: #ffff;">

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

                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex flex-row">
                            
                            <div class="HEADLINE col justify-content-start align-items-center p-0">
                                <span class="m-0 font-weight-bold text-primary"> ADD MENU </span>
                            </div>
                            <div class="col d-flex justify-content-end align-items-center p-0">
                                
                                <div class="col-auto h-100 d-flex justify-content-center align-items-center" style="gap: 0.3rem;"></div>

                            </div>
                            
                        </div>
                        <div class="card-body">
                                <form method="POST">
                                                
                                <div class="CONTENT justify-content-center align-items-center" style="gap: 1rem;">
                                        
                                        <div class="bg-white p-1" style="width: 50rem;">
                
                                            <div class="d-flex justify-content-center align-items-center" style="height: 10rem;">
                                                <img class="img-profile rounded-circle" src="assets/sample.png" width="100rem" heigth="100rem">
                                            </div>
        
                                            <br>
        
                                            <div class="d-flex flex-column justify-content-start align-items-center p-3" style="gap: 1rem;">
        
                                                <div class="col-auto border d-flex flex-row w-100 p-0" style="height: 2.5rem;">
                                                    <div class="col-auto d-flex justify-content-center align-items-center" style="padding-left: 1.5rem; width: 2rem;"> <i class="fas fa-pencil-alt fa-sm fa-fw mr-2 text-gray-400"></i> </div>
                                                    <div class="col">
                                                        <input type="text" name="menu_price" placeholder='Price' class="w-100 h-100" style="border-style:none;">
                                                    </div>
                                                </div>
        
                                                <div class="col-auto border d-flex flex-row w-100 p-0" style="height: 2.5rem;">
                                                    <div class="col-auto d-flex justify-content-center align-items-center" style="padding-left: 1.5rem; width: 2rem;"> <i class="fas fa-pencil-alt fa-sm fa-fw mr-2 text-gray-400"></i> </div>
                                                    <div class="col">
                                                        <input type="text" name="menu_name" placeholder='Name' class="w-100 h-100" style="border-style:none;">
                                                    </div>
                                                </div>
        
                                                <div class="col-auto border d-flex flex-row w-100 p-0" style="height: 2.5rem;">
                                                    <div class="col-auto d-flex justify-content-center align-items-center" style="padding-left: 1.5rem; width: 2rem;"> <i class="fas fa-pencil-alt fa-sm fa-fw mr-2 text-gray-400"></i> </div>
                                                    <div class="col">
                                                        <input type="text" name="menu_description" placeholder='Description' class="w-100 h-100" style="border-style:none;">
                                                    </div>
                                                </div>

                                                <div class='col-auto border d-flex flex-row w-100 p-1' style='height: 2.5rem;'>
                                                    <div class='col-auto d-flex justify-content-center align-items-center' style='padding-left: 1.5rem; width: 2rem;'> <i class='fas fa-pencil-alt fa-sm fa-fw mr-2 text-gray-400'></i> </div>
                                                    <select name='menu_type' class='w-100 h-100' style='border-style:none;'>
                                                        <option value='0'> Select a type </option>
                                                        <option value='Edible'> Edible </option>
                                                        <option value='Drink'> Drink </option>
                                                    </select>
                                                </div>
        
                                                <!-- button: add new menu -->
                                                <div class='w-100' style='height:3rem;'>
                                                    <button type='submit' name='add_menu' class='w-100 h-100' style='background-color: #C5D1A6; color: #91A168; border: none; cursor: pointer;'>
                                                        Add menu
                                                    </button>
                                                </div>
        
                                            </div>
        
                                            <br>
        
                                        </div>
        
                                    </div>
                                
                                </form>
                        </div>
                    </div>

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; NIRAM 2025</span>
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
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
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
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="js/demo/datatables-demo.js"></script>

</body>

</html>