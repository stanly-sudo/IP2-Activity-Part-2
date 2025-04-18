<?php
    include_once("page_restriction.php");
    include_once("fetch_user_data_db.php");

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

        private $conn;

        private $role;

        public function __construct($current_firstname="", $first_name="", $current_lastname="", $last_name="", $current_username="", $username="", $current_age="", $age="", $conn="", $role="")
        {
            $this->current_firstname=$this->checkInputData($current_firstname);
            $this->first_name=$this->checkInputData($first_name);

            $this->current_lastname=$this->checkInputData($current_lastname);
            $this->last_name=$this->checkInputData($last_name);

            $this->current_username=$this->checkInputData($current_username);
            $this->username=$this->checkInputData($username);

            $this->current_age=$this->checkInputData($current_age);
            $this->age=$this->checkInputData($age);

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
            if(!empty($this->first_name) && !empty($this->last_name) && !empty($this->username) && !empty($this->age))
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

                                        $sql="update users set `first_name`='".$this->first_name."',`last_name`='".$this->last_name."',`username`='".$this->username."',`age`='".$this->age."', `role`='".$this->role."' WHERE id='$userID'";

                                        if ($conn->query($sql) === TRUE) 
                                        {
                                            header("Location: view_user_db.php?message=User has been updated&id=".$id."&userID=".$userID);
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
                                        $sql="update users set `first_name`='".$this->first_name."',`last_name`='".$this->last_name."',`username`='".$this->username."',`age`='".$this->age."', `role`='".$this->role."' WHERE id='$userID'";

                                        if ($conn->query($sql) === TRUE) 
                                        {
                                            header("Location: view_user_db.php?message=User has been updated&id=".$id."&userID=".$userID);
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

    if($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['profile']))
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
            header("Location: admin_dashboard.php?&id=".$id);
        }
    }
    
    else if($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['sign_out']))
    {
        setcookie("username", "", time()+(86400 * 2), "/");
        setcookie("user", "Guest", time()+(86400 * 2), "/");

        session_unset();
        session_destroy();

        $conn->close();
        header("Location: login.php");
    }
    else if($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['edit']))
    {
        $id = mysqli_real_escape_string($conn, $_GET['id']);

        $userID = $_POST['delete'];
        if(isset($userID))
        {
            $sql = "DELETE FROM users WHERE id='$userID'";
    
            if($conn->query($sql) === TRUE) 
            {
              header("Location: userstable.php?message=User sucessfully deleted!&id=".$id);
              exit();
            } 
            else 
            {
              echo "Error deleting record: " . $conn->error;
            }  
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

            $sql5 = "SELECT id, first_name, last_name, username, age FROM users WHERE id='$userID'";
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

                    $current_username = $row['username'];
                    $username = $_POST['username'];

                    $role = $_POST['role'];

                    $update = new update($current_firstname, $first_name, $current_lastname, $last_name, $current_username, $username, $current_age, $age, $conn, $role);
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
    <title>Niram - Add user</title>

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

        .b_1{ border: 1px solid #c3c3c3; background-color: #c3c3c3; width: 100%; height: 1.2rem; }
        .b_1:hover{ transition: 0.2s ease-in-out; border: 1px solid #f3f3f3; background-color: #f3f3f3; }

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

        /* Dark mode overrides for the CARD area */
        body.dark-mode .CARD {
            background-color: #2c2c2c !important;
            border-color: #444444 !important;
        }
        body.dark-mode .CARD input,
        body.dark-mode .CARD select,
        body.dark-mode .CARD button,
        body.dark-mode .CARD span {
            color: #bbbbbb !important;
        }

        /* Dark mode override for lock buttons */
        body.dark-mode .CARD .INPUT > button {
            background-color: #4a4a4a !important;
        }

        /* Dark mode override for the create user button */
        body.dark-mode .CARD button[type="submit"] {
            background-color: #3a3a3a !important;
            border-color: #555555 !important;
        }
        body.dark-mode .CARD button[type="submit"] span {
            color: #bbbbbb !important;
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

        /* Dark Mode styling for Card Texts, Headers, and Progress Bar */
        body.dark-mode .card {
            background-color: #292929 !important;
            color: #bbbbbb;
            border-color: #444444;
        }
        body.dark-mode .card-header {
            background-color: #292929 !important;
            color: #bbbbbb;
            border-bottom: 2px solid #444444 !important;
        }
        body.dark-mode .card-body {
            background-color: #292929 !important;
            color: #bbbbbb;
        }
        body.dark-mode .progress-bar {
            background-color: #4a4a4a !important;  /* Adjust as needed */
            color: #bbbbbb;
            border: 1px solid #444444;
        }

        /* Override header text color for dark mode */
        body.dark-mode .card-header h6 {
            color: #bbbbbb !important;
        }

        /* 
           Light mode hover effects 
           (applies when the body doesn't have the dark-mode class)
        */
        .CARD button[type="submit"]:hover {
            background-color: #0b5ed7;  /* Adjust to preferred hover color */
            border-color: #0a58ca;
        }
        .CARD .INPUT > button:hover {
            background-color: #0b5ed7;  /* Adjust to preferred hover color */
        }

        /* 
           Dark mode hover effects 
           (applies when the body has the dark-mode class)
        */
        body.dark-mode .CARD button[type="submit"]:hover {
            background-color: #2a2a2a !important;  /* Slightly darker shade */
            border-color: #666666 !important;
        }
        body.dark-mode .CARD .INPUT > button:hover {
            background-color: #333333 !important;  /* Subtle hover adjustment */
        }
        
    </style>

</head>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <link rel="icon" type="image/x-icon" href="assets/Logo.png">
    <title>Cloudy - Add user</title>

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

        .b_1{ border: 1px solid #c3c3c3; background-color: #c3c3c3; width: 100%; height: 1.2rem; }
        .b_1:hover{ transition: 0.2s ease-in-out; border: 1px solid #f3f3f3; background-color: #f3f3f3; }

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
        }

        @media (min-width: 300px){ 
            .HEADLINE{ display: flex; }
            .SEARCHBAR{ display: none; }

 
        }

        @media (min-width: 490px){ 
            .list-group-item{
                flex-direction: row;
            }

            .INPUT_CONTAINER{
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
            }
        }

        @media (min-width: 590px){ 
            .INPUT_CONTAINER{
                display: flex;
                flex-direction: row;
                justify-content: center;
                align-items: center;
            }
        }

        @media (min-width: 600px){ 
            .COUNT{ display: flex; }
        }

        
        .INPUT{ background-color: #ffff; height: 2.5rem; }

        .INPUT > input{ padding-left: 1rem; border-style: none; outline-style: none; background: transparent; width: 100%; height: 100%; }
        .INPUT > button{ border-style: none; background-color: #0D6EFD; width: 3rem; }

        form > .BUTTON{ border: 1px solid #0D6EFD; background-color: #0D6EFD; height: 2.5rem; }
        .BUTTON > input{ border-style: none; background: transparent; height: 100%; width: 100%; color: #ffff; font-style: Poppins-Regular; font-weight: 500; font-size: 1rem; }

        .INPUT > input::-webkit-file-upload-button{ width: 8rem; height: 100%; }

        input::-webkit-file-upload-button{ border-style: none; background-color: #0D6EFD; width: 8rem; height: 100%; font-family: Poppins-Regular; font-size: 0.8rem; color: #ffff; cursor: pointer; }


     
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

        /* Dark Mode styling for Card Texts, Headers, and Progress Bar */
        body.dark-mode .card {
            background-color: #292929 !important;
            color: #bbbbbb;
            border-color: #444444;
        }
        body.dark-mode .card-header {
            background-color: #292929 !important;
            color: #bbbbbb;
            border-bottom: 2px solid #444444 !important;
        }
        body.dark-mode .card-body {
            background-color: #292929 !important;
            color: #bbbbbb;
        }
        body.dark-mode .progress-bar {
            background-color: #4a4a4a !important;  /* Adjust as needed */
            color: #bbbbbb;
            border: 1px solid #444444;
        }

        /* Override header text color for dark mode */
        body.dark-mode .card-header h6 {
            color: #bbbbbb !important;
        }

        /* 
           Light mode hover effects 
           (applies when the body doesn't have the dark-mode class)
        */
        .CARD button[type="submit"]:hover {
            background-color: #0b5ed7;  /* Adjust to preferred hover color */
            border-color: #0a58ca;
        }
        .CARD .INPUT > button:hover {
            background-color: #0b5ed7;  /* Adjust to preferred hover color */
        }

        /* 
           Dark mode hover effects 
           (applies when the body has the dark-mode class)
        */
        body.dark-mode .CARD button[type="submit"]:hover {
            background-color: #2a2a2a !important;  /* Slightly darker shade */
            border-color: #666666 !important;
        }
        body.dark-mode .CARD .INPUT > button:hover {
            background-color: #333333 !important;  /* Subtle hover adjustment */
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
                            <a class='nav-link' href='admin_dashboard.php?&id=$id'>
                                <i class='fas fa-fw fa-tachometer-alt'></i>
                                <span>Dashboard</span>
                            </a>
                        ";
                    }
                ?>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

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
                        <h6 class="collapse-header">ENCRYPT & DECRYPT:</h6>
                        <?php $id=mysqli_real_escape_string($conn, $_GET['id']); if(isset($id)){ echo"<a class='collapse-item' href='utilities_file_checker.php?&id=$id'>Check file</a>"; } ?>
                    </div>
                </div>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Sidebar Heading -->
            <div class="sidebar-heading">
                Tables
            </div>

            <!-- Nav Item - My Files -->
            <li class="nav-item active">
                <!-- 
                <a class="nav-link" href="userstable.php">
                    <i class="fas fa-fw fa-table"></i>
                    <span>Users</span>
                </a> 
                -->
                <?php
                    $id=mysqli_real_escape_string($conn, $_GET['id']);
                    if(isset($id))
                    {
                        echo"
                            <a class='nav-link' href='userstable.php?&id=$id'>
                                <i class='fas fa-fw fa-table'></i>
                                <span>Users</span>
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
        <div id="content-wrapper" class="CANVAS d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light topbar mb-4 static-top shadow" style="background-color: #ffff;">
                    
                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

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

                    <div class="row">

                        <div class="CARD col p-3 border bg-white">
                            <form method="POST" class="p-1">
                                
                                <?php
                                
                                    $userID = mysqli_real_escape_string($conn, $_GET['userID']);
                                    if(isset($userID))
                                    {
                                        $sql = "SELECT id, first_name, last_name, username, email, age, role FROM users WHERE id='$userID'";
                                        $result = mysqli_query($conn, $sql);

                                        if(mysqli_num_rows($result))
                                        {
                                           while($row = $result->fetch_assoc())
                                           {
                                                if($row['role']==="User")
                                                {
                                                    echo "
                                                        <div class='col d-flex p-0 justify-content-center align-items-center' style='height: 8rem;'>
                                                        <div class='img-profile rounded-circle bg-primary d-flex align-items-center justify-content-center' style='width: 5rem; height: 5rem;'>
                                                            <i class='fas fa-user text-white'></i>
                                                        </div>
                                                        </div>
                        
                                                        <div class='INPUT_CONTAINER col flex-wrap p-0'>
                                                            <div class='col border'style='height: 3rem;'>
                                                                <input minlength='3' maxlength='16' type='text' name='first_name' value='".$row['first_name']."' placeholder='First name' style='border-style: none; outline-style: none; background: transparent; width: 100%; height: 100%; padding-left: 1rem;'>
                                                            </div>
                        
                                                            <div class='col-auto p-2'></div>
                        
                                                            <div class='col border' style='height: 3rem;'>
                                                                <input minlength='3' maxlength='16' type='text' name='last_name' value='".$row['last_name']."' placeholder='Last name' style='border-style: none; outline-style: none; background: transparent; width: 100%; height: 100%; padding-left: 1rem;'>
                                                            </div>
                                                        </div>
                        
                                                        <br>
                        
                                                        <div class='INPUT_CONTAINER col flex-wrap p-0'>
                                                            <div class='col border' style='height: 3rem;'>
                                                                <input min='4' maxlength='8' type='text' name='username' value='".$row['username']."' placeholder='Username' style='border-style: none; outline-style: none; background: transparent; width: 100%; height: 100%; padding-left: 1rem;'>
                                                            </div>
                        
                                                            <div class='col-auto p-2'></div>
                        
                                                            <div class='col border' style='height: 3rem;'>
                                                                <input maxlength='2' type='text' name='age' placeholder='Age' value='".$row['age']."' style='border-style: none; outline-style: none; background: transparent; width: 100%; height: 100%; padding-left: 1rem;'>
                                                            </div>
                                                        </div>
                        
                                                        <br>
                        
                                                        <div class='INPUT_CONTAINER col flex-wrap p-0'>
                                                            <div class='col border' style='height: 3rem;'>
                                                                <select name='role' class='w-100 h-100' style='border-style: none;'>
                                                                    <option value='".$row['role']."'>".$row['role']."</option>
                                                                    <option value='User'>User</option>
                                                                </select>
                                                            </div>
                                                        </div>
                        
                                                        <br>
                        
                                                        <div class='col p-0 border-primary' style='height: 3rem;'>
                                                            <button type='submit' name='edit' value='".$row['id']."' class='w-100 h-100 bg-primary' style='border-style: none;'>
                                                                <span class='text-white' style='font-family: Poppins-regular; font-size: 1rem;'> UPDATE </span>
                                                            </button>
                                                        </div>
                        
                                                        <br>

                                                        <div class='col p-0 border-primary' style='height: 3rem;'>
                                                            <button type='submit' name='cancel_editing' value='".$row['id']."' class='w-100 h-100 bg-warning' style='border-style: none;'>
                                                                <span class='text-white' style='font-family: Poppins-regular; font-size: 1rem;'> CANCEL EDITING </span>
                                                            </button>
                                                        </div>

                                                        <br>
                                                    
                                                        ";
                                                   }
                                                   else
                                                   {
                                                    echo "
                                                        <div class='col d-flex p-0 justify-content-center align-items-center' style='height: 8rem;'>
                                                        <div class='img-profile rounded-circle bg-primary d-flex align-items-center justify-content-center' style='width: 5rem; height: 5rem;'>
                                                            <i class='fas fa-user text-white'></i>
                                                        </div>
                                                        </div>
                        
                                                        <div class='INPUT_CONTAINER col flex-wrap p-0'>
                                                            <div class='col border'style='height: 3rem;'>
                                                                <input minlength='3' maxlength='16' type='text' name='first_name' value='".$row['first_name']."' placeholder='First name' style='border-style: none; outline-style: none; background: transparent; width: 100%; height: 100%; padding-left: 1rem;'>
                                                            </div>
                        
                                                            <div class='col-auto p-2'></div>
                        
                                                            <div class='col border' style='height: 3rem;'>
                                                                <input minlength='3' maxlength='16' type='text' name='last_name' value='".$row['last_name']."' placeholder='Last name' style='border-style: none; outline-style: none; background: transparent; width: 100%; height: 100%; padding-left: 1rem;'>
                                                            </div>
                                                        </div>
                        
                                                        <br>
                        
                                                        <div class='INPUT_CONTAINER col flex-wrap p-0'>
                                                            <div class='col border' style='height: 3rem;'>
                                                                <input min='4' maxlength='8' type='text' name='username' value='".$row['username']."' placeholder='Username' style='border-style: none; outline-style: none; background: transparent; width: 100%; height: 100%; padding-left: 1rem;'>
                                                            </div>
                        
                                                            <div class='col-auto p-2'></div>
                        
                                                            <div class='col border' style='height: 3rem;'>
                                                                <input maxlength='2' type='text' name='age' placeholder='Age' value='".$row['age']."' style='border-style: none; outline-style: none; background: transparent; width: 100%; height: 100%; padding-left: 1rem;'>
                                                            </div>
                                                        </div>
                        
                                                        <br>
                        
                                                        <div class='INPUT_CONTAINER col flex-wrap p-0'>
                                                            <div class='col border' style='height: 3rem;'>
                                                                <select name='role' class='w-100 h-100' style='border-style: none;'>
                                                                    <option value='".$row['role']."'>".$row['role']."</option>
                                                                    <option value='User'>User</option>
                                                                </select>
                                                            </div>
                                                        </div>
                        
                                                        <br>
                        
                                                        <div class='col p-0 border-primary' style='height: 3rem;'>
                                                            <button type='submit' name='save_changes' value='".$row['id']."' class='w-100 h-100 bg-primary' style='border-style: none;'>
                                                                <span class='text-white' style='font-family: Poppins-regular; font-size: 1rem;'> SAVE CHANGES </span>
                                                            </button>
                                                        </div>
                        
                                                        <br>

                                                        <div class='col p-0 border-primary' style='height: 3rem;'>
                                                            <button type='submit' name='cancel_editing' value='".$row['id']."' class='w-100 h-100 bg-warning' style='border-style: none;'>
                                                                <span class='text-white' style='font-family: Poppins-regular; font-size: 1rem;'> CANCEL EDITING </span>
                                                            </button>
                                                        </div>

                                                        <br>
                                                    
                                                        ";
                                                   }
                                           } 
                                        }
                                    }
                                ?>
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

      function toggleCreatedPasswordVisibility()
        {
            let Password = document.getElementById("PASSWORD");
            let lock = document.getElementById("LOCK");

            let default_Lock = "assets/lock.png";
            let toggled_Lock = "assets/unlock.png";

            if(Password.type==='password')
            {
                lock.src=toggled_Lock;
                Password.type='text';
            }
            else if(Password.type==='text')
            {
                lock.src=default_Lock;
                Password.type='password';
            }
        }

        function toggleConfirmedPasswordVisibility()
        {
            let Password = document.getElementById("CPASSWORD");
            let lock = document.getElementById("LOCK_2");

            let default_Lock = "assets/lock.png";
            let toggled_Lock = "assets/unlock.png";

            if(Password.type==='password')
            {
                lock.src=toggled_Lock;
                Password.type='text';
            }
            else if(Password.type==='text')
            {
                lock.src=default_Lock;
                Password.type='password';
            }
        }

    </script>

</body>

</html>