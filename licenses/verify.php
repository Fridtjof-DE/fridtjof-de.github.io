<?php
  error_reporting(E_ERROR | E_WARNING | E_PARSE);
  if(!isset($_GET["v1"]) OR !isset($_GET["v2"])) exit("URL_ERROR");
  require "scripts/connect.php";
  $rand_EW_SKey = $_GET["v1"];
  $key_EW_rand = $_GET["v2"];

  if(isset($_GET["pl"]))$pluginName = $_GET["pl"];
  else $pluginName = "UnValidPluginName!";

  $sKey = toBinary(CKAP_KEY);

  $rand = _xor($rand_EW_SKey, $sKey);
  $key = _xor($key_EW_rand, $rand);

  $usrIP = getUserIP();

  $stingKey = fromBinary($key);

  $passed = 0;

  $res = $link->query("SELECT * FROM `licenses` WHERE `key`='".$stingKey."'");
  if($res->num_rows > 0){
    if(time() < mysqli_result($res, 0, 'expiry') or mysqli_result($res, 0, 'expiry') == -1){
      if(mysqli_result($res, 0, 'plBound') == 0 OR mysqli_result($res, 0, 'plName') == $pluginName){
      #echo "Key found!";
      $currIPs = mysqli_result($res, 0, 'currIPs');
      $lastRef = mysqli_result($res, 0, 'lastRef');
      $ips = mysqli_result($res, 0, 'ips');

      $arrIPs = array();
      $arrRef = array();

      if($currIPs){
        #echo "<br/> Found CurrIPs";
        $arrIPs = explode('#', $currIPs);
        $arrRef = explode('#', $lastRef);

        for ($entryId = count($arrIPs)-1; $entryId >= 0; $entryId--) {
          if($arrRef[$entryId] < (time()-900)) {
            #echo "<br/> Deleted outdated IP ".$entryId." - ".$arrIPs[$entryId];
            unset($arrRef[$entryId]);
            unset($arrIPs[$entryId]);
          }else{
            #echo "<br/>Diff of IP ".$arrIPs[$entryId]." is ".((time()-900));
          }
        }

        for ($entryId=0; $entryId < count($arrIPs); $entryId++) {
          if ($arrIPs[$entryId] == $usrIP) {
            #print_r($arrRef);
            #echo "<br/> Updated IP-Time";
            $arrRef[$entryId] = time();
            #print_r($arrRef);
            $passed = 1;
          }
        }


        if (!$passed AND count($arrIPs) < $ips) {
          #echo "<br/> Added user-ip";
          array_unshift($arrIPs, $usrIP);
          array_unshift($arrRef, time());
          $passed = 1;
        }
      }else{
        #echo "<br/> Force added user-ip";
        array_unshift($arrIPs, $usrIP);
        array_unshift($arrRef, time());
        $passed = 1;
      }

      #echo "<br/> Passed = ".$passed;

      $link->query("UPDATE `licenses`
                   SET `currIPs` = '".implode("#", $arrIPs)."',
                       `lastRef` = '".implode("#", $arrRef)."'
                   WHERE `key`='".$stingKey."'");

      if($passed) echo _xor($rand, _xor($key, $sKey));
      else echo "NOT_VALID_IP";
    } else echo "INVALID_PLUGIN";
    } else echo "KEY_OUTDATED";
  } else echo "KEY_NOT_FOUND";

  addRequestToStats($passed);
?>







<?php

  function addRequestToStats($value='1'){
    if(STATS){
      $logFile = fopen("log.txt", "a+");
      if(!$logFile) return;
      fwrite($logFile, $value.'#'.time()."\n");
      fclose($logFile);
    }
  }

  function _xor($text,$key){
    for($i=0; $i<strlen($text); $i++){
        $text[$i] = intval($text[$i])^intval($key[$i]);
    }
    return $text;
  }

  function getUserIP(){
      $client  = @$_SERVER['HTTP_CLIENT_IP'];
      $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
      $remote  = $_SERVER['REMOTE_ADDR'];

      if(filter_var($client, FILTER_VALIDATE_IP)){
          $ip = $client;
      }elseif(filter_var($forward, FILTER_VALIDATE_IP)){
          $ip = $forward;
      }else{
          $ip = $remote;
      }
      return $ip;
  }

  function toBinary($value='none'){
    $str = "";
    $a = 0;
    while ($a < strlen($value)) {
      $str .= sprintf( "%08d", decbin(ord(substr($value, $a, 1))));
      $a++;
    }
    return $str;
  }

  function fromBinary($value='00100001'){
    $str = "";
    $a = 0;
    while ($a < strlen($value)) {
      $str .= chr(bindec(substr($value, $a, 8)));
      $a = $a+8;
    }
    return $str;
  }

?>
