<?php

$step = 16;
$max = 256;

if (isset($_POST['submit'])) {
	var_dump($_POST);
}

?>

<!DOCTYPE html>

<head>
	<title>Цветушка-красотушка</title>
	<style>

		body {
			font-size: 20px;
			text-align: center;
		}

		div {
			font-size: 24px;
		}

		select {
			font-size: 20px;
			margin: 20px;
		}

		input[type="submit"] {
			font-size: 20px;
			width: auto;
		}

		.red {
			background-color: #f77;
			width: 270px;
			border-radius: 10px;
		}

		.green {
			background-color: #7f7;
			width: 270px;
			border-radius: 10px;
		}

		.blue {
			background-color: #77f;
			width: 270px;
			border-radius: 10px;
		}

	</style>
</head>

<body>
	<div class="main">
		<form method="POST" action="/tests/color-list.php">
			<table>
				<tr>
					<td>
						<div class="red">
							<select size="8" name="redStep">
								<?php for($i = 1; $i <= 128; $i *= 2) {?>
								<option value="<?=$i?>"><?=$i?></option>
							<?php } ?>
						</div>
					</td>
					<td>
						<div class="green">
							<select size="8" name="greenStep">
								<?php for($i = 1; $i <= 128; $i *= 2) {?>
								<option value="<?=$i?>"><?=$i?></option>
							<?php } ?>
						</div>
					</td>
					<td>
						<div class="blue">
							<select size="8" name="blueStep">
								<?php for($i = 1; $i <= 128; $i *= 2) {?>
								<option value="<?=$i?>"><?=$i?></option>
							<?php } ?>
						</div>
					</td>
				</tr>
			</table>
			<input type="submit" name="submit" value="Перестроить цветушку-красотушку">
		</form>
	</div>

	<?php 
	for ($r = 0; $r < $max; $r += $step) { 
		for ($g = 0; $g < $max; $g += $step) {
			echo "<br>";
			for ($b = 0; $b < $max; $b += $step) {
				$hexString = sprintf("%02X%02X%02X", $r, $g, $b);
	?>
				<span style="color: #<?=$hexString?>">
					<?=$hexString?>
				</span>
	<?php } } } ?>
</body>