<div class="content-header">
  <div class="container">
	<div class="row mb-2">
	  <div class="col-sm-6">
		<h1 class="m-0">  <small>Configuration Form </small></h1>
	  </div><!-- /.col -->
	  <div class="col-sm-6">
		<ol class="breadcrumb float-sm-right">
		  <li class="breadcrumb-item"><a href="#">Home</a></li>
		  <li class="breadcrumb-item active">Index Form</li>
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
		  <div class="card-header">
			<a href="?page=tambah_form" class="btn btn-primary">Tambah Form</a>
		  </div>
		  <div class="card-body">
				<div class="card-body">
					<table id="example2" class="table table-bordered table-striped">
						<thead>
							<tr>
								<th>No</th>
								<th>Nama Form</th>
								<th>Author</th>
								<th>Kode Form</th>
								<th>Tanggal Buat</th>
								<th>Delete</th>
							</tr>
						</thead>
						<tbody>
							<?php
								
								$departemen = $_SESSION['departemen'];
								$no = 1;
								$selectForm = pg_query($conn,"SELECT * FROM tbl_form WHERE departemen='$departemen'");
								while($dataForm = pg_fetch_array($selectForm)){
							?>
							<tr>
								<td><?=$no++?></td>
								<td><?=$dataForm['nama_form']?></td>
								<td><?=$dataForm['author']?></td>
								<td><?=$dataForm['kode_form']?></td>
								<td><?=$dataForm['tanggal']?></td>
								<td><button data-id="<?=$dataForm['id']?>" class="btn btn-danger remove">Delete</button</td>
							</tr>
							<?php }?>
						</tbody>
					</table>	
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
<!--
<script src="assets/plugins/jquery/jquery.min.js"></script>
<script src="assets/plugins/jquery/sweetalert.min.js"></script>-->
<script type="text/javascript">
$(document).ready(function(){	
	
	$(".remove").click(function(){
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
				var post1 = 'id='+id;
				
				$.ajax({
					type: "post",
					url	: "page/form/hapus_form_proses.php",
					data: post1,
					success : function(oke){
						$(".load").html(oke);
						location.reload(); 
					}
				});
			//location.reload(); 
		  } else {
			swal("Yeay tidak jadi dihapus");
		  }
		});		
	});

});


</script>