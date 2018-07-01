<?php
include('mp3.php');
$dir = scandir('songs');
?>
<!DOCTYPE html>
<html>
<head>
  <title>
    Blitzkrieg
  </title>
  <link href="/blitzkrieg/css/style.css" rel="css/stylesheet"></link>
<center>
<div id="strt">
<button id="submit" onclick="onTimer();" class="button">Blitzkrieg!</button>
</div>
<font size="1000px">
<div id="timer" class="timer"></div>
</font></center>
<div id="myProgress">
  <div id="myBar"><center>1/60</center></div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script>
$(document).ready(function(){
$("#timer").hide();
$("#myBar").hide();
    $("#submit").click(function(){
        $("#strt").hide();
		$("#timer").show();
		$("#myBar").show();
    });
});
function reset(){
$(document).ready(function(){
    $("#submit").click(function(){
        $("#strt").show();
    });
});
document.getElementById('timer').value = "";
}
</script>
<script>
function shuffle(array) {
  var currentIndex = array.length, temporaryValue, randomIndex;

  // While there remain elements to shuffle...
  while (0 !== currentIndex) {

    // Pick a remaining element...
    randomIndex = Math.floor(Math.random() * currentIndex);
    currentIndex -= 1;

    // And swap it with the current element.
    temporaryValue = array[currentIndex];
    array[currentIndex] = array[randomIndex];
    array[randomIndex] = temporaryValue;
  }

  return array;
}

var indices = [];
var times = [];
for(var x = 2; x < 62; x++){
  indices.push(x);
}
indices = shuffle(indices);
audio = new Audio('airplanes.mp3');
i = 1;
t = 59;
width = 0;
progress = 1;
function onTimer() {
  if(i == progress){
    $('.aud' + indices[progress - 1])[0].currentTime = times[progress - 1];
    $('.aud' + indices[progress - 1])[0].play();
  }
  document.getElementById('timer').innerHTML = i;
  i--;
  if (i < 0) {
  	if (t == 0) {
  	alert ("You're Done!");
  	}else{
      $('.aud' + indices[progress - 1])[0].pause();
      audio.play();
  	t--;
  	i = 60-t;
  	var elem = document.getElementById("myBar");
      if (width >= 100) {
        elem.innerHTML = "Completed";
      } else {
        width = width + (100/60);
        elem.style.width = width + '%';
  	  progress++;
        elem.innerHTML = progress + "/60";
      }
  	onTimer();
    };
  }
  else {
    setTimeout(onTimer, 1000);
  }
}
</script>
</head>
<body>
    <?php
      $count = 0;
      foreach ($dir as $key => $value) {
        if($count > 1){
          $mp3file = new MP3File("songs/".$value."");
          $duration1 = $mp3file->getDurationEstimate();
          $duration1 -= 60;
          $ctime = rand(0, $duration1);
          echo '<audio class="aud aud'.$count.'" id="aud'.$count.'" controls><source src="songs/'.$value.'"></audio>';
          echo "<script>
                  times.push(".$ctime.");
                </script>";
          $count++;
        }
        else{
          $count++;
        }
      }
    ?>
</body>
</html>
