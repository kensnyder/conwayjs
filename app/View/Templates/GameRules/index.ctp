<div class="gameRules index">
	<h2><?php echo __('Game Rules'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id'); ?></th>
			<th><?php echo $this->Paginator->sort('name'); ?></th>
			<th><?php echo $this->Paginator->sort('description'); ?></th>
			<th><?php echo $this->Paginator->sort('rulestring'); ?></th>
			<th><?php echo $this->Paginator->sort('type'); ?></th>
			<th><?php echo $this->Paginator->sort('link'); ?></th>
			<th><?php echo $this->Paginator->sort('sort'); ?></th>
			<th><?php echo $this->Paginator->sort('created'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($gameRules as $gameRule): ?>
	<tr>
		<td><?php echo h($gameRule['GameRule']['id']); ?>&nbsp;</td>
		<td><?php echo h($gameRule['GameRule']['name']); ?>&nbsp;</td>
		<td><?php echo h($gameRule['GameRule']['description']); ?>&nbsp;</td>
		<td><?php echo h($gameRule['GameRule']['rulestring']); ?>&nbsp;</td>
		<td><?php echo h($gameRule['GameRule']['type']); ?>&nbsp;</td>
		<td><?php echo h($gameRule['GameRule']['link']); ?>&nbsp;</td>
		<td><?php echo h($gameRule['GameRule']['sort']); ?>&nbsp;</td>
		<td><?php echo h($gameRule['GameRule']['created']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $gameRule['GameRule']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $gameRule['GameRule']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $gameRule['GameRule']['id']), null, __('Are you sure you want to delete # %s?', $gameRule['GameRule']['id'])); ?>
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
		<li><?php echo $this->Html->link(__('New Game Rule'), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Game Shapes'), array('controller' => 'game_shapes', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Game Shape'), array('controller' => 'game_shapes', 'action' => 'add')); ?> </li>
	</ul>
</div>
