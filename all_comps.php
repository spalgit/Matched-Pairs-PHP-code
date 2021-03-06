<?php
  require_once "pdo.php";
  require_once "bootstrap.php";
  session_start();
  ini_set('memory_limit', '100M');
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
 echo('<th>'."Compound_ID".'</th>');
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
   echo(htmlentities($row['Compound_id']));
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


//  $stmt = $pdo->prepare("select * from (SELECT transform_id, mol_ida, Molecule_id as mol_idb, erk1,". $_SESSION['prop_1']. ".mean as erk2,
//  data_A, data as data_B, count_A ,count as count_B, raw_a, ". $_SESSION['prop_1']. " as raw_b, ". $_SESSION['prop_1']. ".mean-erk1 as difference
// from(SELECT id_a,id_b,transform_id, Molecule_id as mol_ida, ". $_SESSION['prop_1']. ".mean as erk1, data as data_A,
//      count as count_A, ". $_SESSION['prop_1']. " as raw_a FROM mmp
// join ". $_SESSION['prop_1']. " on id_a = ". $_SESSION['prop_1']. ".mol_id) as tab
// join ". $_SESSION['prop_1']. " on id_b = ". $_SESSION['prop_1']. ".mol_id") as tab where difference > 0
// order by difference desc limit 5000" );



$stmt = $pdo->prepare("select * from (select id_a, id_b, mol_ida,mol_idb, erk1, erk2,perk1,perk2, craf1, craf2,
logd_1, ". $_SESSION['prop_4']. ".mean as logd_2,
erk2-erk1 as difference from (select id_a, id_b, mol_ida,mol_idb, erk1, erk2,perk1,perk2, craf1,
". $_SESSION['prop_3']. ".mean as craf2, logd_1 from (select id_a, id_b, mol_ida,mol_idb,
erk1, erk2,perk1, ". $_SESSION['prop_2']. ".mean as perk2 , craf1, logd_1 from
(select id_a, id_b, mol_ida, Molecule_id as mol_idb ,erk1, ". $_SESSION['prop_1']. ".mean as
erk2,perk1, craf1, logd_1 from (select id_a, id_b, mol_ida, erk1, perk1, craf1, ". $_SESSION['prop_4']. ".mean as logd_1 from
(select id_a, id_b, mol_ida, erk1, perk1, ". $_SESSION['prop_3']. ".mean as craf1 from
(select id_a, id_b, mol_ida, erk1, ". $_SESSION['prop_2']. ".mean as perk1 from
(SELECT id_a,id_b, Molecule_id as mol_ida, ". $_SESSION['prop_1']. ".mean as erk1 FROM mmp join
". $_SESSION['prop_1']. " on id_a = ". $_SESSION['prop_1']. ".mol_id) as tab left
join ". $_SESSION['prop_2']. " on id_a = ". $_SESSION['prop_2']. ".mol_id) as
tab left join ". $_SESSION['prop_3']. " on
id_a = ". $_SESSION['prop_3']. ".mol_id) as tab left join ". $_SESSION['prop_4']. " on ". $_SESSION['prop_4']. ".mol_id= id_a)
as tab left join
". $_SESSION['prop_1']. " on id_b = ". $_SESSION['prop_1']. ".mol_id) as tab left
join ". $_SESSION['prop_2']. " on ". $_SESSION['prop_2']. ".mol_id = id_b)
as tab left join
". $_SESSION['prop_3']. " on ". $_SESSION['prop_3']. ".mol_id= id_b) as tab left
join ". $_SESSION['prop_4']. " ON
". $_SESSION['prop_4']. ".mol_id=id_b) as tab where difference >=0 order by difference desc");


// $stmt = $pdo->prepare("select * from (SELECT transform_id, mol_ida, Molecule_id as mol_idb, erk1,". $_SESSION['prop_1']. ".mean as
// erk2,". $_SESSION['prop_1']. ".mean-erk1 as difference ,data_A, data as data_B, count_A ,count as count_B, raw_a,
// ". $_SESSION['prop_1']. " as raw_b from(SELECT id_a,id_b,transform_id, Molecule_id as mol_ida,
// ". $_SESSION['prop_1']. ".mean as erk1, data as data_A, count as count_A, ". $_SESSION['prop_1']. "
// as raw_a FROM mmp join ". $_SESSION['prop_1']. " on id_a = ". $_SESSION['prop_1']. ".mol_id) as tab
// join ". $_SESSION['prop_1']. " on id_b = ". $_SESSION['prop_1']. ".mol_id) as tab where difference >= 0
// order by difference desc");

// print_r($stmt);

$stmt->execute(array());
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

 $master_arr_ikena = array();
 // $master_arr_ikena[] = array('ID_1','ID_2',
 //                             $_SESSION['prop_1']."_1",$_SESSION['prop_1']."_2",
 //                             'All_data_1','All_data_2',
 //                             'Replicates_1','Replicates_2',
 //                             'Registered_data_1', 'Registered_data_2',
 //                            'Trans_id');



$master_arr_ikena[] = array('ID_1','ID_2',
                            $_SESSION['prop_1']."_1",$_SESSION['prop_1']."_2",
                            $_SESSION['prop_2']."_1",$_SESSION['prop_2']."_2",
                            $_SESSION['prop_3']."_1",$_SESSION['prop_3']."_2",
                            $_SESSION['prop_4']."_1",$_SESSION['prop_4']."_2",
                          );


$comp_pairs = array();
for($i = 0; $i < count($rows); ++$i) {
  $indexes = $rows[$i]['mol_ida'].$rows[$i]['mol_idb'];
  if(in_array($indexes, $comp_pairs) ==  false){
         // if($i%2 == 0){
            // $master_arr_ikena[] = array($rows[$i]['mol_ida'],$rows[$i]['mol_idb'],
            //                             $rows[$i]['erk1'],$rows[$i]['erk2'],
            //                             $rows[$i]['data_A'],$rows[$i]['data_B'],
            //                             $rows[$i]['count_A'], $rows[$i]['count_B'],
            //                             $rows[$i]['raw_a'], $rows[$i]['raw_b'],
            //                           $rows[$i]['transform_id']);

            $master_arr_ikena[] = array($rows[$i]['mol_ida'],$rows[$i]['mol_idb'],
                                        $rows[$i]['erk1'],$rows[$i]['erk2'],
                                        $rows[$i]['perk1'],$rows[$i]['perk2'],
                                        $rows[$i]['craf1'], $rows[$i]['craf2'],
                                        $rows[$i]['logd_1'], $rows[$i]['logd_2']
                                      );
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
  <input type='submit' value='Download MMPs' name='Export'>
  <!-- <h3> Download possible for maximum 10000 MMPs </h3> -->
  <!-- <input type='submit' value='Download IKENA MMPs' name='Export' style='display: inline:block; float:right; margin-right:45%; margin-bottom:20px; border-radius: 5px; padding:10px; background-color: #daead5;text-decoration: none;'> -->
 </div>
 <textarea name='export_data' style='display: none;'><?php echo $serialize_user_arr; ?></textarea>
</form>
