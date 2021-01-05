<?php

if(file_exists('connect.php')) require 'connect.php';
elseif (file_exists('scripts/connect.php')) require 'scripts/connect.php';
else exit("Could not find file 'scripts/connect.php'");

$action = $_GET["action"];

if(isset($_COOKIE['auth_key']) AND
   $link->query("SELECT * FROM `auth_keys` WHERE `key`='".$link->real_escape_string($_COOKIE['auth_key'])."'")->num_rows > 0){
  if($action == "create"){
    try {
      $key     = strip_tags($_GET["key"]);
      $ips     = strip_tags($_GET["ips"]);
      $expDate = strip_tags($_GET["expDate"]);
        if($expDate == "null") $expDate = -1;
      $dName   = strip_tags($_GET["dName"]);
      $dDesc   = strip_tags($_GET["dDesc"]);
      $dClient = strip_tags($_GET["dClient"]);
      $dBound  = strip_tags($_GET["dBound"]);
        if($dBound == "true") $dBound = 1;
        else $dBound = 0;

      $link->query("INSERT INTO `licenses` (`key`, `ips`, `expiry`, `plName`, `plDesc`, `plClient`, `plBound`) VALUES
               ('$key', '$ips', '$expDate', '$dName', '$dDesc', '$dClient', '$dBound')");
      echo "SUCCESS!";
    }catch(Exception $e) {
    echo 'FAILED! Error:' .$e->getMessage();
    }
  }

  if($action == "delete"){
    $link->query("DELETE FROM `licenses` WHERE `id`='".$_GET["id"]."'");
  }
}else echo "FAILED! You are not logged in";
?>
