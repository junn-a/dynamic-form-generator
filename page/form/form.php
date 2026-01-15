<?php
	$kode_form = $_GET['nama_form'];
	$selectForm = pg_query($conn,"SELECT * FROM tbl_form WHERE kode_form='$kode_form'");
	$fetchForm = pg_fetch_assoc($selectForm);
	
?>
<div class="content-header">
  <div class="container">
	<div class="row mb-2">
	  <div class="col-sm-6">
		<h1 class="m-0">  <small><?=$fetchForm['nama_form']?></small></h1>
	  </div><!-- /.col -->
	  <div class="col-sm-6">
		<ol class="breadcrumb float-sm-right">
		  <li class="breadcrumb-item"><a href="#">Home</a></li>
		  <li class="breadcrumb-item active">Form</li>
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
				
				  <form action="page/form/form_proses.php" method="post" enctype="multipart/form-data">
  <input type="text" class="form-control" name="nama_form" value="<?= $_GET['nama_form'] ?>" hidden>
  <div class="card-body">
    <div class="row">
      <?php
	  // Baca isi file JSON
      $nama_form = $_GET['nama_form'];
      $nama_file = "page/form/form/{$nama_form}.json";
      $jsonData = file_get_contents($nama_file);

      // Konversi data JSON ke dalam bentuk array PHP
      $dataArray = json_decode($jsonData, true);

      // Periksa apakah data 'datas' ada dalam array
      if (isset($dataArray['datas']) && is_array($dataArray['datas'])) {
        $datas = $dataArray['datas'];

        // Looping melalui elemen-elemen dalam $datas
        foreach ($datas as $item) {
		$loadDb = file_get_contents('db/db_master.json');
		$dataDb = json_decode($loadDb, true);
		
          echo '<div class="col-md-4">';
		  // Tanggal
         
		  if ($item == "tanggal_auto") {
            echo "<div class='form-group'><label>" . $item . "</label><input type='text' class='form-control' name='" . $item . "' value='" . $date . "'' readonly required></div>";
          }
		  else if (strpos($item, 'tanggal_verifikasi') !== false) {
            echo "<div class='form-group'><label>" . $item . "</label><input type='date' class='form-control' name='" . $item . "'  readonly ></div>";
          } 
		  else if (strpos($item, 'tanggal') !== false) {
            echo "<div class='form-group'><label>" . $item . "</label><input type='date' class='form-control' name='" . $item . "' required ></div>";
          } 
		  
		  //Jam
		  else if ($item == "jam") {
            echo "<div class='form-group'><label>" . $item . "</label><input type='time' class='form-control' name='" . $item . "' required></div>";
          }
		  else if ($item == "jam_auto") {
            echo "<div class='form-group'><label>" . $item . "</label><input type='time' class='form-control' name='" . $item . "' value='" . $time . "'' readonly required></div>";
          }
		  // ID
		  else if ($item == "id") {
            echo "<div class='form-group'><label>" . $item . "</label><input type='text' class='form-control' name='" . $item . "' placeholder='Tidak perlu di isi' disabled></div>";
          }
		  else if ($item == "nama_alat") {
            echo "<div class='form-group'><label>" . $item . "</label><input type='text' class='form-control' name='" . $item . "' required></div>";
          }
		   else if ($item == "nama_alat_suporting") {
			  $queryNamaAlatSuporting = pg_query($conn, "SELECT * FROM tbl_master_alat_suporting");

            echo "<div class='form-group'><label>" . $item . "</label><select onchange='getSelectedValue(this)' id='" . $item . "'  class='form-control select2bs4' name='" . $item . "' required>";
            echo "<option value=''>-Select Name-</option>";
            while ($dataAlat = pg_fetch_array($queryNamaAlatSuporting)) {
              echo "<option value='" . $dataAlat['id_alat'] . "-" . $dataAlat['nama_alat'] ."-" . $dataAlat['kategori_alat'] ."-" . $dataAlat['lokasi_alat'] . "'>" . $dataAlat['id_alat'] . "-" . $dataAlat['nama_alat'] ."-" . $dataAlat['kategori_alat'] ."-" . $dataAlat['lokasi_alat'] . "</option>";
		   }
		   echo "</select></div>";
          }
		   
		  // Nama
          else if ($item == "nama_produk") {
            $queryNamaProduk = pg_query($conn3, "SELECT * FROM master_produk");

            echo "<div class='form-group'><label>" . $item . "</label><select onchange='getSelectedValue(this)' id='" . $item . "'  class='form-control select2bs4' name='" . $item . "' required>";
            echo "<option value=''>-Select Name-</option>";
            while ($dataProduk = pg_fetch_array($queryNamaProduk)) {
              echo "<option value='" . $dataProduk['kode_material'] . "-" . $dataProduk['singkatan'] ."-" . $dataProduk['size'] ."-" . $dataProduk['brand'] . "-" . $dataProduk['isi'] . "'>" . $dataProduk['kode_material'] . "-" . $dataProduk['singkatan'] ."-" . $dataProduk['size'] ."-" . $dataProduk['brand'] . "-" . $dataProduk['isi'] . "</option>";
            }
            echo "</select></div>";
          }
		  // FORM SANITASI BUILDING
		  else if ($item == "nama_pic") {
			   echo "<div class='form-group'><label>" . $item . "</label><input type='text' class='form-control' name='" . $item . "' required></div>";
		   }
		  else if (strpos($item, 'kondisi_kebersihan') !== false ){
           echo "<div class='form-group'><label>" . $item . "</label><select class='form-control select2bs4' name='" . $item . "' required>";
				foreach($dataDb['kondisi_kebersihan'] AS $value){
					echo "<option value='".$value."'>".$value."</option>";
				}
            echo "</select></div>";
          }
		  else if (strpos($item, 'kategori_building') !== false ){
           echo "<div class='form-group'><label>" . $item . "</label><select class='form-control select2bs4' name='" . $item . "' required>";
				foreach($dataDb['kategori_building'] AS $value){
					echo "<option value='".$value."'>".$value."</option>";
				}
            echo "</select></div>";
          }
		  else if (strpos($item, 'nama_barang_building') !== false ){
           echo "<div class='form-group'><label>" . $item . "</label><input type='text' class='form-control' name='" . $item . "' required></div>";
          }
		  else if (strpos($item, 'area') !== false ){
           echo "<div class='form-group'><label>" . $item . "</label><select class='form-control select2bs4' name='" . $item . "' required>";
				foreach($dataDb['area'] AS $value){
					echo "<option value='".$value."'>".$value."</option>";
				}
            echo "</select></div>";
          }
		   // Klinik
		  else if (strpos($item, 'nama_klinik') !== false ){
           echo "<div class='form-group'><label>" . $item . "</label><input type='text' class='form-control' name='" . $item . "' required></div>";
          }
          // Nama
          else if (strpos($item, 'nama') !== false) {
            $queryNama = pg_query($conn2, "SELECT * FROM tbl_karyawan");

            echo "<div class='form-group'><label>" . $item . "</label><select class='form-control select2bs4' name='" . $item . "' required>";
            echo "<option value=''>-Select Name-</option>";
            while ($dataKaryawan = pg_fetch_array($queryNama)) {
              echo "<option value='" . $dataKaryawan['nama'] . "-" . $dataKaryawan['nik'] . "'>" . $dataKaryawan['nama'] . "-" . $dataKaryawan['nik'] . "</option>";
            }
            echo "</select></div>";
          }
		  // Regu
		  else if ($item == "regu") {
            echo "<div class='form-group'><label>" . $item . "</label><select class='form-control select2bs4' name='" . $item . "' required>";
				foreach($dataDb['regu'] AS $value){
					echo "<option value='".$value."'>".$value."</option>";
				}
            echo "</select></div>";
          }
		  //status serah terima
		  else if ($item == "status_serah_terima") {
            echo "<div class='form-group'><label>" . $item . "</label><select class='form-control select2bs4' name='" . $item . "' required>";
				foreach($dataDb['status_serah_terima'] AS $value){
					echo "<option value='".$value."'>".$value."</option>";
				}
            echo "</select></div>";
          }
		  // Shift
		  else if ($item == "shift") {
            echo "<div class='form-group'><label>" . $item . "</label><select class='form-control select2bs4' name='" . $item . "' required>";
				foreach($dataDb['shift'] AS $value){
					echo "<option value='".$value."'>".$value."</option>";
				}
            echo "</select></div>";
          }
		  // jenis_pelanggaran
		  else if ($item == "jenis_pelanggaran") {
            echo "<div class='form-group'><label>" . $item . "</label><select class='form-control select2bs4' name='" . $item . "' required>";
				foreach($dataDb['jenis_pelanggaran'] AS $value){
					echo "<option value='".$value."'>".$value."</option>";
				}
            echo "</select></div>";
          }
		  // brand
		  else if ($item == "brand") {
            echo "<div class='form-group'><label>" . $item . "</label><select class='form-control select2bs4' name='" . $item . "' required>";
				foreach($dataDb['brand'] AS $value){
					echo "<option value='".$value['singkatan']."'>".$value['nama']."</option>";
				}
            echo "</select></div>";
          }
		  // no mesin
		  else if ($item == "no_mesin") {
            echo "<div class='form-group'><label>" . $item . "</label><select class='form-control select2bs4' name='" . $item . "' required>";
				foreach($dataDb['mesin_packing'] AS $value){
					echo "<option value='".$value['no_mesin']."'>".$value['no_mesin']."</option>";
				}
            echo "</select></div>";
          }
		  // line
		  else if ($item == "line") {
            echo "<div class='form-group'><label>" . $item . "</label><select class='form-control select2bs4' name='" . $item . "' required>";
				foreach($dataDb['line'] AS $value){
					echo "<option value='".$value['id_line']."'>".$value['nama_line']."</option>";
				}
            echo "</select></div>";
          }
		  // rasa
		  else if ($item == "rasa") {
            echo "<div class='form-group'><label>" . $item . "</label><input type='text' class='form-control' name='" . $item . "' required></div>";
          }
		  // Kondisi Pack
		  else if (strpos($item, 'kondisi_pack') !== false ){
           echo "<div class='form-group'><label>" . $item . "</label><select class='form-control select2bs4' name='" . $item . "' required>";
				foreach($dataDb['kondisi_pack'] AS $value){
					echo "<option value='".$value."'>".$value."</option>";
				}
            echo "</select></div>";
          }
		  // Keperluan Ambil FG
		  else if (strpos($item, 'keperluan_ambil_fg') !== false ){
           echo "<div class='form-group'><label>" . $item . "</label><select class='form-control select2bs4' name='" . $item . "' required>";
				foreach($dataDb['keperluan_ambil_fg'] AS $value){
					echo "<option value='".$value."'>".$value."</option>";
				}
            echo "</select></div>";
          }
		  // Total waste Form Reject Mesin
		  else if (strpos($item, 'waste') !== false) {
           echo "<div class='form-group'><label>" . $item . "</label><input type='number' id='" . $item . "' oninput='calculateWaste()' class='form-control' name='" . $item . "' step='any' required></div>";
          }
		  else if ($item == "total_reject_kg") {
           echo "<div class='form-group'><label>" . $item . "</label><input type='number' id='" . $item . "' oninput='getSelectedValue(document.getElementById(\"nama_produk\"))' class='form-control' name='" . $item . "' step='any' required readonly></div>";
          }
		  
		  else if ($item == "pack_per_kg") {
			  echo "<div class='form-group'><label>" . $item . "</label><input type='number' id='" . $item . "' oninput='getSelectedValue(document.getElementById(\"nama_produk\"))' class='form-control' name='" . $item . "' step='any' required></div>";
			}
		  else if ($item == "hasil_prod_ctn") {
			echo "<div class='form-group'><label>" . $item . "</label><input type='number' id='" . $item . "' oninput='getSelectedValue(document.getElementById(\"nama_produk\"))' class='form-control' name='" . $item . "' step='any' required></div>";
		  }
		  else if ($item == "var_persen") {
           echo "<div class='form-group'><label>" . $item . "</label><input type='number' id='" . $item . "' class='form-control' name='" . $item . "' step='any' required readonly></div>";
          }
		  // Total Kg Form Pengambilan FG
		  else if ($item == "size_gram") {
           echo "<div class='form-group'><label>" . $item . "</label><input type='number' id='" . $item . "' oninput='calculateKg()' class='form-control' name='" . $item . "' step='any' required ></div>";
          }
		  else if ($item == "jumlah_pack") {
           echo "<div class='form-group'><label>" . $item . "</label><input type='number' id='" . $item . "' oninput='calculateKg()' class='form-control' name='" . $item . "' step='any' required ></div>";
          }
		  else if ($item == "jumlah_kg") {
           echo "<div class='form-group'><label>" . $item . "</label><input type='number' id='" . $item . "' class='form-control' name='" . $item . "' step='any' required readonly></div>";
          }
		  // Nilai kg, cm,
		  else if (strpos($item, 'kg') !== false || strpos($item, 'meter') !== false || strpos($item, 'gram') !== false || strpos($item, 'nilai') !== false || strpos($item, 'ctn') !== false || strpos($item, 'persen') !== false || strpos($item, 'pack') !== false || strpos($item, 'jumlah') !== false  || strpos($item, 'pcs') !== false) {
           echo "<div class='form-group'><label>" . $item . "</label><input type='number' class='form-control' name='" . $item . "' step='any' required></div>";
          }
		  //FORM SP
		  // status_karyawan
		  else if (strpos($item, 'status_karyawan') !== false ){
           echo "<div class='form-group'><label>" . $item . "</label><select class='form-control select2bs4' name='" . $item . "' required>";
				foreach($dataDb['status_karyawan'] AS $value){
					echo "<option value='".$value."'>".$value."</option>";
				}
            echo "</select></div>";
          }
		  // perihal sp
		  else if (strpos($item, 'perihal_sp') !== false ){
           echo "<div class='form-group'><label>" . $item . "</label><select class='form-control select2bs4' name='" . $item . "' required>";
				foreach($dataDb['perihal_sp'] AS $value){
					echo "<option value='".$value."'>".$value."</option>";
				}
            echo "</select></div>";
          }
		  // Pasal PKB
		  else if (strpos($item, 'pasal_pkb') !== false ){
           echo "<div class='form-group'><label>" . $item . "</label><select class='form-control select2bs4' name='" . $item . "' required>";
				foreach($dataDb['pasal_pkb'] AS $value){
					echo "<option value='".$value['no_pasal']. "-" .$value['isi']."'>".$value['no_pasal']. "-" .$value['isi']."</option>";
				}
            echo "</select></div>";
          }
		  // Pasal PKB
		  else if (strpos($item, 'kesalahan_karyawan') !== false ){
           echo "<div class='form-group'><label>" . $item . "</label><textarea type='text' class='form-control' name='" . $item . "' required></textarea></div>";
          }
		  else if (strpos($item, 'lt') !== false ){
           echo "<div class='form-group'><label>" . $item . "</label><textarea type='text' class='form-control' name='" . $item . "' required></textarea></div>";
          }
		  // Keperluan Ambil FG
		  else if (strpos($item, 'kondisi') !== false ){
           echo "<div class='form-group'><label>" . $item . "</label><select class='form-control select2bs4' name='" . $item . "' required>";
				foreach($dataDb['kondisi'] AS $value){
					echo "<option value='".$value."'>".$value."</option>";
				}
            echo "</select></div>";
          }
		  // perihal sp
		  else if (strpos($item, 'jenis_absensi') !== false ){
           echo "<div class='form-group'><label>" . $item . "</label><select class='form-control select2bs4' name='" . $item . "' required>";
				foreach($dataDb['jenis_absensi'] AS $value){
					echo "<option value='".$value."'>".$value."</option>";
				}
            echo "</select></div>";
          }else if(strpos($item, 'img') !== false ){
			  echo "<div class='form-group'><label>" . $item . "</label>
						<input type='file' class='form-control'  name='" . $item . "' accept='img/*' required>
						</div>";
		  }
		  
		  else{
            echo "<div class='form-group'><label>" . $item . "</label><input type='text' class='form-control' name='" . $item . "' required></div>";
          }
          echo '</div>'; // Close col-md-6 div
        }
      }
      ?>
    </div> <!-- Close row div -->
  </div>
  <!-- /.card-body -->

  <div class="card-footer">
    <button type="submit" class="btn btn-primary">Submit</button>
    <a href="?page=rekap_form&nama_form=<?= $_GET['nama_form'] ?>" class="btn btn-warning">Lihat Rekap</a>
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

