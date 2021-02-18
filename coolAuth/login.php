<?php

// Страница авторизации

$error = false;

require_once($_SERVER['DOCUMENT_ROOT'] . '/coolAuth/connection.php');

// Функция для генерации случайной строки
function generateCode($length = 6) {
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHI JKLMNOPRQSTUVWXYZ0123456789";
    $code = "";
    $clen = strlen($chars) - 1;

    while (strlen($code) < $length) {
            $code .= $chars[mt_rand(0, $clen)];  
    }

    return $code;
}

// Соединямся с БД
$link = mysqli_connect($host, $user, $password, $database) or die("Ошибка " . mysqli_error($link));

// Когда пришла форма
if(isset($_POST['submit'])) {

    // Вытаскиваем из БД запись, у которой логин равняеться введенному
    $query = mysqli_query($link, "SELECT id, password FROM users WHERE login='" . mysqli_real_escape_string($link, $_POST['login']) . "' LIMIT 1");

    $data = mysqli_fetch_assoc($query);

    // Сравниваем пароли
    if($data['password'] === md5(md5($_POST['password']))) {
        $success = true;

        // Генерируем случайное число и шифруем его
        $hash = md5(generateCode(10));

        // Если пользователь выбрал привязку к IP
        if(!@$_POST['not_attach_ip']) {

            // Переводим IP в строку
            $insip = ", ip=INET_ATON('" . $_SERVER['REMOTE_ADDR'] . "')";
        }

        // Записываем в БД новый хеш авторизации и IP
        mysqli_query($link, "UPDATE users SET hash='" . $hash . "' " . $insip . " WHERE id='" . $data['id'] . "'");

        // Ставим куки
        setcookie("id", $data['id'], time() + 60 * 60 * 24 * 30);
        setcookie("hash", $hash, time() + 60 * 60 * 24 * 30);

        // Переадресовываем браузер на страницу проверки нашего скрипта

    } else {
        $error = true;
    }
}
?>

<!DOCTYPE html>

<head>
    <title>Вход</title>
    <link rel="stylesheet" href="/coolAuth/styles.css">
</head>

<body>

<div class="window">

    <form method="POST">
        <input name="login" placeholder="Логин" type="text"><br>
        <input name="password" placeholder="Пароль" type="password"><br>
        <span>Не прикреплять к IP(не безопасно)</span> <input type="checkbox" name="not_attach_ip"><br>
        <input name="submit" type="submit" value="Войти">
    </form>

    <div class="error">
        <?=$error ? "Вы ввели неправильный логин/пароль" : ''?>
    </div>

    <div class="success">
        <?php
        if (!empty($success)) {
            echo 'Вход выполнен успешно!<br>';
            echo 'Переход на страницу проверки через 2 секунды';
            header("Refresh: 2; url=/coolAuth/check.php"); 
            exit();
        }
        ?>
    </div>
</div>

</body>