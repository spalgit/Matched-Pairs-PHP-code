<?php
  require_once "pdo.php";
  session_start();
  $lhs_id = htmlentities($_GET['lhs_id']);
  require_once "bootstrap.php";
  $compref = $_SESSION['Compound_id'];
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
        <h1>Compound pairs with identical LHS</h1>
        <nav>
          <a href="comp.php?Compound_id=<?php echo($compref) ?>" class = "active">Previous page</a>
        </nav>
      </div>
  </body>
</html>

<?php


      echo("<h2>"."IKENA Compounds and Chembl"."</h2>");

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

      $stmt = $pdo->prepare("SELECT id_a, context_id, erk_ic50,herg, solubility, mean as logd
                           from (select id_a, context_id, erk_ic50, solubility, mean
                           as herg from(select id_a,context_id, erk_ic50,
                           mean as solubility from (SELECT mol_id_a as id_a,context_id,
                           mean as erk_ic50 from(SELECT id_a, ikenacomps.Molecule_id as mol_id_a,
                           context_id FROM mmp join ikenacomps on ikenacomps.id=id_a
                           join transform_left on transform_left.id = mmp.lhs_id
                           where transform_left.id = :left_id) as tab left join ". $_SESSION['prop_1']. "
                           on ". $_SESSION['prop_1']. ".mol_id = id_a) as tab
                           left join ". $_SESSION['prop_2']. "
                           on ". $_SESSION['prop_2']. ".mol_id = id_a) as tab left
                           join ". $_SESSION['prop_3']. "
                           on ". $_SESSION['prop_3']. ".mol_id = id_a) as tab
                           left join ". $_SESSION['prop_4']. " on ". $_SESSION['prop_4']. ".mol_id = id_a");

        $stmt->execute(array(':left_id' => $lhs_id));
        $rowsa = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $stmt = $pdo->prepare("SELECT id_b, context_id, erk_ic50,herg, solubility, mean as logd
                             from (select id_b, context_id, erk_ic50, solubility, mean
                             as herg from(select id_b,context_id, erk_ic50,
                             mean as solubility from (SELECT mol_id_b as id_b,context_id,
                             mean as erk_ic50 from(SELECT id_b, ikenacomps.Molecule_id as mol_id_b,
                             context_id FROM mmp join ikenacomps on ikenacomps.id=id_b
                             join transform_left on transform_left.id = mmp.lhs_id
                             where transform_left.id = :left_id) as tab left join ". $_SESSION['prop_1']. "
                             on ". $_SESSION['prop_1']. ".mol_id = id_b) as tab
                             left join ". $_SESSION['prop_2']. "
                             on ". $_SESSION['prop_2']. ".mol_id = id_b) as tab left
                             join ". $_SESSION['prop_3']. "
                             on ". $_SESSION['prop_3']. ".mol_id = id_b) as tab
                             left join ". $_SESSION['prop_4']. " on ". $_SESSION['prop_4']. ".mol_id = id_b");

         $stmt->execute(array(':left_id' => $lhs_id));
         $rowsb = $stmt->fetchAll(PDO::FETCH_ASSOC);


         echo('<table border="1">'."\n");
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
         echo('<th>Context_id</th></tr>');


         $array_diff = array();

          for($i = 0; $i < count($rowsa); ++$i) {
            $diff = $rowsb[$i]['erk_ic50'] - $rowsa[$i]['erk_ic50'];
            $array_diff += [$i => $diff];
          }

          asort($array_diff);
          foreach($array_diff as $i => $value) {
            if($value != 0){
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
              echo('<a href="context.php?context_id='.$rowsa[$i]['context_id'].'">Query_context</a>');
              echo("</td><tr>\n");
            }
          }


          $stmt = $pdo->prepare("SELECT id_a, context_id, chmbl_clearance,assay_type,chmbl_herg, chmbl_F_percent,
                                chmbl_half_life, assay_chmbl_id,mean as chmbl_vdss from (SELECT id_a, context_id, chmbl_clearance,assay_type,
                                chmbl_herg, assay_chmbl_id,chmbl_F_percent, mean as chmbl_half_life
                               from (select id_a, context_id, chmbl_clearance,assay_type, assay_chmbl_id,chmbl_herg, mean
                               as chmbl_F_percent from (SELECT id_a, context_id,
                              chmbl_clearance,assay_type,assay_chmbl_id, mean as chmbl_herg from(SELECT mol_id_a as id_a,context_id,
                               mean as chmbl_clearance, assay_description as assay_type, assay_chembl_id as assay_chmbl_id from
                                (SELECT id_a, ikenacomps.Molecule_id as mol_id_a,
                                 context_id FROM mmp_chembl join ikenacomps on ikenacomps.id=id_a
                                 join transform_left on transform_left.id = mmp_chembl.lhs_id
                                 where transform_left.id = :left_id) as tab left join ". $_SESSION['prop_5']. "
                               on ". $_SESSION['prop_5']. ".mol_id = id_a)as tab
                               left join ". $field_1. "
                               on ". $field_1. ".mol_id = id_a) as tab
                               left join ". $field_2. "
                               on ". $field_2. ".mol_id = id_a)
                               as tab
                              left join ". $field_3. " on ". $field_3. ".mol_id = id_a)
                              as tab
                              left join ". $field_4. " on ". $field_4. ".mol_id = id_a
                              "
                                   );

          // print_r($stmt);

          $stmt->execute(array(':left_id' => $lhs_id));
          $rowsa = $stmt->fetchAll(PDO::FETCH_ASSOC);


          $stmt = $pdo->prepare("SELECT id_b, context_id, chmbl_clearance,assay_type,chmbl_herg, chmbl_F_percent,assay_chmbl_id,
                                chmbl_half_life, mean as chmbl_vdss from (SELECT id_b, context_id, chmbl_clearance,assay_type,
                                chmbl_herg, chmbl_F_percent, assay_chmbl_id,mean as chmbl_half_life
                               from (select id_b, context_id, chmbl_clearance,assay_type, chmbl_herg, assay_chmbl_id,mean
                               as chmbl_F_percent from (SELECT id_b, context_id,
                              chmbl_clearance,assay_type,assay_chmbl_id, mean as chmbl_herg from(SELECT mol_id_b as id_b,context_id,
                               mean as chmbl_clearance, assay_description as assay_type, assay_chembl_id as assay_chmbl_id from
                                (SELECT id_b, ikenacomps.Molecule_id as mol_id_b,
                                 context_id FROM mmp_chembl join ikenacomps on ikenacomps.id=id_b
                                 join transform_left on transform_left.id = mmp_chembl.lhs_id
                                 where transform_left.id = :left_id) as tab left join ". $_SESSION['prop_5']. "
                               on ". $_SESSION['prop_5']. ".mol_id = id_b)as tab
                               left join ". $field_1. "
                               on ". $field_1. ".mol_id = id_b) as tab
                               left join ". $field_2. "
                               on ". $field_2. ".mol_id = id_b)
                               as tab
                              left join ". $field_3. " on ". $field_3. ".mol_id = id_b)
                              as tab
                              left join ". $field_4. " on ". $field_4. ".mol_id = id_b
                              "
                                   );

          // echo(count($rowsa));
          // print_r($stmt);

          $stmt->execute(array(':left_id' => $lhs_id));
          $rowsb = $stmt->fetchAll(PDO::FETCH_ASSOC);


          echo('<table border="1">'."\n");
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
          echo('<th>'.$field_4.'</th></tr>');


          $array_diff = array();

           for($i = 0; $i < count($rowsa); ++$i) {
             $diff = abs($rowsb[$i]['chmbl_clearance'] - $rowsa[$i]['chmbl_clearance']);
             $array_diff += [$i => $diff];
           }

           arsort($array_diff);
           //
           // echo(count($rowsa));

           foreach($array_diff as $i => $value) {
             if($i<100){

               if($value > 0){
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
                 echo("</td><tr>\n");
               }
             }
           }

 ?>

<!-- <!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Transform</title>
    <link rel="stylesheet" href="css/mmp.css">
  </head>
  <body>
      <div class="container">
        <h1>Compound pairs with identical LHS</h1>
        <nav>
          <a href="comp.php?Compound_id=<?php echo($compref) ?>" class = "active">Previous page</a>
        </nav>
  </body>
</html> -->
