<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('config.php');

$username = '';
$email = '';
$password = '';
$errmsg = '';

if(isset($_POST['register'])){
  $errmsg = '';

  $username = $_POST['uname-register'];
  $email = $_POST['email-register'];
  $password = $_POST['pword-register'];


  if($username == '')
  $errmsg = 'Enter your username';
  if($password == '')
  $errmsg = 'Enter your password';
  if($email == '')
  $errmsg = 'Enter your email';

  if($errmsg == '')
  {
    try{
      $query = $connect->prepare('INSERT INTO users (uname, pword, email) values (:username, :password, :email)');
      $query->execute(array(
        ':username' => $username,
        ':password' => $password,
        ':email' => $email));
        header('Location: index.php?action=joined');
        exit;
      }
      catch(PDOException $e)
      {
        echo $e->getMessage();
      }
    }
  }
  if(isset($_GET['action']) && $_GET['action'] == 'joined') {
    $errmsg = 'Registration successfull. Now you can login.';
  }

  if(isset($_POST['login'])){
    $errmsg = '';

    $username = $_POST['uname-login'];
    $password = $_POST['pword-login'];

    if($username=='')
    $errmsg = 'Enter username';
    if($password=='')
    $errmsg = 'Enter password';

    if($errmsg==''){
      try{
        $query = $connect->prepare('SELECT * FROM users WHERE uname = :username');
        $query->execute(array(
          ':username'=>$username
        ));
        $row = $query->fetch(PDO::FETCH_ASSOC);

        if($row==false){
          $errmsg = "User ".$username." not yet registered. Please register.";
        }
        else{
          if($password == $row['pword']){
            $_SESSION['uname'] = $row['uname'];
            $_SESSION['pword'] = $row['pword'];
            $_SESSION['email'] = $row['email'];

            header('Location: main.php');
            exit;
          }
          else{
            $errmsg = 'Passwords is incorrect. Please try again.';
          }
        }

      }
      catch(PDOException $e){
        echo $e->getMessage();
      }
    }
  }
  ?>


  <!DOCTYPE html>
  <html>
  <head>
    <meta charset="utf-8">
    <title>PasteIt!</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <!-- <link rel="stylesheet" href="styles/reset.css"> -->
    <link rel="stylesheet" href="styles/index-style.css">
    <style>
    #register{
      display : none;
    }
    </style>
  </head>
  <body>
    <div class="jumbotron">
      <h1>PasteIt!</h1>
    </div>

    <div class="error-box">
      <h5><?php echo $errmsg; ?></h5>
    </div>

    <div class="row">
      <div class="col">
        <div class="row">
          <div class="btn-group col-sm-4 offset-1">
            <button type="button" class="btn btn-primary" id="login-button" onclick="loginfunc()">Login</button>
            <button type="button" class="btn btn-primary" id="register-button" onclick="registerfunc()">Register</button>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-5 offset-1" id="login">
            <h3>LOGIN</h3>
            <form  action="" method="post">
              <div class="form-group">
                <label for="username">User Name:</label>
                <input type="text" name="uname-login" value="" class="form-control">
              </div>
              <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" name="pword-login" value="" class="form-control">
              </div>
              <input type="submit" name="login" value="Login" class="form-control btn btn-primary submit">
            </form>
          </div>
          <div class="col-sm-5 offset-1" id="register">
            <h3>REGISTER</h3>
            <form  action="" method="post">
              <div class="form-group">
                <label for="username">User Name:</label>
                <input type="text" name="uname-register" value="" class="form-control">
              </div>
              <div class="form-group">
                <label for="username">Email:</label>
                <input type="text" name="email-register" value="" class="form-control">
              </div>
              <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" name="pword-register" value="" class="form-control">
              </div>
              <input type="submit" name="register" value="Register" class="form-control btn btn-primary submit">
            </form>
          </div>
        </div>
      </div>
      <!--recent pastes pane starts-------------------------------------------------------------------------------->
      <div class="col recent">
        <div class="col-sm-5">
          <h3>Recent Pastes</h3>
        </div>
        <div class="pastebox col-sm-11">
          <?php
          try{
            $query = $connect->prepare('SELECT ptitle, pcontent, randomstr, date_posted FROM pastes WHERE accesskey = 0 order by pid desc limit 5');
            $query->execute();
          }
          catch(PDOException $e){
            echo $e->getMessage();
          }
          while($row=$query->fetch()){
            ?>
            <div class="pastecontent">
              <h5><b><a href=<?php echo "http://localhost/pasteit/view.php?s=" . $row['randomstr']; ?>><?php echo $row['ptitle']; ?></a></b></h5>
              <p><?php echo $row['date_posted']; ?></p>
              <p><?php echo $row['pcontent']; ?></p>
            </div>
          <?php }
          ?>
        </div>

      </div>
    </div>


    <script type="text/javascript">
    function loginfunc(){
      console.log("login clicked");
      document.getElementById('login').style.display = 'inline-block';
      document.getElementById('register').style.display = 'none';
    }
    function registerfunc(){
      console.log("register clicked");
      document.getElementById("login").style.display = 'none';
      document.getElementById("register").style.display = 'inline-block';
    }
    </script>
  </body>
  </html>
