<?php
include('config.php');
$random_string = $_POST['rndstr'];
$uid = $_POST['uid'];
$accesskey = 0;
if($_POST['option']=='public-option')
$accesskey = 0;
else {
  $accesskey = 1;
}
$ptitle = $_POST['pastetitle'];
$pcontent = $_POST['pastearea'];
try{
  $query = $connect->prepare('INSERT INTO pastes(uid, ptitle, pcontent, randomstr, accesskey) VALUES (:uid, :ptitle, :pcontent, :randomstr, :accesskey)');
  $query->execute(array(
    ':uid' => $uid,
    ':ptitle' => $ptitle,
    ':pcontent' => $pcontent,
    ':randomstr' => $random_string,
    ':accesskey' => $accesskey
  ));
}
catch(PDOException $e){
  echo $e->getMessage();
}

if(isset($_POST['logout']))
{
  session_destoy();
  header('Location: index.php');
}

$random_url = "http://localhost/pasteit/view.php?s=" . $random_string;

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
  <title>Show Link</title>
  <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="styles/showlink-style.css">
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
    <p>Your URL is <a href=<?php echo $random_url; ?>><?php echo $random_url; ?></a>
    </div>

  </body>
  </html>
