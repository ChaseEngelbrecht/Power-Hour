<?php
include_once("../../php/database.php");
include_once("../../php/session.php");
require '../vendor/autoload.php';
require 'song.php';

$api = new SpotifyWebAPI\SpotifyWebAPI();
$api->setAccessToken($_SESSION["spotify"]);


if(isset($_POST['device'], $_SESSION['songs'], $_POST['offset'])){
  $device = mysqli_real_escape_string($connection, $_POST['device']);
  $offset = mysqli_real_escape_string($connection, $_POST['offset']);
  $songs = $_SESSION['songs'];
  $api->play($device, ['uris' => [$songs[$offset][3]],]);
  echo $songs[$offset][1] . '--' . $songs[$offset][0] . '--' . $songs[$offset][6] . '--' . $songs[$offset][2] . '--' . $songs[$offset][7];
}
if(isset($_POST['over'])){
  $api->pause();
}
?>
