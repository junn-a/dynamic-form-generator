<?php
	include'../../connection.php';
	$select = pg_query($conn,"SELECT MAX(id) AS id_new FROM tbl_form");
	$data = pg_fetch_assoc($select);
	$id 	= $data['id_new']+1;
	
	$tanggal 			= date('Y-m-d');
	$line 				= $_POST['line'];
	$nama_form 	= $_POST['nama_form'];
	$kode_form 	= date('ym')."-".$_POST['singkatan']."-".$_POST['line']."-".$id;
	$author 			= $_POST['author']; 
	$tipe_form		= $_POST['tipe_form'];
	$kategori_form = $_POST['kategori_form'];
	$singkatan		= $_POST['singkatan'];
	$status				= $_POST['status'];
	$tanggal_exp	= $_POST['tanggal_exp'];
	$departemen	= $_POST['departemen'];
	
	pg_query($conn,"INSERT INTO tbl_form (id, tanggal, line, nama_form, kode_form, author, tipe_form, kategori_form, singkatan, status, tanggal_exp, departemen) VALUES (DEFAULT,'$tanggal', '$line','$nama_form', '$kode_form','$author','$tipe_form', '$kategori_form', '$singkatan', '$status', '$tanggal_exp', '$departemen');");
	
	header("location:../../index.php?page=tambah_form&status=berhasil&kode_form=$kode_form");
?>