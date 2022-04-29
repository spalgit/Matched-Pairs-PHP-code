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

          header('Location: all_comps.php');
          return;

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
<li>pharmacokinetic__cmax__ng_ml_</li>
<li>pharmacokinetic__tmax__h_</li>
<li>pharmacokinetic__t1_2__h_</li>
<li>pharmacokinetic__vdss__l_kg_</li>
<li>pharmacokinetic__cl__ml_min_kg_</li>
<li>pharmacokinetic__bioavailability____</li>
<li>plasma_stability__t1_2</li>
<li>erk5_mef2_ic50_paraza__avg_ic50__nm_</li>
<li>caco2_w_bsa__papp__a_to_b___10_6_cm_s_</li>
<li>caco2_w_bsa__paap__b_to_a___10_6_cm_s_</li>
<li>caco2_w_bsa__efflux_ratio</li>
<li>craf_mek_spr__kd__m_</li>
<li>fbs_binding__equilibrium_dialysis___unbound____</li>
<li>fbs_binding__equilibrium_dialysis___bound____</li>
<li>fbs_binding__equilibrium_dialysis___remaining____</li>
<li>hepatocyte_stability__t1_2__min_</li>
<li>hepatocyte_stability__er__ratio_</li>
<li>hepatocyte_stability__clint__ml_min_kg_</li>
<li>perk_aspc_1__g12d__htrf__4_hour___ic50__um_</li>
<li>perk_aspc_1__g12d__htrf__4_hour___average_ic50__nm_</li>
<li>perk_aspc_1__g12d__icw__4_hour___ic50__um_</li>
<li>perk_aspc_1__g12d__icw__4_hour___ic95__um_</li>
<li>perk_aspc_1__g12d__icw__4_hour___average_ic50__nm_</li>
<li>plasma_protein_binding__dialysis___unbound____</li>
<li>plasma_protein_binding__dialysis___bound____</li>
<li>plasma_protein_binding_ucentri__unbound____</li>
<li>plasma_protein_binding_ucentri__recovery____</li>
<li>aspc_1__g12d__ctg____inhibition____</li>
<li>aspc_1__g12d__ctg__average_ic50__nm_</li>
<li>g12c_kras__gtp_htrf__ic50__um_</li>
<li>g12c_kras__gtp_htrf__average_ic50__nm_</li>
<li>g12c_kras__gtp_htrf__selectivity__g12c_wt_</li>
<li>g12d_kras_craf_gmppnp_htrf__ic50__um_</li>
<li>g12d_kras_craf_gmppnp_htrf__average_ic50__nm_</li>
<li>g12d_kras_craf_gmppnp_htrf__selectivity__g12d_wt_</li>
<li>g12d_kras__gtp_htrf__ic50__um_</li>
<li>g12d_kras__gtp_htrf__average_ic50__nm_</li>
<li>g12v_kras__gtp_htrf__ic50__um_</li>
<li>g12v_kras__gtp_htrf__average_ic50__nm_</li>
<li>g12v_kras__gtp_htrf__selectivity__g12v_wt_</li>
<li>mdck_w_bsa___pgp_inhibitor__papp__a_to_b___10_6_cm_s_</li>
<li>mdck_w_bsa___pgp_inhibitor__papp__b_to_a___10_6_cm_s_</li>
<li>mdck_w_bsa___pgp_inhibitor__efflux_ratio</li>
<li>mdck_w_bsa___pgp_inhibitor__recovery__a_to_b_____</li>
<li>mdck_w_bsa___pgp_inhibitor__recovery__b_to_a_____</li>
<li>microsomal_stability__t1_2__min_</li>
<li>microsomal_stability__er__ratio_</li>
<li>microsomal_stability__clint__ml_min_kg_</li>
<li>pampa_egg__pe</li>
<li>pampa_egg__recovery____</li>
<li>pc9__wt__ctg__ic50__um_</li>
<li>pc9__wt__ctg__ic95__um_</li>
<li>pc9__wt__ctg__average_ic50__nm_</li>
<li>perk_pc9__wt__htrf__4_hour___ic50__um_</li>
<li>perk_pc9__wt__htrf__4_hour___ic95__um_</li>
<li>perk_pc9__wt__htrf__4_hour___average_ic50__nm_</li>
<li>wt_kras_craf_gmppnp_htrf__ic50__um_</li>
<li>wt_kras_craf_gmppnp_htrf__average_ic50__nm_</li>
<li>wt_kras__gtp_htrf__average_ic50__nm_</li>
<li>log_p</li>
<li>log_d</li>
<li>topological_polar_surface_area</li>

          <!-- <li>erk5_biochem_ic50__avg_ic50__nm_</li>
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
          <li>Topological_polar_surface_area</li> -->
        </ol>
        </div>

      </aside>



      <section class = "right">
        <h2> Copy and paste from IKENA and Chembl data</h2>


        <form method="post">
          <p> Main property:
            <p>Choose a table from IKENA data with IC50 values from the options shown in IKENA DATA</p>
             <input type="text" name="prop_1" size="30">
          </p>
          <p> Property_2, Any property of your choice from IKENA DATA :
             <input type="text" name="prop_2" size="30">
          </p>
          <p> Property_3, Any property of your choice from IKENA DATA:
             <input type="text" name="prop_3" size="30">
          </p>
          <p> Property_4, Any property of your choice from IKENA DATA:
             <input type="text" name="prop_4" size="30">
          </p>
          <p> Chembl_Porperty_of_Interest, Any property from CHEMBL TABLES:
             <input type="text" name="prop_5" size="30">
          </p>
           <input type="submit" name="submit" value="Submit">
        </form>


      </section>

      <aside class="center">
        <h2>Chembl Data</h2>
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
