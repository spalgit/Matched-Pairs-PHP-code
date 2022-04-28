<?php
session_start();
$filename = $_SESSION['filename'];

$export_data = unserialize($_POST['export_data']);


// file creation

$dir = "c:\\Users\\sandeep";
$file_temp = $dir."\\".$filename;
// print_r($file_temp);
// $filename = $file_temp;

$file = fopen($file_temp,"w");

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

// readfile($filename);
readfile($file_temp);

// deleting file
unlink($file_temp);

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
