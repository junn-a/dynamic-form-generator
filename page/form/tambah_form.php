<div class="content-header">
  <div class="container">
	<div class="row mb-2">
	  <div class="col-sm-6">
		<h1 class="m-0">  <small>Tambah Form </small></h1>
	  </div><!-- /.col -->
	  <div class="col-sm-6">
		<ol class="breadcrumb float-sm-right">
		  <li class="breadcrumb-item"><a href="?page=dashboard">Home</a></li>
		  <li class="breadcrumb-item active">Tambah Form</li>
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
			<div class="col-md-6">
				<!-- general form elements -->
				<div class="card card-primary">
				  <div class="card-header">
					<h3 class="card-title">Data</h3>
				  </div>
				  <!-- /.card-header -->
				  <!-- form start -->
				  <form action="page/form/tambah_form_proses.php" method="post">
					<div class="card-body">
					<div class="row">
						<div class="col-md-6">
							 <div class="form-group">
								<label for="">Line</label>
								 <select class="form-control select2bs4" name="line" required>
									<option value="ALL">ALL</option>
									<option value="L01">PC 32</option>
									<option value="L02">PC 14</option>
									<option value="L03">TS</option>
									<option value="L04">FCP</option>
									<option value="L05">TWS 5.6</option>
									<option value="L06">TWS 7.2</option>
									<option value="L07">CASSAVA</option>
								  </select>
							  </div>
							  <div class="form-group">
								<label for="">Nama Form</label>
								<input type="text" class="form-control" name="nama_form" placeholder="Nama Form" required>
							  </div>
							  <div class="form-group">
								<label for="">Author</label>
								<input type="text" class="form-control" name="author" placeholder="Author" required>
							  </div>
							  <div class="form-group">
								<label for="">Tipe Form</label>
								<select class="form-control select2bs4" name="tipe_form" required>
									<option value="1">Permanent</option>
									<option value="2">Temporary</option>
								 </select>
							  </div>
						</div>
						<div class="col-md-6">
							 <div class="form-group">
								<label for="">Kategori Form</label>
								<select class="form-control select2bs4" name="kategori_form" required>
									<option value="1">Pengamatan</option>
									<option value="2">Sanitasi</option>
									<option value="3">Laporan</option>
									<option value="4">Stok</option>
									<option value="5">Other</option>
								 </select>
							  </div>
							  <div class="form-group">
								<label for="">Singkatan</label>
								<input type="text" class="form-control" name="singkatan" placeholder="Singkatan" required>
							  </div>
							  <div class="form-group">
								<label for="">Status</label>
								<select class="form-control select2bs4" name="status" required>
									<option value="1">Aktif</option>
									<option value="2">Non Aktif</option>
								 </select>
							  </div>
							  <div class="form-group">
								<label for="">Tanggal Exp</label>
								<input type="date" class="form-control" name="tanggal_exp">
							  </div>
						</div>
						<div class="col-md-6">
							 <div class="form-group">
								<label for="">Sub Departemen</label>
								<select class="form-control select2bs4" name="departemen" required>
									<option value="produksi">Produksi</option>
									<option value="utility">Utility</option>
									<option value="qc">QC</option>
								 </select>
							  </div>
						</div>
					</div>
					 
					  
					</div>
					<!-- /.card-body -->

					<div class="card-footer">
					  <button type="submit" class="btn btn-primary">Submit</button>
					</div>
				  </form>
				</div>
			</div>
			<div class="col-md-6">
				<!-- general form elements -->
				<div class="card card-primary">
				  <div class="card-header">
					<h3 class="card-title">Isi dengan data yang akan di input, Contoh : id, tanggal, jam, shift, regu, line dll.</h3>
				  </div>
				  <!-- /.card-header -->
				  <!-- form start -->
				  <?php
					//echo $nama_db = @$_GET['nama_db'];
					
				  ?>
				  <form  action="page/form/detail_form_proses.php" method="post">
					<div class="card-body">
						<div id="" hidden>
							<div class="form-group">
								<input type="text" class="form-control" name="kode_form" value="<?=$_GET['kode_form']?>">
							  </div>
						</div>
						<div id="input-container">
							<div class="form-group">
								<input type="text" class="form-control" name="data[]">
								<button class="btn btn-danger remove-input" type="button">Hapus</button>
							  </div>
						</div>
					  
					</div>
					<!-- /.card-body -->

					<div class="card-footer">
					  <button type="button" id="add-input" class="btn btn-warning">Add Data</button>
					  <button type="submit" class="btn btn-primary">Submit</button>
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
<script src="assets/js/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        // Menambahkan input baru
        $("#add-input").click(function() {
            var newInput = $('<div class="form-group"><input type="text" class="form-control" name="data[]"><button class="btn btn-danger remove-input" type="button">Hapus</button></div>');
            $("#input-container").append(newInput);
        });

        // Menghapus input saat tombol "Hapus" diklik
        $("#input-container").on("click", ".remove-input", function() {
            $(this).parent().remove();
        });
    });
</script>