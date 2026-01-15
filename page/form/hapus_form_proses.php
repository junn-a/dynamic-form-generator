<?php
	include"../../connection.php";
	$id 		 = $_POST['id'];
	
	pg_query($conn,"DELETE FROM tbl_form WHERE id=$id");

?>