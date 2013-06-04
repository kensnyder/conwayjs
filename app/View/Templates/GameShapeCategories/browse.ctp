<form class="item-box category search" method="post" target="/game_shapes/search">
	<div class="item-column">
		<input type="text" name="term" value="" placeholder="Search for a shape..." />
		<input type="submit" name="go" value="Search" />
	</div>
	<div class="item-column item-nav">&nbsp;</div>
</form>
<?php foreach (Set::map($categories) as $c) { ?>
	<div class="item-box category">
		<div class="item-column">
			<h2 class="item-title">
				<?php echo h($c->name)?>
			</h2>
			<p class="item-description">
				<?php echo h($c->description)?>
				<?php if ($c->link) { ?>
					<a target="_blank" href="<?php echo h($c->link)?>">Wiki</a>
				<?php } ?>
			</p>
		</div>
		<div class="item-column item-nav">
			<a class="item-nav-link" href="/game_shapes/browse/<?php echo h($c->id)?>">
				<img src="/img/nav-next.png" width="15" height="25" alt="&gt;" />
			</a>
		</div>
	</div>
<?php } ?>
