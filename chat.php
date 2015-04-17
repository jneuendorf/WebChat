<?php
session_start();

// successful login
if(isset($_POST["btn"]) and isset($_POST["pw"]) and sha1($_POST["pw"]) == "ce3c2b6a8880424920c445ce4341f3b8b8801a31") {
    // add session var
    $_SESSION["name"] = $_POST["name"];
    // add name to current users
    file_put_contents("users.txt", $_POST["name"]."\n", FILE_APPEND | LOCK_EX);
}

// valid session
if(isset($_SESSION["name"]) and $_SESSION["name"] != "") {
?>
<html>
<head>
    <title>WebChat</title>

    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

    <script src="js/jquery-1.10.2.min.js" type="text/javascript"></script>
    <script src="js/moment.min.js" type="text/javascript"></script>
    <script src="js/string_to_color/string_to_color.min.js" type="text/javascript"></script>
    <script src="js/jquery.cssemoticons.min.js" type="text/javascript"></script>

    <script src="js/chat.js" type="text/javascript"></script>

    <link rel="stylesheet" type="text/css" href="css/jquery.cssemoticons.css">
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>

<div class="overlay"></div>

<div class="chatContainer"></div>

<div class="inputContainer">

    <textarea id="message" class="text message" placeholder="Hier tippen..."></textarea>
    <input class="btn send" type="button" value="Senden" />

    <hr />

    <div class="users"></div>

    <input class="btn updateAll" type="button" value="Gesamten Verlauf laden" />
    <br />
    <input class="btn logout" type="button" value="Logout" />
</div>

<input type="hidden" id="name" value="<?php echo $_SESSION["name"]; ?>" />

</body>
</html>
<?php
}
// invalid session
else {
?>
<html>
<head>
    <meta http-equiv="refresh" content="1.3; URL=index.php" />
</head>
<body>

Ung&uuml;ltige Session! Bitte einloggen!

</body>
</html>
<?php
}
?>
