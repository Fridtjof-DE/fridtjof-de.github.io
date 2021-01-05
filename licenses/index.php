<?php

  require "scripts/connect.php";

  if(HTTPS){
    echo " <script>
    if (window.location.protocol != 'https:')
      window.location.href = 'https:' + window.location.href.substring(window.location.protocol.length);
    </script> ";
  }

  if (NOINDEX) {
  	$meta = "noindex, ";
  } else {
  	$meta = "index, ";
  }
  if (NOFOLLOW) {
  	$meta .= "nofollow";
  } else {
  	$meta .= "follow";
  }

  if(!isset($_GET['page'])) $_GET['page'] = '';
  if($_GET['page'] == 'logout'){
    $link->query("DELETE FROM `auth_keys` WHERE `key` = '".$_COOKIE['auth_key']."'");
    setcookie("usr", "", time() - 3600);
    setcookie("auth_key", "", time() - 3600);
  }

  function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
  }

  if(isset($_POST['login']) AND $_POST['login'] == 1){
    if($_POST['usr'] == '' OR $_POST['pw'] == ''){
      $error = '<div class="al_alert">Please enter username and password</div>';
    }else{
      $result = $link->query("SELECT * FROM `users` WHERE `username` = '".$link->real_escape_string($_POST['usr'])."' AND `password` = '".$link->real_escape_string($_POST['pw'])."'");

      if($result->num_rows > 0){
        setcookie('usr', md5($_POST['usr']), time() + 14400, "/");
        setcookie('auth_key', $cookie_value = generateRandomString(), time() + 14400, "/");
        $key = $cookie_value;
        $usr = md5($_POST['usr']);
        $link->query("DELETE FROM `auth_keys` WHERE `user` = '".$_POST['usr']."'");
        $link->query("INSERT INTO `auth_keys` (`user`,`key`) VALUES ('".$_POST['usr']."','".$cookie_value."')");
      }else{
        $error = '<div class="al_alert">Username or password are incorrect</div>';
      }
    }
  }

  function getButton($name, $url){
    echo "<a href='?page=$url'> <div style='cursor: pointer;' class='al_btn'>
            <div class='anim_btn'>
              $name
            </div>
            $name
          </div> </a>";
  }

  $loggedIn = 0;
  if(!isset($usr) AND isset($_COOKIE['usr'])) $usr = $_COOKIE['usr'];
  if(!isset($key) AND isset($_COOKIE['auth_key'])) $key = $_COOKIE['auth_key'];
  if(!isset($key)) $key = '';
  if(!isset($usr)) $usr = '';
  $res = $link->query("SELECT * FROM `auth_keys` WHERE `key`='".$key."'");
  if($res->num_rows > 0) if(md5(mysqli_result($res, 0, 'user')) == $usr) $loggedIn = 1;

  $page = $_GET["page"];
  if($page == '' OR $page == 'logout') $page = "dashboard";
  if($loggedIn == 0) $page = "login";
?>


<!DOCTYPE html>
<html ng-app="License">
  <head>
    <meta charset="utf-8">
    <title>AdvancedLicense-System</title>

    <meta name="robots" content="<?php echo $meta; ?>">

    <script src='https://code.jquery.com/jquery-latest.min.js' type='text/javascript'></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/1.0.2/Chart.min.js"> </script>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.4.9/angular.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.4.9/angular-animate.js"></script>
    <link href='https://fonts.googleapis.com/css?family=Quicksand' rel='stylesheet' type='text/css'>
    <script type="text/javascript" src="scripts/Angular.JS"></script>
    <link rel='stylesheet' href='css/master.css' type='text/css' charset='utf-8'>
  </head>
  <body>
    <div class="al_nav">
      <div class="title">
        AdvancedLicense
        <i>Coded by Leoko</i>
      </div>

      <?php getButton(" Dashboard","dashboard"); ?>
      <?php getButton(" Manage  license","manage"); ?>
      <?php getButton(" Add license","add"); ?>
      <?php getButton(" Logout","logout"); ?>
    </div>

      <div class="content"> <?php require "content/$page.php"; ?> </div>
  </body>
</html>
