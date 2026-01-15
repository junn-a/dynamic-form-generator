<?php
	include "connection.php";
	date_default_timezone_set('Asia/Jakarta');
	$time 		= date("H:i:s");
	$date	 	= date("Y-m-d");
	session_start();
	if(@$_SESSION['status']!= "login"){
		header("location:login.php");
	}
?>
<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>SMART - Pro</title>
  <link rel="icon" type="image/png" href="assets/img/bot.png">
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="assets/plugins/fontawesome-free/css/all.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="assets/dist/css/adminlte.min.css">
  <link rel="stylesheet" href="assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
  <link rel="stylesheet" href="assets/plugins/bootstrap4-duallistbox/bootstrap-duallistbox.min.css">
  <link rel="stylesheet" href="assets/plugins/select2/css/select2.min.css">
  <link rel="stylesheet" href="assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
  <!-- daterange picker -->
  <link rel="stylesheet" href="assets/plugins/daterangepicker/daterangepicker.css">
</head>
<body class="hold-transition sidebar-collapse layout-top-nav">
<div class="wrapper">

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand-md navbar-light navbar-white">
    <div class="container">
      <a href="?page=dashboard" class="navbar-brand">
        <img src="assets/dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">SMART - Pro</span>
      </a>

      <button class="navbar-toggler order-1" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse order-3" id="navbarCollapse">
		
        <ul class="navbar-nav">
          
          
		  <li class="nav-item dropdown">
            <a id="dropdownSubMenu1" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle">Form</a>
            <ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 shadow">
             
			  <li><a href="?page=index_form" class="dropdown-item">Form Generator </a></li>
			  <li class="dropdown-divider"></li>
			
              <!-- Level two dropdown-->
              <li class="dropdown-submenu dropdown-hover">
                <a id="dropdownSubMenu2" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="dropdown-item dropdown-toggle">Form Tersedia</a>
                <ul aria-labelledby="dropdownSubMenu2" class="dropdown-menu border-0 shadow">
				<?php
					$selectForm = pg_query($conn, "SELECT * FROM tbl_form WHERE kategori_form < '5' AND kode_form != '2312-FWHK-ALL-38' AND departemen='produksi'");
					while ($listForm = pg_fetch_array($selectForm)) {
					?>
						<li><a href="?page=form&nama_form=<?= $listForm['kode_form'] ?>" class="dropdown-item"><?= $listForm['nama_form'] ?></a></li>
						<?php
					}
					?>				
                </ul>
              </li>
			  
            </ul>
          </li>
		  
		  <?php
			if($_SESSION['level'] == 4 || $_SESSION['level'] == 2){
				?>
				<li class="nav-item dropdown">
					<a id="dropdownSubMenu1" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle">Reports</a>
					<ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 shadow">
					  <li><a href="?page=view_report" class="dropdown-item">Data Viewer</a></li>
					  <li class="dropdown-divider"></li>
					  <li><a href="?page=personal_report" class="dropdown-item">Personal Report</a></li>
					  <li><a href="?page=daily_activity_report" class="dropdown-item">Daily Activity Report</a></li>
					  <li class="dropdown-divider"></li>
					  
					  <li class="dropdown-submenu">
						<a id="dropdownSubMenu3" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="dropdown-item dropdown-toggle">Grafik</a>
						<ul aria-labelledby="dropdownSubMenu3" class="dropdown-menu border-0 shadow">
						  <li><a href="?page=grafik_gmp&id=2309-FRPGH-ALL-1" class="dropdown-item">Grafik Pelanggaran GMP</a></li>
						</ul>
					  </li>
					</ul>
				 </li>
				 
				
				<?php
			}
		  
		  ?>
        </ul>
		<?php }?>

        <!-- SEARCH FORM -->
        
      </div>

      <!-- Right navbar links -->
      <ul class="order-1 order-md-3 navbar-nav navbar-no-expand ml-auto">
        <!-- Messages Dropdown Menu -->
		<li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
           Hello <?=$_SESSION['nama_user']?>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <a href="?page=change_password" class="dropdown-item">
            <i class="fas fa-lock"></i> Change Password
          </a>
          <div class="dropdown-divider"></div>
          
        </div>
      </li>
        
        <!-- Notifications Dropdown Menu -->
        <li class="nav-item dropdown">
          <a class="nav-link"  href="login.php">
            <i class="fa fa-power-off"></i>
          </a>
        </li>
        
      </ul>
    </div>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <?php
		if($_SESSION['departemen'] == "produksi"){
			if(@$_GET['page']=='dashboard' || @$_GET['page']==''){
				include'page/dashboard/dashboard.php';
			}
			else if(@$_GET['page']=='index_form'){
				include'page/form/index_form.php';
			}
			else if(@$_GET['page']=='tambah_form'){
				include'page/form/tambah_form.php';
			}
			else if(@$_GET['page']=='form'){
				include'page/form/form.php';
			}
			else if(@$_GET['page']=='rekap_form'){
				include'page/form/rekap_form.php';
			}
			else if(@$_GET['page']=='remove_data_rekap'){
				include'page/form/remove_data_rekap.php';
			}
			else if(@$_GET['page']=='view_report'){
				include'page/form/view_report.php';
			}
			else if(@$_GET['page']=='detail_report'){
				include'page/form/detail_report.php';
			}
			// grafik GMP
			else if(@$_GET['page']=='grafik_gmp'){
				include'page/form/grafik/grafik_gmp.php';
			}
			else if(@$_GET['page']=='detail_grafik_gmp'){
				include'page/form/grafik/detail_grafik_gmp.php';
			}
			//grafik reject mesin
			else if(@$_GET['page']=='grafik_reject_mesin'){
				include'page/form/grafik/grafik_reject_mesin.php';
			}
			else if(@$_GET['page']=='detail_grafik_reject_mesin'){
				include'page/form/grafik/detail_grafik_reject_mesin.php';
			}
			
			//grafik sp
			else if(@$_GET['page']=='grafik_sp'){
				include'page/form/grafik/grafik_sp.php';
			}
			else if(@$_GET['page']=='detail_grafik_sp'){
				include'page/form/grafik/detail_grafik_sp.php';
			}
			//Form HK
			else if(@$_GET['page']=='form_hk'){
				include'page/form_hk/form_hk.php';
			}
			//Cetak Form HK
			else if(@$_GET['page']=='cetak_form_hk'){
				include'page/form_hk/cetakRekap.php';
			}//grafik waste hk
			else if(@$_GET['page']=='grafik_waste_hk'){
				include'page/form/grafik/grafik_waste_hk.php';
			}
			else if(@$_GET['page']=='detail_grafik_waste_hk'){
				include'page/form/grafik/detail_grafik_waste_hk.php';
			}
			// Grafik less content
			else if(@$_GET['page']=='grafik_less_content'){
				include'page/form/grafik/grafik_less_content.php';
			}
			else if(@$_GET['page']=='detail_grafik_less_content'){
				include'page/form/grafik/detail_grafik_less_content.php';
			}
			//Form achievment
			else if(@$_GET['page']=='form_hasil_produksi'){
				include'page/form_achievment/form_hasil_produksi.php';
			}
			else if(@$_GET['page']=='form_cek_hasil'){
				include'page/form_achievment/form_cek_produksi.php';
			}
			// Form cmo - otf
			else if(@$_GET['page']=='form_cmo_otf'){
				include'page/form_achievment/form_cmo_otf.php';
			}
			// Achievment Report
			else if(@$_GET['page']=='achievment_report'){
				include'page/form/achievment_report/achievment_report.php';
			}
			else if(@$_GET['page']=='detail_achievment_report'){
				include'page/form/achievment_report/detail_achievment_report.php';
			}
			//sanitasi building
			else if(@$_GET['page']=='sanitasi_grill'){
				include'page/form/sanitasi_building/sanitasi_grill.php';
			}
			else if(@$_GET['page']=='detail_sanitasi_grill'){
				include'page/form/sanitasi_building/detail_sanitasi_grill.php';
			}
			
			/
			// REPORT
			// Daily Activity Report
			else if(@$_GET['page']=='daily_activity_report'){
				include'page/form/personal_report/daily_activity_report.php';
			}
			else if(@$_GET['page']=='detail_daily_activity_report'){
				include'page/form/personal_report/detail_daily_activity_report.php';
			}
			// Personal report
			else if(@$_GET['page']=='personal_report'){
				include'page/form/personal_report/personal_report.php';
			}
			else if(@$_GET['page']=='detail_personal_report'){
				include'page/form/personal_report/detail_personal_report.php';
			}
			// Change Password
			else if(@$_GET['page']=='change_password'){
				include'page/change_password/change_password.php';
			}// Change Password
			else if(@$_GET['page']=='assets_management'){
				include'page/assets_management/index.php';
			}
			//Form Stok Karton
			else if(@$_GET['page']=='form_stok_karton'){
				include'page/form_stok_karton/form_stok_karton.php';
			}
			//Verifikasi
			else if(@$_GET['page']=='verifikasi'){
				include'page/verifikasi/view_verifikasi.php';
			}
			else if(@$_GET['page']=='detail_verifikasi'){
				include'page/verifikasi/detail_verifikasi.php';
			}
			// Respon ketidaksesuaian
			else if(@$_GET['page']=='respon_ketidaksesuaian'){
				include'page/form_respon_ketidaksesuaian/index_respon_ketidaksesuaian.php';
			}
		}
	?>
  </div>
  <!-- /.content-wrapper -->

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->

  <!-- Main Footer -->
  <footer class="main-footer">
    <!-- To the right -->
    <div class="float-right d-none d-sm-inline">
      V.0.0.1
    </div>
    <!-- Default to the left -->
    <strong>Copyright &copy; 2026 <a href="#">SMART - Pro</a>.</strong> All rights reserved.
  </footer>
