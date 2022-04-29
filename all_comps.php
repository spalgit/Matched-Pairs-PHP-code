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

 $stmt = $pdo->prepare("SELECT id_a,smirks,smiles_a, lhs_id, transform_id, context_id, erk_ic50,herg, solubility, mean as logd
                            from (select id_a,smirks, lhs_id,smiles_a, transform_id, context_id, erk_ic50, herg, mean
                            as solubility from (select id_a,smirks, lhs_id,smiles_a, transform_id, context_id, erk_ic50,
                            mean as herg from (SELECT mol_id_a as id_a,smiles_a,smirks,lhs_id, transform_id, context_id,
                            mean as erk_ic50 from (SELECT id_a, ikenacomps.Molecule_id as mol_id_a, ikenacomps.CXCSmiles as smiles_a,
                            transform.transform as smirks, lhs_id, transform_id,context_id FROM mmp
                            join ikenacomps on ikenacomps.id=id_a join transform on transform.id = transform_id) as tab join ". $_SESSION['prop_1']. "
                            on ". $_SESSION['prop_1']. ".mol_id = id_a) as tab
                            left join ". $_SESSION['prop_2']. "
                            on ". $_SESSION['prop_2']. ".mol_id = id_a) as tab left
                            join ". $_SESSION['prop_3']. "
                            on ". $_SESSION['prop_3']. ".mol_id = id_a) as tab
                           left join ". $_SESSION['prop_4']. " on ". $_SESSION['prop_4']. ".mol_id = id_a");

 $stmt->execute(array());
 $rowsa = $stmt->fetchAll(PDO::FETCH_ASSOC);

 // print_r($stmt);

 // print_r($stmt);

 $stmt = $pdo->prepare("SELECT id_b,smirks, lhs_id,smiles_b, transform_id, context_id, erk_ic50,herg, solubility, mean as logd
                            from (select id_b,smirks,smiles_b, lhs_id, transform_id, context_id, erk_ic50, herg, mean
                            as solubility from (select id_b,smirks,smiles_b, lhs_id, transform_id, context_id, erk_ic50,
                            mean as herg from (SELECT mol_id_b as id_b,smirks,smiles_b,lhs_id, transform_id, context_id,
                            mean as erk_ic50 from (SELECT id_b, ikenacomps.Molecule_id as mol_id_b, ikenacomps.CXCSmiles as smiles_b,
                            transform.transform as smirks, lhs_id, transform_id,context_id FROM mmp
                            join ikenacomps on ikenacomps.id=id_b join transform on transform.id = transform_id) as tab join ". $_SESSION['prop_1']. "
                            on ". $_SESSION['prop_1']. ".mol_id = id_b) as tab
                            left join ". $_SESSION['prop_2']. "
                            on ". $_SESSION['prop_2']. ".mol_id = id_b) as tab left
                            join ". $_SESSION['prop_3']. "
                            on ". $_SESSION['prop_3']. ".mol_id = id_b) as tab
                           left join ". $_SESSION['prop_4']. " on ". $_SESSION['prop_4']. ".mol_id = id_b");

 $stmt->execute(array());
 $rowsb = $stmt->fetchAll(PDO::FETCH_ASSOC);


 $master_arr_ikena = array();
 $master_arr_ikena[] = array('ID_1','ID_2',
                             'Smiles_1', 'Smiles_2',
                             $_SESSION['prop_1'],$_SESSION['prop_1'],
                             $_SESSION['prop_2'],$_SESSION['prop_2'],
                             $_SESSION['prop_3'],$_SESSION['prop_3'],
                             $_SESSION['prop_4'],$_SESSION['prop_4'],
                             'Transform');


$comp_pairs = array();
for($i = 0; $i < count($rowsa); ++$i) {
  $indexes = $rowsa[$i]['id_a'].$rowsb[$i]['id_b'];
  if(in_array($indexes, $comp_pairs) ==  false){
      if($rowsa[$i]['erk_ic50'] >0 || $rowsb[$i]['erk_ic50'] >0){
         if($i%2 == 0){
            $master_arr_ikena[] = array($rowsa[$i]['id_a'],$rowsb[$i]['id_b'],
                                        $rowsa[$i]['smiles_a'],$rowsb[$i]['smiles_b'],
                                        $rowsa[$i]['erk_ic50'],$rowsb[$i]['erk_ic50'],
                                        $rowsa[$i]['herg'], $rowsb[$i]['herg'],
                                        $rowsa[$i]['solubility'], $rowsb[$i]['solubility'],
                                        $rowsa[$i]['logd'], $rowsb[$i]['logd'],
                                        $rowsa[$i]['smirks']);
          }
      }
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
