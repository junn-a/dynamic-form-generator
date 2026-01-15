<?php
	$kode_form = $_GET['nama_form'];
	$selectForm = pg_query($conn,"SELECT * FROM tbl_form WHERE kode_form='$kode_form'");
	$fetchForm = pg_fetch_assoc($selectForm);
	$jam 			= date("Gis");
	if(@$_SESSION['shift']==3 AND $jam > 0 AND $jam <70000){
		$tgl = date('Y-m-d', strtotime("-1 day", strtotime($date )));
	}else{
		$tgl = date('Y-m-d');
	}
?>
<div class="content-header">
  <div class="container">
	<div class="row mb-2">
	  <div class="col-sm-6">
		<h1 class="m-0">  <small>Rekap <?=$fetchForm['nama_form']?></small></h1>
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
										// Load session_cache_expire
										//session_start();
										
										$line = $_SESSION['line'];
										$shift = $_SESSION['shift'];
										$regu = $_SESSION['regu'];
										
										// Baca data JSON dari file
										$jsonFile =$_SERVER['DOCUMENT_ROOT'] . "/smart_pro/page/form/form/isi-" . $_GET['nama_form']. ".json";
										$jsonContent = file_get_contents($jsonFile);

										// Ubah data JSON menjadi array PHP
										$data = json_decode($jsonContent, true);

										// Ambil nama kolom dari objek pertama
										$firstRow = reset($data);
										if(is_array($firstRow)){
											foreach ($firstRow as $key => $value) {
												echo "<th>$key</th>";
											}
										}
										echo "<th>Option</th>";
										if($kode_form == '2309-FRPGH-ALL-1' || $kode_form == '2311-FSP-ALL-33'){
											echo "<th>Cetak</th>";
										}
										?>
									</tr>
								</thead>
								<tbody>
									<?php
										if(is_array($data)){
											foreach ($data as $item) {
												// Periksa apakah nilai dalam data cocok dengan sesi yang diinginkan
												//@$tanggal = $item['tanggal_auto'] ;
												if(empty($item['tanggal_auto'] )){
													if($kode_form == '2309-FRPGH-ALL-1' || $kode_form == '2311-FSP-ALL-33'){
														if ($item['regu'] == $regu && $item['shift'] == $shift && $item['tanggal'] == $tgl) {
															echo "<tr>";
															foreach ($item as $key => $value) {
																if ($key === 'id') {
																	$id = $value; // Simpan ID ke dalam variabel
																}
																if ($key === 'nama_form') {
																	 $nama_form = $value; // Simpan nama_form ke dalam variabel
																}
																echo "<td>$value</td>";
															} ?>
															<td><button class="btn btn-danger remove" data-id="<?=$id?>" data-form="<?=$nama_form?>"><span class="fa fa-trash"></span></button> </td>
															<td><a href="javascript: w=window.open('page/form/sp/cetak_sp_pdf.php?id1=<?=$id?>&id2=<?=$nama_form?>');w.print();" class="btn btn-warning"><i class="fa fa-print"></i></a></td>
															</tr>
															
															
															<?php
														}
													}else{
														if ($item['regu'] == $regu && $item['shift'] == $shift && $item['line'] == $line && $item['tanggal'] == $tgl) {
															echo "<tr>";
															foreach ($item as $key => $value) {
																if ($key === 'id') {
																	$id = $value; // Simpan ID ke dalam variabel
																}
																if ($key === 'nama_form') {
																	 $nama_form = $value; // Simpan nama_form ke dalam variabel
																}
																echo "<td>$value</td>";
															} ?>
															<td><button class="btn btn-danger remove" data-id="<?=$id?>" data-form="<?=$nama_form?>"><span class="fa fa-trash"></span></button> 
															
															</td>
															
															</tr>
															<?php
														}
													}
												}else{
													if ($item['regu'] == $regu && $item['shift'] == $shift && $item['line'] == $line && $item['tanggal_auto'] == $tgl) {
															echo "<tr>";
															foreach ($item as $key => $value) {
																if ($key === 'id') {
																	$id = $value; // Simpan ID ke dalam variabel
																}
																if ($key === 'nama_form') {
																	 $nama_form = $value; // Simpan nama_form ke dalam variabel
																}
																echo "<td>$value</td>";
															} ?>
															<td><button class="btn btn-danger remove" data-id="<?=$id?>" data-form="<?=$nama_form?>"><span class="fa fa-trash"></span></button>
															
															</td>
															
															</tr>
															<?php
														}
													
												}
												
												
											}
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
	</div>
  </div>
</div>

<script src="assets/plugins/sweetalert/jquery-3.3.1.js"></script>
<script src="assets/plugins/sweetalert/sweetalert.min.js"></script>
<script type="text/javascript">

$(document).ready(function(){    
    
	$("#example1").on("click", ".remove", function(){	
        swal({
            
          title: "Yakin mau dihapus?",
          text: "Jika yakin, Klik Ok!",
          icon: "warning",
          buttons: true,
          dangerMode: true,
        })
        .then((willDelete) => {
          if (willDelete) {
                var id = $(this).data('id');
                var id_form = $(this).data('form');
                var post1 = 'id=' + id + '&id_form=' + id_form; 
                
                $.ajax({
                    type: "post",
                    url    : "page/form/remove_data_rekap.php",
                    data: post1,
                    success : function(oke){
                        $(".load").html(oke);
                        location.reload(); 
                    }
                });
            } else {
            swal("Yeay tidak jadi dihapus");
          }
        });     
    });

});
</script>