</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->
<script src="assets/plugins/jquery/jquery.min.js"></script>
<script src="assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/dist/js/adminlte.min.js"></script>
<!--<script src="assets/dist/js/demo.js"></script>-->
<script src="assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="assets/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="assets/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="assets/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="assets/plugins/jszip/jszip.min.js"></script>
<script src="assets/plugins/pdfmake/pdfmake.min.js"></script>
<script src="assets/plugins/pdfmake/vfs_fonts.js"></script>
<script src="assets/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="assets/plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="assets/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
<script src="assets/plugins/bootstrap4-duallistbox/jquery.bootstrap-duallistbox.min.js"></script>
<script src="assets/plugins/select2/js/select2.full.min.js"></script>
<script src="assets/plugins/html2canvas/html2canvas.min.js"></script>
<!-- date-range-picker -->
<script src="assets/plugins/daterangepicker/daterangepicker.js"></script>
<script>
	//Date range picker with time picker
    $('#reservationtime').daterangepicker({
      timePicker: true,
      timePickerIncrement: 30,
      locale: {
        format: 'MM/DD/YYYY hh:mm A'
      }
    })
	
	 //Initialize Select2 Elements
    $('.select2').select2()

    //Initialize Select2 Elements
    $('.select2bs4').select2({
      theme: 'bootstrap4'
    })


  $(function () {
    $("#example1").DataTable({
      "responsive": true, "lengthChange": false, "autoWidth": false,
      "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    
	$('#example2').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "responsive": true,
    });
	
	 $('table.display').each(function() {
        var table = $(this);
        var title = table.data('title'); // Ambil judul dari data-title
        var messageTop = table.data('messagetop'); // Ambil judul dari data-title

        table.DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "buttons": [
                {
                    extend: 'copy',
                    title: title, // Judul untuk tindakan copy
                    messageTop: messageTop // Pesan atau header tambahan jika diperlukan
                },
                {
                    extend: 'csv',
                    title: title, // Judul untuk tindakan CSV
					messageTop: messageTop

                },
                {
                    extend: 'excel',
                    title: title, // Judul untuk tindakan Excel
					messageTop: messageTop
                },
                {
                    extend: 'pdf',
                    title: title, // Judul untuk tindakan PDF
					messageTop: messageTop,
                    customize: function(doc) {
                        doc.content.splice(0, 1, {
                            text: title,
                            fontSize: 16,
                            alignment: 'center',
                            margin: [0, 0, 0, 20]
                        });
                    }
                },
                {
                    extend: 'print',
                    title: title, // Judul untuk tindakan Print
                    messageTop: messageTop, // Pesan atau header tambahan jika diperlukan
					 exportOptions: {
                        columns: ':not(:last-child)' // Tidak menyertakan kolom terakhir
                    }
                },
                "colvis"
            ]
        }).buttons().container().appendTo(table.closest('.dataTables_wrapper').find('.col-md-6:eq(0)'));
    });
	
  });

  
  
</script>
</body>
</html>
