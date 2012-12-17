<div class="gameShapeCategories form">
<?php echo $this->Form->create('GameShapeCategory'); ?>
	<fieldset>
		<legend><?php echo __('Edit Game Shape Category'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('name');
		echo $this->Form->input('description');
		echo $this->Form->input('link');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('GameShapeCategory.id')), null, __('Are you sure you want to delete # %s?', $this->Form->value('GameShapeCategory.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Game Shape Categories'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Game Shapes'), array('controller' => 'game_shapes', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Game Shape'), array('controller' => 'game_shapes', 'action' => 'add')); ?> </li>
	</ul>
</div>
