<?php
    class changepassword
    {
        private $email;
        private $verified_code;
        private $password;
        private $cpassword;
        private $conn;

        public function __construct($email="", $verified_code="", $password="", $cpassword, $conn="")
        {
            $this->email=$this->checkInputData($email);
            $this->verified_code=$this->checkInputData($verified_code);
            $this->password=$this->checkInputData($password);
            $this->cpassword=$this->checkInputData($cpassword);
            $this->conn=$conn;

            $this->fetchUserDataAndUpdate($this->conn);
        }

        public function checkInputData($data) 
        {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }

        public function fetchUserDataAndUpdate($conn)
        {
            if(!empty($this->email) && !empty($this->verified_code) && !empty($this->password) && !empty($this->cpassword))
            {   
                $codes = array(12323, 12233, 13322);
                if(in_array($this->verified_code, $codes))
                {
                    if(strlen($this->password) >=8 && strlen($this->cpassword) >= 8)
                    {
                        if($this->password === $this->cpassword)
                        {
                            $hash = password_hash($this->password, PASSWORD_DEFAULT);

                            if(str_contains($this->email, "@cloudme.com"))
                            {
                                echo "<script> alert('Email is already fixated, don't include it in your name.') </script>";
                            }
                            else
                            {
                                $EMAIL = $this->email . "@cloudme.com";
                                $sql = "SELECT email FROM users WHERE email='$EMAIL'";
                                $result = mysqli_query($conn, $sql);
                                $count_user = mysqli_num_rows($result);
                                if($count_user === 0)
                                {
                                    echo"<script> alert('User does not exist!') </script>";
                                }
                                else
                                {
                                    $sql = "UPDATE users SET password='$hash' WHERE email='$EMAIL'";
                                    $result = mysqli_query($conn, $sql);
            
                                    if($result===false)
                                    {
                                        echo"<script> alert('Database query failed.') </script>";
                                    }
            
                                    $conn->close();
                                    header("Location: login.php?message=Password+has+been+changed!");
                                }
                            }
                        }
                        else
                        {
                            echo "<script> alert('Password does not match.') </script>";
                        }
                    
                    }
                    else
                    {
                        echo "<script> alert('Password must be atleast 8 or above.') </script>";
                    }
                }
                else
                {
                    echo"<script> alert('Verification error') </script>";
                }
            }
            else
            {
                echo "<script> alert('Fields are required.') </script>";
            }
        }
    }
   
    if($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['change_password']))
    {
        include_once("conn_db.php");

        $changepassword = new changepassword($_POST['email'], $_POST['verification_code'], $_POST['created_password'], $_POST['confirmed_password'], $conn);
    }
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="assets/Logo.png">
    <title>Cloudy - Change password</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="main.css">
    <style>
        @media (min-width: 100px){ .BACKGROUND{display: none;} }
        @media (min-width: 300px){ .BACKGROUND{display: none;} }
        @media (min-width: 750px){ .BACKGROUND{display: none;} }
        @media (min-width: 900px){ .BACKGROUND{display: flex; width: 30rem; } }

        .BACKGROUND{  
          background: hsla(216, 98%, 52%, 1); background: linear-gradient(90deg, hsla(216, 98%, 52%, 1) 0%, hsla(186, 100%, 69%, 1) 100%); background: -moz-linear-gradient(90deg, hsla(216, 98%, 52%, 1) 0%, hsla(186, 100%, 69%, 1) 100%); background: -webkit-linear-gradient(90deg, hsla(216, 98%, 52%, 1) 0%, hsla(186, 100%, 69%, 1) 100%); filter: progid: DXImageTransform.Microsoft.gradient( startColorstr="#0D6EFD", endColorstr="#60EFFF", GradientType=1 );
          overflow: hidden;
        }
        .FORM{ box-shadow: 0 4px 6px #0000001a; background-color: #ffff; }
        .INPUT{ background-color: #ffff;  height: 2.5rem; }

        .INPUT > input{ padding-left: 1rem; border-style: none; outline-style: none; background: transparent; width: 100%; height: 100%; }
        .INPUT > button{ border-style: none; background-color: #0D6EFD; width: 3rem; }

        form > .BUTTON{ border: 1px solid #0D6EFD; background-color: #0D6EFD;  height: 2.5rem; }
        .BUTTON > input{ border-style: none; background: transparent; height: 100%; width: 100%; color: #ffff; font-style: Poppins-Regular; font-weight: 500; font-size: 1rem; }

        .INPUT > input::-webkit-file-upload-button{ width: 8rem; height: 100%; }

        input::-webkit-file-upload-button{ border-style: none; background-color: #0D6EFD; width: 8rem; height: 100%; font-family: Poppins-Regular; font-size: 0.8rem; color: #ffff; cursor: pointer; }

        .COUNTER{ display: none; }

        .float {
            animation-name: float;
            animation-duration: 4s;
            animation-iteration-count: infinite;
        }
            
      @keyframes float {
        0% { transform:  translate(0, 15%);  }
        20% { transform: translate(0, -10%); }
        30% { transform: translate(0, -15%); }
        50% { transform: translate(0, -10%); }
        60% { transform: translate(0, -15%); }
        80% { transform: translate(0, -10%); }
        100% { transform: translate(0, -15%); }
      }
    </style>
</head>
<body class="d-flex align-items-center justify-content-center" style="background-color: #F2E7C9;">

    <div class="container-fluid p-0 h-100 d-flex flex-row">
        <div class="BACKGROUND col-auto">

            <div class="col d-flex justify-content-center">
              <div class="container d-flex justify-content-center align-items-center hero-img">
                <img src="assets/hero-img.png" class="img-fluid float" alt="..." width="300rem" height="300rem">
              </div>
            </div>
  
          </div>
        <div class="FORM col p-3">
            <form method="POST" class="h-100">

                <div class="col d-flex justify-content-start align-items-center"> <span style="font-size: 1.75rem; color: #393431; font-family: Righteous;"> CHANGE PASSWORD </span> </div>

                <hr>

                <div class="col p-0 d-flex justify-content-center align-items-center flex-row" style=" height: 2.5rem;">
                    
                    <!-- input: email -->
                    <div class="INPUT border col p-0 d-flex justify-content-center align-items-center flex-row">
                        <input type="text" name="email" placeholder="name">
                        <span class="pr-2" style="font-family: Poppins-regular; font-size: 0.8rem;"> @cloudme.com </span>
                    </div>

                    <div class="col-auto h-100"></div>

                    <!-- input: verification -->
                    <div class="col border h-100 p-0 d-flex flex-row" style="background-color: #ffff;">
                        <input type="text" name="verification_code" placeholder="Verification" style="border-style: none; outline-style: none; background: transparent; width: 100%; height: 100%; padding-left: 1rem;">
                        <button id="VERIFICATION" type="button" onclick="generateVerificationCode()" class="col-auto h-100 justify-content-center align-items-center" style="border: 1px solid #0D6EFD; background-color: #0D6EFD; height: 2.5rem;"> <img src="assets/send.png" alt="..." width="15rem" height="15rem"> </button>
                        <div class="COUNTER col h-100 justify-content-center align-items-center"> <span id="count"> - </span> </div>
                    </div>
                </div>

                <br>

                <!-- input: create password -->
                <div class="INPUT col border p-0 d-flex justify-content-center align-items-center flex-row">
                    <input id="PASSWORD" type="password" name="created_password" placeholder="Create new Password">
                    <button type="button" onclick="toggleCreatedPasswordVisibility()" class="col-auto h-100"> <img id="LOCK" src="assets/lock.png" alt="..." width="15rem" height="15rem"> </button>
                </div>

                <br>

                <!-- input: confirm password -->
                <div class="INPUT col border p-0 d-flex justify-content-center align-items-center flex-row">
                    <input id="CPASSWORD" type="password" name="confirmed_password" placeholder="Confirm new Password">
                    <button type="button" onclick="toggleConfirmedPasswordVisibility()" class="col-auto h-100"> <img id="LOCK_2" src="assets/lock.png" alt="..." width="15rem" height="15rem"> </button>
                </div>

                <br> <br>

                <!-- button: Change password -->
                <div class="BUTTON col d-flex flex-row justify-content-center align-items-center p-0">
                    <input type="submit" name="change_password" value="CHANGE PASSWORD">
                </div>

                <hr style="border-style: none;">

                <!-- redirect: sign in -->
                <div class="col d-flex flex-row justify-content-center align-items-center p-0"> <span style="font-family: Poppins-Regular; font-size: 0.8rem; color: #393431;"> Change your mind? <a href="login.php" style="color: #0D6EFD;" title="Sign in"> Click here. </a> </span> </div>

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

        function generateVerificationCode() 
        {
            let verificationInput = document.querySelector("input[name='verification_code']");
            let codes = [12323, 12233, 13322];
            let random = Math.floor(Math.random() * 3);
            let generatedCode = codes[random];

            verificationInput.value = generatedCode;

            let verification = document.getElementById("VERIFICATION");
            let counter = document.querySelector(".COUNTER");
            let count = document.getElementById("count");
            
            verification.style.display = "none"; 

            let Timer;
            let sec = 10;  
            Timer = setInterval(() => {  
                sec--;  

                if (sec <= 10) {
                    counter.style.display = "flex";
                    count.innerText=sec;
                }
                if (sec <= 0) {  
                    counter.style.display = "none"; 
                    verification.style.display = "flex";
                    clearInterval(Timer); 
                }
            }, 1000); 
        }

    </script>

</body>
</html>