<?php
    session_start();

    class signin
    {
        private $username;
        private $password;
        private $cookies;
        private $conn;

        public function __construct($username="", $password="", $cookies=0, $conn="")
        {
            $this->username=$this->checkInputData($username);
            $this->password=$this->checkInputData($password);
            $this->cookies=$this->checkInputData($cookies);
            $this->conn=$conn;

            $this->fetchUserData($this->conn);
        }

        public function checkInputData($data) 
        {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }

        public function fetchUserData($conn)
        {
            if(!empty($this->username))
            {
                if(is_numeric($this->username))
                {
                    echo"<script> alert('Username cannot start as a number') </script>";
                }
                else
                {
                    if(!empty($this->password))
                    {
                        $sql = "SELECT * FROM users WHERE username='".$this->username."'";
                        $result = mysqli_query($conn, $sql);
                        $count_user = mysqli_num_rows($result);
    
                        $row = mysqli_fetch_assoc($result);
                        if(!$row)
                        {
                            echo"<script> alert('User does not exist!') </script>";
                        }
                        else
                        {
                            if(password_verify($this->password, $row['password'])) 
                            {
                                if($row['role']==="Admin")
                                {
                                    setcookie("username", $row['username'], time()+(86400 * $this->cookies), "/");
                                    $conn->close();
                                    header("Location: dashboard.php?message=Login sucessful!&id=".$row['id']);
                                }
                                else
                                {
                                    setcookie("username", $row['username'], time()+(86400 * $this->cookies), "/");
                                    $conn->close();
                                    header("Location: home.php?message=Login sucessful!&id=".$row['id']);
                                }
                            } 
                            else 
                            {
                                echo "<script> alert('Incorrect password!') </script>";
                            }
                        }
                    }
                    else
                    {
                        echo"<script> alert('Please fill the password field.') </script>";
                    }
                }
            }
            else
            {
                echo"<script> alert('Please fill in your username.') </script>";
            }
        }
    }
    
    if($_SERVER['REQUEST_METHOD']==='POST')
    {
        include_once("conn_db.php");

        if(isset($_POST['remember_me']))
        {
            $cookies = 365;
            $signin = new signin($_POST['username'], $_POST['password'], $cookies, $conn);
        }
        else
        {
            $cookies = 30;
            $signin = new signin($_POST['username'], $_POST['password'], $cookies, $conn);
        }
    }
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="assets/Logo.png">
    <title>Niram - Sign in</title>
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

    </style>
</head>
<body class="d-flex align-items-center justify-content-center" style="background-color: #F2E7C9;">

    <div class="container-fluid p-0 h-100 d-flex flex-row">
        <div class="BACKGROUND col-auto"></div>
        <div class="FORM col p-3">
            <form method="POST" class="h-100">

                <div class="col d-flex justify-content-start align-items-center"> <span style="font-size: 1.75rem; color: #393431; font-family: Righteous;"> SIGN IN </span> </div>
                <div class="col d-flex justify-content-start align-items-center"> <span style="font-size: 1rem; color: #393431; font-family: Poppins-Regular;"> Welcome to Niram - Your Bowl of Endless Possbilities and to Savor the Experience! </span> </div>

                <hr>

                <!-- input: username -->
                <div class="INPUT col border p-0 d-flex justify-content-center align-items-center flex-row">
                    <input type="text" name="username" placeholder="Username">
                </div>

                <br>

                <!-- input: password -->
                <div class="INPUT col border p-0 d-flex justify-content-center align-items-center flex-row">
                    <input id="PASSWORD" type="password" name="password" placeholder="Password">
                    <button type="button" onclick="togglePasswordVisibility()" class="col-auto h-100"> <img id="LOCK" src="assets/lock.png" alt="..." width="15rem" height="15rem"> </button>
                </div>

                <hr style="border-style: none;">

                <!-- redirect: forgot password -->
                <div class="col d-flex flex-row justify-content-end align-items-center p-0"> <span style="font-family: Poppins-Regular; font-size: 0.8rem; color: #393431;"> Forgot Password? <a href="forgotpassword.php" style="color: #D33939;" title="Forgot password"> Click here. </a> </span> </div>

                <br> <br>

                <!-- input: checkbox -->
                <div class="col d-flex flex-row justify-content-center align-items-center p-0" style="gap: 0.3rem;">  
                    <input type="checkbox" name="remember_me" style="width: 0.8rem;">
                    <span style="font-family: Poppins-Regular; font-size: 0.8rem; color: #393431;"> Remember me </span>
                </div>

                <!-- button: log in -->
                <div class="BUTTON col d-flex flex-row justify-content-center align-items-center p-0">
                    <input type="submit" name="sign_in" value="SIGN IN">
                </div>

                <hr style="border-style: none;">

                <!-- redirect: sign up -->
                <div class="col d-flex flex-row justify-content-center align-items-center p-0"> <span style="font-family: Poppins-Regular; font-size: 0.8rem; color: #393431;"> Don't have an account? <a href="signup.php" style="color: #D33939;" title="Sign up"> Sign up here. </a> </span> </div>

            </form>
        </div>
    </div>

    <script>

        function togglePasswordVisibility()
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

    </script>
</body>
</html>