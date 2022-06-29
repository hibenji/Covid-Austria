<!DOCTYPE html>
<html>
<head>

<title>Covid Zahlen</title>
<meta name="viewport" content="width=device-width, initial-scale=1">


</head>

<body>


<?php

$configs = include('config.php');

$webhookurl = $configs["webhook-url"];

$url = 'https://info.gesundheitsministerium.gv.at/data/timeline-faelle-ems.csv';

$myfile = fopen("data.csv", "w") or die("Unable to open file!");



$curl = curl_init($url);
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

//for debug only!
curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($curl, CURLOPT_SSL_CIPHER_LIST, 'DEFAULT@SECLEVEL=1');


$data = curl_exec($curl);
curl_close($curl);


fwrite($myfile, $data);

date_default_timezone_set('Europe/Berlin');

//print all of the data
//echo $data;

//Today
$today =  date('Y-m-d');
$today .= 'T08:00:00+02:00';


$timestamp = date("c", strtotime("now"));



echo '<br>';

$last = 0;

$file = fopen('data.csv', 'r');
while (($line = fgetcsv($file, 5000,";")) !== FALSE) {
 // Extract line array into these variables
 list($date, $bund, $name, $fall) = $line;

 if ($bund ==='10'){
    $yesterday = $fall - $last;
    $last = $fall;
//  echo "<a class=\"item\">$date, $bund, $name, $fall, $yesterday</a>\n";
//  echo '<br>';

 if ($date === $today){
    $status = file_get_contents('today.txt');

    if ($status === '0') {

      $status_write = fopen("today.txt", "w");
      fwrite($status_write, '1');

        $url = $configs["twitter-backend"] . '/faelle/';
        $url .= $yesterday;

        $dump = file_get_contents($url);

     echo 'Heute <strong>' . $yesterday . '</strong> fälle in Östereich.';


     $json_data = json_encode([

      "content" => "<@!499865877945253888>, Heute sind $yesterday Fälle.",
  
      "embeds" => [
  
          [
              // Embed Title
  
              "title" => "Fälle für Heute.",
  
              "description" => "Heute sind $yesterday Fälle.",

              "timestamp" => $timestamp,
  
              "color" => "0"
  
          ]
  
      ]
  
  ]);
  
  $ch = curl_init( $webhookurl );
  
  curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
  
  curl_setopt( $ch, CURLOPT_POST, 1);
  
  curl_setopt( $ch, CURLOPT_POSTFIELDS, $json_data);
  
  curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1);
  
  curl_setopt( $ch, CURLOPT_HEADER, 0);
  
  curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);
  
  $response = curl_exec( $ch );
  
  curl_close( $ch );


  }
  

 } 

}

}

fclose($file);
fclose($myfile);

?>
</body>
</html>