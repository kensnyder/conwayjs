<?php foreach ($shapes as $shape) { ?>
	<div class="shape">
		<img src="<?php echo h($shape['GameShape']['image_path'])?>" />
		<?php echo h($shape['GameShape']['name'])?>
		<a href="/game_shapes/spec/<?php echo h($shape['GameShape']['id'])?>.json">Use</a>
	</div>
<?php } ?>
