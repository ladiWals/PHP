<?php 

// Страница регситрации нового пользователя

require_once($_SERVER['DOCUMENT_ROOT'] . '/coolAuth/connection.php');

// Подключение к БД
$link = mysqli_connect($host, $user, $password, $database) or die("Ошибка " . mysqli_error($link));

if(isset($_POST['submit'])) {

    // Объявляю массив ошибок
    $err = array();

    // Проверям логин
    if(!preg_match("/^[a-zA-Z0-9]+$/", $_POST['login'])) {
        $err[] = "Логин может состоять только из букв английского алфавита и цифр";
    }

    if(strlen($_POST['login']) < 3 or strlen($_POST['login']) > 30) {
        $err[] = "Логин должен быть не меньше 3-х символов и не больше 30";
    }

    // Проверяем, не сущестует ли пользователя с таким именем
    $query = mysqli_query($link, "SELECT COUNT(id) FROM users WHERE login='" . mysqli_real_escape_string($_POST['login']) . "'");

    if(mysql_result($query, 0) > 0) {
        $err[] = "Пользователь с таким логином уже существует в базе данных";
    }

    // Если нет ошибок, то добавляем в БД нового пользователя
    if(count($err) == 0) {
        $login = $_POST['login'];

        // Убираем лишние пробелы и делаем двойное md5-шифрование
        $password = md5(md5(trim($_POST['password'])));

        mysqli_query($link, "INSERT INTO users SET login='" . $login . "', password='" . $password . "'");

        header("Location: login.php"); exit();
    } else {
        print "<b>При регистрации произошли следующие ошибки:</b><br>";

        foreach($err as $error) {
            print $error . "<br>";
        }
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

</body>