<!DOCTYPE html>
<head>
        <title>Impostos</title>
</head>
<html>

<?php
include_once __DIR__ . "/../config.php";
include_once ROOT . "/sistema/painel.php";
include_once ROOT . "/sistema/database/loginAplicativo.php";

$nivelMenuLogin = buscaLoginAplicativo($_SESSION['idLogin'], 'Impostos');

$configuracao = 1;

$nivelMenu = $nivelMenuLogin['nivelMenu'];

?>

<div class="container-fluid mt-1">
    <div class="row">
        <div class="col-md-12 d-flex justify-content-center">
            <ul class="nav a" id="myTabs">


                <?php
                $tab = '';

                if (isset($_GET['tab'])) {
                    $tab = $_GET['tab'];
                }

                ?>


                <?php if ($nivelMenu >= 1) {
                    if ($tab == '') {
                        $tab = 'ncm';
                    } ?>
                    <li class="nav-item mr-1">
                        <a class="nav-link1 nav-link <?php if ($tab == "ncm") {
                            echo " active ";
                        } ?>" href="?tab=ncm"
                            role="tab">NCM/CEST </a>
                    </li>
                <?php }
                if ($nivelMenu >= 1) { ?>
                    <li class="nav-item mr-1">
                        <a class="nav-link1 nav-link <?php if ($tab == "operacoes") {
                            echo " active ";
                        } ?>"
                            href="?tab=operacoes" role="tab">Operações</a>
                    </li>
                <?php }
                if ($nivelMenu >= 4) { ?>
                    <li class="nav-item mr-1">
                        <a class="nav-link1 nav-link <?php if ($tab == "configuracao") {
                            echo " active ";
                        } ?>"
                            href="?tab=configuracao" role="tab" data-toggle="tooltip" data-placement="top"
                            title="Configurações"><i class="bi bi-gear"></i> Configurações</a>
                    </li>
                <?php } ?>


            </ul>


        </div>

    </div>

</div>

<?php
$src = "";

if ($tab == "ncm") {
    $src = "ncm/ncm_table.php";
}
if ($tab == "operacoes") {
    $src = "operacoes/fisoperacao.php";
}
if ($tab == "configuracao") {
    $src = "configuracao/";
    if (isset($_GET['stab'])) {
        $src = $src . "?stab=" . $_GET['stab'];
    }


}

if ($src !== "") {
    //echo URLROOT ."/impostos/". $src;
    ?>
    <div class="diviFrame">
        <iframe class="iFrame container-fluid " id="iFrameTab"
            src="<?php echo URLROOT ?>/impostos/<?php echo $src ?>"></iframe>
    </div>
    <?php
}
?>

</body>

</html>