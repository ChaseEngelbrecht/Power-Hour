<?php
include_once("../../php/database.php");
include_once("../../php/session.php");
require '../vendor/autoload.php';

$api = new SpotifyWebAPI\SpotifyWebAPI();
$api->setAccessToken($_SESSION["spotify"]);
$offset = 0;

if(isset($_GET["id"], $_GET["uid"])){
  $uid = mysqli_real_escape_string($connection, $_GET["uid"]);
  $id = mysqli_real_escape_string($connection, $_GET["id"]);
}

$playlistTracks = $api->getUserPlaylistTracks($uid, $id);
$songs = array();

$client = new Google_Client();
$client->setApplicationName("Codemoji");
$client->setDeveloperKey("AIzaSyBXpg2CPY2G_xR3FmDyXntR0gEnfXUNax8");
$service = new Google_Service_YouTube($client);

function searchListByKeyword($service, $part, $params) {
    $params = array_filter($params);
    $response = $service->search->listSearch(
        $part,
        $params
    );

    return $response->items[0]->id->videoId;
}

foreach ($playlistTracks->items as $track) {
    $track = $track->track;
    if($track->duration_ms >= 60000 && $track->uri != null){
      $video = searchListByKeyword($service, 'snippet', array('maxResults' => 1, 'q' => ($track->name . ' ' . $track->artists[0]->name), 'type' => ''));
      $song = array($track->name, $track->duration_ms, $track->album->images[0]->url, $track->uri, $track->id, 0, $track->artists[0]->name, $video);
      array_push($songs, $song);
    }
}

if(count($songs) >= 60){
  shuffle($songs);
  for($i = 0; $i < 60; $i++){
    $start = floor($songs[$i][1] - 60000);
    $spoint = rand(0, $start);

    $songs[$i][5] = $spoint;
  }
}

$_SESSION['songs'] = $songs;
?>
