<?php
$host = 'localhost'; // адрес сервера 
$database = 'test'; // имя базы данных
$user = 'root'; // имя пользователя
$password = 'VLadmin'; // пароль
?>

<!DOCTYPE html>

<head>
	<meta charset="utf-8">
	<title>PHP</title>
	<!-- <link rel="SHORTCUT ICON" href="favicon.ico" type="image/x-icon"> -->
	<link rel="stylesheet" href="/styles.css">
</head>

<body>
	<h1>Тестики</h1>

	<pre>
		<?php
		$query = "SELECT * FROM calculator_logs";

		// Подключение к БД
		$link = mysqli_connect($host, $user, $password, $database) or die("Ошибка " . mysqli_error($link));

		$result = mysqli_query($link, $query) or die("Ошибка " . mysqli_error($link)); 
		for ($data = []; $row = mysqli_fetch_assoc($result); $data[] = $row);

		if($result)
		{
		    echo "Выполнение запроса прошло успешно";
		    var_dump($data);
		}

	    // закрываем подключение
		mysqli_close($link);
		?>
	</pre>

</body>