<?php
	$tanggal 	=  $_POST['tanggal_range'];
	$shift 		=  $_POST['shift'];
	$regu 		=  $_POST['regu'];
	$line 		=  $_POST['line'];
	$nama_form = $_POST['nama_form'];
	
	$bagian = explode(" - ", $tanggal);

	// Bagian pertama adalah tanggal awal ($start) dan bagian kedua adalah tanggal akhir ($end).
	$start = $bagian[0];
	$end = $bagian[1];

	// Mengubah format tanggal ke "Y-m-d".
	$startFormatted = date("Y-m-d", strtotime($start));
	//$tes = strtotime($startFormatted);
	$endFormatted = date("Y-m-d", strtotime($end));
	
?>
<div class="content-header">
  <div class="container">
	<div class="row mb-2">
	  <div class="col-sm-6">
		<h1 class="m-0">  <small>Rekap </small></h1>
	  </div><!-- /.col -->
	  <div class="col-sm-6">
		<ol class="breadcrumb float-sm-right">
		  <li class="breadcrumb-item"><a href="#">Home</a></li>
		  <li class="breadcrumb-item active">Rekap</li>
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
					<div class="card-body">
						<table id="example1" class="table table-bordered table-striped">
							<thead>
								<tr>
									<?php
									// Baca data JSON dari file
									$jsonFile =$_SERVER['DOCUMENT_ROOT'] . "/smart_pro/page/form/form/isi-" . $nama_form. ".json";
									$jsonContent = file_get_contents($jsonFile);

									// Ubah data JSON menjadi array PHP
									$data = json_decode($jsonContent, true);
									//print_r($data);

									// Ambil nama kolom dari objek pertama
									$firstRow = reset($data);
									foreach ($firstRow as $key => $value) {
										echo "<th>$key</th>";
									}
									?>
								</tr>
							</thead>
							<tbody>
								<?php
									$startDate 	= strtotime($startFormatted); // Tanggal mulai rentang
									$endDate 		= strtotime($endFormatted);   // Tanggal akhir rentang

									foreach ($data as $item) {
										if(!empty($item['tanggal'])){
											$tanggal = strtotime($item['tanggal']); // Ganti 'tanggal' dengan nama kolom tanggal dalam JSON
										}else{
											$tanggal = strtotime($item['tanggal_auto']); 
										}
										

										
										 // Periksa apakah tanggal berada dalam rentang yang diinginkan
										if ($tanggal >= $startDate && $tanggal <= $endDate) {
											if (
													($_POST['shift'] == 'all' || $_POST['shift'] == $item['shift']) &&
													($_POST['regu'] == 'all' || $_POST['regu'] == $item['regu']) &&
													($_POST['line'] == 'all' || $_POST['line'] == $item['line']) &&
													$_POST['nama_form'] == $item['nama_form']
												) {
													echo "<tr>";
													/**foreach ($item as $value) {
														echo "<td>$value</td>";
													}**/
													foreach ($item as $key => $value) {
															// Cek apakah ini adalah path gambar
															if (strpos($value, "/uploads/") !== false) {
																	$fileName = basename($value);
																	echo "<td><a href='$value' target='_blank'>$fileName</a></td>";
															} else {
																	echo "<td>$value</td>";
															}
													}
													echo "</tr>";
													
												}
										}// Periksa apakah tanggal berada dalam rentang yang diinginkan
										
									}
								?>
							</tbody>
						</table>	
					</div>
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