<div class="content-header">
  <div class="container">
	<div class="row mb-2">
	  <div class="col-sm-6">
		<h1 class="m-0">  <small> </small></h1>
	  </div><!-- /.col -->
	  <div class="col-sm-6">
		<ol class="breadcrumb float-sm-right">
		  <li class="breadcrumb-item"><a href="?page=dashboard">Home</a></li>
		  <li class="breadcrumb-item active">Change Password</li>
		</ol>
	  </div><!-- /.col -->
	</div><!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
<div class="content">
  <div class="container">
	<div class="row">
	  
	  <!-- /.col-md-6 -->
	  <div class="col-lg-12">
		<div class="card card-primary card-outline">
		 
		  <div class="card-body">
		  <div class="row">
			<div class="col-md-12">
				<!-- general form elements -->
				<div class="card card-primary">
				  <div class="card-header">
					<h3 class="card-title">Change Password</h3>
				  </div>
				  <!-- /.card-header -->
				  <!-- form start -->
				  <form action="" method="post">
					<div class="card-body">
						<div class="form-group">
							<label for="">New Password</label>
							<input type="password" class="form-control" name="new_password" placeholder="****************" required>
						</div>
						<div class="form-group">
							<label for="">New Password Confirm</label>
							<input type="password" class="form-control" name="new_password_confirm" placeholder="****************" required>
						</div>
					  
					</div>
					<!-- /.card-body -->

					<div class="card-footer">
					  <button type="submit" class="btn btn-primary change">Submit</button>
					</div>
				  </form>
				</div>
			</div>
			
		</div>
			
			
		  </div>
		</div>
	  </div>
	  <!-- /.col-md-6 -->
	</div>
	<!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
<script src="assets/js/jquery-3.3.1.js"></script>
<script src="assets/js/sweetalert.min.js"></script>
<!-- SweetAlert CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- jQuery CDN -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<!--
<script src="assets/plugins/jquery/jquery.min.js"></script>
<script src="assets/plugins/jquery/sweetalert.min.js"></script>-->
<script>
  $(document).ready(function(){
    $('.change').click(function(e){
      e.preventDefault();
      
      // Lakukan validasi di sini sesuai kebutuhan
      var newPassword = $('input[name="new_password"]').val();
      var newPasswordConfirm = $('input[name="new_password_confirm"]').val();
      
      // Contoh validasi sederhana
      if (newPassword !== newPasswordConfirm) {
        // Tampilkan pesan error jika password tidak cocok
        Swal.fire({
          icon: 'error',
          title: 'Oops...',
          text: 'Password confirmation does not match!',
        });
        return; // Stop proses selanjutnya jika ada error
      }
      
      // Lakukan AJAX request ke server untuk menyimpan data atau melakukan operasi lainnya
      // Misalnya:
      $.ajax({
        url: 'page/change_password_proses.php',
        method: 'POST',
        data: { new_password: newPassword, new_password_confirm: newPasswordConfirm },
        success: function(response){
          // Tampilkan SweetAlert jika proses berhasil
          Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: 'Your password has been changed successfully.',
          });
          
          // Lakukan operasi lain setelah sukses disini
        },
        error: function(xhr, status, error) {
          // Tampilkan pesan error jika terjadi masalah saat AJAX request
          Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Something went wrong! Please try again later.',
          });
        }
      });
    });
  });
</script>
