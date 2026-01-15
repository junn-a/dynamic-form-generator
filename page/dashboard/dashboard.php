<?php
	$queryJmlForm = pg_query($conn,"SELECT * FROM tbl_form WHERE departemen='$_SESSION[departemen]'");
	$jmlForm = pg_num_rows($queryJmlForm);
	//echo $count;
	
	$queryJmlUser = pg_query($conn2,"SELECT * FROM tbl_user WHERE departemen='$_SESSION[departemen]'");
	$jmlUser = pg_num_rows($queryJmlUser);
	
	
	
	// Path ke folder dengan file JSON
$folderPath = 'C:/xampp/htdocs/smart_pro/page/form/form';

// Fungsi untuk menghitung ukuran folder
function folderSize($dir)
{
    $size = 0;
    foreach (glob(rtrim($dir, '/').'/*', GLOB_NOSORT) as $each) {
        $size += is_file($each) ? filesize($each) : folderSize($each);
    }
    return $size;
}

// Fungsi untuk mengkonversi ukuran file menjadi format yang lebih mudah dibaca
function formatSize($bytes)
{
    $units = ['B', 'KB', 'MB', 'GB', 'TB'];

    for ($i = 0; $bytes >= 1024 && $i < 4; $i++) {
        $bytes /= 1024;
    }

    return round($bytes, 2).' '.$units[$i];
}

// Hitung ukuran folder
$folderSizeBytes = folderSize($folderPath);
$folderSizeFormatted = formatSize($folderSizeBytes);

?>
<div class="content-header">
  <div class="container">
	<div class="row mb-2">
	  <div class="col-sm-6">
		<h1 class="m-0">  <small>Dashboard </small></h1>
	  </div><!-- /.col -->
	  <div class="col-sm-6">
		<ol class="breadcrumb float-sm-right">
		  <li class="breadcrumb-item"><a href="#">Home</a></li>
		</ol>
	  </div><!-- /.col -->
	</div><!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
<div class="content">
  <div class="container">
	<div class="row">
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-info">
              <div class="inner">
                <h3><?=$jmlForm?></h3>

                <p>Form Active</p>
              </div>
              <div class="icon">
                <i class="ion ion-bag"></i>
              </div>
              <a href="#" class="small-box-footer"></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-success">
              <div class="inner">
                <h3><?=$jmlUser?></h3>

                <p>User</p>
              </div>
              <div class="icon">
                <i class="ion ion-stats-bars"></i>
              </div>
              <a href="#" class="small-box-footer"></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-warning">
              <div class="inner">
                <h3><?=$folderSizeFormatted?></h3>

                <p>Data Storage</p>
              </div>
              <div class="icon">
                <i class="ion ion-person-add"></i>
              </div>
              <a href="#" class="small-box-footer"></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-danger">
              <div class="inner">
                <h3>0</h3>

                <p>Visitors</p>
              </div>
              <div class="icon">
                <i class="ion ion-pie-graph"></i>
              </div>
              <a href="#" class="small-box-footer"></a>
            </div>
          </div>
          <!-- ./col -->
        </div>
	<!-- /.row -->
  </div><!-- /.container-fluid -->
</div>