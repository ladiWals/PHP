<?php

// Сравнительная характеристка

// Файл хранения структуры формы
include_once($_SERVER['DOCUMENT_ROOT'] . '/profitHata/formData.php');

// Когда пришла форма
if (isset($_POST['submit'])) {
	extract($_POST, EXTR_PREFIX_ALL, 'pst');

	// Вычисления основных характеристик кредитов по обеим программам
	for($i = 1; $i <= 2; $i++) {
		${'credit_' . $i} = (int) ${'pst_fullPrice_' . $i} - (int) ${'pst_firstPay_' . $i}; // Сумма кредита
		${'monthTerm_' . $i} = (int) ${'pst_term_' . $i} * 12; // Срок кредита в месяцах
		${'monthRate_' . $i} = (int) ${'pst_percent_' . $i} / 12 / 100; // Ежемесячная ставка
		${'generalRate_' . $i} = (1 + ${'monthRate_' . $i}) ** ${'monthTerm_' . $i}; // Общая ставка
		${'monthPay_' . $i} = (${'credit_' . $i} * ${'monthRate_' . $i} * ${'generalRate_' . $i}) / (${'generalRate_' . $i} - 1); // Ежемесячный платёж
		${'yearPay_' . $i} = ${'monthPay_' . $i} * 12; // Годовая выплата
		${'fullPay_' . $i} = ${'yearPay_' . $i} * (int) ${'pst_term_' . $i}; // Общая выплата
		${'overPay_' . $i} = ${'fullPay_' . $i} - ((int) ${'pst_fullPrice_' . $i} - (int) ${'pst_firstPay_' . $i}); // Общая переплата по кредиту
		${'overPayPercent_' . $i} = ${'overPay_' . $i} / ${'fullPay_' . $i} * 100; // Процент переплаты
		${'firstPayPercent_' . $i} = ${'pst_firstPay_' . $i} / ${'pst_fullPrice_' . $i} * 100;

		${'minSalary_' . $i} = ${'monthPay_' . $i} + ${'pst_monthSpend_' . $i}; // Необходимая зарплата
		${'toFirstPayMonth_' . $i} = ${'pst_firstPay_' . $i} / (${'pst_currentSalary_' . $i} - ${'pst_monthSpend_' . $i} - ${'pst_monthRent_' . $i}); // Сколько месяцев потребуется, чтобы накопить на первый взнос

		${'suffix_' . $i} = ((int) ${'pst_term_' . $i} % 10 == 1) ? 'год' : (in_array((int) ${'pst_term_' . $i} % 10, [2, 3, 4]) ? 'года' : 'лет');
	}

	// Вычисления разниц начальных данных
	foreach ($formList as $item) {
		${'diff_' . $item['name']} = ${'pst_' . $item['name'] . '_2'} - ${'pst_' . $item['name'] . '_1'};
	}

	// Вычисляю разницу выходных данных
	foreach (['credit', 'monthPay', 'yearPay', 'fullPay', 'overPay', 'overPayPercent', 'minSalary', 'toFirstPayMonth'] as $item) {
		${'diff_' . $item} = ${$item . '_2'} - ${$item . '_1'};
	}
}


// Функция для добавления отступов между тысячами
function zeroSpace($number, $signed = false) 
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
	return $signed ? addSign($explodedNum) : $explodedNum;
}

