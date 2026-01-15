<?php
	include 'connection.php';
	
	@$username 			= $_POST['username'];
	@$password 				= $_POST['password'];
	@$shift		 				= $_POST['shift'];
	@$regu		 				= $_POST['regu'];
	@$line		 				= $_POST['line'];
	
	
	$query 	= pg_query($conn2,"SELECT * FROM tbl_user WHERE id_user = '$username' AND password = '$password' ");
	$fetch 	= pg_fetch_assoc($query);
	$cek 		= pg_num_rows($query);
	
	if($cek >0){
		session_start();
		$_SESSION['status'] 		= "login";
		$_SESSION['id_user']		= $fetch['id_user'];
		$_SESSION['nama_user']	= $fetch['nama'];
		$_SESSION['level']			= $fetch['level'];
		$_SESSION['regu']			= $regu;
		$_SESSION['shift']			= $shift;
		$_SESSION['line']				= $line;
		$_SESSION['departemen']	= $fetch['departemen'];
		
		?>
			<script>
				alert('Login Successfully');
				window.location.href="index.php";
			</script>
		<?php
	}else{
		?>
			<script>
				alert('Login Failed');
				window.location.href="index.php";
			</script>
		<?php
	}
?>