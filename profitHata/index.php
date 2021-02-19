<?php
?>

<!DOCTYPE html>

<head>
	<title>Ипотека ВА</title>
	<link href="/profitHata/styles.css" rel="stylesheet">
	<link rel="SHORTCUT ICON" href="/profitHata/favicon.ico" type="image/x-icon">
</head>

<body>
	<h1>Заполните все данные и нажмите "рассчитать"</h1>

	<div class="mainForm">
		<form method="POST" action="/profitHata">
			<table>
				<input name="fullPrice" type="text" placeholder="Цена квартиры" size="20">
				<input name="firstPay" type="text" placeholder="Первый взнос" size="20">
				<input name="percent" type="text" placeholder="Процент по ипотеке" size="20">
				<input name="term" type="text" placeholder="Срок ипотеки" size="20">
				<input type="submit" name="submit" value="Рассчитать" size="20">
			</table>
		</form>
	</div>
	<h1>Результаты расчёта</h1>
	<div class="result">
	</div>
</body>