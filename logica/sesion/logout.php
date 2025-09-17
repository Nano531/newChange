<?php
session_start();  

$_SESSION = array();  
// destruirla  
session_destroy(); 
?>	
<script type='text/javascript'>window.location='../../index.php'</script>