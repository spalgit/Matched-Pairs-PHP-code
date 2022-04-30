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


 $stmt = $pdo->prepare("SELECT transform_id, mol_ida, Molecule_id as mol_idb, erk1,". $_SESSION['prop_1']. " .mean as erk2,
 data_A, data as data_B, count_A ,count as count_B, raw_a, ". $_SESSION['prop_1']. " as raw_b
from(SELECT id_a,id_b,transform_id, Molecule_id as mol_ida, ". $_SESSION['prop_1']. ".mean as erk1, data as data_A,
     count as count_A, ". $_SESSION['prop_1']. " as raw_a FROM mmp
join ". $_SESSION['prop_1']. " on id_a = ". $_SESSION['prop_1']. ".mol_id) as tab
join ". $_SESSION['prop_1']. " on id_b = ". $_SESSION['prop_1']. ".mol_id");


$stmt->execute(array());
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

 $master_arr_ikena = array();
 $master_arr_ikena[] = array('ID_1','ID_2',
                             $_SESSION['prop_1']."_1",$_SESSION['prop_1']."_2",
                             'All_data_1','All_data_2',
                             'Replicates_1','Replicates_2',
                             'Registered_data_1', 'Registered_data_2',
                            'Trans_id');


$comp_pairs = array();
for($i = 0; $i < count($rows); ++$i) {
  $indexes = $rows[$i]['mol_ida'].$rows[$i]['mol_idb'];
  if(in_array($indexes, $comp_pairs) ==  false){
         // if($i%2 == 0){
            $master_arr_ikena[] = array($rows[$i]['mol_ida'],$rows[$i]['mol_idb'],
                                        $rows[$i]['erk1'],$rows[$i]['erk2'],
                                        $rows[$i]['data_A'],$rows[$i]['data_B'],
                                        $rows[$i]['count_A'], $rows[$i]['count_B'],
                                        $rows[$i]['raw_a'], $rows[$i]['raw_b'],
                                      $rows[$i]['transform_id']);
          // }
      // array_push($comp_pairs, $indexes);
  }
  array_push($comp_pairs, $indexes);

}

$serialize_user_arr = serialize($master_arr_ikena);

$_SESSION['filename'] = 'all_mmps.csv';

 ?>

 <form method='post' action='download.php'>
 <div class="downl">
  <input type='submit' value='Download all MMPs' name='Export'>
  <!-- <input type='submit' value='Download IKENA MMPs' name='Export' style='display: inline:block; float:right; margin-right:45%; margin-bottom:20px; border-radius: 5px; padding:10px; background-color: #daead5;text-decoration: none;'> -->
 </div>
 <textarea name='export_data' style='display: none;'><?php echo $serialize_user_arr; ?></textarea>
</form>
