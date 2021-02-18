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
			<ul>
				<li><input name="fullPrice" type="text" placeholder="Цена квартиры"></li>
				<li><input name="firstPay" type="text" placeholder="Первый взнос"></li>
				<li><input name="percent" type="text" placeholder="Процент по ипотеке"></li>
				<li><input name="term" type="text" placeholder="Срок ипотеки"></li>
			</ul>
		</form>
	</div>
	<h1>Результаты расчёта</h1>
	<div class="result">
	</div>
</body>