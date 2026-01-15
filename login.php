<?php
	include "connection.php";
	date_default_timezone_set('Asia/Jakarta');
	session_start();
	session_destroy();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login | Smart - Pro</title>

  <!-- Font Awesome -->
  <link rel="stylesheet"href="assets/plugins/fontawesome-free/css/all.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet"href="assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet"href="assets/dist/css/adminlte.min.css">
  <link rel="icon" type="image/png" href="assets/img/bot.png">
</head>
<body class="hold-transition login-page" style="background: url('assets/img/pabrikNew.jpg') no-repeat center center fixed; background-size: cover;">
<div class="login-box" >
  <!-- /.login-logo -->
  <div class="card card-outline card-primary" >
    <div class="card-header text-center">
      <ahref="#" class="h1"><b>..</b>..</a>
    </div>
    <div class="card-body">
      <p class="login-box-msg">Silahkan Login</p>

      <form action="login_proses.php" method="post">
        <div class="input-group mb-3">
          <input type="text" class="form-control" placeholder="Username" name="username" required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" class="form-control" placeholder="Password"  name="password"  required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
		<div class="input-group mb-3">
          <select class="form-control" name="shift">
			<option value="">Shift</option>
			<option value="1">1</option>
			<option value="2">2</option>
			<option value="3">3</option>
		  </select>
        </div>
		<div class="input-group mb-3">
          <select class="form-control" name="regu">
			<option value="">Regu</option>
			<option value="A">A</option>
			<option value="B">B</option>
			<option value="C">C</option>
			<option value="D">D</option>
			<option value="N">N</option>
		  </select>
        </div>
		<div class="input-group mb-3">
          <select class="form-control" name="line" required>
			<option value="">Line</option>
			<option value="ALL">ALL</option>
			<?php
				$loadDb 	= file_get_contents('db/db_master.json');
				$dataDb 	= json_decode($loadDb, true);
				foreach($dataDb['line'] AS $value){
					echo "<option value='".$value['id_line']."'>".$value['nama_line']."</option>";
				}
			?>
		  </select>
        </div>
		
        <input type="submit" class="btn btn-primary btn-user btn-block" value="Login">
      </form>
    </div>
    <!-- /.card-body -->
  </div>
  <!-- /.card -->
</div>
<!-- /.login-box -->

<!-- jQuery -->
<script src="assets/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="assets/dist/js/adminlte.min.js"></script>
</body>
</html>

