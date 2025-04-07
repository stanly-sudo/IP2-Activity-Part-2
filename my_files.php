<?php
    include_once("page_restriction.php");
    include_once("fetch_user_data_db.php");

    if($_SERVER['REQUEST_METHOD']==="POST" && isset($_POST['add_file']))
    {
        $conn->close();
        header("Location: add_file.php?&id=".$id);
    }
    else if($_SERVER['REQUEST_METHOD']==="POST" && isset($_POST['replace']))
    {
        $replaceID = $_POST['replace'];

        $conn->close();
        header("Location: replacefile.php?&id=".$id."&cloudID=".$replaceID);
    }
    else if($_SERVER['REQUEST_METHOD']==="POST" && isset($_POST['delete']))
    {
        $cloudID = $_POST['delete'];

        if(isset($cloudID))
        {
            $sql = "DELETE FROM cloud WHERE cloudID='$cloudID'";

            if($conn->query($sql) === TRUE) 
            {
              header("Location: my_files.php?message=File sucessfully deleted!&id=".$id);
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
    <title>Cloudy - My files</title>
    
    <!-- <script src="https://code.jquery-3-5-1.slim.min.js"></script>
    <script src="https://jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://jsdelivr.net/npm/@bootstrap@5.3.0/dist/js/umd/bootstrap.min.js"></script> -->

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

            .list-group-item{
                flex-direction: column;
            }

            .navbar-search {
                display: none !important;
            }
        }

        @media (min-width: 300px){ 
            .HEADLINE{ display: flex; }

            .navbar-search {
                display: inline-block !important;
            }
        }

        @media (min-width: 490px){ 
            .list-group-item{
                flex-direction: row;
            }
        }

        @media (min-width: 600px){ 
            .COUNT{ display: flex; }
        }

        /* Slide Toggle Styles */
        .switch {
            position: relative;
            display: inline-block;
            width: 50px;
            height: 24px;
        }
        .switch input { 
            opacity: 0;
            width: 0;
            height: 0;
        }
        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: .4s;
            border-radius: 24px;
        }
        .slider:before {
            position: absolute;
            content: "";
            height: 18px;
            width: 18px;
            left: 3px;
            bottom: 3px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
        }
        input:checked + .slider {
            background-color: #696363;
        }
        input:checked + .slider:before {
            transform: translateX(26px);
        }

        /* Darkwolf-inspired Dark Mode - applies to all elements */
        body.dark-mode {
            background-color: #1e1e1e;
            color: #bbbbbb;
        }
        body.dark-mode *,
        body.dark-mode h1, body.dark-mode h2, body.dark-mode h3,
        body.dark-mode h4, body.dark-mode h5, body.dark-mode h6,
        body.dark-mode p, body.dark-mode span, body.dark-mode a {
            color: inherit;
            background-color: transparent;
        }
        /* Navbar, Sidebar, Cards, and Containers */
        body.dark-mode .navbar,
        body.dark-mode .sidebar,
        body.dark-mode .card,
        body.dark-mode .dropdown-menu,
        body.dark-mode .modal-content,
        body.dark-mode .footer,
        body.dark-mode .table-bordered {
            background-color: #292929 !important;
            color: #bbbbbb;
            border-color: #444444;
        }
        /* Links in dark mode */
        body.dark-mode a {
            color: #9ecdff;
        }
        /* Buttons */
        body.dark-mode .btn, 
        body.dark-mode .btn-primary {
            background-color: #3a3a3a;
            border-color: #444444;
            color: #bbbbbb;
        }
        body.dark-mode .btn:hover, 
        body.dark-mode .btn-primary:hover {
            background-color: #4a4a4a;
            border-color: #555555;
        }
        /* Form elements */
        body.dark-mode .form-control {
            background-color: #333333;
            color: #bbbbbb;
            border-color: #444444;
        }
        body.dark-mode .form-control::placeholder {
            color: #777777;
        }
        /* DataTables elements */
        body.dark-mode .dataTables_wrapper .dataTables_paginate .paginate_button,
        body.dark-mode .dataTables_wrapper .dataTables_filter input {
            background-color: #333333;
            border-color: #444444;
            color: #bbbbbb;
        }
        body.dark-mode .dataTables_wrapper .dataTables_info,
        body.dark-mode .dataTables_wrapper .dataTables_length,
        body.dark-mode .dataTables_wrapper .dataTables_paginate {
            color: #bbbbbb;
        }
        body.dark-mode .container-fluid {
            background-color: #292929;
        }
        body.dark-mode .sidebar {
            border-right: 2px solid #444444;
        }
        body.dark-mode .CANVAS {
            background-color: #292929;
        }
        body.dark-mode #content-wrapper.CANVAS {
            background-color: #292929 !important;
        }
        
        /* Dark mode styling for Navbar with border bottom */
        body.dark-mode .navbar {
            border-bottom: 2px solid #444444;
        }

        /* Override the sticky footer for dark mode */
        body.dark-mode .sticky-footer {
            background-color: #121212 !important; /* A very dark shade */
            color: #bbbbbb;
            border-top: 1px solid #444444;
        }

        /* Table Header Dark Mode */
        body.dark-mode table thead th,
        body.dark-mode table thead td {
            background-color: #292929 !important;
            color: #bbbbbb !important;
            border-bottom: 2px solid #444444 !important;
        }
        body.dark-mode table tfoot th,
        body.dark-mode table tfoot td {
            background-color: #292929 !important;
            color: #bbbbbb !important;
            border-top: 2px solid #444444 !important;
        }
        
        /* User Profile Background Dark Mode */
        body.dark-mode .img-profile {
            background-color: #3a3a3a !important;
        }

        /* Dark mode styling for the HEADLINE element */
        body.dark-mode .HEADLINE span {
            color: #bbbbbb !important;
        }

        /* Base action button styling */
        .action-btn {
            border: 1px solid #0d6efd;
            background-color: #ffff;
            color: #0d6efd;
            cursor: pointer;
            padding: 0.25rem 0.5rem;
            transition: background-color 0.3s ease-in-out;
        }

        /* Hover state for light mode - a light primary shade */
        .action-btn:hover {
            background-color: #cce0ff; /* adjust this tint as needed */
        }

        /* Dark mode overrides */
        body.dark-mode .action-btn {
            background-color: #333333 !important;
            color: #bbbbbb !important;
            border-color: #444444 !important;
        }

        /* Dark mode hover effect - overlay with a semi-transparent primary */
        body.dark-mode .action-btn:hover {
            background-color: rgba(13,110,253,0.3) !important;
        }

        /* Light mode delete button hover (faded red) */
        .btn-danger.action-btn:hover {
            background-color: #f5c6cb !important; /* a soft red tint */
            border-color: #f5c6cb !important;
            color: #842029 !important; /* adjust text color if needed */
        }

        /* Dark mode delete button hover (faded red overlay) */
        body.dark-mode .btn-danger.action-btn:hover {
            background-color: rgba(220,53,69,0.3) !important; /* semi-transparent red */
            border-color: rgba(220,53,69,0.3) !important;
            color: #f5c6cb !important; /* adjust text color for contrast */
        }
        
    </style>

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="bg-primary navbar-nav sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="#">
                <div class="sidebar-brand-icon">
                    <img src="assets/Logo.png" alt="..." width="25rem" height="25rem">
                </div>
                <div class="sidebar-brand-text mx-3"> <span style="font-family: Righteous;"> CLOUDY </span> </div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item">
                <?php
                    $id=mysqli_real_escape_string($conn, $_GET['id']);
                    if(isset($id))
                    {
                        echo"
                            <a class='nav-link' href='user_dashboard.php?&id=$id'>
                                <i class='fas fa-fw fa-tachometer-alt'></i>
                                <span>Dashboard</span>
                            </a>
                        ";
                    }
                ?>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Sidebar Heading -->
            <div class="sidebar-heading">
                My Files
            </div>

            <!-- Nav Item - My Files -->
            <li class="nav-item active">
                <?php
                    $id=mysqli_real_escape_string($conn, $_GET['id']);
                    if(isset($id))
                    {
                        echo"
                            <a class='nav-link' href='my_files.php?&id=$id'>
                                <i class='fas fa-fw fa-folder'></i>
                                <span>My Files</span>
                            </a>
                        ";
                    }
                ?>
            </li>

            <!-- Nav Item - Shared with Me -->
            <li class="nav-item">
                <a class="nav-link" href="#">
                    <i class="fas fa-fw fa-share-alt"></i>
                    <span>Shared with Me</span>
                </a>
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
        <div id="content-wrapper" class="CANVAS d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light topbar mb-4 static-top shadow" style="background-color: #ffff;">
                                    
                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <!-- Topbar Search -->
                    <form method="post"
                        class="SEARCHBAR form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
                        <div class="input-group">
                            <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..."
                                aria-label="Search" aria-describedby="basic-addon2">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="submit" name="search">
                                    <i class="fas fa-search fa-sm"></i>
                                </button>
                            </div>
                            <div class="input-group-append">
                                <button class="btn btn-success" type="submit" name="add_file">
                                    <i class="fas fa-file fa-sm"></i>
                                    <i class="fas fa-plus fa-xs" style="font-size: 0.65rem;"></i>
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">
                        
                        <!-- Slide Toggle for Light/Dark Mode -->
                        <li class="nav-item d-flex align-items-center ml-3">
                            <label class="switch mb-0">
                                <input type="checkbox" id="theme-toggle">
                                <span class="slider round"></span>
                            </label>
                        </li>

                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- Nav Item - User Information -->
                        <form method="POST">
                            <li class="nav-item dropdown no-arrow">
                                <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <span class="mr-2 d-none d-lg-inline text-gray-600 small"> <?php echo $first_name . " " . $last_name ?> </span>
                                    <div class="img-profile rounded-circle bg-primary d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                        <i class="fas fa-user text-white"></i>
                                    </div>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in">
                                    <button type="submit" name="profile" class="b_2 d-flex justify-content-start align-items-center pl-4">
                                        <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i> Profile
                                    </button>
                                    <button type="submit" name="settings" class="b_2 d-flex justify-content-start align-items-center pl-4">
                                        <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i> Settings
                                    </button>
                                    <div class="dropdown-divider"></div>
                                    <button type="submit" name="sign_out" class="b_2 d-flex justify-content-start align-items-center pl-4">
                                        <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i> Logout
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
                                    <span class="m-0 font-weight-bold text-primary"> FILES </span>
                               </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <form method="POST">
                                <div class="table-responsive">
                                    <table class="table table-bordered" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>File</th>
                                                <th>Name</th>
                                                <th>Type</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                                if($result3->num_rows > 0)
                                                {
                                                    while($row = $result3->fetch_assoc())
                                                    {
                                                        $storedFile = $row['file'];
                                                        $trimmedFile = preg_replace('/^cloud\//', '', $storedFile);

                                                        echo 
                                                        " 
                                                        <tr>
                                                                <td class='text-center'>
                                                                    <svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' fill='currentColor' class='file-icon' viewBox='0 0 24 24' style='height: 2rem;'>
                                                                        <path d='M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z'></path>
                                                                        <polyline points='14 2 14 8 20 8'></polyline>
                                                                        <line x1='8' y1='12' x2='16' y2='12' stroke='white' stroke-width='1'/>
                                                                        <line x1='8' y1='15' x2='16' y2='15' stroke='white' stroke-width='1'/>
                                                                        <line x1='8' y1='18' x2='16' y2='18' stroke='white' stroke-width='1'/>
                                                                    </svg>
                                                                </td>
                                                                <td>".$row['file_name']."</td>
                                                                <td>".$row['file_ext']."</td>
                                                                <td>
                                                                    <a href='cloud/{$trimmedFile}' download>Download</a>

                                                                    <button type='submit' name='replace' value='".$row['cloudID']."' class='action-btn'>
                                                                        <i class='fas fa-sync-alt'></i> Replace
                                                                    </button>

                                                                    <button type='submit' name='delete' value='".$row['cloudID']."' class='btn btn-danger action-btn'>
                                                                        <i class='fas fa-trash'></i> Delete
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
                        <span>Copyright &copy; CLOUDY 2025</span>
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

    <script>
      $(document).ready(function() {
          $('#theme-toggle').on('change', function() {
              $('body').toggleClass('dark-mode', this.checked);
          });
      });
    </script>
    

</body>

</html>