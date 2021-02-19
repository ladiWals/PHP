<?php

$step = 16;
$max = 256;

?>

<!DOCTYPE html>

<head>
	<title>Цветушка-красотушка</title>
	<style>
		body {
			font-size: 20px;
			text-align: center;
		}
	</style>
</head>

<body>
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