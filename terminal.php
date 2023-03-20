<?php
session_start();
if(!isset($_SESSION["directory"])){
    $directory = getcwd();
    $user = explode('/', $directory)[2];
    $_SESSION["user"] = $user;
    $_SESSION["directory"] = '/home/'.$user;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $command = $_POST['command'];

    if (!empty($command)) {
        $output = shell_exec('cd '.$_SESSION["directory"].' && '.$command.' && echo ---$PWD');
        if(strlen(trim($output))>0){
            $str = strstr($output, '---/');
            $output = str_replace($str, '', $output);
            $_SESSION["directory"] = str_replace('---', '', trim($str));
        }
        echo json_encode(['output' => $output??'', 'directory' => str_replace('/home/'.$_SESSION["user"], '~', $_SESSION["directory"]), 'user' => $_SESSION["user"]]);
    }
}
else{
    echo json_encode(['directory' => str_replace('/home/'.$_SESSION["user"], '~', $_SESSION["directory"]), 'user' => $_SESSION["user"]]);
}
