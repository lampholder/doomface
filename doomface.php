<?php
include('SimpleImage.php');

set_error_handler("customError"); 

$health = $_GET['health'];
if ($health == "") { $health = "100"; }
$special = strtoupper($_GET['special']);
$height = $_GET['height'];
if ($height >= 5000) { $height = "5000"; }
$width = $_GET['width'];
if ($width >= 5000) { $width = "5000"; }
$scale = $_GET['scale'];
if ($scale >= 10000) { $scale = "10000"; }

$random = $_GET['random'];
if ($random != "") {
    $health = rand(0, 100);
    if (rand(0, 2) == 0) {
        $pick_special = array("GOD", "OUCH", "KILL", "EVIL", "LEFT", "RIGHT");
        shuffle($pick_special);
        $special = $pick_special[0];
    }
}

# 80% - 200%    Completely healthy
# 60% - 79%     Bloody nose, hair slightly mussed
# 40% - 59%     Face swollen, grimacing
# 20% - 39%     Eyes crossed slightly, face dirty and bleeding
# 1% - 19%      Similar to 20% - 39%, but even bloodier
# 0%            Dead
# GODLIKE       Completely healthy, golden eyes
# EVIL          Demonic expression
# OUCH          Expression of pain (surprise) - rarely observed in gameplay due to a bug
# KILL          A killing face

function customError($errno, $errstr) {
    if ($errno == E_USER_ERROR) {
        header("Location: help.html");
    }
}

function healthToState($health) {
    if ($health >= 80) return "0";
    if ($health >= 60) return "1";
    if ($health >= 40) return "2";
    if ($health >= 20) return "3";
    if ($health >= 1) return 4;
}

$stub = "STF";
if ($health <= 0) {
    $mid = "DEAD";
    $end = "0";
}
else if ($special == "GOD") {
    $mid = "GOD";
    $end = "0";
}
else if ($special == "OUCH") {
    $mid = "OUCH";
    $end = healthToState($health);
}
else if ($special == "KILL") {
    $mid = "KILL";
    $end = healthToState($health);
}
else if ($special == "EVIL") {
    $mid = "EVL";
    $end = healthToState($health);
}
else if ($special == "LEFT") {
    $mid = "TL";
    $end = healthToState($health) . "0";
}
else if ($special == "RIGHT") {
    $mid = "TR";
    $end = healthToState($health) . "0";
}
else {
    $mid = "ST";
    $end = healthToState($health) . rand(0,2);
}

$filename = "faces/" . $stub . $mid . $end . ".png";
$image = new SimpleImage();
$image->load($filename);

if ($scale >= 5) {
    $image->scale($scale);
}
else if ($width != "" && $height != "") {
    $image->resize($width, $height);
}
else if ($width != "") {
    $image->resizeToWidth($width);
}
else if ($height != "") {
    $image->resizeToHeight($height);
}

header('Content-Type: image/png');
$image->output(IMAGETYPE_PNG);


?>
