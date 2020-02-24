<?php
$pdo = new PDO('mysql:host=localhost;port=3036;dbname=sheep', 'user', 'pass'); //The same port number may not work on different OS'. Check the port number of the 'mysql' server before running the web-app.
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
