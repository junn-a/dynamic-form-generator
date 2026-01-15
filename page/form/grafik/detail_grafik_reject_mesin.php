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
						<h5 style="text-align:center">Resume Form Reject Mesin Packing</h5>
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
					$total_bocor_kg = 0;
					$total_non_bocor_kg = 0;
					$total_start_up_kg = 0;
					$total_shut_down_kg = 0;
					$total_reject_aval_kg = 0;

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
								$bocor_kg 			= $item['waste_bocor_kg'];
								$non_bocor_kg 	= $item['waste_non_bocor_kg'];
								$start_up_kg 		= $item['waste_start_up_kg'];
								$shut_down_kg 		= $item['waste_shut_down_kg'];
								$reject_aval_kg 		= $item['waste_reject_aval_kg'];
								
								 // Menambahkan nilai waste ke total
								$total_bocor_kg += $bocor_kg;
								$total_non_bocor_kg += $non_bocor_kg;
								$total_start_up_kg += $start_up_kg;
								$total_shut_down_kg += $shut_down_kg;
								$total_reject_aval_kg += $reject_aval_kg;
								
								// Var
								
								$nama_operator = $item['nama_operator'];
								$no_mesin = $item['no_mesin'];
								$var_persen = floatval($item['var_persen']); // Ubah ke tipe data float
						
								// Tambahkan nilai var_persen ke array dengan kunci no_mesin // by mesin
								if (!isset($var_persen_data[$no_mesin])) {
									$var_persen_data[$no_mesin] = array(
										'total' => 0,
										'count' => 0,
									);
								}

								$var_persen_data[$no_mesin]['total'] += $var_persen;
								$var_persen_data[$no_mesin]['count']++;
								
								// Tambahkan nilai var_persen ke array dengan kunci no_mesin // by opr
								if (!isset($var_persen_data_opr[$nama_operator])) {
									$var_persen_data_opr[$nama_operator] = array(
										'total' => 0,
										'count' => 0,
									);
								}

								$var_persen_data_opr[$nama_operator]['total'] += $var_persen;
								$var_persen_data_opr[$nama_operator]['count']++;
								
								
							}
						}
					}
					//data untuk grafik
					$categories = []; // Ini akan menjadi label sumbu X pada grafik
					$seriesData = []; // Ini akan menjadi data untuk series pada grafik

					// Setelah mengumpulkan data, hitung rata-rata untuk setiap nomor mesin
					foreach ($var_persen_data as $no_mesin => $values) {
						$average = $values['total'] / $values['count'];
						// Tambahkan nomor mesin ke array $categories
						$categories[] = $no_mesin;
						// Tambahkan nilai rata-rata ke array $seriesData
						$seriesData[] = $average;
						
					}
					
					// Buat array baru untuk menyimpan data rata-rata bersama dengan nama operator dan nomor mesin // by mesin
					$topAverages = [];
					foreach ($var_persen_data as $no_mesin => $values) {
						$average = $values['total'] / $values['count'];
						//$nama_operator = $item['nama_operator']; // Ganti ini sesuai dengan kolom nama operator dalam JSON
						$topAverages[] = [
							'no_mesin' => $no_mesin,
							//'nama_operator' => $nama_operator,
							'average' => $average,
						];
					}
					
					// Buat array baru untuk menyimpan data rata-rata bersama dengan nama operator dan nomor mesin // by opr
					$topAverages_opr = [];
					foreach ($var_persen_data as $nama_operator => $values) {
						$average = $values['total'] / $values['count'];
					}
					foreach ($var_persen_data_opr as $nama_operator => $values) {
						$average_opr = $values['total'] / $values['count'];
						//$nama_operator = $item['nama_operator']; // Ganti ini sesuai dengan kolom nama operator dalam JSON
						$topAverages_opr[] = [
							//'no_mesin' => $no_mesin,
							'nama_operator' => $nama_operator,
							'average_opr' => $average_opr,
						];
					}
					
					// Data waste
					$wasteData = array(
						array(
							'name' => 'Bocor',
							'y' => $total_bocor_kg,
						),
						array(
							'name' => 'Non Bocor',
							'y' => $total_non_bocor_kg,
						),
						array(
							'name' => 'Start Up',
							'y' => $total_start_up_kg,
						),
						array(
							'name' => 'Shut Down',
							'y' => $total_shut_down_kg,
						),
						array(
							'name' => 'Reject Aval',
							'y' => $total_reject_aval_kg,
						)
					);
					// Mengambil nama waste dari data dan mengubahnya menjadi array kategori
					$wasteCategories = array();
					foreach ($wasteData as $dataPoint) {
						$wasteCategories[] = $dataPoint['name'];
					}

					

					//print_r($categories);
					//print_r($seriesData);
					?>
						<div class="row">
							<div class="col-md-6">
								<div id="column"></div>
							</div>
							
							<div class="col-md-2">
								<div id="">
									<b>Top 5 Var by Mesin</b>
									<?php 
										// Urutkan array berdasarkan nilai rata-rata dari yang tertinggi ke yang terendah
										usort($topAverages, function ($a, $b) {
											return $b['average'] <=> $a['average'];
										});

										// Ambil 5 data pertama dari array yang telah diurutkan
										$top5Averages = array_slice($topAverages, 0, 5);
										echo "<table class='table table-bordered' style='font-size:14px'>";
										echo '<tr><th>No Mesin</th><th>Avg Var</th></tr>';
										foreach ($top5Averages as $data) {
											echo '<tr>';
											echo '<td>' . $data['no_mesin'] . '</td>';
											echo '<td>' . number_format($data['average'],3) . '</td>';
											echo '</tr>';
										}
										echo "</table>";
									?>
								</div>
							</div>
							<div class="col-md-4">
								<div id="">
									<b>Top 5 Var by Operator</b>
									<?php 
										// Urutkan array berdasarkan nilai rata-rata dari yang tertinggi ke yang terendah
										usort($topAverages_opr, function ($a, $b) {
											return $b['average_opr'] <=> $a['average_opr'];
										});

										// Ambil 5 data pertama dari array yang telah diurutkan
										$topAverages_opr = array_slice($topAverages_opr, 0, 5);
										echo "<table class='table table-bordered' style='font-size:14px'>";
										echo '<tr><th>Operator</th><th>Avg Var</th></tr>';
										foreach ($topAverages_opr as $data) {
											echo '<tr>';
											echo '<td>' . $data['nama_operator'] . '</td>';
											echo '<td>' . number_format($data['average_opr'],3) . '</td>';
											echo '</tr>';
										}
										echo "</table>";
									?>
								</div>
							</div>
							<!--
							<div class="col-md-4">
								<div id="">
									<b>Top 5 Usage Var Tertinggi</b>
									<?php 
									
									?>
								</div>
							</div>-->
						</div>
						<div class="row">
							<div class="col-md-6">
								<div id="column-waste"></div>
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
        text: 'Rata Rata Usage Var Per Mesin',
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
        max: 1.4, // Mengatur nilai maksimum sumbu Y
		tickInterval: 0.2,
        title: {
            text: 'Count var'
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
                    return this.y.toFixed(3);
                }
            }
        }
    },
    series: [{
        name: 'Var Persen Average',
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
//column waste
Highcharts.chart('column-waste', {
    chart: {
        type: 'column'
    },
    title: {
        text: 'Waste Packing',
        align: 'left',
        style: {
            fontSize: '14px' // Mengatur ukuran font judul chart
        }
    },
    xAxis: {
        categories: <?php echo json_encode($wasteCategories); ?>
    },
    credits: {
        enabled: false
    },
    yAxis: {
        allowDecimals: false,
        min: 0,
        max: 5, // Mengatur nilai maksimum sumbu Y
		tickInterval: 1,
        title: {
            text: 'Kg'
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
                    return this.y.toFixed(3);
                }
            }
        }
    },
    series: [{
        name: 'Waste',
        data: <?php echo json_encode($wasteData); ?>
    }] // Gunakan data stacking
});
</script>