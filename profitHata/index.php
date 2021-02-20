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
	$overPay = $fullPay - ((int) $pst_fullPrice - (int) $pst_firstPay); // Общая переплата по кредиту
	$overPayPercent = $overPay / $fullPay * 100; // Процент переплаты

	$minSalary = $monthPay + $pst_monthSpend; // Необходимая зарплата
	$toFirstPayMonth = $pst_firstPay / ($pst_currentSalary- $pst_monthSpend - $pst_monthRent);

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
				<input name="fullPrice" type="text" placeholder="Цена квартиры" value="<?=isset($pst_fullPrice) ? $pst_fullPrice : ''?>">
				<input name="firstPay" type="text" placeholder="Первый взнос" value="<?=isset($pst_firstPay) ? $pst_firstPay : ''?>">
				<input name="percent" type="text" placeholder="Процент по ипотеке" value="<?=isset($pst_percent) ? $pst_percent : ''?>">
				<input name="term" type="text" placeholder="Срок ипотеки" value="<?=isset($pst_term) ? $pst_term : ''?>">
				<input name="monthSpend" type="text" placeholder="Траты на жизнь в месяц" value="<?=isset($pst_monthSpend) ? $pst_monthSpend : ''?>">
				<input name="monthRent" type="text" placeholder="Траты на съём до ипотеки" value="<?=isset($pst_monthRent) ? $pst_monthRent : ''?>">
				<input name="currentSalary" type="text" placeholder="Текущая зарплата" value="<?=isset($pst_currentSalary) ? $pst_currentSalary : ''?>">
				<input type="submit" name="submit" value="Рассчитать">
			</table>
		</form>

	</div>

	<?php
	if (isset($_POST['submit'])) {
	?>
		<h1>Введённые данные:</h1>

		<div class="initial">
			<ul>
				<li>Цена квартиры: <span><?=$pst_fullPrice?> &#8381</span></li>
				<li>Первый взнос: <span><?=$pst_firstPay?> &#8381</span></li>
				<li>Процент по ипотеке: <span><?=$pst_percent?> %</span></li>
				<li>Срок ипотеки: <span><?=$pst_term . ' ' . $suffix?></span></li>

				<li>Ежемесячные траты: <span><?=$pst_monthSpend?></span></li>
				<li>Аренда жилья до ипотеки: <span><?=$pst_monthRent?></span></li>
				<li>Текущая зарплата: <span><?=$pst_currentSalary?></span></li>
			</ul>
		</div>

		<h1>Результаты расчёта:</h1>
		<div class="result">
			<ul>
				<li>Сумма кредита: <span><?=round($credit)?> &#8381</span></li>
				<li>Ежемесячный платёж: <span><?=round($monthPay)?> &#8381</span></li>
				<li>Годовой платёж: <span><?=round($yearPay)?> &#8381</span></li>
				<li>Общая выплата: <span><?=round($fullPay)?> &#8381</span></li>
				<li>Переплата по кредиту: <span><?=round($overPay)?> &#8381</span></li>
				<li>Процент переплаты: <span><?=round($overPayPercent, 1)?> %</span></li>
				<li>Необходимая зарплата: <span><?=ceil($minSalary / 1000)?> К</span></li>
				<li>Месяцев копить на первый взнос: <span><?=round($toFirstPayMonth, 1)?></span></li>
			</ul>
		</div>
	<?php } ?>

</body>