// Функция добавляет знак к числу
function addSign($str) {
	$str = trim($str);
	return ($str[0] !== '-') ? ('+' . $str) : ($str);
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

	<center>
		<div class="compare">

		<form method="POST" action="/profitHata/compare.php">
			<table>

				<?php

				// Автоматизированный вывод формы на основе массива из подключённого файла
				foreach($formList as $item) { ?>
				 	<tr>
				 		<td>
				 			<div class="hint"><?=$item['label'] . ' 1'?></div>
				 			<input name="<?=$item['name'] . '_1'?>" type="text" value="<?=isset(${'pst_' . $item['name'] . '_1'}) ? ${'pst_' . $item['name'] . '_1'} : ''?>">
				 		</td>
				 		<td>
				 			<div class="hint"><?=$item['label'] . '2'?></div>
				 			<input name="<?=$item['name'] . '_2'?>" type="text" value="<?=isset(${'pst_' . $item['name'] . '_2'}) ? ${'pst_' . $item['name'] . '_2'} : ''?>">
				 		</td>
				 	</tr>
				<?php } ?>

			</table>
			<input type="submit" name="submit" value="Рассчитать">
		</form>

		</div>
	</center>

	<?php

	// Вывод блока обработанных начальных данных 
	if (isset($_POST['submit'])) {
	?>
		<h1>Введённые данные:</h1>

		<center>
			<div class="initial">
				<table>
					<tr>
						<td>Цена квартиры:</td>
						<td><span><?=zeroSpace($pst_fullPrice_1)?> &#8381</span></td>
						<td><img src="/profitHata/images/<?=$diff_fullPrice < 0 ? 'not_' : ''?>stonks_white.ico" width="35" height="25"> <?=zeroSpace($diff_fullPrice, true)?></td>
						<td><span><?=zeroSpace($pst_fullPrice_2)?> &#8381</span></td>
					</tr>
					<tr>
						<td>Первый взнос:</td>
						<td><span><?=zeroSpace($pst_firstPay_1)?> &#8381 <?='(' . round($firstPayPercent_1, 1) . '%)'?></span></td>
						<td><img src="/profitHata/images/<?=$diff_firstPay < 0 ? 'not_' : ''?>stonks_white.ico" width="35" height="25"> <?=zeroSpace($diff_firstPay, true)?></td>
						<td><span><?=zeroSpace($pst_firstPay_2)?> &#8381 <?='(' . round($firstPayPercent_2, 1) . '%)'?></span></td>
					</tr>
					<tr>
						<td>Процент по ипотеке:</td> 
						<td><span><?=$pst_percent_1?> %</span></td>
						<td><img src="/profitHata/images/<?=$diff_percent < 0 ? 'not_' : ''?>stonks_white.ico" width="35" height="25"> <?=zeroSpace($diff_percent, true)?></td>
						<td><span><?=$pst_percent_2?> %</span></td>
					</tr>
					<tr>
						<td>Срок ипотеки:</td>
						<td><span><?=$pst_term_1 . ' ' . $suffix_1?></span></td>
						<td><img src="/profitHata/images/<?=$diff_firstPay < 0 ? 'not_' : ''?>stonks_white.ico" width="35" height="25"> <?=zeroSpace($diff_firstPay, true)?></td>
						<td><span><?=$pst_term_2 . ' ' . $suffix_2?></span></td>
					</tr>
					<tr>
						<td>Ежемесячные траты:</td>
						<td><span><?=zeroSpace($pst_monthSpend_1)?> &#8381</span></td>
						<td><img src="/profitHata/images/<?=$diff_monthSpend < 0 ? 'not_' : ''?>stonks_white.ico" width="35" height="25"> <?=zeroSpace($diff_monthSpend, true)?></td>
						<td><span><?=zeroSpace($pst_monthSpend_2)?> &#8381</span></td>
					</tr>
					<tr>
						<td>Аренда жилья до ипотеки:</td>
						<td><span><?=zeroSpace($pst_monthRent_1)?> &#8381</span></td>
						<td><img src="/profitHata/images/<?=$diff_monthRent < 0 ? 'not_' : ''?>stonks_white.ico" width="35" height="25"> <?=zeroSpace($diff_monthRent, true)?></td>
						<td><span><?=zeroSpace($pst_monthRent_2)?> &#8381</span></td>
					</tr>
					<tr>
						<td>Текущая зарплата:</td>
						<td><span><?=zeroSpace($pst_currentSalary_1)?> &#8381</span></td>
						<td><img src="/profitHata/images/<?=$diff_currentSalary < 0 ? 'not_' : ''?>stonks_white.ico" width="35" height="25"> <?=zeroSpace($diff_currentSalary, true)?></td>
						<td><span><?=zeroSpace($pst_currentSalary_2)?> &#8381</span></td>
				</table>
			</div>
		</center>

		<h1>Результаты расчёта:</h1>
		<center>
			<div class="result">
				<table>
					<tr>
						<td>Сумма кредита: </td>
						<td><span><?=zeroSpace(round($credit_1))?> &#8381</span></td>
						<td><img src="/profitHata/images/<?=$diff_credit < 0 ? 'not_' : ''?>stonks_white.ico" width="35" height="25"> <?=zeroSpace(round($diff_credit), true)?></td>
						<td><span><?=zeroSpace(round($credit_2))?> &#8381</span></td>
					</tr>
					<tr>
						<td>Ежемесячный платёж: </td>
						<td><span><?=zeroSpace(round($monthPay_1))?> &#8381</span></td>
						<td><img src="/profitHata/images/<?=$diff_monthPay < 0 ? 'not_' : ''?>stonks_white.ico" width="35" height="25"> <?=zeroSpace(round($diff_monthPay), true)?></td>
						<td><span><?=zeroSpace(round($monthPay_2))?> &#8381</span></td>
					</tr>
					<tr>
						<td>Годовой платёж: </td>
						<td><span><?=zeroSpace(round($yearPay_1))?> &#8381</span></td>
						<td><img src="/profitHata/images/<?=$diff_yearPay < 0 ? 'not_' : ''?>stonks_white.ico" width="35" height="25"> <?=zeroSpace(round($diff_yearPay), true)?></td>
						<td><span><?=zeroSpace(round($yearPay_2))?> &#8381</span></td>
					</tr>
					<tr>
						<td>Общая выплата: </td>
						<td><span><?=zeroSpace(round($fullPay_1))?> &#8381</span></td>
						<td><img src="/profitHata/images/<?=$diff_fullPay < 0 ? 'not_' : ''?>stonks_white.ico" width="35" height="25"> <?=zeroSpace(round($diff_fullPay), true)?></td>
						<td><span><?=zeroSpace(round($fullPay_2))?> &#8381</span></td>
					</tr>
					<tr>
						<td>Переплата по кредиту: </td>
						<td><span><?=zeroSpace(round($overPay_1))?> &#8381</span></td>
						<td><img src="/profitHata/images/<?=$diff_overPay < 0 ? 'not_' : ''?>stonks_white.ico" width="35" height="25"> <?=zeroSpace(round($diff_overPay), true)?></td>
						<td><span><?=zeroSpace(round($overPay_2))?> &#8381</span></td>
					</tr>
					<tr>
						<td>Процент переплаты: </td>
						<td><span><?=round($overPayPercent_1, 1)?>%</span></td>
						<td><img src="/profitHata/images/<?=$diff_overPayPercent < 0 ? 'not_' : ''?>stonks_white.ico" width="35" height="25"> <?=zeroSpace(round($diff_overPayPercent, 1), true)?></td>
						<td><span><?=round($overPayPercent_2, 1)?>%</span></td>
					</tr>
					<tr>
						<td>Необходимая зарплата: </td>
						<td><span><?=ceil($minSalary_1 / 1000)?>K</span></td>
						<td><img src="/profitHata/images/<?=$diff_minSalary < 0 ? 'not_' : ''?>stonks_white.ico" width="35" height="25"> <?=zeroSpace(round($diff_minSalary / 1000), true)?></td>
						<td><span><?=ceil($minSalary_2 / 1000)?>K</span></td>
					</tr>
					<tr>
						<td>Месяцев копить на первый взнос: </td>
						<td><span><?=round($toFirstPayMonth_1, 1)?></span></td>
						<td><img src="/profitHata/images/<?=$diff_toFirstPayMonth < 0 ? 'not_' : ''?>stonks_white.ico" width="35" height="25"> <?=zeroSpace(round($diff_toFirstPayMonth, 1), true)?></td>
						<td><span><?=round($toFirstPayMonth_2, 1)?></span></td>
					</tr>
				</table>
			</div>
		</center>
	<?php } ?>

</body>