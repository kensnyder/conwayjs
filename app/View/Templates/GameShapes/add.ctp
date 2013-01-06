<div class="gameShapes form">
<?php echo $this->Form->create('GameShape'); ?>
	<fieldset>
		<legend><?php echo __('Add Game Shape'); ?></legend>
	<?php
		echo $this->Form->input('game_shape_category_id');
		echo $this->Form->input('name');
		echo $this->Form->input('desc');
		echo $this->Form->input('comments');
		echo $this->Form->input('link');
		echo $this->Form->input('found_year');
		echo $this->Form->input('found_by');
		echo $this->Form->input('image_path');
		echo $this->Form->input('image_width');
		echo $this->Form->input('image_height');
		echo $this->Form->input('start_position');
		echo $this->Form->input('size_x');
		echo $this->Form->input('size_y');
		echo $this->Form->input('rulestring');
		echo $this->Form->input('game_rule_id');
		echo $this->Form->input('format');
		echo $this->Form->input('spec');
		echo $this->Form->input('period');
		echo $this->Form->input('user_id');
		echo $this->Form->input('created_by');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Game Shapes'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Game Shape Categories'), array('controller' => 'game_shape_categories', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Game Shape Category'), array('controller' => 'game_shape_categories', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Game Rules'), array('controller' => 'game_rules', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Game Rule'), array('controller' => 'game_rules', 'action' => 'add')); ?> </li>
	</ul>
</div>
