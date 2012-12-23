<?php foreach ($shapes as $shape) { ?>
	<div class="item-box shape">
		<div class="item-column shape-thumb">
			<img src="<?php echo h($shape['GameShape']['image_path'])?>" />
		</div>
		<div class="item-column shape-details">
			<h2 class="item-title"><?php echo h($shape['GameShape']['name'])?></h2>
			<p class="item-description"><?php echo h(trim($shape['GameShape']['desc'] . ' ' . $shape['GameShape']['comments']))?></p>
			<?php if($shape['GameShape']['link']) { ?>
				<p class="item-description item-link"><a href="<?php echo h($shape['GameShape']['link'])?>">Wiki</a></p>
			<?php } ?>
		</div>
		<div class="item-column item-nav">
			<a class="item-nav-link" href="/game_shapes/spec/<?php echo h($shape['GameShape']['id'])?>.json">
				<img src="/img/nav-next.png" width="15" height="25" alt="&gt;" />
			</a>
		</div>
	</div>
<?php } ?>
