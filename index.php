<?php
    
    include_once("set_index_session.php");

    if($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['sign_in']))
    {
      header("Location: login.php");
      exit();
    }
    else if($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['sign_up']))
    {
      header("Location: signup.php");
      exit();
    }

?>
<!DOCTYPE html>
<!-- saved from url=(0054)https://getbootstrap.com/docs/4.0/examples/jumbotron/# -->
<html lang="en"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="assets/logo.png">

    <title>Cloudy : Landing page</title>

    <link rel="canonical" href="https://getbootstrap.com/docs/4.0/examples/jumbotron/">

    <!-- Bootstrap core CSS -->
    <link href="./Jumbotron Template for Bootstrap_files/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="./Jumbotron Template for Bootstrap_files/jumbotron.css" rel="stylesheet">

    <!-- my css -->
     <link rel="stylesheet" href="main.css">
     <style>
      
      .btn_1{ width: 6rem; height: 1.875rem; cursor: pointer; }
      .btn_1 > span{ font-size: 0.8rem; }

      .active{ border: 1px solid #0D6EFD; background-color: #0D6EFD; }
      .active > span{ font-family: Poppins-regular; color: #ffff; }
      .active:hover{ transition: 0.3s ease-in-out; border: 1px solid #3180f5; background-color: #3180f5; }

      .inactive{ border: 1px solid #0D6EFD; background-color: #ffff; }
      .inactive > span{ font-family: Poppins-regular; color: #0D6EFD; }
      .inactive:hover{ transition: 0.3s ease-in-out; border: 1px solid #3180f5; background-color: #3180f5; }
      .inactive:hover > span{ color: #ffff; }

      .btn_2{ border: 1px solid #25549b; background-color: #25549b; width: 10rem; height: 3rem; cursor: pointer; }
      .btn_2 > span{ font-size: 1.2rem; color: #ffff; }
      .btn_2:hover{ transition:0.3s ease-in-out; border: 1px solid #2761b8; background-color: #2761b8; }

      a{ outline-style: none; }

      .navbar{
        box-shadow: 0px 2px 2px 0px rgba(0,0,0,0.2); 
      }

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

    .PRICING > .card{
      width: 20rem;
      height: 26rem;
    }
    .PRICING > .card:hover{
     top: -1rem;
    }
    .PRICING > .unselected_card{
      border-top: 3px solid #A2A2A2;
    }
    .PRICING > .unselected_card:hover{
      transition: 0.3s ease-in-out;
      border-top: 3px solid #0D6EFD;
    }

    @media (min-width: 300px){ 
      .jumbotron{
        display: flex;
        flex-direction: column-reverse;
      }

      .PRICING{
        display: flex;
        flex-direction: column;
      }
    }

    @media (min-width: 900px){ 
      .jumbotron{
        display: flex;
        flex-direction: row;
      }

      .PRICING{
        display: flex;
        flex-direction: row;
      }
    }

     </style>
  </head>

  <body>


    <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark bg-white">
      <a href="index.php" class="navbar-brand text-primary" style="font-family: Righteous;">CLOUDY</a>

      <div class="collapse navbar-collapse" id="navbarsExampleDefault">
        <ul class="navbar-nav mr-auto"></ul>
        <form method="post" class="form-inline my-2 my-lg-0" style="gap: 1rem;">
          
          <button type="submit" name="sign_in" class="btn_1 inactive">
              <span> Sign in </span>
          </button>

          <button type="submit" name="sign_up" class="btn_1 active">
            <span> Sign up </span>
          </button>

        </form>
      </div>
    </nav>

    <main role="main">

      <!-- Main jumbotron for a primary marketing message or call to action -->
      <div class="jumbotron" style="background: hsla(216, 98%, 52%, 1); background: linear-gradient(90deg, hsla(216, 98%, 52%, 1) 0%, hsla(186, 100%, 69%, 1) 100%); background: -moz-linear-gradient(90deg, hsla(216, 98%, 52%, 1) 0%, hsla(186, 100%, 69%, 1) 100%); background: -webkit-linear-gradient(90deg, hsla(216, 98%, 52%, 1) 0%, hsla(186, 100%, 69%, 1) 100%); filter: progid: DXImageTransform.Microsoft.gradient( startColorstr='#0D6EFD', endColorstr='#60EFFF', GradientType=1 );">
        <!-- <div class="container">
          <h1 class="display-3">Hello, world!</h1>
          <p>This is a template for a simple marketing or informational website. It includes a large callout called a jumbotron and three supporting pieces of content. Use it as a starting point to create something more unique.</p>
          <p><a class="btn btn-primary btn-lg" href="https://getbootstrap.com/docs/4.0/examples/jumbotron/#" role="button">Learn more »</a></p>
        </div> -->
        <div class="container">
          <h1 class="display-3 text-white" style="font-family: Righteous;">CLOUDY</h1>
          <p class="text-white" style="font-family: Poppins-regular;">Save your documents right away with Raincloud!</p>
          <!-- <p><a class="btn btn-primary btn-lg" href="https://getbootstrap.com/docs/4.0/examples/jumbotron/#" role="button">Learn more »</a></p> -->
           <form method="post">
            <button type="submit" name="sign_up" class="btn_2"> <span> SIGN UP </span> </button>
           </form>
        </div>
        <div class="container d-flex justify-content-center align-items-center hero-img">
          <img src="assets/hero-img.png" class="img-fluid float" alt="..." width="300rem" height="300rem">
        </div>
      </div>

      <div class="container">
        <!-- Example row of columns -->
        <div class="col d-flex justify-content-center align-items-center" style="font-family: Righteous; font-size: 3rem;"> <span class="text-primary"> PRICING </span> </div>
        <br>
        <div class="row">
          <div class="PRICING col p-0 justify-content-center align-items-center flex-wrap" style="gap: 3rem;">

            <div class="card unselected_card p-1">
              <div class="card-body bg-white">
                
                <div class="col p-0"> <span style="font-family: Poppins-medium; font-size: 2rem; color: #1B4179;"> Free </span> </div>
                <div class="col p-0"> <span class="text-primary" style="font-family: Poppins-medium; font-size: 2rem;"> $0 </span> <span style="font-family: Poppins-regular; color: #A2A2A2;"> /free </span> </div>
                <div class="col p-0" style="height: 13rem;">

                  <div class="col p-0"> <img src="assets/check.png" alt="..." width="30rem" height="30rem"> <span style="font-family: Poppins-regular; color: #A2A2A2;"> 15GB of storage </span> </div>
                  <div class="col p-0"> <img src="assets/check.png" alt="..." width="30rem" height="30rem"> <span style="font-family: Poppins-regular; color: #A2A2A2;"> Never expires </span> </div>
                  <div class="col p-0"> <img src="assets/check.png" alt="..." width="30rem" height="30rem"> <span style="font-family: Poppins-regular; color: #A2A2A2;"> Highly secured </span> </div>
                  <div class="col p-0"> <img src="assets/check.png" alt="..." width="30rem" height="30rem"> <span style="font-family: Poppins-regular; color: #A2A2A2;"> Fast and reliable access </span> </div>
                  <div class="col p-0"> <img src="assets/check.png" alt="..." width="30rem" height="30rem"> <span style="font-family: Poppins-regular; color: #A2A2A2;"> Fair usage policy </span> </div>
                  <div class="col p-0"> <img src="assets/check.png" alt="..." width="30rem" height="30rem"> <span style="font-family: Poppins-regular; color: #A2A2A2;"> Free </span> </div>

                </div>
                <div class="col p-0 border-primary" style="height: 2.5rem;">
                  <button type="submit" class="bg-primary w-100 h-100" style="border: none; cursor: pointer;">
                    <span class="text-white" style="font-family: Poppins-regular; font-size: 1.2rem;"> START FREE </span>
                  </button>
                </div>

              </div>
            </div>
            <div class="card p-1" style="border-top: 3px solid #0D6EFD;">
              <div class="card-body bg-white">
                
                <div class="col p-0"> <span style="font-family: Poppins-medium; font-size: 2rem; color: #1B4179;"> Lifetime </span> </div>
                <div class="col p-0"> <span class="text-primary" style="font-family: Poppins-medium; font-size: 2rem;"> $109 </span> <span style="font-family: Poppins-regular; color: #A2A2A2;"> /Lifetime </span> </div>
                <div class="col p-0" style="height: 13rem;">

                  <div class="col p-0"> <img src="assets/check.png" alt="..." width="30rem" height="30rem"> <span style="font-family: Poppins-regular; color: #A2A2A2;"> 100GB of storage </span> </div>
                  <div class="col p-0"> <img src="assets/check.png" alt="..." width="30rem" height="30rem"> <span style="font-family: Poppins-regular; color: #A2A2A2;"> Never expires </span> </div>
                  <div class="col p-0"> <img src="assets/check.png" alt="..." width="30rem" height="30rem"> <span style="font-family: Poppins-regular; color: #A2A2A2;"> Highly secured </span> </div>
                  <div class="col p-0"> <img src="assets/check.png" alt="..." width="30rem" height="30rem"> <span style="font-family: Poppins-regular; color: #A2A2A2;"> Fast and reliable access </span> </div>
                  <div class="col p-0"> <img src="assets/check.png" alt="..." width="30rem" height="30rem"> <span style="font-family: Poppins-regular; color: #A2A2A2;"> Fair usage policy </span> </div>
                  <div class="col p-0"> <img src="assets/check.png" alt="..." width="30rem" height="30rem"> <span style="font-family: Poppins-regular; color: #A2A2A2;"> One time payment </span> </div>

                </div>
                <div class="col p-0 border-primary" style="height: 2.5rem;">
                  <button type="submit" class="bg-primary w-100 h-100" style="border: none; cursor: pointer;">
                    <span class="text-white" style="font-family: Poppins-regular; font-size: 1.2rem;"> BUY LIFETIME </span>
                  </button>
                </div>

              </div>
            </div>
            <div class="card unselected_card p-1">
              <div class="card-body bg-white">
                
                <div class="col p-0"> <span style="font-family: Poppins-medium; font-size: 2rem; color: #1B4179;"> Monthly </span> </div>
                <div class="col p-0"> <span class="text-primary" style="font-family: Poppins-medium; font-size: 2rem;"> $9 </span> <span style="font-family: Poppins-regular; color: #A2A2A2;"> /month </span> </div>
                <div class="col p-0" style="height: 13rem;">

                  <div class="col p-0"> <img src="assets/check.png" alt="..." width="30rem" height="30rem"> <span style="font-family: Poppins-regular; color: #A2A2A2;"> 30GB of storage </span> </div>
                  <div class="col p-0"> <img src="assets/check.png" alt="..." width="30rem" height="30rem"> <span style="font-family: Poppins-regular; color: #A2A2A2;"> Expires </span> </div>
                  <div class="col p-0"> <img src="assets/check.png" alt="..." width="30rem" height="30rem"> <span style="font-family: Poppins-regular; color: #A2A2A2;"> Highly secured </span> </div>
                  <div class="col p-0"> <img src="assets/check.png" alt="..." width="30rem" height="30rem"> <span style="font-family: Poppins-regular; color: #A2A2A2;"> Fast and reliable access </span> </div>
                  <div class="col p-0"> <img src="assets/check.png" alt="..." width="30rem" height="30rem"> <span style="font-family: Poppins-regular; color: #A2A2A2;"> Fair usage policy </span> </div>
                  <div class="col p-0"> <img src="assets/xmark.png" alt="..." width="20rem" height="20rem"> <span style="font-family: Poppins-regular; color: #A2A2A2;"> One time payment </span> </div>

                </div>
                <div class="col p-0 border-primary" style="height: 2.5rem;">
                  <button type="submit" class="bg-primary w-100 h-100" style="border: none; cursor: pointer;">
                    <span class="text-white" style="font-family: Poppins-regular; font-size: 1.2rem;"> UPGRADE </span>
                  </button>
                </div>

              </div>
            </div>

          </div>
        </div>
        
        <hr>

      </div> <!-- /container -->

    </main>

    <footer class="container">
      <p>© Cloudy 2025-<span id="CURRENT_YEAR">2025</span> </p>
    </footer>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="./Jumbotron Template for Bootstrap_files/jquery-3.2.1.slim.min.js.download" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script>window.jQuery || document.write('<script src="../../assets/js/vendor/jquery-slim.min.js"><\/script>')</script>
    <script src="./Jumbotron Template for Bootstrap_files/popper.min.js.download"></script>
    <script src="./Jumbotron Template for Bootstrap_files/bootstrap.min.js.download"></script>
  

    <!-- my script -->
     <script>

        function get_current_year()
        {
          const currentDate = new Date(); const year = currentDate.getFullYear();
          document.getElementById("CURRENT_YEAR").innerText=year;
        }
        get_current_year();
        

     </script>

</body></html>