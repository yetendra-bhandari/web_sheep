<?php
session_start();
if (!isset($_SESSION['page_no'])) {
    $_SESSION['page_no'] = 1;
}
if (isset($_POST['back'])) {
    header("Location: index.php?page_no=" . $_SESSION['page_no']);
    return;
}
if (isset($_POST['email']) && isset($_POST['pass'])) {
    unset($_SESSION['user_id']);
    unset($_SESSION['name']);
    unset($_COOKIE['remember']);
    setcookie('remember', '', 1);
    $_POST['email'] = trim($_POST['email']);
    $_POST['pass'] = trim($_POST['pass']);
    if (strlen($_POST['email']) < 1) {
        $_SESSION['error'] = "Please enter the email address!";
        header("Location: login.php");
        return;
    } else if (strlen($_POST['pass']) < 1) {
        $_SESSION['error'] = "Please enter the password!";
        header("Location: login.php");
        return;
    } else {
        require_once 'src/pdo.php';
        $salt = 'super_salt';
        $password = hash('md5', $salt . $_POST['pass']);
        /*
        $stmt = $pdo->prepare('SELECT user_id, name FROM users WHERE email = :e AND password = :p');
        $stmt->execute(array(':e' => $_POST['email'], ':p' => $password));
        */
        $stmt = $pdo->query('SELECT user_id, name FROM users WHERE password = "' . $password . '" AND email = "' . $_POST['email'] . '"'); //This will make the website susceptible to SQL Injection as the user input is being directly concatenated to the SQL query.
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            $_SESSION['user_id'] = $row['user_id'];
            $_SESSION['name'] = $row['name'];
            $_SESSION['success'] = 'Logged In Successfully';
            if (isset($_POST['remember'])) {
                setcookie('remember', $password, time() + 1800);
            }
            header("Location: index.php?page_no=" . $_SESSION['page_no']);
            return;
        } else {
            $_SESSION['error'] = 'Incorrect email or password';
            header("Location: login.php");
            return;
        }
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8" />
    <title>Log In</title>
    <link rel="icon" href="images/favicon.ico" />
    <link rel="Stylesheet" href="src/web_sheep.css" />
</head>

<body>
    <img id="logo" src="images/logo.png" title="Web-Sheep" alt="Web-Sheep" onclick="location.href='index.php'" />
    <h1 id="title" class="heading">Web-Sheep</h1>
    <div id="subtitle">A vulnerable web-app!</div>
    <h1 class="heading">Login</h1>
    <?php
    if (isset($_SESSION['error'])) {
        echo '<p id="error" class="flash">' . $_SESSION['error'] . '</p>';
        unset($_SESSION['error']);
    }
    ?>
    <div class="main">
        <form method="POST">
            <input type="text" name="email" style="width:16em" placeholder="Email"><br>
            <input type="password" name="pass" style="width:16em" placeholder="Password"><br>
            <div id="rememberMe"><input type="checkbox" name="remember" value="rememberMe" checked>Remember Me</div>
            <input type="submit" class="greenButton" value="Login">
            <input type="submit" class="blueButton" name="back" value="Back" formnovalidate>
        </form>
        <br>
        Want to <a href="signup.php">sign up</a> instead?
    </div>
</body>

</html>