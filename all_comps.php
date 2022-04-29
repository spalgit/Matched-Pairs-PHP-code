<?php
  require_once "pdo.php";
  require_once "bootstrap.php";
  session_start();
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>MMP Main page</title>
    <link rel="stylesheet" href="css/mmp.css">
  </head>
  <body>
    <div class="container">
    <h1>Compound Data </h1>
    <nav>
      <a href="index.php" class = "active">Back to main page</a>
    </nav>
  </div>


<?php

$stmt = $pdo->prepare("SELECT Compound_id,erk_ic50,herg_IC50, Fassif_sol, erk_molid, mean as logd
                       FROM (select Compound_id,erk_ic50,herg_IC50, mean as Fassif_sol, erk_molid
                       FROM (select Compound_id,erk_ic50, mean as herg_IC50,erk_molid
                       FROM (select Molecule_id as Compound_id, mean as erk_ic50, mol_id as erk_molid
                       FROM ". $_SESSION['prop_1']. ") as tab
                       left join ". $_SESSION['prop_2']. " on ". $_SESSION['prop_2']. ".mol_id = erk_molid) as tab
                       left join ". $_SESSION['prop_3']. "
                       on ". $_SESSION['prop_3']. ".mol_id = erk_molid)
                       as tab left join ". $_SESSION['prop_4']. " on ". $_SESSION['prop_4']. ".mol_id = erk_molid
                       order by erk_ic50");


 $stmt->execute(array());
 $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

 echo('<table border="5">'."\n");
 echo('<tr><th>Compound</th>');
 echo('<th>'.$_SESSION['prop_1'].'</th>');
 echo('<th>'.$_SESSION['prop_2'].'</th>');
 echo('<th>'.$_SESSION['prop_3'].'</th>');
 echo('<th>'.$_SESSION['prop_4'].'</th>');

 echo('<th>MMP_links</th></tr>');

 foreach ($rows as $row){
   echo "<tr><td>";
   $img = "images/".$row['Compound_id'].".png";
   echo("<img src=".$img.">");
   echo("</td><td>");
   echo(htmlentities($row['erk_ic50']));
   echo("</td><td>");
   echo(htmlentities($row['herg_IC50']));
   echo("</td><td>");
   echo(htmlentities($row['Fassif_sol']));
   echo("</td><td>");
   echo(htmlentities($row['logd']));
   echo("</td><td>");
   echo('<a href="comp.php?Compound_id='.$row['erk_molid'].'">Query_mmps</a>');
   echo("</td><tr>\n");
 }

 ?>
