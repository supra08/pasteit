<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
//ini_set('memory_limit', -1);
include('config.php');
include('random-string.php');

try{
  $query = $connect->prepare(' DELETE FROM pastes WHERE NOW() > DATE_ADD(date_posted, INTERVAL 30 DAY)');
  $query->execute();
}
catch(PDOException $e){
  echo $e->getMessage();
}

function generate_key(){
  global $connect;
  $str = random_string();
  $query = $connect->prepare('SELECT randomstr FROM pastes WHERE randomstr = :rndstr');
  $query->bindParam("rndstr", $str, PDO::PARAM_STR);
  $query->execute();
  $data = $query->fetch(PDO::FETCH_ASSOC);
  if($data==false){
    return $str;
  }
  else{
    generate_key();
  }
}
if(empty($_SESSION['uname']))
header('Location: index.php');
$uname = $_SESSION['uname'];
$uemail = $_SESSION['email'];

try{
  $getuid = $connect->prepare('SELECT * FROM users WHERE uname = :uname');
  $getuid->execute(array(
    ':uname'=>$uname
  ));

  $row = $getuid->fetch(PDO::FETCH_ASSOC);
  $uid = $row['id'];
}
catch(PDOException $e)
{
  echo $e->getMessage();
}

//$accesskey = 1;

if(isset($_POST['logout']))
{
  session_start();
  $_SESSION = array();
  session_destroy();
  header('Location: index.php');
}

//$randomstr = random_string();
$randomstr = generate_key();
$randomurl = '';

?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Dashboard</title>
  <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="styles/main-style.css">
  <style>
  #mypastes{
    display: none;
  }
  </style>
</head>
<body>
  <div class="jumbotron">
    <div class='row'>
      <h1 class='col-sm-6'>PasteIt!</h1>
      <h6 class='col-sm-2 offset-2'>Welcome <?php echo $_SESSION['uname']; ?></h6>
      <form style="display:inline-block;" method="post">
        <input type="submit" name="logout" class='btn btn-primary' value='logout'>
      </form>
    </div>
  </div>

  <div class="btn-group tabs col-sm-4 offset-1">
    <button type="button" class="btn btn-primary" id="create-button" onclick="createfunc()">Create Paste</button>
    <button type="button" class="btn btn-primary" id="mypastes-button" onclick="mypastesfunc()">My Pastes</button>
  </div>
  <div class="paste col-sm-10 offset-1" id="paste">
    <form action="showlink.php" method="post">
      <div class="access-select col-sm-2 offset-8">
        <label class="radio-inline"><input type="radio" name="option" value="public-option">Public</label>
        <label class="radio-inline"><input type="radio" name="option" value="private-option">Private</label>
      </div>
      <div class="form-group pastetitle col-sm-9 offset-1">
        <input type="text" name="pastetitle" placeholder="Enter Paste Title" class="form-control">
      </div>
      <div class="form-group pastearea col-sm-9 offset-1">
        <textarea name="pastearea" class="form-control" rows="8" cols="20" placeholder="Create your paste here:"></textarea>
      </div>
      <div class="col-sm-2 offset-1">
        <input type="submit" name="paste-submit" value="Submit Paste" class = "btn btn-primary" onclick="viewlink()">
      </div>
      <input type="text" style="display:none;" name="rndstr" value="<?php echo $randomstr; ?>">
      <input type="text" style="display:none;" name="uid" value="<?php echo $uid; ?>">
    </form>
  </div>
  <div class="mypastes col-sm-8 offset-1" id="mypastes">
    <?php
    try{
      $query = $connect->prepare('SELECT * FROM pastes WHERE uid = :uid');
      $query->execute(array(
        ':uid'=>$uid
      ));
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
      <div class="row">
        <div class="delete-button">
          <form class="" action="delete.php" method="post">
            <input type="text" style="display:none;" name="pasteid" value='<?php echo $row['pid']; ?>'>
            <input type="submit" name="delete-button" class="btn btn-danger" value="Delete">
          </form>
        </div>
        <div class="set-access offset-6">
          <form action="set-access.php" method="post">
            <input type="text" style="display:none;" name="pasteid" value='<?php echo $row['pid']; ?>'>
            <label class="radio-inline"><input type="radio" name="option" value="public-option">Public</label>
            <label class="radio-inline"><input type="radio" name="option" value="private-option">Private</label>
            <input type="submit" style="display:inline;" name="set-access" class="btn btn-info" value="Set Access" id="set-access">
          </form>
        </div>
      </div><hr><hr>

    <?php } ?>
  </div>


  <?php
  if(isset($_POST['paste-submit'])){
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
        ':randomstr' => $randomstr,
        ':accesskey' => $accesskey
      ));
    }
    catch(PDOException $e){
      echo $e->getMessage();
    }
  }

  ?>

  <script type="text/javascript">
  function createfunc(){
    document.getElementById('paste').style.display = 'block';
    document.getElementById('mypastes').style.display = 'none';
  }
  function mypastesfunc(){
    document.getElementById('mypastes').style.display = 'inline-block';
    document.getElementById('paste').style.display = 'none';
  }
</script>
</body>
</html>
