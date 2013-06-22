<script>
	window.parent.$('#loginButton').val(<?php echo json_encode($authUser['User']['fname'])?>);
</script>
<h1>You have successfully logged in.</h1>