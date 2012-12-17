<div class="gameRules form">
<?php echo $this->Form->create('GameRule'); ?>
	<fieldset>
		<legend><?php echo __('Add Game Rule'); ?></legend>
	<?php
		echo $this->Form->input('name');
		echo $this->Form->input('description');
		echo $this->Form->input('rulestring');
		echo $this->Form->input('type');
		echo $this->Form->input('link');
		echo $this->Form->input('sort');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Game Rules'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Game Shapes'), array('controller' => 'game_shapes', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Game Shape'), array('controller' => 'game_shapes', 'action' => 'add')); ?> </li>
	</ul>
</div>
