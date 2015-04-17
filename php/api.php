<?php
session_start();

$chatlog_file = "../chatlog.txt";
$users_file = "../users.txt";
$delimiter = "<>";

$r = $_GET["r"];

if($r == "save" and isset($_SESSION["name"]) and $_SESSION["name"] != "") {
    $name = $_POST["n"];
    $message = $_POST["m"];
    // escape special chars and make \n to <br>
    $message = nl2br(htmlentities($message, ENT_QUOTES, "UTF-8"));
    // remove remaining line breaks
    $message = preg_replace("/\r|\n/", "", $message);
    // write to file
    $res = file_put_contents($chatlog_file, time().$delimiter.$name.$delimiter.$message."\n", FILE_APPEND | LOCK_EX);

    echo $res;
}
// get all messages
elseif($r == "update_all" and isset($_SESSION["name"]) and $_SESSION["name"] != "")	{
    $latestTimestamp = intval($_POST["ts"]);

    $chatlog = file_get_contents($chatlog_file);
    $lines = explode("\n", $chatlog);
    $res = array();

    // echo $latestTimestamp."\n";

    for($i = 0; $i < count($lines); ++$i) {
        $parts = explode($delimiter, $lines[$i]);
        $parts[0] = intval($parts[0]);
        if($parts[0] > $latestTimestamp) {
            array_push($res, array(
                "timestamp" => $parts[0],
                "name" => $parts[1],
                "message" => $parts[2]
            ));
        }
    }

    echo json_encode($res);
}
// get messages only from today
elseif($r == "update" and isset($_SESSION["name"]) and $_SESSION["name"] != "")	{
    $latestTimestamp = intval($_POST["ts"]);

    $chatlog = file_get_contents($chatlog_file);
    $lines = explode("\n", $chatlog);
    $res = array();

    // echo $latestTimestamp."\n";

    for($i = 0; $i < count($lines); ++$i) {
        $parts = explode($delimiter, $lines[$i]);
        $timestamp = intval($parts[0]);
        if($timestamp > strtotime(date("Y-m-d")) and $timestamp > $latestTimestamp) {
            array_push($res, array(
                "timestamp" => $timestamp,
                "name" => $parts[1],
                "message" => $parts[2]
            ));
        }
    }

    echo json_encode($res);
}
// get messages only from today
elseif($r == "users" and isset($_SESSION["name"]) and $_SESSION["name"] != "")	{
    $users = file_get_contents($users_file);
    $names = explode("\n", $users);
    $names = array_filter($names);
    $names = array_slice($names, 0);
    $names = array_unique($names, SORT_STRING);

    sort($names, SORT_STRING);

    echo json_encode($names);
}
elseif($r == "logout") {
    session_destroy();

    // remove user from users.txt
    $users = file_get_contents($users_file);
    $users = str_replace($_SESSION["name"]."\n", "", $users);
    file_put_contents($users_file, $users."\n", LOCK_EX);
}

?>
