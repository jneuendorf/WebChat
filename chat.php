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
    <title>Neuer Tab</title>

    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

    <script src="js/jquery-1.11.3.min.js" type="text/javascript"></script>
    <script src="js/moment.min.js" type="text/javascript"></script>
    <script src="js/string_to_color/string_to_color.min.js" type="text/javascript"></script>
    <script src="js/jquery.cssemoticons.min.js" type="text/javascript"></script>

    <script src="js/chat.min.js" type="text/javascript"></script>

    <link rel="stylesheet" type="text/css" href="css/jquery.cssemoticons.css">
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>

<div class="overlay"></div>

<div class="lockOverlay">
    <div class="row" style="margin-top: 150px;">
        <div class="col-xs-8 col-md-6 col-lg-4 col-xs-push-2 col-md-push-3 col-lg-push-4">
            <input class="form-control unlock" type="password" name="unlock" />
            <div class="row">
                <div class="col-md-4 col-md-push-4" style="margin-top: 10px; text-align: center;">
                    <button class="btn btn-primary btn-lg unlock" type="button">Entsperren</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="lock" title="Sperren">
    <span class="glyphicon glyphicon-lock lock" aria-hidden="true"></span>
    <div class="countDown">

    </div>
</div>

<div class="row">
    <div class="wrapper col-xs-12 col-md-8 col-md-push-2 col-lg-6 col-lg-push-3">
        <div class="row">
            <div class="col-xs-12 chatContainer"></div>

            <div class="col-xs-12 col-md-12 inputContainer">

                <div class="row padded">
                    <div class="col-xs-12 col-md-9">
                        <div class="row">
                            <div class="col-xs-12">
                                <textarea id="message" class="form-control text message" placeholder="Hier tippen..."></textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-6">
                                <div class="input-group input-group-sm padded">
                                    <span class="input-group-addon">
                                        <input id="sendOnEnter" type="checkbox" checked="" />
                                    </span>
                                  <input type="text" class="form-control" disabled="" value="Bei Enter absenden" />
                                </div>

                            </div>
                            <div class="col-xs-6">
                                <div class="input-group input-group-sm">
                                    <span class="input-group-addon">
                                        <input id="autoLock" type="checkbox" checked="" />
                                    </span>
                                  <input type="text" class="form-control" disabled="" value="Chat automatisch sperren" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-3">
                        <div class="row">
                            <div class="col-xs-4 col-md-12">
                                <button class="btn btn-lg btn-primary padded send" type="button">Senden</button>
                            </div>
                            <div class="col-xs-4 col-md-12">
                                <button class="btn btn-lg btn-default padded updateAll" type="button">Alles laden</button>
                            </div>
                            <div class="col-xs-4 col-md-12">
                                <button class="btn btn-lg btn-danger logout" type="button">Logout</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-12 col-md-10 col-md-push-1 users"></div>
                </div>

            </div>

        </div>

    </div>
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
    <meta http-equiv="refresh" content="1; URL=index.php" />
</head>
<body>
Ung&uuml;ltige Session! Bitte einloggen!
</body>
</html>
<?php
}
?>
