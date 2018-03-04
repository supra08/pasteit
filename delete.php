<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('config.php');
$pid = $_POST['pasteid'];
echo $pid;

try{
  $query = $connect->prepare('DELETE FROM pastes WHERE pid = :pid');
  $query->execute(array(
    ':pid'=>$pid));
  }
  catch(PDOException $e){
    echo $e->getMessage();
  }

  if(isset($_POST['logout']))
  {
    session_start();
    $_SESSION = array();
    session_destroy();
    header('Location: index.php');
  }

  ?>

  <!DOCTYPE html>
  <html>
  <head>
    <meta charset="utf-8">
    <title>Delete Paste</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <style>
    .pastecontent{
      margin-top: 20vh;
      background:rgb(211, 221, 237);
      box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
      height: 60vh;
      text-align: center;
      padding: 25vh 10vw;
      justify-content: space-around;
    }

    .jumbotron{
      font-family: 'Rainbow';
      text-align: center;
      height: 15vh;
      padding-top: 3vh;
      background: url('../images/texture-back.jpg');
    }

    .jumbotron h6{
      padding-top: 1.6vh;
      font-size: 1.2em;
    }

    .btn{
      margin-left: 1vw;
    }

    </style>
  </head>
  <body>
    <div class="jumbotron">
      <div class='row'>
        <h1 class='col-sm-5'>PasteIt!</h1>
        <h6 class='col-sm-2 offset-2'>Welcome <?php echo $_SESSION['uname']; ?></h6>
        <form action='main.php' style="display:inline-block;" method="post">
          <input type="submit" name="dash" class='btn btn-primary' value='Go To Dashboard'>
        </form>
        <form style="display:inline-block;" method="post">
          <input type="submit" name="logout" class='btn btn-primary' value='logout'>
        </form>
      </div>
    </div>
    <div class="col-sm-8 offset-2 pastecontent">
      <p>The paste has been deleted. <a href='main.php'>Go Back</a></p>
    </div>
  </body>
  </html>
