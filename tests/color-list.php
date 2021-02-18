<?php

?>

<!DOCTYPE html>

<head>
	<title>Цветушка-красотушка</title>
	<style>
		body {
			font-size: 25px;
			text-align: center;
		}
	</style>
</head>

<body>
	<?php 
	for ($r = 0; $r < 16; $r += 2) { 
		for ($g = 0; $g < 16; $g += 2) {
			echo "<br>";
			for ($b = 0; $b < 16; $b += 2) {
	?>
				<span 
					style="color: #<?php printf("%x%x%x", $r, $g, $b); ?>">
					<?php 
					printf("%x%x%x", $r, $g, $b);
					?>
				</span>
	<?php } } }?>
</body>