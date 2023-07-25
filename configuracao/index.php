<?php
include_once(__DIR__ . '/../head.php');
?>

<style>
  .temp {
    color: black
  }
</style>
<div class="container-fluid">
  <div class="row">
    <div class="col-md-2 mb-3">
      <ul class="nav nav-pills flex-column" id="myTab" role="tablist">
        <?php
        $stab = 'fisatividade';
        if (isset($_GET['stab'])) {
          $stab = $_GET['stab'];
        }
        //echo "<HR>stab=" . $stab;
        ?>
        <li class="nav-item ">
          <a class="nav-link <?php if ($stab == "fisatividade") {
            echo " active ";
          } ?>"
            href="?tab=configuracao&stab=fisatividade" role="tab" style="color:black">Atividade</a>
        </li>
        <li class="nav-item ">
          <a class="nav-link <?php if ($stab == "fisnatureza") {
            echo " active ";
          } ?>"
            href="?tab=configuracao&stab=fisnatureza" role="tab" style="color:black">Natureza</a>
        </li>
        <li class="nav-item ">
          <a class="nav-link <?php if ($stab == "fisprocesso") {
            echo " active ";
          } ?>"
            href="?tab=configuracao&stab=fisprocesso" role="tab" style="color:black">Processo</a>
        </li>
    

      </ul>
    </div>
    <div class="col-md-10">
      <?php
          $ssrc = "";

          if ($stab == "fisatividade") {
            $ssrc = "fisatividade.php";
          }
          if ($stab == "fisnatureza") {
            $ssrc = "fisnatureza.php";
          }
          if ($stab == "fisprocesso") {
            $ssrc = "fisprocesso.php";
          }

          if ($ssrc !== "") {
            //echo $ssrc;
            include($ssrc);
          }

      ?>

    </div>
  </div>



</div>