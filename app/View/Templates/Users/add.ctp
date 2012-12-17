<div class="users form">
<?php echo $this->Form->create('User'); ?>
	<fieldset>
		<legend><?php echo __('Add User'); ?></legend>
	<?php
		echo $this->Form->input('username');
		echo $this->Form->input('password', array('type'=>'password'));
		echo $this->Form->input('is_admin');
		echo $this->Form->input('is_active');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Users'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Game Shapes'), array('controller' => 'game_shapes', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Game Shape'), array('controller' => 'game_shapes', 'action' => 'add')); ?> </li>
	</ul>
</div>
