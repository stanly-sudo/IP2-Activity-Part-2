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
        $conn->close();
        header("Location: dashboard.php?&id=".$id);
    }
    else if($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['view']))
    {
        $userID = $_POST['view'];

        $conn->close();
        header("Location: view_user_db.php?&id=".$id."&userID=".$userID);
    }
    else if($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['edit']))
    {
        $userID = $_POST['edit'];

        $conn->close();
        header("Location: edit_user_db.php?&id=".$id."&userID=".$userID);
    }
    else if($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['delete']))
    {
        $userID = $_POST['delete'];

        if(isset($userID))
        {
            $sql = "DELETE FROM users WHERE id='$userID'";

            if($conn->query($sql) === TRUE) 
            {
              header("Location: userstable.php?message=User sucessfully deleted!&id=".$id);
            } 
            else 
            {
              echo "Error deleting record: " . $conn->error;
            }
        }
        else
        {
            echo "<script> alert('No id found.') </script>";
        }   
    }
    else if($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['search_for_in_table']))
    {
        // echo "hello 1";
        $search_value = $_POST['search'];
        $sql = "SELECT id, first_name, last_name, username, age, address, role FROM users WHERE id='$search_value' OR first_name='$search_value' OR last_name='$search_value' OR username='$search_value' OR age='$search_value' OR address LIKE '$search_value' OR role='$search_value'";
        $result = mysqli_query($conn, $sql);
        if($result->num_rows > 0)
        {
            while($row = $result->fetch_assoc())
            {
                // echo "found";
                // $conn->close();
                // header("Location: search_in_user_table_db.php?message=User found!&id=".$id."&search=".$search_value);

                // echo "hello ".$row['first_name']." ";
                // echo "[ userID: ".$row['id']."]<br>";

                // $data = array($row['id']);
                // foreach ($data as $x) {
                //     echo "$x <br>";

                //     header("Location: userstable.php?message=users found&id=".$x);
                // }

                // hmmm....

                // $conn->close();
                header("Location: search_in_user_table_db.php?message=Users found.&id=".$id."&search_value=".$search_value);
                
            }
        }
        else
        {
            $error_value=0;
            header("Location: search_in_user_table_db.php?message=Users found.&id=".$id."&search_value=".$error_value);
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
    <title>Niram - Users Table</title>

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

        @media (min-width: 100px){ 
            .HEADLINE{ display: none; }
            .COUNT{ display: none; }
        }

        @media (min-width: 300px){ 
            .HEADLINE{ display: flex; }
            .SEARCHBAR{ display: none; }
        }

        @media (min-width: 490px){ 
            .SEARCHBAR{ display: flex; }
        }

        @media (min-width: 600px){ 
            .COUNT{ display: flex; }
        }

    </style>

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="my-nav navbar-nav sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="#">
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
<!--                         
                        <a href="#">text</a>
                        <a href="#">text</a> -->

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
            <li class="nav-item active">
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
            <li class="nav-item">
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
                            
                            <div class="HEADLINE col justify-content-start align-items-center p-0" style="gap: 0.8rem;">
                               <div>
                                    <span class="m-0 font-weight-bold text-primary"> USERS </span>
                               </div>
                               <div class="SEARCHBAR col p-0 w-100 h-100 flex-row">
                                    <form method="POST" class="d-flex flex-row w-100" style="gap:0.6rem;">
                                        <div class="d-flex flex-row w-100" style="border: 1px solid rgb(233, 233, 233);">
                                            <div class="col-auto border-primary p-0 h-100" style="width: 2.5rem;">
                                                <button type="submit" title="Search" name="search_for_in_table" class="w-100 h-100 bg-primary d-flex justify-content-center align-items-center" style="border:1px solid #0d6efd;">
                                                    <img src="assets/search.png" alt="..." width="15rem" height="15rem">
                                                </button>
                                            </div>
                                            <div class="col p-0 w-100 bg-white">
                                                <input type="text" name="search" placeholder="Search for..." class="w-100 h-100 pl-2" style="outline-style: none; border:none;">
                                            </div>
                                        </div>
                                    </form>
                               </div>
                            </div>

                            <div class="col d-flex justify-content-end align-items-center p-0">
                                
                                <!-- PAGINATION -->
                                
                                <div class="COUNT col justify-content-end align-items-center">
                                    <span class="span_2" style="font-size: 1rem;"> Showing 1 out of <?php echo $pages ?> </span>
                                </div>
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
                                            <a href='?page-nr=1&id=$id' class='border d-flex justify-content-center align-items-center' style='background-color: #f3f3f3; border-radius: 6px; width: 2.5rem; font-size: 0.8rem; text-align: center;' title='First'>
                                                <span> First </span>
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
                                                        <a href='?page-nr=$previous_page&id=$id' class='border d-flex justify-content-center align-items-center' style='background-color: #f3f3f3; border-radius: 6px; width: 2.5rem; font-size: 0.8rem; text-align: center;' title='Previous'>
                                                            <span> Prev </span>
                                                        </a>
                                                    ";
                                                }
                                                else
                                                {
                                                    echo "
                                                        <a class='border d-flex justify-content-center align-items-center' style='background-color: #f3f3f3; border-radius: 6px; width: 2.5rem; font-size: 0.8rem; text-align: center;' title='Previous'>
                                                            <span> Prev </span>
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
                                                        <a href='?page-nr=2&id=$id' class='border d-flex justify-content-center align-items-center' style='background-color: #f3f3f3; border-radius: 6px; width: 2.5rem; font-size: 0.8rem; text-align: center;' title='Next'>
                                                            <span> Next </span>
                                                        </a> 
                                                    ";
                                                }
                                                else
                                                {
                                                    if($_GET['page-nr'] >= $pages)
                                                    {
                                                        echo "
                                                            <a class='border d-flex justify-content-center align-items-center' style='background-color: #f3f3f3; border-radius: 6px; width: 2.5rem; font-size: 0.8rem; text-align: center;' title='Next'>
                                                                <span> Next </span>
                                                            </a> 
                                                        ";
                                                    }
                                                    else
                                                    {
                                                        $next_page = intval($_GET['page-nr']) + 1;
                                        
                                                        echo "
                                                            <a href='?page-nr=$next_page&id=$id' class='border d-flex justify-content-center align-items-center' style='background-color: #f3f3f3; border-radius: 6px; width: 2.5rem; font-size: 0.8rem; text-align: center;' title='Next'>
                                                                <span> Next </span>
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
                                                    <a href='?page-nr=$pages&id=$id' class='border d-flex justify-content-center align-items-center' style='background-color: #f3f3f3; border-radius: 6px; width: 2.5rem; font-size: 0.8rem; text-align: center;' title='Last'>
                                                        <span> Last </span>
                                                    </a>
                                                ";
                                            }
                                        ?>
                                    
        
                                </div>

                            </div>
                            
                        </div>
                        <div class="card-body">
                            <form method="POST">
                                <div class="table-responsive">
                                    <table class="table table-bordered" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Profile</th>
                                                <th>Name</th>
                                                <th>Address</th>
                                                <th>Age</th>
                                                <th>Role</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tfoot>
                                            <tr>
                                                <th>ID</th>
                                                <th>Profile</th>
                                                <th>Name</th>
                                                <th>Address</th>
                                                <th>Age</th>
                                                <th>Role</th>
                                                <th>Action</th>
                                            </tr>
                                        </tfoot>
                                        <tbody>
                                            <!-- <tr>
                                                <td class="d-flex justify-content-center"> <img src="torvalds - Overview.jfif" alt="..." width="35rem" height="35rem"> </td>
                                                <td>Airi Satou</td>
                                                <td>lorem</td>
                                                <td>33</td>
                                                <td>Admin</td>
                                                <td>  
                                                    <button type="submit">
                                                        view
                                                    </button>
                                                </td>
                                            </tr> -->

                                            <?php
                                                if($result2->num_rows > 0)
                                                {
                                                    while($row = $result2->fetch_assoc())
                                                    {
                                                        echo 
                                                        " 
                                                        <tr>
                                                                <td>".$row['id']."</td>
                                                                <td class='d-flex justify-content-center'> <img src='".$row['profile_image']."' alt='...' width='35rem' height='35rem'> </td>
                                                                <td>".$row['first_name']." ".$row['last_name']."</td>
                                                                <td>".$row['address']."</td>
                                                                <td>".$row['age']."</td>
                                                                <td>".$row['role']."</td>
                                                                <td>                                  
                                                                        <button type='submit' name='view' value='".$row['id']."' style='border: 1px solid #D33939; background-color: #ffff; color: #0d6efd; border: none; cursor: pointer;'>
                                                                            view
                                                                        </button>

                                                                        <button type='submit' name='edit' value='".$row['id']."' style='border: 1px solid #D33939; background-color: #ffff; color: #0d6efd; border: none; cursor: pointer;'>
                                                                            edit
                                                                        </button>

                                                                        <button type='submit' name='delete' value='".$row['id']."' style='border: 1px solid #D33939; background-color: #ffff; color: #0d6efd; border: none; cursor: pointer;'>
                                                                            delete
                                                                        </button>
                                                                </td>
                                                            </tr> 
                                                        ";
                                                    }
                                                }
                                                else
                                                {
                                                    echo "";
                                                }         
                                            ?>

                                        </tbody>
                                    </table>
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