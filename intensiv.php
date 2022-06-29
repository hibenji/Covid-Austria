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
        Hier COVID Intensivfälle für gestern und heute.
      </h1>
      <h6 class="subtitle is-6">Intensivfälle werden jeden Tag zwischen 9:30 und 12 aktualisiert</h6>
      <p class="subtitle">



<?php

$url = 'https://info.gesundheitsministerium.gv.at/data/timeline-faelle-bundeslaender.csv';

$myfile = fopen("data2.csv", "w") or die("Unable to open file!");



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
$twoday .= 'T09:30:00+02:00';
//echo $twoday;



//Yesterday
$yesterday_date = new DateTime('yesterday');
$yesterday_date = $yesterday_date->format('Y-m-d');
$yesterday_date .= 'T09:30:00+02:00';
//echo $yesterday_date;

//Today
$today =  date('Y-m-d');
$today .= 'T09:30:00+02:00';




//echo '<br>';

$last = 0;

$file = fopen('data2.csv', 'r');
while (($line = fgetcsv($file, 5000,";")) !== FALSE) {
 // Extract line array into these variables
 list($date, $bund_id, $bund_name, $fall, $tod, $genesen, $hospital, $intensiv, $test, $test_pcr, $test_antigen) = $line;

//  echo $bund_id;
//  echo '<br>';

 if ($bund_id ==='10'){
    $yesterday = $intensiv - $last;
    $last = $intensiv;
//  echo "<a class=\"item\">$date, $bund, $name, $fall, $yesterday</a>\n";
//  echo '<br>';

 if ($date === $today){
         echo '<div class="content is-large">';

     echo 'Heute <strong>' . $yesterday . '</strong> mehr Intensivfälle in Östereich.';
     echo '<br>';
     echo 'Insgesamt <strong>' . $intensiv . '</strong> Intensivfälle.';
     echo '<br>';

 } 

 if ($date === $yesterday_date){
    echo 'Gestern <strong>' . $yesterday . '</strong> Intensivfälle in Östereich.';
    echo '<br>';
    echo 'Gestern Insgesamt <strong>' . $intensiv . '</strong> Intensivfälle.';
    echo '<br>';
}

if ($date === $twoday){
    echo 'Vorgestern <strong>' . $yesterday . '</strong> Intensivfälle in Östereich.';
    echo '<br>';
    echo 'Vorgestern Insgesamt <strong>' . $intensiv . '</strong> Intensivfälle.';
    echo '<br>';
    echo '<br>';
}

}

}

fclose($file);
fclose($myfile);

?>

</p>
</div>
<a class="button is-primary" href="/index.php" role="button">Normal</a>
<a class="button is-primary" href="/tod.php" role="button">Tod</a>
</div>


  </section>

</body>
</html>