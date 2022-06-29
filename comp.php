<?php
 session_start();
 require_once "pdo.php";
 require_once "bootstrap.php";
 $ref_id = htmlentities($_GET['Compound_id']);
 $_SESSION['Compound_id'] = $ref_id;
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
     <h1>Matched Molecular Pairs </h1>
     <nav>
       <a href="all_comps.php" class = "active">Back to compounds data</a>
     </nav>
   </div>

   <!-- <form method='post' action='download.php'>
     <div class="downl">
    <input type='submit' value='Download IKENA MMPs' name='Export'>
    </div> -->

   <!-- </body>
 </html> -->

 <?php
      // echo("<h2>"."IKENA Matched  Pairs for compound". $ref_id ."</h2>");
 /* Get all the parameters of the reference molecule*/

       $stmt = $pdo->prepare("SELECT mean, Molecule_id FROM ". $_SESSION['prop_1']. "
                              where mol_id = :id_comp");
       $stmt->execute(array(':id_comp' => $ref_id));
       $row_ref_erk = $stmt->fetch(PDO::FETCH_ASSOC);

       $stmt = $pdo->prepare("SELECT mean FROM ". $_SESSION['prop_2']. "
                              where mol_id = :id_comp");
       $stmt->execute(array(':id_comp' => $ref_id));
       $row_ref_herg = $stmt->fetch(PDO::FETCH_ASSOC);
       if($row_ref_herg === false){
         $row_ref_herg['mean'] = ' ';
       }

       $stmt = $pdo->prepare("SELECT mean FROM ". $_SESSION['prop_3']. "
                              where mol_id = :id_comp");
       $stmt->execute(array(':id_comp' => $ref_id));
       $row_ref_sol = $stmt->fetch(PDO::FETCH_ASSOC);
       if($row_ref_sol === false){
         $row_ref_sol['mean'] = ' ';
       }

       $stmt = $pdo->prepare("SELECT mean FROM ". $_SESSION['prop_4']. "
                              where mol_id = :id_comp");
       $stmt->execute(array(':id_comp' => $ref_id));
       $row_ref_logd = $stmt->fetch(PDO::FETCH_ASSOC);
       if($row_ref_logd === false){
         $row_ref_logd['mean'] = ' ';
       }

    /* Now extract all the parameters of the Matched Pairs*/

    $stmt = $pdo->prepare("SELECT mol_id_b as id_b,smirks, lhs_id, transform_id, context_id, erk_ic50,
                           herg, solubility, mean as logd
                           from (select id_b,mol_id_b,smirks, lhs_id, transform_id, context_id, erk_ic50, herg, mean
                           as solubility from (select id_b,mol_id_b,smirks, lhs_id, transform_id, context_id, erk_ic50,
                             mean as herg from (SELECT id_b,mol_id_b,smirks,lhs_id, transform_id, context_id,
                             mean as erk_ic50 FROM ". $_SESSION['prop_1']. "
                             join (SELECT id_b, ikenacomps.Molecule_id as mol_id_b, transform.transform as smirks,
                             lhs_id, transform_id,context_id FROM mmp join ikenacomps on ikenacomps.id=id_b
                             join transform on transform.id = transform_id where id_a = :id_comp) as tab
                             on ". $_SESSION['prop_1']. ".mol_id = id_b) as tab left join ". $_SESSION['prop_2']. "
                             on ". $_SESSION['prop_2']. ".mol_id = id_b) as tab
                             left join ". $_SESSION['prop_3']. "
                             on ". $_SESSION['prop_3']. ".mol_id = id_b) as tab
                             left join ". $_SESSION['prop_4']. " on ". $_SESSION['prop_4']. ".mol_id = id_b
                           order by erk_ic50");


     $stmt->execute(array(':id_comp' => $ref_id));
     $rowss = $stmt->fetchAll(PDO::FETCH_ASSOC);

     echo($_SESSION['prop_2']);

     print_r($stmt);
     //
     // print_r($row_ref_erk['Molecule_id']);


     echo("<h2>"."IKENA Matched  Pairs for compound ". $row_ref_erk['Molecule_id'] ."</h2>");
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
     echo('<th>Transform</th>');
     echo('<th>Transform_id</th>');
     echo('<th>Lhs_id</th>');
     echo('<th>Context_id</th></tr>');

     $master_arr_ikena = array();
     $master_arr_ikena[] = array('ID_1','ID_2',
                                 $_SESSION['prop_1'],$_SESSION['prop_1'],
                                 $_SESSION['prop_2'],$_SESSION['prop_2'],
                                 $_SESSION['prop_3'],$_SESSION['prop_3'],
                                 $_SESSION['prop_4'],$_SESSION['prop_4'],
                                 'Transform');

     foreach ($rowss as $row){
        $master_arr_ikena[] = array($row_ref_erk['Molecule_id'],$row['id_b'],
                                    $row_ref_erk['mean'],$row['erk_ic50'],
                                    $row_ref_herg['mean'],$row['herg'],
                                    $row_ref_sol['mean'],$row['solubility'],
                                    $row_ref_logd['mean'], $row['logd'],
                                    $row['smirks']);
     }

     $serialize_user_arr = serialize($master_arr_ikena);

     $_SESSION['filename'] = 'comps_mmps.csv';

     echo($row['id_b']);


     foreach ($rowss as $row){
       echo "<tr><td>";
       $img = "images/".$row_ref_erk['Molecule_id'].".png";
       echo("<img src=".$img.">");
       echo("</td><td>");
       $img = "images/".$row['id_b'].".png";
       echo("<img src=".$img.">");
       echo("</td><td>");
       echo(htmlentities($row_ref_erk['mean']));
       echo("</td><td>");
       echo(htmlentities($row['erk_ic50']));
       echo("</td><td>");
       echo(htmlentities($row_ref_herg['mean']));
       echo("</td><td>");
       echo(htmlentities($row['herg']));
       echo("</td><td>");
       echo(htmlentities($row_ref_sol['mean']));
       echo("</td><td>");
       echo(htmlentities($row['solubility']));
       echo("</td><td>");
       echo(htmlentities($row_ref_logd['mean']));
       echo("</td><td>");
       echo(htmlentities($row['logd']));
       echo("</td><td>");
       echo(htmlentities($row['smirks']));
       echo("</td><td>");
       echo('<a href="transform.php?transform_id='.$row['transform_id'].'">Query_transform</a>');
       echo("</td><td>");
       echo('<a href="lhs.php?lhs_id='.$row['lhs_id'].'">Query_LHS</a>');
       echo("</td><td>");
       echo('<a href="context.php?context_id='.$row['context_id'].'">Query_context</a>');
       echo("</td><tr>\n");
     }

  ?>

 <!-- <!DOCTYPE html>
 <html lang="en" dir="ltr">
   <head>
     <meta charset="utf-8">
     <title>MMP Main page</title>
     <link rel="stylesheet" href="css/mmp.css">
   </head>
   <body>
     <div class="container">
     <h1>Matched Molecular Pairs </h1>
     <nav>
       <a href="index.php" class = "active">Back to main page</a>
     </nav>
     <form method='post' action='download.php'>
      <input type='submit' value='Download IKENA MMPs' name='Export'>

   </body>
 </html> -->

 <form method='post' action='download.php'>
   <div class="downl">
  <input type='submit' value='Download IKENA MMPs' name='Export'>
  </div>

 <textarea name='export_data' style='display: none;'><?php echo $serialize_user_arr; ?></textarea>
</form>
</body>
</html>

   </body>
 </html>

<!-- </body>
</html> -->
