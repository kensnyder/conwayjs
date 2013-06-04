<h1><?php echo h($h1)?></h1>
<?php echo $this->Form->create('GameShape')?>
	<?php echo $this->Form->hidden('spec')?>
	<?php echo $this->Form->hidden('size_x')?>
	<?php echo $this->Form->hidden('size_y')?>
	<?php echo $this->Form->hidden('image')?>
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
			</td>
			<td id="SaveShapeForm" class="save-form">
				<?php
					echo $this->Form->inputs(array(
						'legend' => 'Required Info',
						'created_by' => array(
							'label' => 'Your Name'
						),
						'name' => array(
							'label' => 'Shape Name'
						),
					));
					echo $this->Form->inputs(array(
						'legend' => 'Board Options',
						'rulestring' => array(
							'label' => 'Rule',
						),
						'start_position' => array(
							'default' => 'middle-center',
							'type' => 'select',			
							'options' => array(
								'top-left' => 'top left',
								'top-center' => 'top center',
								'top-right' => 'top right',
								'middle-left' => 'middle left',
								'middle-center' => 'middle center',
								'middle-right' => 'middle right',
								'bottom-left' => 'bottom left',
								'bottom-center' => 'bottom center',
								'bottom-right' => 'bottom right',
							)
						),
						'start_block_size' => array(
							'label' => 'Cell Size',
							'type' => 'select',
						),
						'start_speed' => array(
							'label' => 'Speed',
							'type' => 'select',							
						),
					));										
				?>
			</td>
			<td id="SaveShapeOptions" class="save-form">
			<?php
				echo $this->Form->inputs(array(
						'legend' => 'More Information',
						'desc' => array(
							'label' => 'Description',
							'type' => 'textarea',
							'cols' => 20,
							'rows' => 3,
						),
						'game_shape_category_id' => array(
							'label' => 'Category',
							'default' => 11
						),						
						'link' => array(
							'label' => 'Info URL'
						),
						'period' => array(
							'label' => 'Period/Lifespan'
						),
					));
					echo $this->Form->inputs(array(
						'legend' => 'Discovery',
						'found_year' => array(

						),
						'found_by' => array(

						),
					));
				?>
			</td>
		</tr>
		<tr>
			<td colspan="3">
				<?php echo $this->Form->submit('Save')?>
			</td>
		</tr>
	</table>
<?php echo $this->Form->end()?>
</div>

<script>
(function() { "use strict";
	var $shapes = $('#SaveShapeImages fieldset');
	var $radios = $('#SaveShapeImages input[type=radio]');
//	var $spec = $('#GameShapeSpec');
	$shapes.click(function() {
		var $shape = $(this);
		$shapes.removeClass('selected');
		$shape.addClass('selected');
		$radios.each(function() {
			this.checked = false;
		});
		var $img = $shape.find('img');
		var spec = $img.data('spec');
		$shape.find('input[type=radio]')[0].checked = true;
		$('#GameShapeSpec').val(JSON.stringify(spec.points));
		$('#GameShapeSizeX').val(spec.size[0]);
		$('#GameShapeSizeY').val(spec.size[1]);
		$('#GameShapeImage').val($img.prop('src'));		
	});
	
//	var $starting = $('#ChooseStartingImg');
//	var $current = $('#ChooseCurrentImg')
//	var $points = $('#GameShapePoints');
//	var startingRadio = $('#ChooseStartingRadio')[0];
//	var currentRadio = $('#ChooseCurrentRadio')[0];
	new WindowMessager(window.parent)
		.on('setStartingImg', function(spec) {
			$('#StartingImg')
				.prop('src', spec.png)
				.data('spec', spec);
			;
			$('#ChooseStartingImg input[type=radio]').click();
			$('#PasteRle').hide();	
		})
		.on('setCurrentImg', function(spec) {
			$('#CurrentImg')
				.prop('src', spec.png)
				.data('spec', spec)
			;
			$('#PasteRle').hide();	
		})
		.on('setBoardOptions', function(options) {
console.log(options);			
			function unserializeSelect(element, serial) {
console.log('unser',serial,element);				
				$.each(serial.options, function(i, option) {
					element.options[i] = new Option(option.text, option.value);
				});
				element.selectedIndex = serial.selectedIndex;
			}
			unserializeSelect($('#GameShapeStartBlockSize').get(0), options.blockSize);
			unserializeSelect($('#GameShapeStartSpeed').get(0), options.interval);
			$('#GameShapeRulestring').val(options.rule);
		})
	;
	
}());
</script>