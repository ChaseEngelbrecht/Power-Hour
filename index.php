<?php
include('spotify.php');
$dir = scandir('songs');
?>
<!DOCTYPE html>
<html>
<head>
  <title>
    Blitzkrieg
  </title>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<link href="/blitzkrieg/css/style.css" rel="stylesheet" type="text/css"></link>
</head>
<body>
<h1 class="title">Welcome</h1>
<h3 class="subtitle">Select a playlist from below to begin</h3>
<div class="playlists">
<?php
foreach($playlists->items as $p){
  if($p->tracks->total >= 60){
    echo '<a class="playlist" href="play/?uid='.$api->me()->id.'&id='.$p->id.'"><img src="'.$p->images[0]->url.'"><h5>' . $p->name . '</h5><h6>'.$p->tracks->total.' Songs</h6></a>';
  }
}
?>
</div>
</body>
</html>
