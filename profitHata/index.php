<?php

// Когда пришла форма
if (isset($_POST['submit'])) {
	extract($_POST, EXTR_PREFIX_ALL, 'pst');

	$credit = (int) $pst_fullPrice - (int) $pst_firstPay; // Сумма кредита
	$monthTerm = (int) $pst_term * 12; // Срок кредита в месяцах
	$monthRate = (int) $pst_percent / 12 / 100; // Ежемесячная ставка
	$generalRate = (1 + $monthRate) ** $monthTerm; // Общая ставка
	$monthPay = ($credit * $monthRate * $generalRate) / ($generalRate - 1); // Ежемесячный платёж
	$yearPay = $monthPay * 12; // Годовая выплата
	$fullPay = $yearPay * (int) $pst_term; // Общая выплата
	$overPay = $fullPay - (int) $pst_fullPrice; // Общая переплата по кредиту

	$suffix = ((int) $pst_term % 10 == 1) ? 'год' : (in_array((int) $pst_term % 10, [2, 3, 4]) ? 'года' : 'лет');
}

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

		<form method="POST" action="/profitHata/">
			<table>
				<input name="fullPrice" type="text" placeholder="Цена квартиры">
				<input name="firstPay" type="text" placeholder="Первый взнос">
				<input name="percent" type="text" placeholder="Процент по ипотеке">
				<input name="term" type="text" placeholder="Срок ипотеки">
				<input type="submit" name="submit" value="Рассчитать">
			</table>
		</form>

	</div>

	<h1>Результаты расчёта:</h1>

	<div class="result">
		<ul>

			<?php
			if (isset($_POST['submit'])) {
			?>
				<li>Цена квартиры: <span><?=$pst_fullPrice?></span></li>
				<li>Первый взнос: <span><?=$pst_firstPay?></span></li>
				<li>Процент по ипотеке: <span><?=$pst_percent?></span></li>
				<li>Срок ипотеки: <span><?=$pst_term?></span></li>
				<hr>
			<?php } ?>

			<li>Сумма кредита: <span><?=round($credit)?> &#8381</span></li>
			<li>Ежемесячный платёж: <span><?=round($monthPay)?> &#8381</span></li>
			<li>Годовой платёж: <span><?=round($yearPay)?> &#8381</span></li>
			<li>Общая выплата: <span><?=round($fullPay)?> &#8381</span></li>
			<li>Переплата по кредиту: <span><?=round($overPay)?> &#8381</span></li>
		</ul>
	</div>

</body>