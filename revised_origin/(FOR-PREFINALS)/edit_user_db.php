<?php
    include_once("fetched_data_db.php");

    class update
    {
        private $current_firstname;
        private $first_name;

        private $current_lastname;
        private $last_name;

        private $current_username;
        private $username;

        private $current_age;
        private $age;

        private $current_address;
        private $address;

        private $conn;

        private $file;

        private $role;

        public function __construct($current_firstname="", $first_name="", $current_lastname="", $last_name="", $current_username="", $username="", $current_age="", $age="", $current_address="", $address="", $conn="", $file="", $role="")
        {
            $this->current_firstname=$this->checkInputData($current_firstname);
            $this->first_name=$this->checkInputData($first_name);

            $this->current_lastname=$this->checkInputData($current_lastname);
            $this->last_name=$this->checkInputData($last_name);

            $this->current_username=$this->checkInputData($current_username);
            $this->username=$this->checkInputData($username);

            $this->current_age=$this->checkInputData($current_age);
            $this->age=$this->checkInputData($age);

            $this->current_address=$this->checkInputData($current_address);
            $this->address=$this->checkInputData($address);

            $this->file=$this->checkInputData($file);

            $this->role=$this->checkInputData($role);

            $this->conn=$conn;

            $this->fetchDataAndUpdate($this->conn);
        }
    
        public function checkInputData($data) 
        {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }

        public function fetchDataAndUpdate($conn)
        {
            if(!empty($this->first_name) && !empty($this->last_name) && !empty($this->username) && !empty($this->age) && !empty($this->address))
            {
                if(!ctype_alpha($this->first_name) || !ctype_alpha($this->last_name))
                {
                    echo "<script> alert('Fields must be a letter.') </script>"; 
                }
                else
                {
                    if(!is_numeric($this->first_name) && !is_numeric($this->last_name) && !is_numeric($this->username))
                    {
                        if(is_numeric($this->age))
                        {
                            if($this->age >= 18)
                            {

                                $sql = "SELECT * FROM users WHERE username='".$this->username."'";
                                $result = mysqli_query($this->conn, $sql);
                                $count_rows = mysqli_num_rows($result);
                                
                                if($this->username === $this->current_username)
                                {
                                    // echo "no changes made";
                                    
                                    $id = mysqli_real_escape_string($this->conn, $_GET['id']);
                                    $userID = mysqli_real_escape_string($this->conn, $_GET['userID']);
                                    if(isset($userID))
                                    {
                                        // echo "userID: ".$userID;

                                        $sql="update users set `profile_image`='".$this->file."',`first_name`='".$this->first_name."',`last_name`='".$this->last_name."',`username`='".$this->username."',`age`='".$this->age."',`address`='".$this->address."', `role`='".$this->role."' WHERE id='$userID'";

                                        if ($conn->query($sql) === TRUE) 
                                        {
                                            header("Location: edit_user_db.php?message=User has been updated&id=".$id."&userID=".$userID);
                                        } 
                                        else 
                                        {
                                            echo "Error updating record: " . $conn->error;
                                        }
                                    }
                                }
                                else if($count_rows === 0)
                                {
                                    // echo "no users with existing username";
                                    $id = mysqli_real_escape_string($this->conn, $_GET['id']);
                                    $userID = mysqli_real_escape_string($this->conn, $_GET['userID']);
                                    if(isset($userID))
                                    {
                                        $sql="update users set `profile_image`='".$this->file."',`first_name`='".$this->first_name."',`last_name`='".$this->last_name."',`username`='".$this->username."',`age`='".$this->age."',`address`='".$this->address."', `role`='".$this->role."' WHERE id='$userID'";

                                        if ($conn->query($sql) === TRUE) 
                                        {
                                            header("Location: edit_user_db.php?message=User has been updated&id=".$id."&userID=".$userID);
                                        } 
                                        else 
                                        {
                                            echo "Error updating record: " . $conn->error;
                                        }
                                    }
                                }
                                else if($count_rows > 0)
                                {
                                    echo "user already exists!";
                                }
                            }
                            else
                            {
                                echo "<script> alert('Age must be 18 or above.') </script>"; 
                            }
                        }
                        else
                        {
                            echo "<script> alert('Age must be a number.') </script>"; 
                        }
                    }
                    else
                    {
                        echo "<script> alert('Fields cannot start as a number.') </script>"; 
                    }
                }
            }
            else
            {
                echo "<script> alert('Please fill up the fields.') </script>";
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
    else if($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['save_changes']))
    {
        $userID = mysqli_real_escape_string($conn, $_GET['userID']);
        $id = mysqli_real_escape_string($conn, $_GET['id']);

        // echo "hello".$id." ".$userID;

        if(isset($userID))
        {
            // echo "hello world";

            $current_firstname = "";
            $current_lastname = "";
            $current_username = "";
            $current_age = 0;
            $current_address = "";

            $sql5 = "SELECT id, profile_image, first_name, last_name, username, age, address FROM users WHERE id='$userID'";
            $result5 = mysqli_query($conn, $sql5);
            
            if($result5->num_rows > 0)
            {
                while($row = $result5->fetch_assoc())
                {
                    // echo "hello world".$row['username'];
                    $current_firstname = $row['first_name'];
                    $first_name = $_POST['first_name'];

                    $current_lastname = $row['last_name'];
                    $last_name = $_POST['last_name'];

                    $current_age = $row['age'];
                    $age = $_POST['age'];

                    $current_address = $row['address'];
                    $address = $_POST['address'];

                    $current_username = $row['username'];
                    $username = $_POST['username'];

                    $current_profile = $row['profile_image'];
                    $file = $_FILES['image'];

                    $role = $_POST['role'];


                    $fileName = $_FILES['image']['name'];
                    $fileTmpName = $_FILES['image']['tmp_name'];
                    $fileSize = $_FILES['image']['size'];
                    $fileError = $_FILES['image']['error'];
                    $fileType = $_FILES['image']['type'];
        
                    $fileExt = explode('.', $fileName);
                    $fileActualExt = strtolower(end($fileExt));

                    if($_FILES['image']['name']=='')
                    {
                        $destination = $current_profile;

                        // echo "default";

                        $update = new update($current_firstname, $first_name, $current_lastname, $last_name, $current_username, $username, $current_age, $age, $current_address, $address, $conn, $destination, $role);
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
                                    // echo "updated";

                                    $update = new update($current_firstname, $first_name, $current_lastname, $last_name, $current_username, $username, $current_age, $age, $current_address, $address, $conn, $destination, $role);
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
            }

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
    <title>Niram - Edit</title>

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
        select{ border-style:none; outline-style:none; }

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
                            
                            <div class="HEADLINE col justify-content-start align-items-center p-0">
                                <span class="m-0 font-weight-bold text-primary"> EDIT </span>
                            </div>
                            <div class="col d-flex justify-content-end align-items-center p-0">
                                
                                <!-- PAGINATION -->
                                
                                <div class="col-auto h-100 d-flex justify-content-center align-items-center" style="gap: 0.3rem;"></div>

                            </div>
                            
                        </div>
                        <div class="card-body">
                        <form method="POST" enctype="multipart/form-data">
                                        
                                <div class="CONTENT justify-content-center align-items-center" style="gap: 1rem;">

                                    <div class="col d-flex flex-column">

                                        <?php
                                                $userID = mysqli_real_escape_string($conn, $_GET['userID']);
                                                if(isset($userID))
                                                {
                                                    $sql5 = "SELECT id, profile_image, first_name, last_name, username, age, address, role FROM users WHERE id='$userID'";
                                                    $result5 = mysqli_query($conn, $sql5);

                                                    if($result5->num_rows > 0)
                                                    {
                                                        while($row = $result5->fetch_assoc())
                                                        {
                                                            // echo "hello ".$row['first_name'];
                                                            if($row['role']==="Admin")
                                                            {
                                                                echo "
                                                                    <div class='d-flex flex-row'>
                                                                            <div class='col'></div>
                                                                            <div class='col-auto p-0'>
                                                                                <button type='submit' name='cancel_editing' class='b_2 d-flex justify-content-center align-items-center w-1' title='Cancel edit'>
                                                                                    <i class='fas fa-times fa-sm fa-fw mr-2 text-gray-400'></i>
                                                                                </button>
                                                                            </div>
                                                                    </div>

                                                                    <div class='d-flex justify-content-center align-items-center' style='height: 10rem;'>
                                                                        <img class='img-profile rounded-circle' src='".$row['profile_image']."' width='100rem' heigth='100rem'>
                                                                    </div>

                                                                    <div class='d-flex flex-column justify-content-start align-items-center p-3' style='gap: 1rem;'>
                                            
                                                                        <div class='col-auto border d-flex flex-row w-100 p-0' style='height: 2.5rem; overflow: hidden;'>
                                                                            <div class='col p-0'>
                                                                                <input type='file' name='image' value='".$row['profile_image']."' class='w-100 h-100' style='border-style:none;'>
                                                                            </div>
                                                                        </div>

                                                                        <div class='col-auto border d-flex flex-row w-100 p-0' style='height: 2.5rem;'>
                                                                            <div class='col-auto d-flex justify-content-center align-items-center' style='padding-left: 1.5rem; width: 2rem;'> <i class='fas fa-pencil-alt fa-sm fa-fw mr-2 text-gray-400'></i> </div>
                                                                            <div class='col'>
                                                                                <input minlength='3' maxlength='16' type='text' name='first_name' value='".$row['first_name']."' class='w-100 h-100' style='border-style:none;'>
                                                                            </div>
                                                                        </div>

                                                                        <div class='col-auto border d-flex flex-row w-100 p-0' style='height: 2.5rem;'>
                                                                            <div class='col-auto d-flex justify-content-center align-items-center' style='padding-left: 1.5rem; width: 2rem;'> <i class='fas fa-pencil-alt fa-sm fa-fw mr-2 text-gray-400'></i> </div>
                                                                            <div class='col'>
                                                                                <input  minlength='3' maxlength='16' type='text' name='last_name' value='".$row['last_name']."' class='w-100 h-100' style='border-style:none;'>
                                                                            </div>
                                                                        </div>

                                                                        <div class='col-auto border d-flex flex-row w-100 p-0' style='height: 2.5rem;'>
                                                                            <div class='col-auto d-flex justify-content-center align-items-center' style='padding-left: 1.5rem; width: 2rem;'> <i class='fas fa-pencil-alt fa-sm fa-fw mr-2 text-gray-400'></i> </div>
                                                                            <div class='col'>
                                                                                <input min='4' maxlength='8' type='text' name='username' value='".$row['username']."' class='w-100 h-100' style='border-style:none;'>
                                                                            </div>
                                                                        </div>

                                                                        <div class='col-auto border d-flex flex-row w-100 p-0' style='height: 2.5rem;'>
                                                                            <div class='col-auto d-flex justify-content-center align-items-center' style='padding-left: 1.5rem; width: 2rem;'> <i class='fas fa-pencil-alt fa-sm fa-fw mr-2 text-gray-400'></i> </div>
                                                                            <div class='col'>
                                                                                <input maxlength='2' type='text' name='age' value='".$row['age']."' class='w-100 h-100' style='border-style:none;'>
                                                                            </div>
                                                                        </div>

                                                                        <div class='col-auto border d-flex flex-row w-100 p-0' style='height: 2.5rem;'>
                                                                            <div class='col-auto d-flex justify-content-center align-items-center' style='padding-left: 1.5rem; width: 2rem;'> <i class='fas fa-pencil-alt fa-sm fa-fw mr-2 text-gray-400'></i> </div>
                                                                            <div class='col'>
                                                                                <input type='text' name='address' value='".$row['address']."' class='w-100 h-100' style='border-style:none;'>
                                                                            </div>
                                                                        </div>
                                                                        
                                                                        <div class='col-auto border d-flex flex-row w-100 p-0' style='height: 2.5rem;'>
                                                                            <div class='col'>
                                                                                <select name='role' class='w-100 h-100'>
                                                                                    <option value='".$row['role']."'> ".$row['role']." </option>
                                                                                    <option value='User'> User </option>
                                                                                </select>
                                                                            </div>
                                                                        </div>

                                                                    </div>

                                                                    <br>

                                                                    <div class='w-100' style='height:3rem;'>
                                                                        <button type='submit' name='save_changes' class='w-100 h-100' style='background-color: #C5D1A6; color: #91A168; border: none; cursor: pointer;'>
                                                                            Save changes
                                                                        </button>
                                                                    </div>

                                                                    <br>
                                                                ";
                                                            }
                                                            else if($row['role']==="User")
                                                            {
                                                                echo "
                                                                    
                                                                    <div class='d-flex flex-row'>
                                                                            <div class='col'></div>
                                                                            <div class='col-auto p-0'>
                                                                                <button type='submit' name='cancel_editing' class='b_2 d-flex justify-content-center align-items-center w-1' title='Cancel edit'>
                                                                                    <i class='fas fa-times fa-sm fa-fw mr-2 text-gray-400'></i>
                                                                                </button>
                                                                            </div>
                                                                    </div>

                                                                    <div class='d-flex justify-content-center align-items-center' style='height: 10rem;'>
                                                                        <img class='img-profile rounded-circle' src='".$row['profile_image']."' width='100rem' heigth='100rem'>
                                                                    </div>

                                                                    <div class='d-flex flex-column justify-content-start align-items-center p-3' style='gap: 1rem;'>
                                            
                                                                        <div class='col-auto border d-flex flex-row w-100 p-0' style='height: 2.5rem; overflow: hidden;'>
                                                                            <div class='col p-0'>
                                                                                <input type='file' name='image' value='".$row['profile_image']."' class='w-100 h-100' style='border-style:none;'>
                                                                            </div>
                                                                        </div>

                                                                        <div class='col-auto border d-flex flex-row w-100 p-0' style='height: 2.5rem;'>
                                                                            <div class='col-auto d-flex justify-content-center align-items-center' style='padding-left: 1.5rem; width: 2rem;'> <i class='fas fa-pencil-alt fa-sm fa-fw mr-2 text-gray-400'></i> </div>
                                                                            <div class='col'>
                                                                                <input minlength='3' maxlength='16' type='text' name='first_name' value='".$row['first_name']."' class='w-100 h-100' style='border-style:none;'>
                                                                            </div>
                                                                        </div>

                                                                        <div class='col-auto border d-flex flex-row w-100 p-0' style='height: 2.5rem;'>
                                                                            <div class='col-auto d-flex justify-content-center align-items-center' style='padding-left: 1.5rem; width: 2rem;'> <i class='fas fa-pencil-alt fa-sm fa-fw mr-2 text-gray-400'></i> </div>
                                                                            <div class='col'>
                                                                                <input  minlength='3' maxlength='16' type='text' name='last_name' value='".$row['last_name']."' class='w-100 h-100' style='border-style:none;'>
                                                                            </div>
                                                                        </div>

                                                                        <div class='col-auto border d-flex flex-row w-100 p-0' style='height: 2.5rem;'>
                                                                            <div class='col-auto d-flex justify-content-center align-items-center' style='padding-left: 1.5rem; width: 2rem;'> <i class='fas fa-pencil-alt fa-sm fa-fw mr-2 text-gray-400'></i> </div>
                                                                            <div class='col'>
                                                                                <input min='4' maxlength='8' type='text' name='username' value='".$row['username']."' class='w-100 h-100' style='border-style:none;'>
                                                                            </div>
                                                                        </div>

                                                                        <div class='col-auto border d-flex flex-row w-100 p-0' style='height: 2.5rem;'>
                                                                            <div class='col-auto d-flex justify-content-center align-items-center' style='padding-left: 1.5rem; width: 2rem;'> <i class='fas fa-pencil-alt fa-sm fa-fw mr-2 text-gray-400'></i> </div>
                                                                            <div class='col'>
                                                                                <input maxlength='2' type='text' name='age' value='".$row['age']."' class='w-100 h-100' style='border-style:none;'>
                                                                            </div>
                                                                        </div>

                                                                        <div class='col-auto border d-flex flex-row w-100 p-0' style='height: 2.5rem;'>
                                                                            <div class='col-auto d-flex justify-content-center align-items-center' style='padding-left: 1.5rem; width: 2rem;'> <i class='fas fa-pencil-alt fa-sm fa-fw mr-2 text-gray-400'></i> </div>
                                                                            <div class='col'>
                                                                                <input type='text' name='address' value='".$row['address']."' class='w-100 h-100' style='border-style:none;'>
                                                                            </div>
                                                                        </div>
                                                                        
                                                                        <div class='col-auto border d-flex flex-row w-100 p-0' style='height: 2.5rem;'>
                                                                            <div class='col'>
                                                                                <select name='role' class='w-100 h-100'>
                                                                                    <option value='".$row['role']."'> ".$row['role']." </option>
                                                                                    <option value='Admin'> Admin </option>
                                                                                </select>
                                                                            </div>
                                                                        </div>

                                                                    </div>

                                                                    <br>

                                                                    <div class='w-100' style='height:3rem;'>
                                                                        <button type='submit' name='save_changes' class='w-100 h-100' style='background-color: #C5D1A6; color: #91A168; border: none; cursor: pointer;'>
                                                                            Save changes
                                                                        </button>
                                                                    </div>

                                                                    <br>
                                                                ";
                                                            }
                                                        }
                                                    }
                                                }
                                                else
                                                {
                                                    echo "<script> alert('Something went wrong!') </script>";
                                                } 
                                            ?>

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