<script>
	$(document).ready(function() {
		$("#add-input").click(function() {
			$("#input-container").append('<input type="text" class="form-control" name="data[]"><br>');
		});
	});
	
	
	// Hitung Otomatis
	// form reject mesin
	function calculateWaste() {
		const bocor_kg = parseFloat(document.getElementById("waste_bocor_kg").value);
		const non_bocor_kg = parseFloat(document.getElementById("waste_non_bocor_kg").value);
		const start_up_kg = parseFloat(document.getElementById("waste_start_up_kg").value);
		const shut_down_kg = parseFloat(document.getElementById("waste_shut_down_kg").value);
		const reject_aval_kg = parseFloat(document.getElementById("waste_reject_aval_kg").value);
		const total_reject_kg = document.getElementById("total_reject_kg");

		if (!isNaN(bocor_kg) && !isNaN(non_bocor_kg) && !isNaN(start_up_kg) && !isNaN(shut_down_kg) && !isNaN(reject_aval_kg)) {
			const total = bocor_kg + non_bocor_kg + start_up_kg + shut_down_kg + reject_aval_kg;
			total_reject_kg.value = total;
		} else {
			total_reject_kg.value = "0";
		}
	}
	
	function getSelectedValue(selectElement) {
		 const pack_per_kg = parseFloat(document.getElementById("pack_per_kg").value);
		 const hasil_prod_ctn = parseFloat(document.getElementById("hasil_prod_ctn").value);
		 const total_reject_kg = parseFloat(document.getElementById("total_reject_kg").value);
		  // Mendapatkan nilai yang dipilih dalam elemen select
		  const selectedValue = selectElement.value;

		  // Memisahkan nilai dengan tanda "-"
		  const parts = selectedValue.split('-');

		  // Mendapatkan bagian yang kedua (indeks 1) dari array
		  const valueToSend = ((total_reject_kg*pack_per_kg)/(hasil_prod_ctn*parts[4]))*100;

		  // Gunakan nilai ini sesuai kebutuhan, misalnya mengisinya ke input "var_persen"
		  document.getElementById('var_persen').value = valueToSend;
	}
	
	// form ambil fg
	function calculateKg() {
		const size_gram = parseFloat(document.getElementById("size_gram").value);
		const jumlah_pack = parseFloat(document.getElementById("jumlah_pack").value);
		const jumlah_kg = document.getElementById("jumlah_kg");

		if (!isNaN(size_gram) && !isNaN(jumlah_pack)) {
			const total = size_gram * jumlah_pack /1000;
			jumlah_kg.value = total;
		} else {
			jumlah_kg.value = "0";
		}
	}
</script>