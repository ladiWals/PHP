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
	$firstPayPercent = $pst_firstPay / $pst_fullPrice * 100; // Процент первого зноса в общем кредите

	$suffix = ((int) $pst_term % 10 == 1) ? 'год' : (in_array((int) $pst_term % 10, [2, 3, 4]) ? 'года' : 'лет');
}

function explodeThousand($number) 
{
	$explodedNum = '';
	$number	= strrev((string) $number);
	for($i = 0; $i < strlen($number); $i++) {
		$explodedNum .= $number[$i];
		if(($i + 1) % 3 === 0) {
			$explodedNum .= ' ';
		}
	}
	$explodedNum = strrev($explodedNum);
	return $explodedNum;
}

?>

<!DOCTYPE html>

<head>
	<title>Простейший расчёт</title>
	<link href="/profitHata/styles.css" rel="stylesheet">
	<link rel="SHORTCUT ICON" href="/profitHata/favicon.ico" type="image/x-icon">
</head>

<body>
	<h1>Заполните все данные и нажмите "рассчитать"</h1>

	<div class="mainForm">
		<form method="POST" action="/profitHata/simple.php">
			<table>
				<div class="hint">Цена квартиры</div>
				<input name="fullPrice" type="text" value="<?=isset($pst_fullPrice) ? $pst_fullPrice : ''?>">
				<div class="hint">Первый взнос</div>
				<input name="firstPay" type="text" value="<?=isset($pst_firstPay) ? $pst_firstPay : ''?>">
				<div class="hint">Процент по ипотеке</div>
				<input name="percent" type="text" value="<?=isset($pst_percent) ? $pst_percent : ''?>">
				<div class="hint">Срок ипотеки в годах</div>
				<input name="term" type="text" value="<?=isset($pst_term) ? $pst_term : ''?>">
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
				<li>Цена квартиры: <span><?=explodeThousand($pst_fullPrice)?> &#8381</span></li>
				<li>Первый взнос: <span><?=explodeThousand($pst_firstPay)?> &#8381 <?='( ' . round($firstPayPercent, 1) . '% )'?></span></li>
				<li>Процент по ипотеке: <span><?=$pst_percent?> %</span></li>
				<li>Срок ипотеки: <span><?=$pst_term . ' ' . $suffix?></span></li>
			</ul>
		</div>

		<h1>Результаты расчёта:</h1>
		<div class="result">
			<ul>
				<li>Сумма кредита: <span><?=explodeThousand(round($credit))?> &#8381</span></li>
				<li>Ежемесячный платёж: <span><?=explodeThousand(round($monthPay))?> &#8381</span></li>
				<li>Годовой платёж: <span><?=explodeThousand(round($yearPay))?> &#8381</span></li>
				<li>Общая выплата: <span><?=explodeThousand(round($fullPay))?> &#8381</span></li>
				<li>Переплата по кредиту: <span><?=explodeThousand(round($overPay))?> &#8381</span></li>
				<li>Процент переплаты: <span><?=round($overPayPercent, 1)?> %</span></li>
			</ul>
		</div>
	<?php } ?>

</body>