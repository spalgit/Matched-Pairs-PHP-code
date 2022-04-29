<?php
  require_once "pdo.php";
  require_once "bootstrap.php";
  session_start();
  $trans_id = htmlentities($_GET['transform_id']);
  $compref = $_SESSION['Compound_id'];
  // echo();
?>


<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Transform</title>
    <link rel="stylesheet" href="css/mmp.css">
  </head>
  <body>
      <div class="container">
        <h1>Compound pairs with same transform</h1>
        <nav>
          <a href="comp.php?Compound_id=<?php echo($compref) ?>" class = "active">Previous page</a>
        </nav>
      </div>

       <!-- <form method='post' action='download.php'>
        <input type='submit' value='Download IKENA MMPs' name='Export'> -->

  <!-- </body>
</html> -->


<?php

    // echo("<h2>"."IKENA Compounds and Chembl"."</h2>");

    if($_SESSION['prop_5'] == 'chembl_herg'){
      $field_1 = "chembl_clearance";
      $field_2 = "chembl_vdss";
      $field_3 = "chembl_bioavailability";
      $field_4 = "chembl_half_life";
    }elseif ($_SESSION['prop_5'] == 'chembl_clearance') {
      $field_1 = "chembl_herg";
      $field_2 = "chembl_vdss";
      $field_3 = "chembl_bioavailability";
      $field_4 = "chembl_half_life";
    }elseif ($_SESSION['prop_5'] == 'chembl_vdss') {
      $field_1 = "chembl_herg";
      $field_2 = "chembl_clearance";
      $field_3 = "chembl_bioavailability";
      $field_4 = "chembl_half_life";
    }elseif ($_SESSION['prop_5'] == 'chembl_bioavailability') {
      $field_1 = "chembl_herg";
      $field_2 = "chembl_clearance";
      $field_3 = "chembl_vdss";
      $field_4 = "chembl_half_life";
    }elseif ($_SESSION['prop_5'] == 'chembl_half_life') {
      $field_1 = "chembl_herg";
      $field_2 = "chembl_clearance";
      $field_3 = "chembl_vdss";
      $field_4 = "chembl_bioavailability";
    }

    $stmt = $pdo->prepare("SELECT id_a,smirks,smiles_a, lhs_id, transform_id, context_id, erk_ic50,herg, solubility, mean as logd
                               from (select id_a,smirks, lhs_id,smiles_a, transform_id, context_id, erk_ic50, herg, mean
                               as solubility from (select id_a,smirks, lhs_id,smiles_a, transform_id, context_id, erk_ic50,
                               mean as herg from (SELECT mol_id_a as id_a,smiles_a,smirks,lhs_id, transform_id, context_id,
                               mean as erk_ic50 from (SELECT id_a, ikenacomps.Molecule_id as mol_id_a, ikenacomps.CXCSmiles as smiles_a,
                               transform.transform as smirks, lhs_id, transform_id,context_id FROM mmp
                               join ikenacomps on ikenacomps.id=id_a join transform on transform.id = transform_id
                               where transform.id = :trans_id) as tab left join ". $_SESSION['prop_1']. "
                               on ". $_SESSION['prop_1']. ".mol_id = id_a) as tab
                               left join ". $_SESSION['prop_2']. "
                               on ". $_SESSION['prop_2']. ".mol_id = id_a) as tab left
                               join ". $_SESSION['prop_3']. "
                               on ". $_SESSION['prop_3']. ".mol_id = id_a) as tab
                              left join ". $_SESSION['prop_4']. " on ". $_SESSION['prop_4']. ".mol_id = id_a");

    $stmt->execute(array(':trans_id' => $trans_id));
    $rowsa = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // print_r($stmt);

    $stmt = $pdo->prepare("SELECT id_b,smirks, lhs_id,smiles_b, transform_id, context_id, erk_ic50,herg, solubility, mean as logd
                               from (select id_b,smirks,smiles_b, lhs_id, transform_id, context_id, erk_ic50, herg, mean
                               as solubility from (select id_b,smirks,smiles_b, lhs_id, transform_id, context_id, erk_ic50,
                               mean as herg from (SELECT mol_id_b as id_b,smirks,smiles_b,lhs_id, transform_id, context_id,
                               mean as erk_ic50 from (SELECT id_b, ikenacomps.Molecule_id as mol_id_b, ikenacomps.CXCSmiles as smiles_b,
                               transform.transform as smirks, lhs_id, transform_id,context_id FROM mmp
                               join ikenacomps on ikenacomps.id=id_b join transform on transform.id = transform_id
                               where transform.id = :trans_id) as tab left join ". $_SESSION['prop_1']. "
                               on ". $_SESSION['prop_1']. ".mol_id = id_b) as tab
                               left join ". $_SESSION['prop_2']. "
                               on ". $_SESSION['prop_2']. ".mol_id = id_b) as tab left
                               join ". $_SESSION['prop_3']. "
                               on ". $_SESSION['prop_3']. ".mol_id = id_b) as tab
                              left join ". $_SESSION['prop_4']. " on ". $_SESSION['prop_4']. ".mol_id = id_b");

    $stmt->execute(array(':trans_id' => $trans_id));
    $rowsb = $stmt->fetchAll(PDO::FETCH_ASSOC);


    // print_r($rowsa['0']['smirks']);

    echo("<h2>"."IKENA Compounds and Chembl with transform ". $rowsa['0']['smirks'] ."</h2>");

    echo('<table border="5">'."\n");
    echo('<tr><th>Compound_1</th>');
    echo('<th>Compound_2</th>');
    echo('<th>'.$_SESSION['prop_1'].'</th>');
    echo('<th>'.$_SESSION['prop_1'].'</th>');
    echo('<th>'.$_SESSION['prop_2'].'</th>');
    echo('<th>'.$_SESSION['prop_2'].'</th>');
    echo('<th>'.$_SESSION['prop_3'].'</th>');
    echo('<th>'.$_SESSION['prop_3'].'</th>');
    echo('<th>'.$_SESSION['prop_4'].'</th>');
    echo('<th>'.$_SESSION['prop_4'].'</th>');
    echo('<th>Transform</th>');
    echo('<th>Lhs_id</th>');
    echo('<th>Context_id</th></tr>');

   $array_diff = array();

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
      $diff = abs($rowsb[$i]['erk_ic50'] - $rowsa[$i]['erk_ic50']);
      $indexes = $rowsa[$i]['id_a'].$rowsb[$i]['id_b'];

      // echo($indexes);
      // echo('<br>');
      // echo($rowsa[$i]['erk_ic50']."_".$rowsb[$i]['erk_ic50']);
      // // echo($rowsb[$i]['erk_ic50']);
      // echo('<br>');
      $array_diff += [$i => $diff];
      // print_r(in_array($indexes, $comp_pairs));
      // echo('<br>');
      // array_push($comp_pairs, $indexes);
      // print_r($comp_pairs);
      // echo('<br>');
  // if(in_array($indexes, $comp_pairs_chembl) == false){
      if(in_array($indexes, $comp_pairs) ==  false){
          if($rowsa[$i]['erk_ic50'] >0 || $rowsb[$i]['erk_ic50'] >0){
                $master_arr_ikena[] = array($rowsa[$i]['id_a'],$rowsb[$i]['id_b'],
                                            $rowsa[$i]['smiles_a'],$rowsb[$i]['smiles_b'],
                                            $rowsa[$i]['erk_ic50'],$rowsb[$i]['erk_ic50'],
                                            $rowsa[$i]['herg'], $rowsb[$i]['herg'],
                                            $rowsa[$i]['solubility'], $rowsb[$i]['solubility'],
                                            $rowsa[$i]['logd'], $rowsb[$i]['logd'],
                                            $rowsa[$i]['smirks']);
          }
          // array_push($comp_pairs, $indexes);
      }
      array_push($comp_pairs, $indexes);

    }

    arsort($array_diff);

    $serialize_user_arr = serialize($master_arr_ikena);

    $_SESSION['filename'] = 'transforms.csv';

    foreach($array_diff as $i => $value) {
      if($rowsa[$i]['erk_ic50'] >0 || $rowsb[$i]['erk_ic50'] >0){
        echo "<tr><td>";
        $img = "images/".$rowsa[$i]['id_a'].".png";
        echo("<img src=".$img.">");
        echo("</td><td>");
        $img = "images/".$rowsb[$i]['id_b'].".png";
        echo("<img src=".$img.">");
        echo("</td><td>");
        echo(htmlentities($rowsa[$i]['erk_ic50']));
        echo("</td><td>");
        echo(htmlentities($rowsb[$i]['erk_ic50']));
        echo("</td><td>");
        echo(htmlentities($rowsa[$i]['herg']));
        echo("</td><td>");
        echo(htmlentities($rowsb[$i]['herg']));
        echo("</td><td>");
        echo(htmlentities($rowsa[$i]['solubility']));
        echo("</td><td>");
        echo(htmlentities($rowsb[$i]['solubility']));
        echo("</td><td>");
        echo(htmlentities($rowsa[$i]['logd']));
        echo("</td><td>");
        echo(htmlentities($rowsb[$i]['logd']));
        echo("</td><td>");
        echo(htmlentities($rowsa[$i]['smirks']));
        echo("</td><td>");
        echo('<a href="lhs.php?lhs_id='.$rowsa[$i]['lhs_id'].'">Query_LHS</a>');
        echo("</td><td>");
        echo('<a href="context.php?context_id='.$rowsa[$i]['context_id'].'">Query_context</a>');
        echo("</td><tr>\n");
      }
    }

    ?>





  <form method='post' action='download.php'>
  <div class="downl">
   <input type='submit' value='Download IKENA MMPs' name='Export'>
   <!-- <input type='submit' value='Download IKENA MMPs' name='Export' style='display: inline:block; float:right; margin-right:45%; margin-bottom:20px; border-radius: 5px; padding:10px; background-color: #daead5;text-decoration: none;'> -->
  </div>
  <textarea name='export_data' style='display: none;'><?php echo $serialize_user_arr; ?></textarea>
 </form>


 <!-- <form method='post' action='download_chem.php'>
 <div class="downl">
   <input type='submit' value='Download CHEMBL MMPs' name='Export'>
</div> -->




  <?php

    // echo("<h2>"."CHEMBL Matched  Pairs"."</h2>");


    $stmt = $pdo->prepare("SELECT id_a,smirks, lhs_id, transform_id,smiles_a,context_id, chmbl_clearance,assay_type,assay_chmbl_id,
                             chmbl_herg, chmbl_F_percent,chmbl_half_life, mean as chmbl_vdss
                             from (SELECT id_a,smirks, lhs_id,smiles_a, transform_id, context_id, chmbl_clearance,
                              assay_type,assay_chmbl_id,chmbl_herg, chmbl_F_percent, mean as chmbl_half_life
                              from (SELECT id_a,smirks, lhs_id,smiles_a, transform_id, context_id, chmbl_clearance,assay_type,assay_chmbl_id,
                              chmbl_herg, mean
                              as chmbl_F_percent from (SELECT id_a,smirks,smiles_a, lhs_id, transform_id, context_id,
                              chmbl_clearance,assay_type,assay_chmbl_id, mean as chmbl_herg from
                              (SELECT mol_id_a as id_a,smirks,lhs_id,smiles_a, transform_id, context_id,
                               mean as chmbl_clearance, assay_description as assay_type, assay_chembl_id as assay_chmbl_id from
                               (SELECT id_a, ikenacomps.Molecule_id as mol_id_a, ikenacomps.CXCSmiles as smiles_a,
                               transform.transform as smirks, lhs_id, transform_id,context_id FROM mmp_chembl
                               join ikenacomps on ikenacomps.id=id_a join transform on transform.id = transform_id
                               where transform.id = :trans_id) as tab left join ". $_SESSION['prop_5']. "
                               on ". $_SESSION['prop_5']. ".mol_id = id_a) as tab
                               left join ". $field_1. "
                               on ". $field_1. ".mol_id = id_a) as tab
                               left join ". $field_2. "
                               on ". $field_2. ".mol_id = id_a) as tab
                              left join ". $field_3. " on ". $field_3. ".mol_id = id_a) as tab
                              left join ". $field_4. "  on ". $field_4. ".mol_id = id_a"
                             );

    // print_r($stmt);

    $stmt->execute(array(':trans_id' => $trans_id));
    $rowsa = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmt = $pdo->prepare("SELECT id_b,smirks, lhs_id,smiles_b, transform_id, context_id, chmbl_clearance,assay_type,chmbl_herg, chmbl_F_percent,
                             chmbl_half_life, assay_chmbl_id,mean as chmbl_vdss
                             from (SELECT id_b,smirks,smiles_b, lhs_id, transform_id, context_id, chmbl_clearance,
                              assay_type,chmbl_herg, chmbl_F_percent,assay_chmbl_id, mean as chmbl_half_life
                              from (SELECT id_b,smirks, lhs_id,smiles_b, transform_id, context_id, chmbl_clearance,assay_type,
                              chmbl_herg,assay_chmbl_id, mean
                              as chmbl_F_percent from (SELECT id_b,smiles_b,smirks, lhs_id, transform_id, context_id,
                                chmbl_clearance,assay_type,assay_chmbl_id,  mean as chmbl_herg from
                              (SELECT mol_id_b as id_b,smiles_b,smirks,lhs_id, transform_id, context_id,
                               mean as chmbl_clearance, assay_description as assay_type, assay_chembl_id as assay_chmbl_id from
                               (SELECT id_b, ikenacomps.Molecule_id as mol_id_b,ikenacomps.CXCSmiles as smiles_b,
                               transform.transform as smirks, lhs_id, transform_id,context_id FROM mmp_chembl
                               join ikenacomps on ikenacomps.id=id_b join transform on transform.id = transform_id
                               where transform.id = :trans_id) as tab left join ". $_SESSION['prop_5']. "
                               on ". $_SESSION['prop_5']. ".mol_id = id_b) as tab
                               left join ". $field_1. "
                               on ". $field_1. ".mol_id = id_b) as tab
                               left join ". $field_2. "
                               on ". $field_2. ".mol_id = id_b) as tab
                              left join ". $field_3. " on ". $field_3. ".mol_id = id_b) as tab
                              left join ". $field_4. "  on ". $field_4. ".mol_id = id_b"
                             );

    // print_r($stmt);

    $stmt->execute(array(':trans_id' => $trans_id));
    $rowsb = $stmt->fetchAll(PDO::FETCH_ASSOC);


    // echo("<h2>"."Chembl Compounds"."</h2>");
    // echo("<br>");

    echo('<table border="5">'."\n");
    echo('<tr><th>Compound_1</th>');
    echo('<th>Compound_2</th>');
    echo('<th>'.$_SESSION['prop_5'].'</th>');
    echo('<th>'.$_SESSION['prop_5'].'</th>');
    echo('<th>'."Assay_Chembl_ID".'</th>');
    echo('<th>'."Description".'</th>');
    echo('<th>'.$field_1.'</th>');
    echo('<th>'.$field_1.'</th>');
    echo('<th>'.$field_2.'</th>');
    echo('<th>'.$field_2.'</th>');
    echo('<th>'.$field_3.'</th>');
    echo('<th>'.$field_3.'</th>');
    echo('<th>'.$field_4.'</th>');
    echo('<th>'.$field_4.'</th>');
    echo('<th>Transform</th></tr>');

    // echo(count($rowsa));


    $array_diff = array();

     // for($i = 0; $i < count($rowsa); ++$i) {
     //   $diff = abs($rowsb[$i]['chmbl_clearance'] - $rowsa[$i]['chmbl_clearance']);
     //   $array_diff += [$i => $diff];
     // }

     $master_arr_chembl = array();
     $master_arr_chembl[] = array('ID_1','ID_2',
                                 'Smiles_1', 'Smiles_2',
                                 $_SESSION['prop_5'],$_SESSION['prop_5'],
                                 'Assay_Chembl_ID', 'Description',
                                 $field_1,$field_1,
                                 $field_2,$field_2,
                                 $field_3,$field_3,
                                 $field_4,$field_4,
                                 'Transform'
                                 );

    $comp_pairs_chembl = array();

    for($i = 0; $i < count($rowsa); ++$i) {
      $indexes = $rowsa[$i]['id_a'].$rowsb[$i]['id_b'];
      // if(in_array($indexes, $comp_pairs_chembl, True)){
      //   echo($indexes);
      //   echo("<br>");
      //   // echo("Sandeep");
      // }

      if(in_array($indexes, $comp_pairs_chembl) == false){

        // echo($indexes);
        // echo("<br>");
        // echo("<br>");

        // echo("<br>");
          if($rowsa[$i]['chmbl_clearance'] >0 || $rowsa[$i]['chmbl_clearance'] >0){
            $diff = abs($rowsb[$i]['chmbl_clearance'] - $rowsa[$i]['chmbl_clearance']);
            $array_diff += [$i => $diff];
            $master_arr_chembl[] = array($rowsa[$i]['id_a'], $rowsb[$i]['id_b'],
                                         $rowsa[$i]['smiles_a'], $rowsb[$i]['smiles_b'],
                                         $rowsa[$i]['chmbl_clearance'], $rowsb[$i]['chmbl_clearance'],
                                         $rowsa[$i]['assay_chmbl_id'],$rowsa[$i]['assay_type'],
                                         $rowsa[$i]['chmbl_herg'],$rowsb[$i]['chmbl_herg'],
                                         $rowsa[$i]['chmbl_F_percent'],$rowsb[$i]['chmbl_F_percent'],
                                         $rowsa[$i]['chmbl_half_life'],$rowsb[$i]['chmbl_half_life'],
                                         $rowsa[$i]['chmbl_vdss'],$rowsb[$i]['chmbl_vdss'],
                                         $rowsa[$i]['smirks']);
           }
           // array_push($comp_pairs_chembl, $indexes);
       }
       array_push($comp_pairs_chembl, $indexes);
    }

    $serialize_chembl_arr = serialize($master_arr_chembl);

    $_SESSION['chembl_filename'] = 'chembl_transfoms.csv';

     arsort($array_diff);

    foreach($array_diff as $i => $value) {
      // if($i<100){
        if($rowsa[$i]['chmbl_clearance'] >0 || $rowsa[$i]['chmbl_clearance'] >0){

          if(strlen($rowsa[$i]['assay_chmbl_id'])<1) {
            $assay_id = $rowsb[$i]['assay_chmbl_id'];
            $assay_t = $rowsb[$i]['assay_type'];
          }elseif (strlen($rowsb[$i]['assay_chmbl_id'])<1) {
            $assay_id = $rowsa[$i]['assay_chmbl_id'];
            $assay_t = $rowsa[$i]['assay_type'];
          }else{
            $assay_id = $rowsa[$i]['assay_chmbl_id'];
            $assay_t = $rowsa[$i]['assay_type'];
          }

          echo "<tr><td>";
          $img = "images/".$rowsa[$i]['id_a'].".png";
          echo("<img src=".$img.">");
          echo("</td><td>");
          $img = "images/".$rowsb[$i]['id_b'].".png";
          echo("<img src=".$img.">");
          echo("</td><td>");
          echo(htmlentities($rowsa[$i]['chmbl_clearance']));
          echo("</td><td>");
          echo(htmlentities($rowsb[$i]['chmbl_clearance']));
          echo("</td><td>");
          echo(htmlentities($assay_id));
          // echo(htmlentities($rowsa[$i]['assay_chmbl_id']."/".$rowsb[$i]['assay_chmbl_id']));
          echo("</td><td>");
          echo(htmlentities($assay_t));
          // echo(htmlentities($rowsa[$i]['assay_type']));
          echo("</td><td>");
          echo(htmlentities($rowsa[$i]['chmbl_herg']));
          echo("</td><td>");
          echo(htmlentities($rowsa[$i]['chmbl_herg']));
          echo("</td><td>");
          echo(htmlentities($rowsa[$i]['chmbl_F_percent']));
          echo("</td><td>");
          echo(htmlentities($rowsb[$i]['chmbl_F_percent']));
          echo("</td><td>");
          echo(htmlentities($rowsa[$i]['chmbl_half_life']));
          echo("</td><td>");
          echo(htmlentities($rowsb[$i]['chmbl_half_life']));
          echo("</td><td>");
          echo(htmlentities($rowsa[$i]['chmbl_vdss']));
          echo("</td><td>");
          echo(htmlentities($rowsb[$i]['chmbl_vdss']));
          echo("</td><td>");
          echo(htmlentities($rowsa[$i]['smirks']));
          echo("</td><tr>\n");
        }
      // } //100
    }

 ?>


<!-- </div> -->

<h2>Chembl Matched pairs</h2>
<form method='post' action='download_chem.php'>
<div class="downl">
  <input type='submit' value='Download CHEMBL MMPs' name='Export'>
</div>
<textarea name='export_chembl_data' style='display: none;'><?php echo $serialize_chembl_arr; ?></textarea>
</form>



</body>
</html>

<!-- </div>

</body>
</html> -->
