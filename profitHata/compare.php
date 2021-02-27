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
	$firstPayPercent = $pst_firstPay / $pst_fullPrice * 100;

	$minSalary = $monthPay + $pst_monthSpend; // Необходимая зарплата
	$toFirstPayMonth = $pst_firstPay / ($pst_currentSalary- $pst_monthSpend - $pst_monthRent); // Сколько месяцев потребуется, чтобы накопить на первый взнос

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
	<title>Сравненительный расчёт</title>
	<link href="/profitHata/styles.css" rel="stylesheet">
	<link rel="SHORTCUT ICON" href="/profitHata/favicon.ico" type="image/x-icon">
</head>

<body>
	<h1>Заполните все данные и нажмите "рассчитать"</h1>

	<div class="compare">

		<form method="POST" action="/profitHata/advanced.php">
			<table>

				<tr>
					<td>
						<div class="hint">Цена квартиры-1</div>
						<input name="fullPrice" type="text" value="<?=isset($pst_fullPrice) ? $pst_fullPrice : ''?>">
					</td>
					<td>
						<div class="hint">Цена квартиры-2</div>
						<input name="fullPrice" type="text" value="<?=isset($pst_fullPrice) ? $pst_fullPrice : ''?>">
					</td>
				</tr>

				<tr>
					<td>
				<div class="hint">Первый взнос-1</div>
				<input name="firstPay" type="text" value="<?=isset($pst_firstPay) ? $pst_firstPay : ''?>">
					</td>
					<td>
				<div class="hint">Первый взнос-2</div>
				<input name="firstPay" type="text" value="<?=isset($pst_firstPay) ? $pst_firstPay : ''?>">
					</td>
				</tr>

				<tr>
					<td>
				<div class="hint">Процент по ипотеке-1</div>
				<input name="percent" type="text" value="<?=isset($pst_percent) ? $pst_percent : ''?>">
					</td>
					<td>
						<div class="hint">Процент по ипотеке-2</div>
						<input name="percent" type="text" value="<?=isset($pst_percent) ? $pst_percent : ''?>">
					</td>
				</tr>
				
				<tr>
					<td>
				<div class="hint">Срок ипотеки в годах-1</div>
				<input name="term" type="text" value="<?=isset($pst_term) ? $pst_term : ''?>">
					</td>
					<td>
						<div class="hint">Срок ипотеки в годах-2</div>
						<input name="term" type="text" value="<?=isset($pst_term) ? $pst_term : ''?>">
					</td>
				</tr>
				
				<tr>
					<td>
				<div class="hint">Ежемесячные траты на жизнь-1</div>
				<input name="monthSpend" type="text" value="<?=isset($pst_monthSpend) ? $pst_monthSpend : ''?>">
					</td>
					<td>
						<div class="hint">Ежемесячные траты на жизнь-2</div>
						<input name="monthSpend" type="text" value="<?=isset($pst_monthSpend) ? $pst_monthSpend : ''?>">
					</td>
				</tr>
				
				<tr>
					<td>
				<div class="hint">Ежемесячные траты на съём до ипотеки-1</div>
				<input name="monthRent" type="text" value="<?=isset($pst_monthRent) ? $pst_monthRent : ''?>">
					</td>
					<td>
				<div class="hint">Ежемесячные траты на съём до ипотеки-2</div>
				<input name="monthRent" type="text" value="<?=isset($pst_monthRent) ? $pst_monthRent : ''?>">
					</td>
				</tr>

				<tr>
					<td>
				<div class="hint">Текущая зарплата-1</div>
				<input name="currentSalary" type="text" value="<?=isset($pst_currentSalary) ? $pst_currentSalary : ''?>">
					</td>
					<td>
				<div class="hint">Текущая зарплата-2</div>
				<input name="currentSalary" type="text" value="<?=isset($pst_currentSalary) ? $pst_currentSalary : ''?>">
					</td>

			</table>
			<input type="submit" name="submit" value="Рассчитать">
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

				<li>Ежемесячные траты: <span><?=explodeThousand($pst_monthSpend)?></span></li>
				<li>Аренда жилья до ипотеки: <span><?=explodeThousand($pst_monthRent)?></span></li>
				<li>Текущая зарплата: <span><?=explodeThousand($pst_currentSalary)?></span></li>
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
				<li>Необходимая зарплата: <span><?=ceil($minSalary / 1000)?> К</span></li>
				<li>Месяцев копить на первый взнос: <span><?=round($toFirstPayMonth, 1)?></span></li>
			</ul>
		</div>
	<?php } ?>

</body>