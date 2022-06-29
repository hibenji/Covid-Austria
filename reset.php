<?php

$status_write = fopen("today.txt", "w");
fwrite($status_write, '0');

$status_write_intensiv = fopen("today_intensiv.txt", "w");
fwrite($status_write_intensiv, '0');

?>