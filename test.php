<!DOCTYPE html>
<html>
<head>

<title>Covid Zahlen</title>
<meta name="viewport" content="width=device-width, initial-scale=1">


</head>

<body>


<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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

//Today
$today =  date('Y-m-d');
$today .= 'T09:30:00+02:00';


$timestamp = date("c", strtotime("now"));


echo '<br>';

$last_int = 0;
$last_tod = 0;

$file = fopen('data2.csv', 'r');
while (($line = fgetcsv($file, 5000,";")) !== FALSE) {
 // Extract line array into these variables
 list($date, $bund_id, $bund_name, $fall, $tod, $genesen, $hospital, $intensiv, $test, $test_pcr, $test_antigen) = $line;

 if ($bund_id ==='10'){
    $yesterday_int = $intensiv - $last_int;
    $last_int = $intensiv;

    $yesterday_tod = $tod - $last_tod;
    $last_tod = $tod;
//  echo "<a class=\"item\">$date, $bund, $name, $fall, $yesterday</a>\n";
//  echo '<br>';

 if ($date === $today){
    $status = file_get_contents('today_intensiv.txt');
    echo $status;
    echo "huh";

    if ($status === '0') {

        // $status_write = fopen("today_intensiv.txt", "w");
        // fwrite($status_write, '1');


        echo $tod;
        echo "bruh";
        echo '<br>';
        echo $yesterday_tod;
        echo '<br>';
        echo $yesterday_int;
        echo '<br>';
        echo $intensiv;


      $webhookurl = "https://discord.com/api/webhooks/906110563522871326/RbbNxO80DzicNMWOjU1zhA2rV_ULq1lwAHGNWT-itkfxPpGOJJiEAIcVs1tapNfQ4tkA";

     echo 'Heute <strong>' . $intensiv . '</strong> Intensivfälle in Östereich.';


     $json_data = json_encode([

      "content" => "<@!499865877945253888>, Heute sind $intensiv Intensivfälle.",
  
      "embeds" => [
  
          [
              // Embed Title
  
              "title" => "Fälle für Heute.",

              "timestamp" => $timestamp,
  
              "color" => "0",

              "author" => [
                "name" => "Benji",
                "url" => "https://benji.link/"
                ],

                "fields" => [
                    // Field 1
                    [
                        "name" => "Neue Intensivfälle",
                        "value" => $yesterday_int,
                        "inline" => true
                    ],
                    // Field 2
                    [
                        "name" => "Insgesamt Intensivfälle",
                        "value" => $intensiv,
                        "inline" => true
                    ],
                    [
                        "name" => "Neue Todesfälle",
                        "value" => $yesterday_tod,
                        "inline" => false
                    ],
                    // Field 2
                    [
                        "name" => "Insgesamt Todesfälle",
                        "value" => $tod,
                        "inline" => false
                    ]
                ]

  
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

  echo $response;

  
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