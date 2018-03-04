<?php
$random_string = $_GET['s'];
include('config.php');

try{
  $query = $connect->prepare('SELECT ptitle, pcontent, date_posted FROM pastes WHERE randomstr = :randomstr');
  $query->execute(array(
    ':randomstr'=>$random_string
  ));
}
catch(PDOException $e){
  echo $e->getMessage();
}

$data = $query->fetch(PDO::FETCH_ASSOC);
if($data != null){
  $title = $data['ptitle'];
  $date = $data['date_posted'];
  $content = $data['pcontent'];}
  else{
    echo "404 page not found";
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
    <title>View Paste</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="styles/view-style.css">
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
      <h1><?php echo $title; ?></h1><hr><hr>
      <h6>Date Posted: <?php echo $data['date_posted']; ?></h6>
      <p><?php echo $content; ?></p>

    </div>
  </body>
  </html>
