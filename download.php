<?php
session_start();
$filename = $_SESSION['filename'];
// $chembl_file = $_SESSION['chembl_filename'];
// $export_data = unserialize($_POST['export_data']);


// print_r($_POST['export_data']);
$export_data = unserialize($_POST['export_data']);
// $export_chembl_data = unserialize($_POST['export_chembl_data']);

// print_r($export_data);


// file creation
$file = fopen($filename,"w");

foreach ($export_data as $line){
 fputcsv($file,$line);
}

fclose($file);


// file creation
// $file = fopen($chembl_file,"w");
//
// foreach ($export_chembl_data as $line){
//  fputcsv($file,$line);
// }
//
// fclose($file);

// download
header("Content-Description: File Transfer");
header("Content-Disposition: attachment; filename=".$filename);
header("Content-Type: application/csv; ");

readfile($filename);

// deleting file
unlink($filename);

// download Chembl
// header("Content-Description: File Transfer");
// header("Content-Disposition: attachment; filename=".$chembl_file);
// header("Content-Type: application/csv; ");
//
// readfile($chembl_file);
//
// // deleting file
// unlink($chembl_file);


exit();
