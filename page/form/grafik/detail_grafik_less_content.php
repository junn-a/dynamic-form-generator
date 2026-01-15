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
						<h5 style="text-align:center">Resume Less Content & Empty Pack</h5>
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
					$empty_pack_data = array();
					

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
								// Var
								
								$nama_operator	= $item['nama_operator'];
								$no_mesin 			= $item['no_mesin'];
								$empty_pack 			= floatval($item['empty_pack_pcs']); // Ubah ke tipe data float
								$less_content 			= floatval($item['less_content_pcs']); // Ubah ke tipe data float
						
								// Tambahkan nilai var_persen ke array dengan kunci no_mesin // by mesin
								if (!isset($empty_pack_data[$no_mesin])) {
									$empty_pack_data[$no_mesin] = array(
										'total_empty_pack' => 0,
										'count_empty_pack' => 0,
										'total_less_content' => 0,
										'count_less_content' => 0,
									);
								}

								$empty_pack_data[$no_mesin]['total_empty_pack'] += $empty_pack;
								$empty_pack_data[$no_mesin]['count_empty_pack']++;

								$empty_pack_data[$no_mesin]['total_less_content'] += $less_content;
								$empty_pack_data[$no_mesin]['count_less_content']++;
								
								// Tambahkan nilai var_persen ke array dengan kunci no_mesin // by opr
								if (!isset($empty_pack_data_opr[$nama_operator])) {
									$empty_pack_data_opr[$nama_operator] = array(
										'total' => 0,
										'count' => 0,
										'total_less_content' => 0,
										'count_less_content' => 0,
									);
								}

								$empty_pack_data_opr[$nama_operator]['total'] += $empty_pack;
								$empty_pack_data_opr[$nama_operator]['count']++;

								$empty_pack_data_opr[$nama_operator]['total_less_content'] += $less_content;
								$empty_pack_data_opr[$nama_operator]['count_less_content']++;
								
								
							}
						}
					}
					// Data untuk grafik
					$categories = []; // Ini akan menjadi label sumbu X pada grafik
					$seriesDataEmptyPack = []; // Ini akan menjadi data untuk series empty_pack pada grafik
					$seriesDataLessContent = []; // Ini akan menjadi data untuk series less_content pada grafik

					// Setelah mengumpulkan data, hitung rata-rata untuk setiap nomor mesin
					foreach ($empty_pack_data as $no_mesin => $values) {
						$empty_pack = $values['total_empty_pack'];
						$less_content = $values['total_less_content'];

						// Tambahkan nomor mesin ke array $categories
						$categories[] = $no_mesin;

						// Tambahkan nilai rata-rata empty_pack ke array $seriesDataEmptyPack
						$seriesDataEmptyPack[] = $empty_pack;

						// Tambahkan nilai rata-rata less_content ke array $seriesDataLessContent
						$seriesDataLessContent[] = $less_content;
					}
					
					// Buat array baru untuk menyimpan data rata-rata bersama dengan nama operator dan nomor mesin // by mesin
					$topAverages = [];
					
					foreach ($empty_pack_data as $no_mesin => $values) {
						$empty_pack = $values['total_empty_pack'];
						$less_content = $values['total_less_content'];

						// Hitung Loss Content (empty_pack - less_content)
						$loss_content = $empty_pack - $less_content;

						$topAverages[] = [
							'no_mesin' => $no_mesin,
							'empty_pack' => $empty_pack,
							'less_content' => $less_content,
						];
					}
					
					// Buat array baru untuk menyimpan data rata-rata bersama dengan nama operator dan nomor mesin // by opr
					//$topAverages_opr = [];
					$topAverages_opr = [];
					
					foreach ($empty_pack_data_opr as $nama_operator => $values) {
						$average_opr = $values['total'];
						$less_content_opr = isset($values['total_less_content']) ? $values['total_less_content'] : 0;

						$topAverages_opr[] = [
							'nama_operator' => $nama_operator,
							'average_opr' => $average_opr,
							'less_content_opr' => $less_content_opr,
						];
					}
					
					// Data waste
				

					

					//print_r($categories);
					//print_r($seriesData);
					?>
						<div class="row">
							<div class="col-md-6">
								<div id="column"></div>
							</div>
							
							<div class="col-md-2">
								<div id="">
									<b>Top 5 Empty Pack by Mesin</b>
									<?php 
										// Urutkan array berdasarkan nilai rata-rata dari yang tertinggi ke yang terendah
										usort($topAverages, function ($a, $b) {
											return $b['empty_pack'] <=> $a['empty_pack'];
										});

										// Ambil 5 data pertama dari array yang telah diurutkan
										$top5Averages = array_slice($topAverages, 0, 5);
										echo "<table class='table table-bordered' style='font-size:14px'>";
										echo '<tr><th>No MC</th><th>EP</th><th>LC</th></tr>';
										foreach ($top5Averages as $data) {
											echo '<tr>';
											echo '<td>' . $data['no_mesin'] . '</td>';
											echo '<td>' . number_format($data['empty_pack'],0) . '</td>';
											echo '<td>' . number_format($data['less_content'],0) . '</td>';
											echo '</tr>';
										}
										echo "</table>";
									?>
								</div>
							</div>
							<div class="col-md-4">
								<div id="">
									<b>Top 5 Empty Pack by Operator</b>
									<?php 
										// Urutkan array berdasarkan nilai rata-rata dari yang tertinggi ke yang terendah
										usort($topAverages_opr, function ($a, $b) {
											return $b['average_opr'] <=> $a['average_opr'];
										});

										// Ambil 5 data pertama dari array yang telah diurutkan
										$topAverages_opr = array_slice($topAverages_opr, 0, 5);
										echo "<table class='table table-bordered' style='font-size:14px'>";
										echo '<tr><th>Nama Operator</th><th>EP</th><th>LC</th></tr>';
										foreach ($topAverages_opr as $data) {
											echo '<tr>';
											echo '<td>' . $data['nama_operator'] . '</td>';
											echo '<td>' . number_format($data['average_opr'],0) . '</td>';
											echo '<td>' . number_format($data['less_content_opr'],0) . '</td>';
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
        text: 'Total Empty Pack Per Mesin',
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
        title: {
            text: 'Total (Pcs)'
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
                    return this.y.toFixed(0);
                }
            }
        }
    },
    series: [{
        name: 'Empty Pack',
        data: <?php echo json_encode($seriesDataEmptyPack); ?>,
		color: '#dc3912'
    },
	{
        name: 'Less Content',
        data: <?php echo json_encode($seriesDataLessContent); ?>,
		color: '#3366cc'
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