<div class="gameShapes view">
<h2><?php  echo __('Game Shape'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($gameShape['GameShape']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Game Shape Category'); ?></dt>
		<dd>
			<?php echo $this->Html->link($gameShape['GameShapeCategory']['name'], array('controller' => 'game_shape_categories', 'action' => 'view', $gameShape['GameShapeCategory']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Name'); ?></dt>
		<dd>
			<?php echo h($gameShape['GameShape']['name']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Desc'); ?></dt>
		<dd>
			<?php echo h($gameShape['GameShape']['desc']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Comments'); ?></dt>
		<dd>
			<?php echo h($gameShape['GameShape']['comments']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Link'); ?></dt>
		<dd>
			<?php echo h($gameShape['GameShape']['link']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Found Year'); ?></dt>
		<dd>
			<?php echo h($gameShape['GameShape']['found_year']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Found By'); ?></dt>
		<dd>
			<?php echo h($gameShape['GameShape']['found_by']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Image Path'); ?></dt>
		<dd>
			<?php echo h($gameShape['GameShape']['image_path']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Image Width'); ?></dt>
		<dd>
			<?php echo h($gameShape['GameShape']['image_width']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Image Height'); ?></dt>
		<dd>
			<?php echo h($gameShape['GameShape']['image_height']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Start Position'); ?></dt>
		<dd>
			<?php echo h($gameShape['GameShape']['start_position']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Size X'); ?></dt>
		<dd>
			<?php echo h($gameShape['GameShape']['size_x']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Size Y'); ?></dt>
		<dd>
			<?php echo h($gameShape['GameShape']['size_y']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Rulestring'); ?></dt>
		<dd>
			<?php echo h($gameShape['GameShape']['rulestring']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Game Rule'); ?></dt>
		<dd>
			<?php echo $this->Html->link($gameShape['GameRule']['name'], array('controller' => 'game_rules', 'action' => 'view', $gameShape['GameRule']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Format'); ?></dt>
		<dd>
			<?php echo h($gameShape['GameShape']['format']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Spec'); ?></dt>
		<dd>
			<?php echo h($gameShape['GameShape']['spec']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Period'); ?></dt>
		<dd>
			<?php echo h($gameShape['GameShape']['period']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created'); ?></dt>
		<dd>
			<?php echo h($gameShape['GameShape']['created']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('User Id'); ?></dt>
		<dd>
			<?php echo h($gameShape['GameShape']['user_id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created By'); ?></dt>
		<dd>
			<?php echo h($gameShape['GameShape']['created_by']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Game Shape'), array('action' => 'edit', $gameShape['GameShape']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Game Shape'), array('action' => 'delete', $gameShape['GameShape']['id']), null, __('Are you sure you want to delete # %s?', $gameShape['GameShape']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Game Shapes'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Game Shape'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Game Shape Categories'), array('controller' => 'game_shape_categories', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Game Shape Category'), array('controller' => 'game_shape_categories', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Game Rules'), array('controller' => 'game_rules', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Game Rule'), array('controller' => 'game_rules', 'action' => 'add')); ?> </li>
	</ul>
</div>
