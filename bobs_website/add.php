<?php
require_once 'src/resume.php';
if (isset($_POST['back'])) {
    header("Location: index.php?page_no=" . $_SESSION['page_no']);
    return;
}
if (!isset($_SESSION['user_id'])) {
    die("Please Login!");
}
if (isset($_POST['heading']) && isset($_POST['message'])) {
    $_POST['heading'] = trim($_POST['heading']);
    $_POST['message'] = trim($_POST['message']);
    if (strlen($_POST['heading']) < 1) {
        $_SESSION['error'] = "Please enter a heading!";
        header("Location: add.php");
        return;
    } else if (strlen($_POST['heading']) > 128) {
        $_SESSION['error'] = "Heading too long!";
        header("Location: add.php");
        return;
    } else if (strlen($_POST['message']) < 1) {
        $_SESSION['error'] = "Please type in the content!";
        header("Location: add.php");
        return;
    } else {
        $stmt = $pdo->prepare('INSERT INTO blog (user_id,heading,message) VALUES (:i,:h,:m)');
        $stmt->execute(array(':i' => $_SESSION['user_id'], ':h' => $_POST['heading'], ':m' => $_POST['message']));
        $_SESSION['success'] = 'New Post Created';
        $_SESSION['page_no'] = 1;
        header("Location: index.php?page_no=" . $_SESSION['page_no']);
        return;
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8" />
    <title>New Post</title>
    <link rel="icon" href="images/favicon.ico" />
    <link rel="Stylesheet" href="src/web_sheep.css" />
</head>

<body>
    <img id="logo" src="images/logo.png" title="Web-Sheep" alt="Web-Sheep" onclick="location.href='index.php'" />
    <h1 id="title" class="heading">Web-Sheep</h1>
    <div id="subtitle">A vulnerable web-app!</div>
    <h1 class="heading">New Post</h1>
    <?php
    if (isset($_SESSION['error'])) {
        echo '<p id="error" class="flash">' . $_SESSION['error'] . '</p>';
        unset($_SESSION['error']);
    }
    ?>
    <div style="text-align: center;">
        <form method="POST">
            <input id="heading" type="text" name="heading" placeholder="Heading"><br>
            <textarea id="message" name="message" placeholder="Content" style=" margin-bottom: 5px;"></textarea><br>
            <input type="submit" class="greenButton" value="Create Post">
            <input type="submit" class="blueButton" name="back" value="Back" formnovalidate>
        </form>
    </div>
</body>

</html>