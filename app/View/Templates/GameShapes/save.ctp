<h1><?php echo h($h1)?></h1>
<?php echo $this->Form->create('GameShape')?>
	<table class="layout">
		<tr>
			<td id="SaveShapeImages">
				<label>Choose a shape</label>
				<fieldset id="ChooseStartingImg">
					<legend>Starting Shape</legend>
					<img id="StartingImg" src="data:image/gif;base64," alt="" />
				</fieldset>
				<fieldset id="ChooseCurrentImg">
					<legend>Current Shape</legend>
					<img id="CurrentImg" src="data:image/gif;base64," alt="" />
				</fieldset>
				<label>or paste a RLE spec</label>
				<?php echo $this->Form->input('rle', array(
					'type' => 'textarea',
					'cols' => 20,
					'rows' => 3,
				))?>
				<?php echo $this->Form->input('points', array(
					'type' => 'hidden',
				))?>
			</td>
			<td id="SaveShapeForm">
				<?php
					echo $this->Form->inputs(array(
						'legend' => 'Required Info',
						'game_shape_category_id' => array(
							'label' => 'Category',
							'default' => 11
						),
						'name' => array(

						),
						'game_rule_id' => array(

						),
						'rulestring' => array(
							'label' => false,
							'style' => 'display:none'
						),
					));
					echo $this->Form->inputs(array(
						'legend' => 'More Information',
						'desc' => array(
							'label' => 'Description',
							'type' => 'textarea',
							'cols' => 20,
							'rows' => 3,
						),
						'link' => array(
							'label' => 'Info URL'
						),
						'period' => array(
							'label' => 'Period or Lifespan'
						),
					));
					echo $this->Form->inputs(array(
						'legend' => 'Created',
						'found_year' => array(

						),
						'found_by' => array(

						),
						'created_by' => array(
							'label' => 'Your Name'
						)
					));
				?>
			</td>
		</tr>
	</table>
<?php echo $this->Form->end(__('Submit')); ?>
</div>

<script>
(function() { "use strict";
	var $starting = $('#ChooseStartingImg');
	var $current = $('#ChooseCurrentImg')
	var $points = $('#GameShapePoints');
	new WindowMessager(window.parent)
		.on('setStartingImg', function(spec) {
			$('#StartingImg').prop('src', spec.png);
			var chooseStarting = function() {
				$points.val(spec.points);
				$starting.addClass('selected');
				$current.removeClass('selected');
			};
			$starting.click(chooseStarting);
			chooseStarting();
console.log('setStartingImg', spec);
		})
		.on('setCurrentImg', function(spec) {
			$('#CurrentImg').prop('src', spec.png);
			var chooseCurrent = function() {
				$points.val(spec.points);
				$current.addClass('selected');
				$starting.removeClass('selected');
			};
			$current.click(chooseCurrent);
console.log('setCurrentImg', spec);
		})
	;
}());
</script>