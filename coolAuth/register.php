<?php 

// Страница регситрации нового пользователя

require_once($_SERVER['DOCUMENT_ROOT'] . '/coolAuth/connection.php');

// Подключение к БД
$link = mysqli_connect($host, $user, $password, $database) or die("Ошибка " . mysqli_error($link));

// Если пришла форма
if (isset($_POST['submit'])) {

    // Объявляю массив ошибок
    $err = array();

    // Достаю логин из POST
    $login = $_POST['login'];

    // Проверям логина на допустимость символов
    if (!preg_match("/^[a-zA-Z0-9]+$/", $login)) {
        $err[] = "Логин может состоять только из букв английского алфавита и цифр";
    }

    // Проверка логина на длину
    if (strlen($_POST['login']) < 3 or strlen($login) > 30) {
        $err[] = "Логин должен быть не меньше 3-х символов и не больше 30";
    }

    // Проверяем, не сущестует ли пользователя с таким именем
    $query = mysqli_fetch_assoc(mysqli_query($link, "SELECT COUNT(id) FROM users WHERE login='" . mysqli_real_escape_string($link, $login) . "'"));

    if ($query["COUNT(id)"] != '0') {
        $err[] = "Пользователь с таким логином уже существует в базе данных";
    }

    // Если нет ошибок, то добавляем в БД нового пользователя
    if (count($err) == 0) {

        // Убираем лишние пробелы и делаем двойное md5-шифрование
        $password = md5(md5(trim($_POST['password'])));

        $succReg = mysqli_query($link, "INSERT INTO users SET login='" . $login . "', password='" . $password . "'");
    } 
}

// Закрываем соединение с БД
mysqli_close($link);
?>

<!DOCTYPE html>

<head>
    <title>Регистрация</title>
    <link rel="stylesheet" href="/coolAuth//styles.css">
</head>

<body>

    <div class="window">
        <form method="POST">
            <input name="login" placeholder="Логин" type="text"><br>
            <input name="password" placeholder="Пароль" type="password"><br>
            <input name="submit" type="submit" value="Зарегистрироваться">
        </form>

        <div class="error">
            <?php
            if (count($err) != 0) { ?>
                <ul>При регистрации произошли следующие ошибки:</ul>
                <?php
                foreach ($err as $error) { ?>
                    <li><?=$error?></li>
                <?php }
            } ?>
        </div>

        <div class="success">
            <?php
            if ($succReg == true) {
                echo 'Вы успешно зарегистрировались!<br>';
                echo 'Переход на страницу входа через 2 секунды';
                header("Refresh: 2; url=/coolAuth/login.php"); 
                exit();
            }
            ?>
        </div>
    </div>

</body>