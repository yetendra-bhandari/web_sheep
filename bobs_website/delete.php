<?php
require_once "src/resume.php";
if (!isset($_SESSION['page_no'])) {
    $_SESSION['page_no'] = 1;
}
if (isset($_POST['back'])) {
    header("Location: index.php?page_no=" . $_SESSION['page_no']);
    return;
}
if (!isset($_SESSION['user_id'])) {
    die("Please Login!");
}
if (isset($_POST['delete']) && isset($_SESSION['delete'])) {
    $stmt = $pdo->prepare('DELETE FROM blog WHERE entry_id = :e');
    $stmt->execute(array(':e' => $_SESSION['delete']));
    unset($_SESSION['delete']);
    $_SESSION['success'] = 'Post Deleted';
    header("Location: index.php");
    return;
}
$row = false;
if (isset($_POST['entry_id'])) {
    $stmt = $pdo->prepare('SELECT user_id, heading, message, time FROM blog WHERE entry_id = :e');
    $stmt->execute(array(':e' => $_POST['entry_id']));
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row && $row['user_id'] == $_SESSION['user_id']) {
        $_SESSION['delete'] = $_POST['entry_id'];
    } else {
        die("Invalid Entry!");
    }
} else {
    die("Invalid Entry!");
}
$row['heading'] = htmlentities($row['heading']);
$row['message'] = nl2br(htmlentities($row['message']));
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8" />
    <title>Delete Post</title>
    <link rel="icon" href="images/favicon.ico" />
    <link rel="Stylesheet" href="src/web_sheep.css" />
</head>

<body>
    <img id="logo" src="images/logo.png" title="Web-Sheep" alt="Web-Sheep" onclick="location.href='index.php'" />
    <h1 id="title" class="heading">Web-Sheep</h1>
    <div id="subtitle">A vulnerable web-app!</div>
    <h1 class="heading">Delete This Post?</h1>
    <div class="blog">
        <h2 class="blogHeading"><?= $row['heading'] ?></h2>
        <h5 class="blogDate"><?= $row['time'] ?></h5>
        <p class="blogContent"><?= $row['message'] ?></p>
    </div>
    <form method="POST" class="main">
        <input type="submit" class="redButton" name="delete" value="Delete">
        <input type="submit" class="blueButton" name="back" value="Back" formnovalidate>
    </form>
</body>

</html>