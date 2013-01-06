<h1><?php echo h($h1)?></h1>
<?php echo $this->Form->create('GameShape'); ?>
	<?php
		echo $this->Form->input('game_shape_category_id');
		echo $this->Form->input('name');
		echo $this->Form->input('desc');
		echo $this->Form->input('link');
		echo $this->Form->input('found_year');
		echo $this->Form->input('found_by');
		echo $this->Form->input('start_position');
		echo $this->Form->input('rulestring');
		echo $this->Form->input('game_rule_id');
		echo $this->Form->input('format');
		echo $this->Form->input('spec');
		echo $this->Form->input('period');
		echo $this->Form->input('user_id');
		echo $this->Form->input('created_by');
	?>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
