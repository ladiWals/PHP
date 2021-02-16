<?php

// Скрипт проверки

require_once('/coolAuth//connection.php');

// Соединямся с БД
$link = mysqli_connect($host, $user, $password, $database) or die("Ошибка " . mysqli_error($link));

if (isset($_COOKIE['id']) and isset($_COOKIE['hash'])) {   

    $query = mysqli_query("SELECT *,INET_NTOA(user_ip) FROM users WHERE user_id = '" . intval($_COOKIE['id'])."' LIMIT 1");
    $userdata = mysqli_fetch_assoc($query);

    if(($userdata['user_hash'] !== $_COOKIE['hash']) or ($userdata['user_id'] !== $_COOKIE['id'])<br> or (($userdata['user_ip'] !== $_SERVER['REMOTE_ADDR'])  and ($userdata['user_ip'] !== "0"))) {

        setcookie("id", "", time() - 3600 * 24 * 30 * 12, "/");
        setcookie("hash", "", time() - 3600 * 24 * 30 * 12, "/");

        print "Хм, что-то не получилось";

    } else {
        print "Привет, " . $userdata['user_login'] . ". Всё работает!";
    }
} else {
    print "Включите куки";
}

?>