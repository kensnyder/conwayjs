<div class="gameRules view">
<h2><?php  echo __('Game Rule'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($gameRule['GameRule']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Name'); ?></dt>
		<dd>
			<?php echo h($gameRule['GameRule']['name']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Description'); ?></dt>
		<dd>
			<?php echo h($gameRule['GameRule']['description']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Rulestring'); ?></dt>
		<dd>
			<?php echo h($gameRule['GameRule']['rulestring']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Type'); ?></dt>
		<dd>
			<?php echo h($gameRule['GameRule']['type']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Link'); ?></dt>
		<dd>
			<?php echo h($gameRule['GameRule']['link']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Sort'); ?></dt>
		<dd>
			<?php echo h($gameRule['GameRule']['sort']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created'); ?></dt>
		<dd>
			<?php echo h($gameRule['GameRule']['created']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Game Rule'), array('action' => 'edit', $gameRule['GameRule']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Game Rule'), array('action' => 'delete', $gameRule['GameRule']['id']), null, __('Are you sure you want to delete # %s?', $gameRule['GameRule']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Game Rules'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Game Rule'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Game Shapes'), array('controller' => 'game_shapes', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Game Shape'), array('controller' => 'game_shapes', 'action' => 'add')); ?> </li>
	</ul>
</div>
<div class="related">
	<h3><?php echo __('Related Game Shapes'); ?></h3>
	<?php if (!empty($gameRule['GameShape'])): ?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('Game Shape Category Id'); ?></th>
		<th><?php echo __('Name'); ?></th>
		<th><?php echo __('Desc'); ?></th>
		<th><?php echo __('Comments'); ?></th>
		<th><?php echo __('Link'); ?></th>
		<th><?php echo __('Found Year'); ?></th>
		<th><?php echo __('Found By'); ?></th>
		<th><?php echo __('Image Path'); ?></th>
		<th><?php echo __('Image Width'); ?></th>
		<th><?php echo __('Image Height'); ?></th>
		<th><?php echo __('Start Position'); ?></th>
		<th><?php echo __('Size X'); ?></th>
		<th><?php echo __('Size Y'); ?></th>
		<th><?php echo __('Rulestring'); ?></th>
		<th><?php echo __('Game Rule Id'); ?></th>
		<th><?php echo __('Format'); ?></th>
		<th><?php echo __('Spec'); ?></th>
		<th><?php echo __('Lifespan'); ?></th>
		<th><?php echo __('Created'); ?></th>
		<th><?php echo __('User Id'); ?></th>
		<th><?php echo __('Created By'); ?></th>
		<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php
		$i = 0;
		foreach ($gameRule['GameShape'] as $gameShape): ?>
		<tr>
			<td><?php echo $gameShape['id']; ?></td>
			<td><?php echo $gameShape['game_shape_category_id']; ?></td>
			<td><?php echo $gameShape['name']; ?></td>
			<td><?php echo $gameShape['desc']; ?></td>
			<td><?php echo $gameShape['comments']; ?></td>
			<td><?php echo $gameShape['link']; ?></td>
			<td><?php echo $gameShape['found_year']; ?></td>
			<td><?php echo $gameShape['found_by']; ?></td>
			<td><?php echo $gameShape['image_path']; ?></td>
			<td><?php echo $gameShape['image_width']; ?></td>
			<td><?php echo $gameShape['image_height']; ?></td>
			<td><?php echo $gameShape['start_position']; ?></td>
			<td><?php echo $gameShape['size_x']; ?></td>
			<td><?php echo $gameShape['size_y']; ?></td>
			<td><?php echo $gameShape['rulestring']; ?></td>
			<td><?php echo $gameShape['game_rule_id']; ?></td>
			<td><?php echo $gameShape['format']; ?></td>
			<td><?php echo $gameShape['spec']; ?></td>
			<td><?php echo $gameShape['lifespan']; ?></td>
			<td><?php echo $gameShape['created']; ?></td>
			<td><?php echo $gameShape['user_id']; ?></td>
			<td><?php echo $gameShape['created_by']; ?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'game_shapes', 'action' => 'view', $gameShape['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'game_shapes', 'action' => 'edit', $gameShape['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'game_shapes', 'action' => 'delete', $gameShape['id']), null, __('Are you sure you want to delete # %s?', $gameShape['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Game Shape'), array('controller' => 'game_shapes', 'action' => 'add')); ?> </li>
		</ul>
	</div>
</div>
