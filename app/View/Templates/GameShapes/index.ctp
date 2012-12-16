<div class="gameShapes index">
	<h2><?php echo __('Game Shapes'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id'); ?></th>
			<th><?php echo $this->Paginator->sort('game_shape_category_id'); ?></th>
			<th><?php echo $this->Paginator->sort('name'); ?></th>
			<th><?php echo $this->Paginator->sort('desc'); ?></th>
			<th><?php echo $this->Paginator->sort('comments'); ?></th>
			<th><?php echo $this->Paginator->sort('link'); ?></th>
			<th><?php echo $this->Paginator->sort('found_year'); ?></th>
			<th><?php echo $this->Paginator->sort('found_by'); ?></th>
			<th><?php echo $this->Paginator->sort('image_path'); ?></th>
			<th><?php echo $this->Paginator->sort('image_width'); ?></th>
			<th><?php echo $this->Paginator->sort('image_height'); ?></th>
			<th><?php echo $this->Paginator->sort('start_position'); ?></th>
			<th><?php echo $this->Paginator->sort('size_x'); ?></th>
			<th><?php echo $this->Paginator->sort('size_y'); ?></th>
			<th><?php echo $this->Paginator->sort('rulestring'); ?></th>
			<th><?php echo $this->Paginator->sort('game_rule_id'); ?></th>
			<th><?php echo $this->Paginator->sort('format'); ?></th>
			<th><?php echo $this->Paginator->sort('spec'); ?></th>
			<th><?php echo $this->Paginator->sort('lifespan'); ?></th>
			<th><?php echo $this->Paginator->sort('created'); ?></th>
			<th><?php echo $this->Paginator->sort('user_id'); ?></th>
			<th><?php echo $this->Paginator->sort('created_by'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($gameShapes as $gameShape): ?>
	<tr>
		<td><?php echo h($gameShape['GameShape']['id']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($gameShape['GameShapeCategory']['name'], array('controller' => 'game_shape_categories', 'action' => 'view', $gameShape['GameShapeCategory']['id'])); ?>
		</td>
		<td><?php echo h($gameShape['GameShape']['name']); ?>&nbsp;</td>
		<td><?php echo h($gameShape['GameShape']['desc']); ?>&nbsp;</td>
		<td><?php echo h($gameShape['GameShape']['comments']); ?>&nbsp;</td>
		<td><?php echo h($gameShape['GameShape']['link']); ?>&nbsp;</td>
		<td><?php echo h($gameShape['GameShape']['found_year']); ?>&nbsp;</td>
		<td><?php echo h($gameShape['GameShape']['found_by']); ?>&nbsp;</td>
		<td><?php echo h($gameShape['GameShape']['image_path']); ?>&nbsp;</td>
		<td><?php echo h($gameShape['GameShape']['image_width']); ?>&nbsp;</td>
		<td><?php echo h($gameShape['GameShape']['image_height']); ?>&nbsp;</td>
		<td><?php echo h($gameShape['GameShape']['start_position']); ?>&nbsp;</td>
		<td><?php echo h($gameShape['GameShape']['size_x']); ?>&nbsp;</td>
		<td><?php echo h($gameShape['GameShape']['size_y']); ?>&nbsp;</td>
		<td><?php echo h($gameShape['GameShape']['rulestring']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($gameShape['GameRule']['name'], array('controller' => 'game_rules', 'action' => 'view', $gameShape['GameRule']['id'])); ?>
		</td>
		<td><?php echo h($gameShape['GameShape']['format']); ?>&nbsp;</td>
		<td><?php echo h($gameShape['GameShape']['spec']); ?>&nbsp;</td>
		<td><?php echo h($gameShape['GameShape']['lifespan']); ?>&nbsp;</td>
		<td><?php echo h($gameShape['GameShape']['created']); ?>&nbsp;</td>
		<td><?php echo h($gameShape['GameShape']['user_id']); ?>&nbsp;</td>
		<td><?php echo h($gameShape['GameShape']['created_by']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $gameShape['GameShape']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $gameShape['GameShape']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $gameShape['GameShape']['id']), null, __('Are you sure you want to delete # %s?', $gameShape['GameShape']['id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
	</table>
	<p>
	<?php
	echo $this->Paginator->counter(array(
	'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
	));
	?>	</p>
	<div class="paging">
	<?php
		echo $this->Paginator->prev('< ' . __('previous'), array(), null, array('class' => 'prev disabled'));
		echo $this->Paginator->numbers(array('separator' => ''));
		echo $this->Paginator->next(__('next') . ' >', array(), null, array('class' => 'next disabled'));
	?>
	</div>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('New Game Shape'), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Game Shape Categories'), array('controller' => 'game_shape_categories', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Game Shape Category'), array('controller' => 'game_shape_categories', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Game Rules'), array('controller' => 'game_rules', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Game Rule'), array('controller' => 'game_rules', 'action' => 'add')); ?> </li>
	</ul>
</div>
