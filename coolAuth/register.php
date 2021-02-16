<?php 

// Страница регситрации нового пользователя

require_once($_SERVER['DOCUMENT_ROOT'] . '/coolAuth/connection.php');

// Подключение к БД
$link = mysqli_connect($host, $user, $password, $database) or die("Ошибка " . mysqli_error($link));

// Если пришла форма
if(isset($_POST['submit'])) {

    // Объявляю массив ошибок
    $err = array();

    // Достаю логин из POST
    $login = $_POST['login'];

    // Проверям логина на допустимость символов
    if(!preg_match("/^[a-zA-Z0-9]+$/", $login)) {
        $err[] = "Логин может состоять только из букв английского алфавита и цифр";
    }

    // Проверка логина на длину
    if(strlen($_POST['login']) < 3 or strlen($login) > 30) {
        $err[] = "Логин должен быть не меньше 3-х символов и не больше 30";
    }

    // Проверяем, не сущестует ли пользователя с таким именем
    $query = mysqli_fetch_assoc(mysqli_query($link, "SELECT COUNT(id) FROM users WHERE login='" . mysqli_real_escape_string($link, $login) . "'"));

    if($query["COUNT(id)"] != '0') {
        $err[] = "Пользователь с таким логином уже существует в базе данных";
    }

    // Если нет ошибок, то добавляем в БД нового пользователя
    if(count($err) == 0) {

        // Убираем лишние пробелы и делаем двойное md5-шифрование
        $password = md5(md5(trim($_POST['password'])));

        mysqli_query($link, "INSERT INTO users SET login='" . $login . "', password='" . $password . "'");

        // header("Location: login.php"); exit();
    } 
}

// Закрываем соединение с БД
mysqli_close($link);
?>

<!DOCTYPE html>

<head>
    <title>Регистрация</title>
    <link rel="stylesheet" href="/styles.css">
</head>

<body>

    <form method="POST">
        Логин <input name="login" type="text"><br>
        Пароль <input name="password" type="password"><br>
        <input name="submit" type="submit" value="Зарегистрироваться">
    </form>

    <?php
    if(count($err) != 0) { ?>
        <ul>При регистрации произошли следующие ошибки:</ul>
        <?php
        foreach($err as $error) { ?>
            <li><?=$error?></li>
        <?php }
    } ?>

</body>