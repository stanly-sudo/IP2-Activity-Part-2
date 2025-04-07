<?php
    include_once("fetched_data_db.php");

    class update
    {
        private $first_name;
        private $last_name; 
        private $username; 
        private $age; 
        private $address; 
        private $conn;
        private $file;
        private $current_username;

        public function __construct($first_name = "", $last_name = "", $username = "", $age = "", $address = "", $conn="", $file="", $current_username="")
        {
            $this->first_name=$this->checkInputData($first_name);
            $this->last_name=$this->checkInputData($last_name);
            $this->username=$this->checkInputData($username);
            $this->age=$this->checkInputData($age);
            $this->address=$this->checkInputData($address);
            $this->file=$this->checkInputData($file);
            $this->current_username=$this->checkInputData($current_username);
            $this->conn=$conn;

            $this->validateDataAndUpdateUser($this->conn);
        }
    
        public function checkInputData($data) 
        {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }

        public function validateDataAndUpdateUser($conn)
        {
            if(!empty($this->first_name) && !empty($this->last_name))
            {
                if(is_numeric($this->first_name) && is_numeric($this->last_name))
                {
                    echo "<script> alert('Fields cannot start as a number.') </script>";
                }
                else
                {
                    if(!ctype_alpha($this->first_name) || !ctype_alpha($this->last_name))
                    {
                        echo "<script> alert('Fields must be a letter.') </script>"; 
                    }   
                    else
                    {
                        if(!empty($this->username))
                        {
                            if(is_numeric($this->username))
                            {
                                echo "<script> alert('Username cannot start as a number.') </script>"; 
                            }
                            else
                            {
                                
                                $id = mysqli_real_escape_string($this->conn, $_GET['id']);

                                $sql = "select * from users where username='".$this->username."'";
                                $result = mysqli_query($conn, $sql);
                                $count_user = mysqli_num_rows($result);
                                
                                // echo "USERNAME: " . $this->username;
                                // echo "CURRENT: " . $this->current_username;

                                if($this->username === $this->current_username)
                                {
                                    // echo "username has not changed";
                                    if(!empty($this->age))
                                    {
                                        if(is_numeric($this->age))
                                        {
                                            if($this->age >= 18)
                                            {
                                                if(!empty($this->address))
                                                {
                                                    $sql="update users set `profile_image`='".$this->file."',`first_name`='".$this->first_name."',`last_name`='".$this->last_name."',`username`='".$this->username."',`age`='".$this->age."',`address`='".$this->address."' WHERE id='$id'";

                                                    if ($conn->query($sql) === TRUE) 
                                                    {
                                                        $this->conn->close();
                                                        header("Location: user_profile.php?message=Update Successful&id=".$id);
                                                    } 
                                                    else 
                                                    {
                                                        echo "Error updating record: " . $conn->error;
                                                    }
                                                }
                                                else
                                                {
                                                    echo "<script> alert('Please fill in your address.') </script>";
                                                }
                                            }   
                                            else
                                            {
                                                echo "<script> alert('Age must be 18 or above') </script>";
                                            }
                                        }
                                        else
                                        {
                                            echo "<script> alert('Age must be a number') </script>";
                                        }
                                    }
                                    else
                                    {
                                        echo "<script> alert('Please fill up your age') </script>";
                                    }
                                }
                                else if($count_user === 0)
                                {
                                    // echo "username does not exist";
                                    if(!empty($this->age))
                                    {
                                        if(is_numeric($this->age))
                                        {
                                            if($this->age >= 18)
                                            {
                                                if(!empty($this->address))
                                                {
                                                    $sql="update users set `profile_image`='".$this->file."',`first_name`='".$this->first_name."',`last_name`='".$this->last_name."',`username`='".$this->username."',`age`='".$this->age."',`address`='".$this->address."' WHERE id='$id'";

                                                    if ($conn->query($sql) === TRUE) 
                                                    {
                                                        $this->conn->close();
                                                        header("Location: user_profile.php?message=Update Successful&id=".$id);
                                                    } 
                                                    else 
                                                    {
                                                        echo "Error updating record: " . $conn->error;
                                                    }
                                                }
                                                else
                                                {
                                                    echo "<script> alert('Please fill in your address.') </script>";
                                                }
                                            }   
                                            else
                                            {
                                                echo "<script> alert('Age must be 18 or above') </script>";
                                            }
                                        }
                                        else
                                        {
                                            echo "<script> alert('Age must be a number') </script>";
                                        }
                                    }
                                    else
                                    {
                                        echo "<script> alert('Please fill up your age') </script>";
                                    }
                                }
                                else if($this->username !== $this->current_username && $count_user > 0)
                                {
                                    // echo "username does not exist";
                                    if(!empty($this->age))
                                    {
                                        if(is_numeric($this->age))
                                        {
                                            if($this->age >= 18)
                                            {
                                                if(!empty($this->address))
                                                {
                                                    header("Location: edit_user_profile.php?message=Username already exists!&id=".$id);
                                                }
                                                else
                                                {
                                                    echo "<script> alert('Please fill in your address.') </script>";
                                                }
                                            }   
                                            else
                                            {
                                                echo "<script> alert('Age must be 18 or above') </script>";
                                            }
                                        }
                                        else
                                        {
                                            echo "<script> alert('Age must be a number') </script>";
                                        }
                                    }
                                    else
                                    {
                                        echo "<script> alert('Please fill up your age') </script>";
                                    }
                                }
                            }
                        }
                        else
                        {
                            echo "<script> alert('Please fill a username.') </script>"; 
                        }
                    }    
                }
            }
            else
            {
                echo "<script> alert('Please fill in the fields.') </script>";
            }
        }
    }

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
        header("Location: user_profile.php?&id=".$id);
    }
    else if($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['settings']))
    {
        $conn->close();
        header("Location: user_settings.php?&id=".$id);}
    else if($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['orders']))
    {
        $conn->close();
        header("Location: 404.php?&id=".$id);
    }
    else if($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['home']))
    {
        $conn->close();
        header("Location: home.php?&id=".$id);
    }
    else if($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['cancel_editing']))
    {
        $conn->close();
        header("Location: user_profile.php?&id=".$id);
    }
    else if($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['save_changes']))
    {
        $id = $_GET['id'];

        $sql = "SELECT id, profile_image, first_name, last_name, username, age, address FROM users WHERE id='$id'";
        $result = mysqli_query($conn, $sql);
        $count_user = mysqli_num_rows($result);
        $row = mysqli_fetch_assoc($result);

        $profile_image="";
        $first_name="";
        $last_name="";
        $age=0;
        $address="";
        $username="";
        $role="";
        
        if($row)
        {
            $first_name= $_POST['first_name'];
            $last_name= $_POST['last_name'];
            $age=$_POST['age'];
            $address=$_POST['address'];
            $username=$_POST['username'];

            $current_profile = $row['profile_image'];
            $current_username =  $row['username'];

            $file = $_FILES['image'];
            $fileName = $_FILES['image']['name'];
            $fileTmpName = $_FILES['image']['tmp_name'];
            $fileSize = $_FILES['image']['size'];
            $fileError = $_FILES['image']['error'];
            $fileType = $_FILES['image']['type'];

            $fileExt = explode('.', $fileName);
            $fileActualExt = strtolower(end($fileExt));

            if($_FILES['image']['name']=='')
            {
                $uploadDir = "uploads/";
                $destination = $uploadDir . basename($current_profile);

                $update = new update($first_name, $last_name, $username, $age, $address, $conn, $destination, $current_username);
            }
            else
            {
                $allowed = array('jpg', 'jpeg', 'jfif', 'png');
                if(in_array($fileActualExt, $allowed))
                {
                    if($fileError===0 && $fileSize < 1000000)
                    {
                        $uploadDir = "uploads/";
                        $destination = $uploadDir . uniqid() . '-' . basename($fileName);
                            
                        if(move_uploaded_file($fileTmpName, $destination))
                        {
                            $update = new update($first_name, $last_name, $username, $age, $address, $conn, $destination, $current_username);
                        }
                        else
                        {
                            echo"<script> alert('Your file is too big!') </script>";
                        }
                    }
                    else
                    {
                        echo"<script> alert('There was an error uploading your file!') </script>";
                    }
                }
                else
                {
                    echo"<script> alert('Not a type of image format!') </script>";
                }
            }
        }
        else
        {
            echo"<script> alert('Something went wrong.') </script>";
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
    <title>Niram - Profile</title>

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

        .card{ border: 1px solid #c3c3c3; background-color: #ffff; width: 15rem; height: 20rem; }
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

        input::-webkit-file-upload-button{ border-style: none; background-color: #B35C4E; width: 8rem; height: 100%; font-size: 0.8rem; color: #ffff; cursor: pointer; }

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
                                    <!-- HOME -->
                                    <button type="submit" name="home" class="b_2 d-flex justify-content-start align-items-center pl-4">
                                        <i class="fas fa-home fa-sm fa-fw mr-2 text-gray-400"></i>
                                        Home
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
                        <form method="POST" enctype="multipart/form-data">
                                        
                            <div class="CONTENT justify-content-center align-items-center" style="gap: 1rem;">
                                        
                                <div class="border bg-white p-1" style="width: 50rem;">

                                    <div class="d-flex flex-row">
                                            <div class="col"></div>
                                            <div class="col-auto p-0">
                                                <button type="submit" name="cancel_editing" class="b_2 d-flex justify-content-center align-items-center w-1" title="Cancel edit">
                                                    <i class="fas fa-times fa-sm fa-fw mr-2 text-gray-400"></i>
                                                </button>
                                            </div>
                                    </div>

                                    <div class="d-flex justify-content-center align-items-center" style="height: 10rem;">
                                        <img class="img-profile rounded-circle" src="<?php echo $profile_image; ?>" width="100rem" heigth="100rem">
                                    </div>

                                    <br>

                                    <div class="d-flex flex-column justify-content-start align-items-center p-3" style="gap: 1rem;">

                                        <div class="col-auto border d-flex flex-row w-100 p-0" style="height: 2.5rem; overflow: hidden;">
                                            <div class="col p-0">
                                                <input type="file" name="image" value="<?php echo $profile_image?>" class="w-100 h-100" style="border-style:none;">
                                            </div>
                                        </div>

                                        <div class="col-auto border d-flex flex-row w-100 p-0" style="height: 2.5rem;">
                                            <div class="col-auto d-flex justify-content-center align-items-center" style="padding-left: 1.5rem; width: 2rem;"> <i class="fas fa-pencil-alt fa-sm fa-fw mr-2 text-gray-400"></i> </div>
                                            <div class="col">
                                                <input minlength="3" maxlength="16" type="text" name="first_name" value="<?php echo $first_name; ?>" class="w-100 h-100" style="border-style:none;">
                                            </div>
                                        </div>

                                        <div class="col-auto border d-flex flex-row w-100 p-0" style="height: 2.5rem;">
                                            <div class="col-auto d-flex justify-content-center align-items-center" style="padding-left: 1.5rem; width: 2rem;"> <i class="fas fa-pencil-alt fa-sm fa-fw mr-2 text-gray-400"></i> </div>
                                            <div class="col">
                                                <input  minlength="3" maxlength="16" type="text" name="last_name" value="<?php echo $last_name; ?>" class="w-100 h-100" style="border-style:none;">
                                            </div>
                                        </div>

                                        <div class="col-auto border d-flex flex-row w-100 p-0" style="height: 2.5rem;">
                                            <div class="col-auto d-flex justify-content-center align-items-center" style="padding-left: 1.5rem; width: 2rem;"> <i class="fas fa-pencil-alt fa-sm fa-fw mr-2 text-gray-400"></i> </div>
                                            <div class="col">
                                                <input min="4" maxlength="8" type="text" name="username" value="<?php echo $username; ?>" class="w-100 h-100" style="border-style:none;">
                                            </div>
                                        </div>

                                        <div class="col-auto border d-flex flex-row w-100 p-0" style="height: 2.5rem;">
                                            <div class="col-auto d-flex justify-content-center align-items-center" style="padding-left: 1.5rem; width: 2rem;"> <i class="fas fa-pencil-alt fa-sm fa-fw mr-2 text-gray-400"></i> </div>
                                            <div class="col">
                                                <input maxlength="2" type="text" name="age" value="<?php echo $age; ?>" class="w-100 h-100" style="border-style:none;">
                                            </div>
                                        </div>

                                        <div class="col-auto border d-flex flex-row w-100 p-0" style="height: 2.5rem;">
                                            <div class="col-auto d-flex justify-content-center align-items-center" style="padding-left: 1.5rem; width: 2rem;"> <i class="fas fa-pencil-alt fa-sm fa-fw mr-2 text-gray-400"></i> </div>
                                            <div class="col">
                                                <input type="text" name="address" value="<?php echo $address; ?>" class="w-100 h-100" style="border-style:none;">
                                            </div>
                                        </div>

                                        <!-- button: save changes -->
                                        <div class="d-flex flex-row justify-content-center align-items-center p-0 w-100" style="border: 1px solid #91A168; background-color: #91A168; height: 2.5rem;">
                                            <!-- <input type="submit" name="save_changes" value="SAVE CHANGES" class="w-100 h-100" style="font-size: 1rem; background-color:transparent; color: #ffff;"> -->
                                            <button type="submit" name="save_changes" class="w-100 h-100" style="border-style:none; background:transparent;">
                                                <span style="color:#ffff;"> SAVE CHANGES </span>
                                            </button>
                                        </div>

                                    </div>

                                    <br>

                                </div>

                            </div>
            
                        </form>
                      </div>
            
                      <br>
                      
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
    <script src="vendor/chart.js/Chart.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="js/demo/chart-area-demo.js"></script>
    <script src="js/demo/chart-pie-demo.js"></script>

</body>

</html>