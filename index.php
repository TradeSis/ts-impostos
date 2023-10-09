<?php
//lucas 09102023 novo padrao
include_once __DIR__ . "/../config.php";
include_once "header.php";
include_once ROOT . "/sistema/database/loginAplicativo.php";

$nivelMenuLogin = buscaLoginAplicativo($_SESSION['idLogin'], 'Impostos');

$configuracao = 1;

$nivelMenu = $nivelMenuLogin['nivelMenu'];

?>
<!doctype html>
<html lang="pt-BR">

<head>

    <?php include_once ROOT . "/vendor/head_css.php"; ?>
    <title>Impostos</title>

</head>

<body>

    <?php include_once  ROOT . "/sistema/painelmobile.php"; ?>

    <div class="d-flex">

        <?php include_once  ROOT . "/sistema/painel.php"; ?>

        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-10 d-none d-md-none d-lg-block pr-0 pl-0 fundoAbas">
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
                                <a class="nav-link1 nav-link 
                                <?php if ($tab == "ncm") {echo " active ";} ?>" 
                                href="?tab=ncm" role="tab">NCM/CEST </a>
                            </li>
                        <?php }
                        if ($nivelMenu >= 1) { ?>
                            <li class="nav-item mr-1">
                                <a class="nav-link1 nav-link 
                                <?php if ($tab == "operacoes") {echo " active ";} ?>" 
                                href="?tab=operacoes" role="tab">Operações</a>
                            </li>
                        <?php }
                        if ($nivelMenu >= 4) { ?>
                            <li class="nav-item mr-1">
                                <a class="nav-link1 nav-link 
                                <?php if ($tab == "configuracao") {echo " active ";} ?>" 
                                href="?tab=configuracao" role="tab" data-toggle="tooltip" data-placement="top" title="Configurações"><i class="bi bi-gear"></i> Configurações</a>
                            </li>
                        <?php } ?>

                    </ul>
                </div>
                <!--Essa coluna só vai aparecer em dispositivo mobile-->
                <div class="col-7 col-md-9 d-md-block d-lg-none" style="background-color: #13216A;">
                    <!--atraves do GET testa o valor para selecionar um option no select-->
                    <?php if (isset($_GET['tab'])) {
                        $getTab = $_GET['tab'];
                    } else {
                        $getTab = '';
                    } ?>
                    <select class="form-select mt-2" id="subtabSistema" style="color:#000; width:160px;text-align:center;">
                        <option value="<?php echo URLROOT ?>/sistema/index.php?tab=ncm" 
                        <?php if ($getTab == "ncm") {echo " selected ";} ?>>NCM/CEST</option>

                        <option value="<?php echo URLROOT ?>/sistema/index.php?tab=operacoes" 
                        <?php if ($getTab == "operacoes") {echo " selected ";} ?>>Operações</option>

                        <option value="<?php echo URLROOT ?>/sistema/index.php?tab=configuracao" 
                        <?php if ($getTab == "configuracao") {echo " selected ";} ?>>Configurações</option>
                    </select>
                </div>

                <?php include_once  ROOT . "/sistema/novoperfil.php"; ?>

            </div><!--row-->

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

            if ($src !== "") { ?>
                <div class="diviFrame">
                    <iframe class="iFrame container-fluid " id="iFrameTab" src="<?php echo URLROOT ?>/impostos/<?php echo $src ?>"></iframe>
                </div>
            <?php } ?>

        </div><!-- div container -->
    </div><!-- div class="d-flex" -->


    <!-- LOCAL PARA COLOCAR OS JS -->

    <?php include_once ROOT . "/vendor/footer_js.php"; ?>

    <script src="<?php echo URLROOT ?>/sistema/js/mobileSelectTabs.js"></script>

    <!-- LOCAL PARA COLOCAR OS JS -FIM -->
</body>

</html>