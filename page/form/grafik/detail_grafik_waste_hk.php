<?php
	$tanggal 	=  $_POST['tanggal_range'];
	$shift 		=  $_POST['shift'];
	$regu 		=  $_POST['regu'];
	$line 		=  $_POST['line'];
	
	$nama_form = $_POST['nama_form'];
	
	$bagian = explode(" - ", $tanggal);

	
	$loadDb = file_get_contents('db/db_master.json');
	$dataDb = json_decode($loadDb, true);
	$found = false; // Inisialisasi variabel penanda pencocokan
	$nama_line = null; // Inisialisasi variabel nama_line
	if (is_array($dataDb) && isset($dataDb['line'])) {
		foreach ($dataDb['line'] as $value) {
			if ($value['id_line'] == $line) {
				$found = true; // Pencocokan ditemukan
				$nama_line = $value['nama_line'];
				break; // Keluar dari loop setelah pencocokan ditemukan
			}else{
				$nama_line = "all";
			}
		}
	}
	//echo $nama_line;
				
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
	  <div class="col-sm-8">
	  </div><!-- /.col -->
	  <div class="col-sm-4">
		<ol class="breadcrumb float-sm-right">
		  <li class="breadcrumb-item"><a href="#">Home</a></li>
		  <li class="breadcrumb-item active">Rekap</li>
		</ol>
	  </div><!-- /.col -->
	</div><!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
<div class="content" >
  <div class="container">
	<div class="row" >
	  
	  <!-- /.col-md-6 -->
	  <div class="col-lg-12" >
		<div class="card card-primary card-outline">
			<div class="card-header">
                <!-- Ikon Kamera -->
				<i class="fa fa-window-maximize float-right" style="margin-right:0px"></i>
                <i class="fa fa-camera float-right" style="margin-right:20px" id="convertToImage"></i>
            </div>
		  <div class="card-body">
		  <div class="row">
			<div class="col-md-12" id="divToConvert">
				<!-- general form elements -->
				<div class="card">
					<div class="card-header" >
						<h5 style="text-align:center">Resume Form Waste Housekeeping</h5>
						<p style="text-align:center">Date <b><?=$startFormatted." - ".$endFormatted ?></b> / Shift <b><?=$shift?></b> / Regu <b><?=$regu?></b> / Line  <b><?php echo $nama_line ?></b></p>
					</div>
					<div class="card-body">
					<?php
					// Baca data JSON dari file
					$jsonFile =$_SERVER['DOCUMENT_ROOT'] . "/smart_pro/page/form/form/isi-" . $nama_form. ".json";
					$jsonContent = file_get_contents($jsonFile);

					// Ubah data JSON menjadi array PHP
					$data = json_decode($jsonContent, true);
					
					$startDate 	= strtotime($startFormatted); // Tanggal mulai rentang
					$endDate 		= strtotime($endFormatted);   // Tanggal akhir rentang
					
					// Inisialisasi array untuk menyimpan nilai var_persen untuk setiap mesin
					$var_persen_data = array();
					
					$berat_waste_data = array();
					

					foreach ($data as $item) {
						$tanggal = strtotime($item['tanggal']); // Ganti 'tanggal' dengan nama kolom tanggal dalam JSON

						// Periksa apakah tanggal berada dalam rentang yang diinginkan
						if ($tanggal >= $startDate && $tanggal <= $endDate) {
							if (
								($_POST['shift'] == 'all' || $_POST['shift'] == $item['shift']) &&
								($_POST['regu'] == 'all' || $_POST['regu'] == $item['regu']) &&
								($_POST['line'] == 'all' || $_POST['line'] == $item['line']) &&
								$_POST['nama_form'] == $item['nama_form']
							) {
								// Waste
														
								// Var
								
								
								$nama_waste = $item['nama_waste'];
								$berat_waste = floatval($item['berat_waste']); // Ubah ke tipe data float
						
								// Tambahkan nilai var_persen ke array dengan kunci no_mesin // by mesin
								if (!isset($berat_waste_data[$nama_waste])) {
									$berat_waste_data[$nama_waste] = array(
										'total' => 0,
										'count' => 0,
									);
								}

								$berat_waste_data[$nama_waste]['total'] += $berat_waste;
								$berat_waste_data[$nama_waste]['count']++;
								
							}
						}
					}
					//data untuk grafik
					$categories = []; // Ini akan menjadi label sumbu X pada grafik
					$seriesData = []; // Ini akan menjadi data untuk series pada grafik

					// Setelah mengumpulkan data, hitung rata-rata untuk setiap nomor mesin
					foreach ($berat_waste_data as $berat_waste => $values) {
						$average = $values['total'];
						// Tambahkan nomor mesin ke array $categories
						$categories[] = $berat_waste;
						// Tambahkan nilai rata-rata ke array $seriesData
						$seriesData[] = $average;
						
					}
					//print_r($categories);
					//print_r($seriesData);
					?>
						<div class="row">
							<div class="col-md-12">
								<div id="column"></div>
							</div>
						</div>
						
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


<!--<script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>-->
<script src="assets/plugins/highcharts/highcharts.js"></script>
<script src="assets/plugins/highcharts/exporting.js"></script>
<script src="assets/plugins/highcharts/export-data.js"></script>
<script src="assets/plugins/highcharts/accessibility.js"></script>

<script>
//column var 
Highcharts.chart('column', {
    chart: {
        type: 'column'
    },
    title: {
        text: 'Waste Housekeeping',
        align: 'left',
        style: {
            fontSize: '14px' // Mengatur ukuran font judul chart
        }
    },
    xAxis: {
        categories: <?php echo json_encode($categories); ?>
    },
    credits: {
        enabled: false
    },
    yAxis: {
        allowDecimals: false,
        min: 0,
        title: {
            text: 'Sum Kg'
        }
    },
    tooltip: {
        headerFormat: '<b>{point.x}</b><br/>',
        pointFormat: '{series.name}: {point.y}<br/>Total: {point.stackTotal}'
    },
    plotOptions: {
        column: {
            stacking: 'normal',
            dataLabels: {
                enabled: true, // Aktifkan data label
                color: 'black', // Warna teks label
                style: {
                    fontSize: '12px' // Gaya teks label
                },
                formatter: function () {
                    // Format label agar hanya menampilkan 2 angka dibelakang koma
                    return this.y.toFixed(2);
                }
            }
        }
    },
    series: [{
        name: 'Waste Housekeeping',
        data: <?php echo json_encode($seriesData); ?>
    }] // Gunakan data stacking
});


// take pict
document.getElementById('convertToImage').addEventListener('click', function () {
   // Temukan elemen div yang akan diubah menjadi gambar
   var divToConvert = document.getElementById('divToConvert');

   // Gunakan library html2canvas untuk merender div menjadi gambar
   html2canvas(divToConvert, {
       backgroundColor: '#FFFFFF' // Atur latar belakang menjadi putih
   }).then(function(canvas) {
       // Buat elemen <a> untuk mengunduh gambar
       var a = document.createElement('a');
       a.href = canvas.toDataURL('image/png'); // Konversi gambar ke format PNG
       a.download = 'div_image.png'; // Nama file untuk pengunduhan
       a.style.display = 'none';

       // Tambahkan elemen <a> ke dalam dokumen
       document.body.appendChild(a);

       // Klik secara otomatis pada elemen <a> untuk memulai pengunduhan
       a.click();

       // Hapus elemen <a> setelah pengunduhan
       document.body.removeChild(a);
   });
});
</script>