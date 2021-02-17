<?php

// Скрипт проверки

require_once ($_SERVER['DOCUMENT_ROOT'] . '/coolAuth//connection.php');

// Соединямся с БД
$link = mysqli_connect($host, $user, $password, $database) or die("Ошибка " . mysqli_error($link));

if (isset($_COOKIE['id']) && isset($_COOKIE['hash'])) {   

    $query = mysqli_query($link, "SELECT *,INET_NTOA(ip) FROM users WHERE id = '" . intval($_COOKIE['id'])."' LIMIT 1");
    $userdata = mysqli_fetch_assoc($query);

    if (($userdata['hash'] !== $_COOKIE['hash']) || ($userdata['id'] !== $_COOKIE['id']) || (($userdata['INET_NTOA(ip)'] !== $_SERVER['REMOTE_ADDR']) && ($userdata['ip'] !== "0"))) {
        setcookie("id", "", time() - 3600 * 24 * 30 * 12, "/");
        setcookie("hash", "", time() - 3600 * 24 * 30 * 12, "/");
        $result = "Хм, что-то не получилось";
    } else {
        $result = "Привет, " . $userdata['login'] . ". Всё работает!";
    }
} else {
    $result = "Включите куки";
}
?>

<!DOCTYPE html>

<head>
    <title>Проверка</title>
    <link rel="stylesheet" href="/coolAuth/styles.css">
</head>

<body>
    <div class="check">
        <?=$result?>
    </div>
</body>