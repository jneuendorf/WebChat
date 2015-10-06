<html>
<head>
    <title>Neuer Tab</title>
    <script src="js/jquery-1.11.3.min.js" type="text/javascript"></script>
    <script src="js/functionsDisplay.js" type="text/javascript"></script>
    <script src="js/setup.js" type="text/javascript"></script>

    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css" />

    <link rel="stylesheet" type="text/css" href="css/style.css" />
    <link rel="stylesheet" type="text/css" href="css/table.css" />
</head>
<body style="overflow: hidden;">
<div class="index wrapper col-xs-10 col-md-8 col-lg-6 col-xs-push-1 col-md-push-2 col-lg-push-3">
    <div class="row">
        <div class="col-xs-6 col-xs-push-3">
            <form id="form" action="chat.php" method="post">
                <div class="input-group input-group-lg">
                    <span class="input-group-addon">&gt;</span>
                    <input type="text" class="form-control" placeholder="Nickname" name="name" />
                </div>
                <div class="input-group input-group-lg">
                    <span class="input-group-addon">&gt;</span>
                    <input type="password" class="form-control" placeholder="Passwort" name="pw" />
                </div>
                <button class="btn btn-lg btn-primary" type="submit" name="btn">Login</button>
            </form>
        </div>
    </div>
</div>

</body>
</html>
