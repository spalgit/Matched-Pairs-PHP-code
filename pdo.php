<?php

try{

  // $myPDO = new PDO("pgsql:host=localhost, dbname=postgres", "postgres", "sql");
  $pdo = new PDO('mysql:host=localhost;port=3306;dbname=mmpdb',
     'sandeep', '$chrodinger123');
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  // echo("Connected");

}catch(PDOException $e){
  echo $e->getMessage();
}



 ?>
