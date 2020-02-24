<?php
session_start();
if (!isset($_SESSION['page_no'])) {
    $_SESSION['page_no'] = 1;
}
if (isset($_POST['back'])) {
    header("Location: index.php?page_no=" . $_SESSION['page_no']);
    return;
}
if (isset($_POST['name']) && isset($_POST['email']) && isset($_POST['pass']) && isset($_POST['conf'])) {
    unset($_SESSION['user_id']);
    unset($_SESSION['name']);
    unset($_COOKIE['remember']);
    setcookie('remember', '', 1);
    $_POST['name'] = trim($_POST['name']);
    $_POST['email'] = trim($_POST['email']);
    $_POST['pass'] = trim($_POST['pass']);
    $_POST['conf'] = trim($_POST['conf']);
    if (strlen($_POST['name']) < 1) {
        $_SESSION['error'] = "Please enter the name!";
        header("Location: signup.php");
        return;
    } else if (strlen($_POST['email']) < 1) {
        $_SESSION['error'] = "Please enter the email address!";
        header("Location: signup.php");
        return;
    } else if (strlen($_POST['pass']) < 1) {
        $_SESSION['error'] = "Please enter the password!";
        header("Location: signup.php");
        return;
    } else if (strlen($_POST['conf']) < 1) {
        $_SESSION['error'] = "Please confirm the password!";
        header("Location: signup.php");
        return;
    } else if (strcmp($_POST['pass'], $_POST['conf']) != 0) {
        $_SESSION['error'] = "Passwords do not match!";
        header("Location: signup.php");
        return;
    } else {
        require_once "src/pdo.php";
        $stmt = $pdo->prepare('SELECT 1 from users WHERE email=:e LIMIT 1');
        $stmt->execute(array(':e' => $_POST['email']));
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            $_SESSION['error'] = 'Email ID already exists!';
            header("Location: signup.php");
            return;
        } else {
            $salt = 'super_salt';
            $password = hash('md5', $salt . $_POST['pass']);
            $stmt = $pdo->prepare('INSERT INTO users (name,email,password) VALUES (:n,:e,:p)');
            $stmt->execute(array(':n' => $_POST['name'], ':e' => $_POST['email'], ':p' => $password));
            $_SESSION['user_id'] = $pdo->lastInsertId();
            $_SESSION['name'] = $_POST['name'];
            $_SESSION['success'] = 'Signed Up Successfully';
            if (isset($_POST['remember'])) {
                setcookie('remember', $password, time() + 1800);
            }
            header("Location: index.php?page_no=" . $_SESSION['page_no']);
            return;
        }
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8" />
    <title>Sign Up</title>
    <link rel="icon" href="images/favicon.ico" />
    <link rel="Stylesheet" href="src/web_sheep.css" />
</head>

<body>
    <img id="logo" src="images/logo.png" title="Web-Sheep" alt="Web-Sheep" onclick="location.href='index.php'" />
    <h1 id="title" class="heading">Web-Sheep</h1>
    <div id="subtitle">A vulnerable web-app!</div>
    <h1 class="heading">Sign Up</h1>
    <?php
    if (isset($_SESSION['error'])) {
        echo '<p id="error" class="flash">' . $_SESSION['error'] . '</p>';
        unset($_SESSION['error']);
    }
    ?>
    <div class="main">
        <form method="POST">
            <input type="text" name="name" style="width:16em" placeholder="Name"><br>
            <input type="email" name="email" style="width:16em" placeholder="Email"><br>
            <input type="password" name="pass" style="width:16em" placeholder="Password"><br>
            <input type="password" name="conf" style="width:16em" placeholder="Confirm Password"><br>
            <div id="rememberMe"><input type="checkbox" name="remember" value="rememberMe" checked>Remember Me</div>
            <input type="submit" class="greenButton" value="Sign Up">
            <input type="submit" class="blueButton" name="back" value="Back" formnovalidate>
        </form>
        <br>
        Want to <a href="78login.php">login</a> instead?
    </div>
</body>

</html>