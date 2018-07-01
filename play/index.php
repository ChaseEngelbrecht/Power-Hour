<?php
include('songs.php');
$sngs = $_SESSION["songs"];
?>
<!DOCTYPE html>
<html>
<head>
  <title>
    Blitzkrieg
  </title>
<link href="/blitzkrieg/css/style.css" rel="stylesheet" type="text/css"></link>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
</head>
<body>
  <script src="https://sdk.scdn.co/spotify-player.js"></script>
  <script>
  var offset = 0;
  var mode = 0;
  var totalTime = 3600;
  var timeBetween = 60000;
  var int;
  var int2;
     window.onSpotifyWebPlaybackSDKReady = () => {
       const token = '<?php echo $_SESSION["spotify"];?>';
       const player = new Spotify.Player({
         name: 'Web Playback SDK Quick Start Player',
         getOAuthToken: cb => { cb(token); },
         volume: 0.0
       });

       // Error handling
       player.addListener('initialization_error', ({ message }) => { console.error(message); });
       player.addListener('authentication_error', ({ message }) => { console.error(message); });
       player.addListener('account_error', ({ message }) => { console.error(message); });
       player.addListener('playback_error', ({ message }) => { console.error(message); });

       // Playback status updates
       player.addListener('player_state_changed', state => { console.log(state); });

       // Ready
       player.addListener('ready', ({ device_id }) => {
         console.log('Ready with Device ID', device_id);
         $('.start').css('background', '#1ed760');
         $('.start').css('cursor', 'pointer');
         $('.start').click(function(){
           $('.start').remove();

           var tr = 0;
           if(mode == 2){
              tr = 1;
           }
           else{
             tr = 60;
           }

           $('.song').text('Song 1/60');
           $('.info').text('Time Remaining ' + tr + ' Seconds');

           $('.spotify-info').css('display', 'block');
           $('.youtube').css('display', 'block');
           playSong(device_id, player);
           var width = 0;
           int = setInterval(function(){
             width++;
             $('#myBar').css('width', (width/totalTime) + '%');
             $('#counter').text(Math.round(width/totalTime) + '%');
           }, 1);
           int2 = setInterval(function(){
             var txt = $('.info').text();
             var curr = txt.substring(txt.indexOf('Remaining') + 10, txt.indexOf('Seconds'));
             var newtime = parseInt(curr) - 1;
             if(newtime < 0){
               newtime = 0;
             }
             $('.info').text('Time Remaining ' + newtime + ' Seconds');
           }, 1000);
         });
       });

       // Not Ready
       player.addListener('not_ready', ({ device_id }) => {
         console.log('Device ID has gone offline', device_id);
       });

       // Connect to the player!
       player.connect();
     };

     function playSong(device_id, player){
       $.ajax({
         url: 'playsong.php',
         method: 'POST',
         data: 'device='+device_id+'&offset='+offset,
         success: function(result){
           var time = result.substring(0, result.indexOf('--'));
           time = Math.floor(time) - 60000;
           var startpos = Math.floor(Math.random() * time);
           result = result.substring(result.indexOf('--') + 2);
           handleUI(result, Math.round(startpos/1000));
           setTimeout(function(){
             player.seek(startpos);
           }, 300);
           offset++;
           playNextSong(device_id, player);
         }
       });
     }

     function playNextSong(device_id, player){
       if(offset >= 60){
         $.ajax({
           url: 'playsong.php',
           method: 'POST',
           data: 'over=over',
           success: function(results){

           }
         });
         clearInterval(int);
         clearInterval(int2);
         $('.spotify-info').css('display', 'none');
         $('.youtube').css('display', 'none');
         $('.end').css('display', 'block');
         return;
       }
       if(mode == 1){
         timeBetween -= 1000;
       }
       else if(mode == 2){
         timeBetween += 1000;
       }
       setTimeout(function(){
         player.pause();
         playSong(device_id, player);
         var txt = $('.song').text();
         var song = txt.substring(txt.indexOf('Song') + 5, txt.indexOf('/'));
         var newnum = parseInt(song) + 1;
         $('.song').text('Song ' + newnum + '/60');
         $('.info').text('Time Remaining ' + timeBetween/1000 + ' Seconds');
       }, timeBetween);
     }

     function handleUI(result, start){
       var title = result.substring(0, result.indexOf('--'));
       result = result.replace(title + '--', '');
       var artist = result.substring(0, result.indexOf('--'));
       result = result.replace(artist + '--', '');
       var img = result.substring(0, result.indexOf('--'));
       var src = result.substring(result.indexOf('--') + 2);
       $('#sImg').attr('src', img);
       $('#sTitle').text(title);
       $('#sArtist').text(artist);
       $('#youtubeVid').attr('src', 'https://www.youtube.com/embed/' + src + '?autoplay=1&muted=1&start=' + (start + 1));
     }

     function removeActives(){
       $('#powerhour').removeClass('active');
       $('#blitz').removeClass('active');
       $('#reverse').removeClass('active');
     }

     $(function(){
       $('.mode').click(function(){
         removeActives();
         if($(this).attr('id') == 'powerhour'){
           mode = 0;
           totalTime = 3600;
           timeBetween = 60000;
         }
         else if($(this).attr('id') == 'blitz'){
           mode = 1;
           totalTime = 1800;
           timeBetween = 60000;
         }
         else if($(this).attr('id') == 'reverse'){
           mode = 2;
           totalTime = 1800;
           timeBetween = 1000;
         }

         $(this).addClass('active');
       });
     });

   </script>
   <a class="nav" href="/blitzkrieg">Home</a>
   <div class="mode active" id="powerhour">
     Power Hour
   </div>
   <div class="mode" id="blitz">
     Blitzkrieg
   </div>
   <div class="mode" id="reverse">
     Reverse Blitzkrieg
   </div>
   <div class="start">
     Start
   </div>
   <div class="spotify-info">
     <img id="sImg" src="/images/spinner.gif">
     <h3 id="sTitle"></h3>
     <h4 id="sArtist"></h4>
   </div>
   <div class="youtube">
     <iframe width="755" height="425" id="youtubeVid" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
   </div>
   <div class="end">
     All Done.
   </div>
   <div class="song">
     Song -/-
   </div>
   <div class="info">
     Time Remaining - Seconds
   </div>
   <div id="myProgress">
     <span id="counter">0%</span>
     <div id="myBar"></div>
   </div>
</body>
</html>
