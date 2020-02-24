<?php
session_start();
$p = $_SESSION['page_no'];
unset($_COOKIE['remember']);
setcookie('remember', '', 1);
session_destroy();
header("Location: index.php?page_no=" . $p);
return;
