<h1><?php echo h($h1)?></h1>
<?php echo $this->Form->create('GameShape')?>
	<table class="layout">
		<tr>
			<td id="SaveShapeImages">
				<fieldset id="ChooseStartingImg">
					<legend><label><input type="radio" name="shape" /> Starting Shape</label></legend>
					<img id="StartingImg" src="data:image/gif;base64," alt="" />
				</fieldset>
				<fieldset id="ChooseCurrentImg">
					<legend><label><input type="radio" name="shape" /> Current Shape</label></legend>
					<img id="CurrentImg" src="data:image/gif;base64," alt="" />
				</fieldset>
				<fieldset id="PasteRle">
					<legend><label><input type="radio" name="shape" /> Paste RLE File</label></legend>
					<?php echo $this->Form->input('rle', array(
						'label'=> false,
						'type' => 'textarea',
						'cols' => 20,
						'rows' => 3,
					))?>
				</fieldset>
				<?php echo $this->Form->input('spec', array(
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
	var $shapes = $('#SaveShapeImages fieldset');
	var $radios = $('#SaveShapeImages input[type=radio]');
	var $spec = $('#GameShapeSpec');
	$shapes.click(function() {
		var $shape = $(this);
		$shapes.removeClass('selected');
		$shape.addClass('selected');
		$radios.each(function() {
			this.checked = false;
		});
		$shape.find('input[type=radio]')[0].checked = true;
		$spec.val($shape.find('img').data('spec'));
	});
	
	var $starting = $('#ChooseStartingImg');
	var $current = $('#ChooseCurrentImg')
	var $points = $('#GameShapePoints');
	var startingRadio = $('#ChooseStartingRadio')[0];
	var currentRadio = $('#ChooseCurrentRadio')[0];
	new WindowMessager(window.parent)
		.on('setStartingImg', function(spec) {
			$('#StartingImg')
				.prop('src', spec.png)
				.data('spec', JSON.stringify(spec))
				.click()
			;
		})
		.on('setCurrentImg', function(spec) {
			$('#CurrentImg')
				.prop('src', spec.png)
				.data('spec', JSON.stringify(spec))
			;
		})
	;
}());
</script>