<?php
require_once 'src/resume.php';
$limit = 5;
$page_no = $_SESSION['page_no'];
$pages = 1;
$stmt = $pdo->query('SELECT COUNT(*) FROM blog');
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$count = $row['COUNT(*)'];
if ($count > $limit) {
  $pages = ceil($count / $limit);
}
if (isset($_GET['page_no']) && ctype_digit($_GET['page_no']) && (int) $_GET['page_no'] <= $pages) {
  $page_no = $_SESSION['page_no'] = (int) $_GET['page_no'];
}
?>
<!DOCTYPE html>
<html lang="en-US">

<head>
  <meta charset="UTF-8" />
  <title>Web-Sheep</title>
  <link rel="icon" href="images/favicon.ico" />
  <link rel="Stylesheet" href="src/web_sheep.css" />
</head>

<body>
  <img id="logo" src="images/logo.png" title="Web-Sheep" alt="Web-Sheep" onclick="location.href='index.php'" />
  <h1 id="title" class="heading">Web-Sheep</h1>
  <div id="subtitle">A vulnerable web-app!</div>
  <?php
  if (isset($_SESSION['user_id'])) {
    $name = htmlentities($_SESSION['name']);
    if (strlen($name) > 20) {
      $name = substr($name, 0, 20) . '...';
    }
    echo '<a href="logout.php" id="logout">Logout, ' . $name . '</a><form id="newPost" action="add.php"><input type="submit" class="greenButton" value="Create New Post"></form>';
  } else {
    echo '<div class="main"><a href="signup.php">Sign Up</a> | <a href="login.php">Log In</a></div>';
  }
  $stmt = $pdo->query('SELECT  blog.user_id, entry_id, name, heading, message, time FROM  users JOIN blog ON users.user_id = blog.user_id ORDER BY entry_id DESC LIMIT ' . (($page_no - 1) * $limit) . ',' . $limit);
  $row = $stmt->fetch(PDO::FETCH_ASSOC);
  if ($row) {
    while ($row) {
      $flag = false;
      if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $row['user_id']) {
        $flag = true;
        $row['name'] = 'You';
      } else {
        $row['name'] = htmlentities($row['name']);
      }
      $row['heading'] = htmlentities($row['heading']);
      //$row['message'] = htmlentities($row['message']); //This will make the website susceptible to HTML Injection as the user input has not been properly escaped.
      $row['message'] = nl2br($row['message']);
      echo '<div class="blog"><h2 class="blogHeading">' . $row['heading'] . '</h2><h5 class="blogDate">' . $row['time'] . ', by ' . $row['name'] . '</h5><p class="blogContent">' . $row['message'] . '</p>';
      if ($flag) {
        echo '<form action="delete.php" method="POST" class="deleteButton"><input type="hidden" name="entry_id" value="' . $row['entry_id'] . '"><input type="submit" class="redButton" title="Delete This Post" value="🗑"></form>';
      }
      echo '</div>';
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
    }
  } else {
    echo '<div class="main">No Posts Yet!</div>';
  }
  if ($pages > 1) {
    echo '<div class="main">';
    for ($i = 1; $i <= $pages; $i++) {
      if ($i == $page_no) {
        echo $i . ' ';
      } else {
        echo '<a href="index.php?page_no=' . $i . '" style="text-decoration-line: underline;">' . $i . '</a> ';
      }
    }
    echo '</div>';
  }
  if (isset($_SESSION['success'])) {
    echo '<p id="success" class="flash">' . $_SESSION['success'] . '</p>';
    unset($_SESSION['success']);
  }
  ?>
</body>

</html>