<?php
    class signup
    {
        private $first_name;
        private $last_name; 
        private $username; 
        private $age; 
        private $address; 
        private $password; 
        private $cpassword;
        private $conn;
        private $file;

        public function __construct($first_name = "", $last_name = "", $username = "", $age = "", $address = "", $password = "", $cpassword = "", $conn="", $file="")
        {
            $this->first_name=$this->checkInputData($first_name);
            $this->last_name=$this->checkInputData($last_name);
            $this->username=$this->checkInputData($username);
            $this->age=$this->checkInputData($age);
            $this->address=$this->checkInputData($address);
            $this->password=$this->checkInputData($password);
            $this->cpassword=$this->checkInputData($cpassword);
            $this->file=$this->checkInputData($file);
            $this->conn=$conn;

            $this->validateDataAndCreateUser($this->conn);
        }
    
        public function checkInputData($data) 
        {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }

        public function validateDataAndCreateUser($conn)
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
                                if(!empty($this->age))
                                {
                                    if(!is_numeric($this->age))
                                    {
                                        echo "<script> alert('Age must be a number.') </script>"; 
                                    }
                                    else
                                    {
                                        if($this->age >= 18)
                                        {
                                            if(!empty($this->address))
                                            {
                                                if(!empty($this->password) && !empty($this->cpassword))
                                                {
                                                    if(strlen($this->password) >=8 && strlen($this->cpassword) >= 8)
                                                    {
                                                        $sql = "SELECT * FROM users WHERE username='".$this->username."'";
                                                        $result = mysqli_query($conn, $sql);
                                                        $count_user = mysqli_num_rows($result);
                                                        if($count_user === 0)
                                                        {
                                                            if($this->password === $this->cpassword)
                                                            {
                                                                $role="User";
                                                                $hash = password_hash($this->password, PASSWORD_DEFAULT);
                                                                $sql = "INSERT INTO `users`(id, profile_image, first_name, last_name, username, age, address, role, password) VALUES (NULL,'".$this->file."','".$this->first_name."','".$this->last_name."','".$this->username."','".$this->age."','".$this->address."', '$role','$hash')";
                                                                $result = mysqli_query($conn, $sql);
                                    
                                                                if($result===false)
                                                                {
                                                                    echo"<script> alert('Database query failed.') </script>";
                                                                }
                                    
                                                                $conn->close();
                                                                header("Location: signup.php?message=User has been added successfully!");
                                                            }
                                                            else
                                                            {
                                                                echo "<script> alert('Password does not match.') </script>";
                                                            }
                                                        }
                                                        else
                                                        {
                                                            echo "<script> alert('User already exists!') </script>";
                                                        }
                                                    }
                                                    else
                                                    {
                                                        echo "<script> alert('Password must be atleast 8 or above.') </script>";
                                                    }
                                                }
                                                else
                                                {
                                                    echo "<script> alert('Pls create your password.') </script>"; 
                                                }
                                            }
                                            else
                                            {
                                                echo "<script> alert('Please fill in your address.') </script>"; 
                                            }
                                        }
                                        else
                                        {
                                            echo "<script> alert('Age must be 18 and above.') </script>"; 
                                        }
                                    }
                                }
                                else
                                {
                                    echo "<script> alert('Please fill in your age.') </script>"; 
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

    if($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['sign_up']))
    {
        include_once("conn_db.php");

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
            $default="sample.png";
            $uploadDir = "assets/";
            $destination = $uploadDir . basename($default);

            $signup = new signup($_POST['first_name'], $_POST['last_name'], $_POST['username'], $_POST['age'], $_POST['address'], $_POST['created_password'], $_POST['confirmed_password'], $conn, $destination);
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
                        $signup = new signup($_POST['first_name'], $_POST['last_name'], $_POST['username'], $_POST['age'], $_POST['address'], $_POST['created_password'], $_POST['confirmed_password'], $conn, $destination);
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
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="assets/Logo.png">
    <title>Niram - Sign up</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="main.css">
    <style>
        @media (min-width: 100px){ .BACKGROUND{display: none;} }
        @media (min-width: 300px){ .BACKGROUND{display: none;} }
        @media (min-width: 750px){ .BACKGROUND{display: none;} }
        @media (min-width: 900px){ .BACKGROUND{display: flex; width: 30rem; } }

        .BACKGROUND{ background-size: cover; background-repeat: no-repeat; background-image: url(assets/ramenBG.jpg); }
        .FORM{ box-shadow: 0 4px 6px #0000001a; background-color: #ffff; }
        .INPUT{ background-color: #ffff; height: 2.5rem; }

        .INPUT > input{ padding-left: 1rem; border-style: none; outline-style: none; background: transparent; width: 100%; height: 100%; }
        .INPUT > button{ border-style: none; background-color: #D33939; width: 3rem; }

        form > .BUTTON{ border: 1px solid #D33939; background-color: #D33939; height: 2.5rem; }
        .BUTTON > input{ border-style: none; background: transparent; height: 100%; width: 100%; color: #ffff; font-style: Poppins-Regular; font-weight: 500; font-size: 1rem; }

        .INPUT > input::-webkit-file-upload-button{ width: 8rem; height: 100%; }
        input::-webkit-file-upload-button{ border-style: none; background-color: #D33939; width: 8rem; height: 100%; font-family: Poppins-Regular; font-size: 0.8rem; color: #ffff; cursor: pointer; }

    </style>
</head>
<body class="d-flex align-items-center justify-content-center" style="background-color: #ffff;">

    <div class="container-fluid p-0 h-100 d-flex flex-row">
        <div class="BACKGROUND col-auto"></div>
        <div class="FORM col p-3">
            <form method="POST" class="h-100" enctype="multipart/form-data">

                <div class="col d-flex justify-content-start align-items-center"> <span style="font-size: 1.75rem; color: #393431; font-family: Righteous;"> SIGN UP </span> </div>
                <div class="col d-flex justify-content-start align-items-center"> <span style="font-size: 1rem; color: #393431; font-family: Poppins-Regular;"> Create Your Niram Account and Order Up or Take home! </span> </div>

                <hr>

                <div class="col p-0 d-flex justify-content-center align-items-center flex-row" style="height: 2.5rem;">
                    
                    <!-- input: first name -->
                    <div class="col border h-100 p-0 d-flex flex-row" style="background-color: #ffff;">
                        <input minlength="3" maxlength="16" type="text" name="first_name" placeholder="First name" style="border-style: none; outline-style: none; background: transparent; width: 100%; height: 100%; padding-left: 1rem;">
                    </div>

                    <div class="col-auto h-100"></div>

                    <!-- input: last name -->
                    <div class="col border h-100 p-0 d-flex flex-row" style="background-color: #ffff;">
                        <input minlength="3" maxlength="16" type="text" name="last_name" placeholder="Last name" style="border-style: none; outline-style: none; background: transparent; width: 100%; height: 100%; padding-left: 1rem;">
                    </div>
                </div>

                <br>

                <div class="col p-0 d-flex justify-content-center align-items-center flex-row" style="height: 2.5rem;">
                    
                    <!-- input: username -->
                    <div class="col border h-100 p-0 d-flex flex-row" style="background-color: #ffff;">
                        <input min="4" maxlength="8" type="text" name="username" placeholder="Username" style="border-style: none; outline-style: none; background: transparent; width: 100%; height: 100%; padding-left: 1rem;">
                    </div>

                    <div class="col-auto h-100"></div>

                    <!-- input: age -->
                    <div class="col border h-100 p-0 d-flex flex-row" style="background-color: #ffff;">
                        <input maxlength="2" type="text" name="age" placeholder="Age" style="border-style: none; outline-style: none; background: transparent; width: 100%; height: 100%; padding-left: 1rem;">
                    </div>
                </div>

                <br>

                <!-- input: address -->
                <div class="INPUT border col p-0 d-flex justify-content-center align-items-center flex-row">
                    <div class="col h-100 d-flex justify-content-center align-items-center"> <img src="assets/location.png" alt="..." width="15rem" height="15rem"> </div>
                    <input type="text" placeholder="Address" name="address">
                </div>

                <br>

                <!-- input: file -->
                <div class="col border p-0 d-flex justify-content-start align-items-center flex-row" style="overflow: hidden; background-color: #ffff; height: 2.5rem;">
                    <input type="file" name="image" style="border-style: none; width: 100%; height: 100%;">
                </div>

                <br>

                <!-- input: create password -->
                <div class="INPUT border col p-0 d-flex justify-content-center align-items-center flex-row">
                    <input minlength="8" maxlength="12" id="PASSWORD" type="password" name="created_password" placeholder="Create Password">
                    <button type="button" onclick="toggleCreatedPasswordVisibility()" class="col-auto h-100"> <img id="LOCK" src="assets/lock.png" alt="..." width="15rem" height="15rem"> </button>
                </div>

                <br>

                <!-- input: confirm password -->
                <div class="INPUT border col p-0 d-flex justify-content-center align-items-center flex-row">
                    <input minlength="8" maxlength="12" id="CPASSWORD" type="password" name="confirmed_password" placeholder="Confirm Password">
                    <button type="button" onclick="toggleConfirmedPasswordVisibility()" class="col-auto h-100"> <img id="LOCK_2" src="assets/lock.png" alt="..." width="15rem" height="15rem"> </button>
                </div>

                <br> <br>

                <!-- button: sign up -->
                <div class="BUTTON col d-flex flex-row justify-content-center align-items-center p-0">
                    <input type="submit" name="sign_up" value="SIGN UP">
                </div>

                <hr style="border-style: none;">

                <!-- redirect: sign in -->
                <div class="col d-flex flex-row justify-content-center align-items-center p-0"> <span style="font-family: Poppins-Regular; font-size: 0.8rem; color: #393431;"> Have an existing account? <a href="index.php" style="color: #D33939;" title="Sign in"> Sign in here. </a> </span> </div>

            </form>
        </div>
    </div>

    <script>

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