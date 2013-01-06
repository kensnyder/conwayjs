<!DOCTYPE html>
<html lang="en">
<head>
	<?php echo $this->Html->charset(); ?>
	<title>
		<?php echo $title_for_layout; ?>
	</title>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
	<script src="/game.js.php"></script>
	<link rel="stylesheet" href="/game.css.php" />
	<?php
		echo $this->fetch('meta');
		echo $this->fetch('css');
		echo $this->fetch('script');
	?>
</head>
<body>
	<div id="Viewport">
		<div id="Content">
			<?php echo $this->Session->flash()?>
			<?php echo $this->fetch('content')?>
		</div>
	</div>
</body>
</html>
