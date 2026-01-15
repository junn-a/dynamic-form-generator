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
	
	// Bagian pertama adalah tanggal awal ($start) dan bagian kedua adalah tanggal akhir ($end).
	$start = $bagian[0];
	$end = $bagian[1];

	// Mengubah format tanggal ke "Y-m-d".
	$startFormatted = date("d M y", strtotime($start));
	//$tes = strtotime($startFormatted);
	$endFormatted = date("d M y", strtotime($end));
	
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
<div class="content">
  <div class="container">
	<div class="row">
	  
	  <!-- /.col-md-6 -->
	  <div class="col-lg-12">
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
					<div class="card-header">
						<h5 style="text-align:center">Resume Form Pelanggaran GMP</h5>
						<p style="text-align:center">Date <b><?=$startFormatted." - ".$endFormatted ?></b> / Shift <b><?=$shift?></b> / Regu <b><?=$regu?></b> / Line  <b><?php echo $nama_line ?></b></p>
					</div>
					<div class="card-body">
					<?php
					// Baca data JSON dari file
					$jsonFile =$_SERVER['DOCUMENT_ROOT'] . "/smart_pro/page/form/form/isi-" . $nama_form. ".json";
					$jsonContent = file_get_contents($jsonFile);

					// Ubah data JSON menjadi array PHP
					$data = json_decode($jsonContent, true);
					
					
					
					// Ambil nama kolom dari objek pertama
					$firstRow = reset($data);
					foreach ($firstRow as $key => $value) {
						//echo "<th>$key</th>";
					}
					$startDate 	= strtotime($startFormatted); // Tanggal mulai rentang
					$endDate 		= strtotime($endFormatted);   // Tanggal akhir rentang
					$groupedData = [];
					$jumlahPelanggaran = array();
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
								$nama = $item['nama'];
								$keterangan = $item['keterangan'];
    
								 // Jika nama belum ada dalam array $jumlahPelanggaran, inisialisasi jumlahnya dengan 1
								if (!isset($jumlahPelanggaran[$nama])) {
									$jumlahPelanggaran[$nama] = 1;
									$keteranganPelanggaran[$nama] = $keterangan; // Menyimpan keterangan pelanggaran
								} else {
									// Jika nama sudah ada dalam array $jumlahPelanggaran, tambahkan 1 ke jumlah pelanggarannya
									$jumlahPelanggaran[$nama]++;
									// Tambahkan keterangan pelanggaran jika sudah ada, dipisahkan oleh koma
									$keteranganPelanggaran[$nama] .= ', ' . $keterangan;
								}
								// Melakukan pengelompokan data berdasarkan tanggal dan jenis pelanggaran
								$tanggal = $item["tanggal"];
								$jenisPelanggaran = $item["jenis_pelanggaran"];

								// Menambahkan data ke dalam array kelompok yang sesuai dengan tanggal dan jenis pelanggaran
								if (!isset($groupedData[$tanggal])) {
									$groupedData[$tanggal] = [];
								}

								if (!isset($groupedData[$tanggal][$jenisPelanggaran])) {
									$groupedData[$tanggal][$jenisPelanggaran] = 1;
								} else {
									$groupedData[$tanggal][$jenisPelanggaran]++;
								}
							}
						}
					}
					
					//pie 
					$jenisPelanggaranFrequencies = [];

					foreach ($groupedData as $tanggal => $jenisPelanggaranData) {
						foreach ($jenisPelanggaranData as $jenisPelanggaran => $count) {
							if (!isset($jenisPelanggaranFrequencies[$jenisPelanggaran])) {
								$jenisPelanggaranFrequencies[$jenisPelanggaran] = $count;
							} else {
								$jenisPelanggaranFrequencies[$jenisPelanggaran] += $count;
							}
						}
					}
					$pieChartData = [];

					// Hitung pelanggaran terbanyak
					$jenisPelanggaranTerbanyak = '';
					$jumlahPelanggaranTerbanyak = 0;

					foreach ($jenisPelanggaranFrequencies as $jenisPelanggaran => $yValue) {
						if ($yValue > $jumlahPelanggaranTerbanyak) {
							$jenisPelanggaranTerbanyak = $jenisPelanggaran;
							$jumlahPelanggaranTerbanyak = $yValue;
						}
					}

					// Loop untuk membuat data point
					foreach ($jenisPelanggaranFrequencies as $jenisPelanggaran => $yValue) {
						$dataPoint = [
							'name' => $jenisPelanggaran,
							'y' => $yValue,
						];

						// Set 'sliced' dan 'selected' untuk pelanggaran terbanyak
						if ($jenisPelanggaran === $jenisPelanggaranTerbanyak) {
							$dataPoint['sliced'] = true;
							$dataPoint['selected'] = true;
						}

						$pieChartData[] = $dataPoint;
					}

					$chartData = [
						'name' => '',
						'colorByPoint' => true,
						'data' => $pieChartData,
					];

					$chartDataJson = json_encode([$chartData]);

					//$chartDataJson;
					//print_r($pieChartData);
					// Menampilkan hasil pengelompokan
					foreach ($groupedData as $tanggal => $jenisPelanggaran) {
						//echo "Tanggal: $tanggal\n";
						foreach ($jenisPelanggaran as $jenis => $jumlah) {
							//echo " - Jenis Pelanggaran: $jenis, Jumlah: $jumlah\n";
						}
						echo "\n";
					}
					$categories = []; // Ini akan menjadi label sumbu X pada grafik
					$seriesData = []; // Ini akan menjadi data untuk series pada grafik

					// Mengumpulkan semua tanggal yang ada dalam data pelanggaran
					$allDates = [];
					foreach ($groupedData as $tanggal => $jenisPelanggaran) {
						$allDates[] = $tanggal;
					}
					$allDates = array_unique($allDates);
					sort($allDates);

					// Inisialisasi data untuk setiap jenis pelanggaran
					foreach ($groupedData as $tanggal => $jenisPelanggaran) {
						foreach ($jenisPelanggaran as $jenis => $jumlah) {
							if (!isset($seriesData[$jenis])) {
								$seriesData[$jenis] = array_fill(0, count($allDates), 0);
							}
						}
					}

					// Mengisi data untuk setiap jenis pelanggaran pada tanggal tertentu
					foreach ($groupedData as $tanggal => $jenisPelanggaran) {
						foreach ($jenisPelanggaran as $jenis => $jumlah) {
							$index = array_search($tanggal, $allDates);
							$seriesData[$jenis][$index] = (int)$jumlah;
						}
					}

					$categories = $allDates;

					// Mengubah data ke dalam format yang diterima oleh Highcharts
					$series = [];
					$jenisPelanggaran = array_keys($seriesData); // Ambil jenis pelanggaran dari kunci array

					// Periksa jumlah elemen dalam $categories
					$numCategories = count($categories);
					//untuk grafik bar stacked
					foreach ($jenisPelanggaran as $jenis) {
						$data = [];

						// Periksa jumlah elemen dalam $seriesData[$jenis]
						if (isset($seriesData[$jenis])) {
							$jenisData = $seriesData[$jenis];
							for ($i = 0; $i < $numCategories; $i++) {
								if (isset($jenisData[$i])) {
									$data[] = (int)$jenisData[$i]; // Ubah ke integer
								} else {
									$data[] = 0; // Jika data tidak ada, set nilai menjadi 0
								}
							}
						}

						$series[] = [
							'name' => $jenis,
							'data' => $data,
							'stack' => 'jenis_pelanggaran'
						];
					}


					//print_r($seriesData);
					?>
						<div class="row">
							<div class="col-md-4">
								<div id="column"></div>
							</div>
							<div class="col-md-4">
								<div id="pie"></div>
							</div>
							<div class="col-md-4">
								<div id="">
									<b>Top 5 Pelanggar GMP</b>
									<?php 
									// Urutkan array $jumlahPelanggaran berdasarkan jumlah pelanggaran secara descending
									arsort($jumlahPelanggaran);

									// Ambil 5 nama teratas
									$top5 = array_slice($jumlahPelanggaran, 0, 5);

									// Tampilkan 5 nama teratas dan jumlah pelanggaran dalam tabel
									echo "<table class='table table-bordered' style='font-size:14px'>";
									echo "<tr><th>Nama</th><th>Jml</th><th>Keterangan</th></tr>";
									foreach ($top5 as $nama => $jumlah) {
										echo "<tr><td>$nama</td><td>$jumlah</td><td style='font-size:12px'>";
										// Tampilkan keterangan yang sesuai dengan nama
										if (isset($keteranganPelanggaran[$nama])) {
											echo $keteranganPelanggaran[$nama];
										} else {
											echo "Tidak ada keterangan";
										}
										echo "</td></tr>";
									}
									echo "</table>";
									?>
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
	  <!-- /.col-md-6 -->
	</div>
	<!-- /.row -->
  </div><!-- /.container-fluid -->
