<?php
  require_once "pdo.php";
  require_once "bootstrap.php";
  session_start();
?>


<?php

   if(isset($_POST['prop_1']) &&
             isset($_POST['prop_2']) && isset($_POST['prop_3'])
             && isset($_POST['prop_4']) && isset($_POST['prop_5'])){

       unset($_SESSION['prop_1']);
       unset($_SESSION['prop_2']);
       unset($_SESSION['prop_3']);
       unset($_SESSION['prop_4']);
       unset($_SESSION['prop_5']);
       unset($_SESSION['Compound_id']);

       if(strlen($_POST['prop_1'])<1 || strlen($_POST['prop_2'])<1
               || strlen($_POST['prop_3']) < 1 || strlen($_POST['prop_4'])<1
             || strlen($_POST['prop_5']) < 1) {
         $_SESSION['err'] = "All fields are required";
       }else{


          $_SESSION['prop_1'] = $_POST['prop_1'];
          $_SESSION['prop_2'] = $_POST['prop_2'];
          $_SESSION['prop_3'] = $_POST['prop_3'];
          $_SESSION['prop_4'] = $_POST['prop_4'];
          $_SESSION['prop_5'] = $_POST['prop_5'];

        $stmt = $pdo->prepare("SELECT Compound_id,erk_ic50,herg_IC50, Fassif_sol, erk_molid, mean as logd
                               FROM (select Compound_id,erk_ic50,herg_IC50, mean as Fassif_sol, erk_molid
                               FROM (select Compound_id,erk_ic50, mean as herg_IC50,erk_molid
                               FROM (select Molecule_id as Compound_id, mean as erk_ic50, mol_id as erk_molid
                               FROM ". $_POST['prop_1']. ") as tab
                               left join ". $_POST['prop_2']. " on ". $_POST['prop_2']. ".mol_id = erk_molid) as tab
                               left join ". $_POST['prop_3']. "
                               on ". $_POST['prop_3']. ".mol_id = erk_molid)
                               as tab left join ". $_POST['prop_4']. " on ". $_POST['prop_4']. ".mol_id = erk_molid
                               order by erk_ic50");

         // print_r($stmt);

         $stmt->execute(array());
         $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

         // $output = array_slice($rows, 0, 3);

         // print_r($rows);
         // $serialize_user_arr = base64_encode(serialize($rows));

         $serialize_user_arr = serialize($rows);

         // print_r($serialize_user_arr);


         echo('<table border="1">'."\n");
         echo('<tr><th>Compound</th>');
         echo('<th>'.$_POST['prop_1'].'</th>');
         echo('<th>'.$_POST['prop_2'].'</th>');
         echo('<th>'.$_POST['prop_3'].'</th>');
         echo('<th>'.$_POST['prop_4'].'</th>');

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
       }  // else all field are required

   } // Last

 ?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>MMP Webservice</title>
    <link rel="stylesheet"  href= "css/mmp.css">
    <script src="js/jvscript.js"></script>
  </head>
  <body>
    <header>
       <h1> MMP webservice</h1>
       <?php
         if (isset($_SESSION["err"])){
           echo('<h2 style="color:red">'.$_SESSION["err"]."</h2\n>");
           unset($_SESSION["err"]);
         }

        ?>



       <div class="desc">
         <h3> This webservice is designed to perform MMP analysis of IKENA compounds</h3>
       </div>
    </header>
    <main>
      <aside class="left">
        <h2> IKENA Data</h2>
        <div class="buttons">
          <button type="button" onclick="openMe()">See the Tables</button>
        	<button type="button" onclick="closeMe()">Hide the Tables</button>
        </div>
        <div id = "demo">
        <p>Please choose some tabels and cut and paste in the form on the right</p>
        <ol>
          <li>erk5_biochem_ic50__avg_ic50__nm_</li>
          <li>herg__ic50__um_</li>
          <li>kinetic_solubility__fassif___kinetic_solubility__um_</li>
          <li>measured_logd__logd</li>
          <li>Pharmacokinetic__Cmax__ng_mL_</li>
          <li>Pharmacokinetic__T1_2__h_</li>
          <li>Pharmacokinetic__Tmax__h_</li>
          <li>Pharmacokinetic__Vdss__L_kg_</li>
          <li>Pharmacokinetic__Cl__mL_min_kg_</li>
          <li>Pharmacokinetic__Bioavailability____</li>
          <li>Plasma_Stability__T1_2</li>
          <li>ERK5_MEF2_IC50_Paraza__Avg_IC50__nM_</li>
          <li>Caco2_w_BSA__Papp__A_to_B___10_6_cm_s_</li>
          <li>Caco2_w_BSA__Paap__B_to_A___10_6_cm_s_</li>
          <li>Caco2_w_BSA__Efflux_Ratio</li>
          <li>CRaf_MEK_SPR__KD__M_</li>
          <li>FBS_Binding__Equilibrium_Dialysis___Unbound____</li>
          <li>FBS_Binding__Equilibrium_Dialysis___Bound____</li>
          <li>FBS_Binding__Equilibrium_Dialysis___Remaining____</li>
          <li>Hepatocyte_stability__T1_2__min_</li>
          <li>Hepatocyte_stability__ER__Ratio_</li>
          <li>Hepatocyte_stability__Clint__mL_min_kg_</li>
          <li>pERK_AsPC_1__G12D__HTRF__4_hour___IC50__uM_</li>
          <li>pERK_AsPC_1__G12D__HTRF__4_hour___Average_IC50__nM_</li>
          <li>pERK_AsPC_1__G12D__ICW__4_hour___IC50__uM_</li>
          <li>pERK_AsPC_1__G12D__ICW__4_hour___IC95__uM_</li>
          <li>pERK_AsPC_1__G12D__ICW__4_hour___Average_IC50__nM_</li>
          <li>Plasma_Protein_Binding__dialysis___Unbound____</li>
          <li>Plasma_Protein_Binding__dialysis___Bound____</li>
          <li>Plasma_Protein_Binding_ucentri__Unbound____</li>
          <li>Plasma_Protein_Binding_ucentri__Recovery____</li>
          <li>AsPC_1__G12D__CTG____inhibition____</li>
          <li>AsPC_1__G12D__CTG__Average_IC50__nM_</li>
          <li>>G12C_KRas__GTP_HTRF__IC50__uM_</li>
          <li>G12C_KRas__GTP_HTRF__Average_IC50__nM_</li>
          <li>G12C_KRas__GTP_HTRF__Selectivity__G12C_WT_</li>
          <li>G12D_KRas_CRAF_GMPPNP_HTRF__IC50__uM_</li>
          <li>G12D_KRas_CRAF_GMPPNP_HTRF__Average_IC50__nM_</li>
          <li>G12D_KRas_CRAF_GMPPNP_HTRF__Selectivity__G12D_WT_</li>
          <li>G12D_KRas__GTP_HTRF__IC50__uM_</li>
          <li>G12D_KRas__GTP_HTRF__Average_IC50__nM_</li>
          <li>G12V_KRas__GTP_HTRF__IC50__uM_</li>
          <li>G12V_KRas__GTP_HTRF__Average_IC50__nM_</li>
          <li>G12V_KRas__GTP_HTRF__Selectivity__G12V_WT_</li>
          <li>MDCK_w_BSA___PGP_Inhibitor__Papp__A_to_B___10_6_cm_s_</li>
          <li>MDCK_w_BSA___PGP_Inhibitor__Papp__B_to_A___10_6_cm_s_</li>
          <li>MDCK_w_BSA___PGP_Inhibitor__Efflux_Ratio</li>
          <li>MDCK_w_BSA___PGP_Inhibitor__Recovery__A_to_B_____</li>
          <li>MDCK_w_BSA___PGP_Inhibitor__Recovery__B_to_A_____</li>
          <li>Microsomal_Stability__T1_2__min_</li>
          <li>Microsomal_Stability__ER__Ratio_</li>
          <li>Microsomal_Stability__Clint__mL_min_kg_</li>
          <li>PAMPA_egg__Pe</li>
          <li>PAMPA_egg__Recovery____</li>
          <li>PC9__WT__CTG__IC50__uM_</li>
          <li>PC9__WT__CTG__IC95__uM_</li>
          <li>PC9__WT__CTG__Average_IC50__nM_</li>
          <li>pERK_PC9__WT__HTRF__4_hour___IC50__uM_</li>
          <li>pERK_PC9__WT__HTRF__4_hour___IC95__uM_</li>
          <li>pERK_PC9__WT__HTRF__4_hour___Average_IC50__nM_</li>
          <li>WT_KRas_CRAF_GMPPNP_HTRF__IC50__uM_</li>
          <li>WT_KRas_CRAF_GMPPNP_HTRF__Average_IC50__nM_</li>
          <li>WT_KRas__GTP_HTRF__Average_IC50__nM_</li>
          <li>log_P</li>
          <li>log_D</li>
          <li>Topological_polar_surface_area</li>
        </ol>
        </div>

      </aside>



      <section class = "right">
        <h2> Please paste database tables here</h2>
        <form method="post">
          <p> Main property:
            <p>Please choose a table from IKENA data with IC50 values from the options shown in IKENA DATA</p>
             <input type="text" name="prop_1" size="30">
          </p>
          <p> Property_2, Any property of your choice from IKENA DATA :
            <!-- <p>Any property of your choice from IKENA DATA</p> -->
             <input type="text" name="prop_2" size="30">
          </p>
          <p> Property_3, Any property of your choice from IKENA DATA:
            <!-- <p>Any property of your choice from IKENA DATA</p> -->
             <input type="text" name="prop_3" size="30">
          </p>
          <p> Property_4, Any property of your choice from IKENA DATA:
            <!-- <p>Any property of your choice from IKENA DATA</p> -->
             <input type="text" name="prop_4" size="30">
          </p>
          <p> Chembl_Porperty_of_Interest, Any property from CHEMBL TABLES:
            <!-- <p>Any property from CHEMBL TABLES</p> -->
             <input type="text" name="prop_5" size="30">
          </p>
           <input type="submit" name="submit" value="Submit">
        </form>
        <!-- <form method='post' action='download.php'>
         <input type='submit' value='Export' name='Export'>
         <textarea name='export_data' style='display: none;'><?php echo $serialize_user_arr; ?></textarea>
       </form> -->

      </section>

      <aside class="center">
        <h2>Chembl Tables</h2>
        <div id = "demo">
          <p>Please choose one table and cut and paste</p>
          <ol>
            <li>chembl_herg</li>
            <li>chembl_clearance</li>
            <li>chembl_vdss</li>
            <li>chembl_bioavailability</li>
            <li>chembl_half_life</li>
          </ol>
        </div>
      </aside>
    </main>

  </body>
</html>
