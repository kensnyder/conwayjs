<?php echo $this->Form->create()?>
<?php echo $this->Form->input('email')?>
<?php echo $this->Form->input('password')?>
<label><?php echo $this->Form->checkbox('remember_me', array('value'=>'1'))?> Remember Me?</label>
<?php echo $this->Form->end('login')?>

