<div class="users view">
<h2><?php  echo __('User'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($user['User']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Username'); ?></dt>
		<dd>
			<?php echo h($user['User']['username']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Is Admin'); ?></dt>
		<dd>
			<?php echo h($user['User']['is_admin']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Is Active'); ?></dt>
		<dd>
			<?php echo h($user['User']['is_active']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created'); ?></dt>
		<dd>
			<?php echo h($user['User']['created']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Modified'); ?></dt>
		<dd>
			<?php echo h($user['User']['modified']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit User'), array('action' => 'edit', $user['User']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete User'), array('action' => 'delete', $user['User']['id']), null, __('Are you sure you want to delete # %s?', $user['User']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Users'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New User'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Game Shapes'), array('controller' => 'game_shapes', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Game Shape'), array('controller' => 'game_shapes', 'action' => 'add')); ?> </li>
	</ul>
</div>
<div class="related">
	<h3><?php echo __('Related Game Shapes'); ?></h3>
	<?php if (!empty($user['GameShape'])): ?>
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
		<th><?php echo __('Period'); ?></th>
		<th><?php echo __('Created'); ?></th>
		<th><?php echo __('User Id'); ?></th>
		<th><?php echo __('Created By'); ?></th>
		<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php
		$i = 0;
		foreach ($user['GameShape'] as $gameShape): ?>
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
			<td><?php echo $gameShape['period']; ?></td>
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
