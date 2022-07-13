<?php

if(file_exists('../config.php')) require '../config.php';
elseif (file_exists('config.php')) require 'config.php';
else breakDown("Could not find file 'config.php'");

function mysqli_result($res, $row, $field=0) {
    $res->data_seek($row);
    $datarow = $res->fetch_array();
    return $datarow[$field];
}

function breakDown($msg, $alert='0'){
  echo "<head>";
  echo "<link rel='stylesheet' href='css/master.css' type='text/css' charset='utf-8'>";
  echo "</head>";
  echo "<body>";
  $allCalss = "al_info";
  if($alert) $allCalss = "al_alert";
  exit("<div class='$allCalss'>$msg</div> ");
}

$link = new mysqli(HOST, USERNAME, PASSWORD, DB_NAME);
if($link->connect_error){
  breakDown("Failed connection to MySQL-Server please make sure that you've entered all
        MySQL-Data correctly into the config.php and that the MySQL-Server is running!", 1);
}else{
  if(!$link->query("DESCRIBE `licenses`")){
    $link->query("CREATE TABLE `licenses` (
      `id` INT AUTO_INCREMENT,
      `key` TEXT NULL,
      `ips` INT NULL DEFAULT '1',
      `expiry` BIGINT NULL,
      `plBound` INT NULL DEFAULT 0,
      `plName` TEXT NULL,
      `plDesc` TEXT NULL,
      `plClient` TEXT NULL,
      `lastRef` TEXT NULL,
      `currIPs` TEXT NULL,
      PRIMARY KEY (`id`))
      ENGINE = InnoDB
    DEFAULT CHARACTER SET = utf8");

    breakDown("The table 'licenses' got createt successfully! Please refresh the page | Step [1/4]");
  }

  if(!$link->query("DESCRIBE `auth_keys`")) {
    $link->query("CREATE TABLE `auth_keys` (
      `id` INT AUTO_INCREMENT,
      `user` TEXT NULL,
      `key` TEXT NULL,
      PRIMARY KEY (`id`))
      ENGINE = InnoDB
    DEFAULT CHARACTER SET = utf8");

    breakDown("The table 'auth_keys' got createt successfully! Please refresh the page | Step [2/4]<");
  }

  if(!$link->query("DESCRIBE `users`")) {
    $link->query("CREATE TABLE `users` (
      `username` TEXT NULL,
      `password` TEXT NULL )
      ENGINE = InnoDB
    DEFAULT CHARACTER SET = utf8");

    breakDown("The table 'users' got createt successfully! Please refresh the page | Step [3/4]");
  }

  if(!ADMIN_USERNAME OR !ADMIN_PASSWORD) breakDown("You have to enter the data for the Admin-Account in the config.php" ,1);
  else{
    $result = $link->query("SELECT * FROM `users`");
    if ($result->num_rows > 0) {
      $link->query("UPDATE `users` SET
          `username` = '".ADMIN_USERNAME."',
          `password` = '".ADMIN_PASSWORD."'
        WHERE
          `username` = '".mysqli_result($result, 0, 'username')."' AND
          `password` = '".mysqli_result($result, 0, 'password')."' ");
    } else {
      $link->query("INSERT INTO `users` (`username`, `password`) VALUES ('".ADMIN_USERNAME."','".ADMIN_PASSWORD."')");
    }
  }
}
?>
