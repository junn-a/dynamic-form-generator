<link rel="stylesheet" href="assets/plugins/daterangepicker/daterangepicker1.css">
<div class="content-header">
  <div class="container">
	<div class="row mb-2">
	  <div class="col-sm-6">
		<h1 class="m-0">  <small>Grafik Viewer </small></h1>
	  </div><!-- /.col -->
	  <div class="col-sm-6">
		<ol class="breadcrumb float-sm-right">
		  <li class="breadcrumb-item"><a href="#">Home</a></li>
		  <li class="breadcrumb-item active">Grafik Less Content & Empty Pack Viewer</li>
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
		<div class="card card-success card-outline">
		 
		  <div class="card-body">
		  <div class="row">
			<div class="col-md-12">
				<!-- general form elements -->
				<div class="card card-success">
				  <div class="card-header">
					<h3 class="card-title">Grafik Less Content & Empty Pack Viewer</h3>
				  </div>
				  <!-- /.card-header -->
				  <!-- form start -->
				  <form action="?page=detail_grafik_less_content" method="post">
					<div class="card-body">
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
								  <label>Date range:</label>

								  <div class="input-group">
									<div class="input-group-prepend">
									  <span class="input-group-text">
										<i class="far fa-calendar-alt"></i>
									  </span>
									</div>
									<input type="text" class="form-control float-right" id="datarange" name="tanggal_range">
								  </div>
								  <!-- /.input group -->
								</div>
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label>Shift</label>
											 <select class="form-control " name="shift" required>
												<option value="all">ALL</option>
												<option value="1">1</option>
												<option value="2">2</option>
												<option value="3">3</option>
											 </select>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label>Regu</label>
											 <select class="form-control " name="regu" required>
												<option value="all">ALL</option>
												<option value="A">A</option>
												<option value="B">B</option>
												<option value="C">C</option>
												<option value="D">D</option>
												<option value="N">N</option>
											 </select>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>Line</label>
									 <select class="form-control select2bs4" name="line" required>
										<option value="all">ALL</option>
										<option value="L01">PC 32</option>
										<option value="L02">PC 14</option>
										<option value="L03">TS</option>
										<option value="L04">FCP</option>
										<option value="L05">TWS 5.6</option>
										<option value="L06">TWS 7.2</option>
										<option value="L07">CASSAVA</option>
										<option value="L08">STANDING POUCH</option>
									  </select>
								  </div>
								  <div class="form-group">
									<label>Nama Form</label>
									<input type="text" class="form-control float-right" id="" name="nama_form" value="<?php echo $_GET['id']?>">
								  </div>
							</div>
						</div>
					</div>
					<!-- /.card-body -->

					<div class="card-footer">
					  <button type="submit" class="btn btn-success">Generate Grafik Less Content & Empty Pack</button>
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
<script src="assets/plugins/jquery/jquery1.min.js"></script>
<script src="assets/plugins/daterangepicker/moment.min.js"></script>
<script src="assets/plugins/daterangepicker/daterangepicker.min.js"></script>

<script>
$(function() {
  $('#datarange').daterangepicker({
    opens: 'left'
  }, function(start, end, label) {
    console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
  });
});
</script>