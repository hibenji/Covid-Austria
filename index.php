<!DOCTYPE html>
<html>
<head>

<title>Covid Zahlen</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.3/css/bulma.min.css">
<link rel="stylesheet" type="text/css" href="https://unpkg.com/bulma-prefers-dark" />
<script async src="https://arc.io/widget.min.js#Cv2cAKhg"></script>
</head>

<body>

<section class="section">
    <div class="container">
      <h1 class="title">
      COVID-Fälle in Österreich
      </h1>
      <h6 class="subtitle is-6">Fälle werden jeden Tag zwischen 8 und 9 aktualisiert</h6>
      <p class="subtitle">

<?php


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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

//Day before yesterday
$twoday = new DateTime();
$twoday->sub(new DateInterval('P2D'));
$twoday = $twoday->format('Y-m-d');
$twoday .= 'T08:00:00+02:00';
//echo $twoday;


//Yesterday
$yesterday_date = new DateTime('yesterday');
$yesterday_date = $yesterday_date->format('Y-m-d');
$yesterday_date .= 'T08:00:00+02:00';
//echo $yesterday_date;

//Today
$today =  date('Y-m-d');
$today .= 'T08:00:00+02:00';


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
          echo '<div class="content is-large">';

      echo 'Heute <strong>' . $yesterday . '</strong> Fälle in Östereich.';
    } 

    if ($date === $yesterday_date){
      echo 'Gestern <strong>' . $yesterday . '</strong> Fälle in Östereich.';
      echo '<br>';
    }

    if ($date === $twoday){
      echo 'Vorgestern <strong>' . $yesterday . '</strong> Fälle in Östereich.';
      echo '<br>';
    }

  }

}

fclose($file);
fclose($myfile);

?>

</p>
</div>
<a class="button is-primary" href="/intensiv.php" role="button">Intensiv</a>
<a class="button is-primary" href="/tod.php" role="button">Tod</a>
</div>
  </section>

</body>
</html>