</div>

<script src="assets/plugins/highcharts/highcharts.js"></script>
<script src="assets/plugins/highcharts/exporting.js"></script>
<script src="assets/plugins/highcharts/export-data.js"></script>
<script src="assets/plugins/highcharts/accessibility.js"></script>

<script>
// Data retrieved from https://netmarketshare.com
Highcharts.chart('pie', {
    chart: {
        plotBackgroundColor: null,
        plotBorderWidth: 0, // Menghilangkan border
        plotShadow: false,
        type: 'pie',
		
    },
    title: {
        text: 'Sebaran Data Pelanggaran GMP',
        align: 'left',
		style: {
            fontSize: '14px' // Mengatur ukuran font judul chart
        }
    },
    tooltip: {
        pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
    },
    accessibility: {
        point: {
            valueSuffix: '%'
        }
    },
    credits: {
        enabled: false
    },
    
    plotOptions: {
        pie: {
            allowPointSelect: true,
            cursor: 'pointer',
            dataLabels: {
                enabled: true,
                format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                distance: -30, // Jarak negatif untuk menempatkan label di dalam pie
            },
            size: '80%',
            innerSize: '50%'
        }
    },
    series: [<?php echo json_encode($chartData); ?>]
});
// Data retrieved from https://en.wikipedia.org/wiki/Winter_Olympic_Games
Highcharts.chart('column', {
    chart: {
        type: 'column'
    },
    title: {
        text: 'Trend Pelanggaran GMP',
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
            text: 'Count pelanggaran'
        }
    },
    tooltip: {
        headerFormat: '<b>{point.x}</b><br/>',
        pointFormat: '{series.name}: {point.y}<br/>Total: {point.stackTotal}'
    },
    plotOptions: {
        column: {
            stacking: 'normal'
        }
    },
    series: <?php echo json_encode($series); ?> // Gunakan data stacking
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