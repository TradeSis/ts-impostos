<?php
//Lucas 29022024 - id862 Empresa Administradora
//Lucas 13102023 novo padrao
// gabriel 060623 15:06
include_once(__DIR__ . '/../header.php');
include_once(__DIR__ . '/../database/fisatividade.php');

$atividades = buscaAtividade();
?>
<!doctype html>
<html lang="pt-BR">

<head>

    <?php include_once ROOT . "/vendor/head_css.php"; ?>

</head>

<body>
    <div class="container-fluid">

        <div class="row">
            <BR> <!-- MENSAGENS/ALERTAS -->
        </div>
        <div class="row">
            <BR> <!-- BOTOES AUXILIARES -->
        </div>
        <div class="row align-items-center"> <!-- LINHA SUPERIOR A TABLE -->
            <div class="col-3 text-start">
                <!-- TITULO -->
                <h2 class="ts-tituloPrincipal">Atividades Fiscais</h2>
            </div>
            <div class="col">
                <!-- FILTROS -->
                <div class="input-group">
                    <input type="text" class="form-control ts-input" id="buscaDemanda" placeholder="Buscar por id ou titulo">
                    <span class="input-group-btn">
                        <button class="btn btn-primary" id="buscar" type="button">
                            <span style="font-size: 20px;font-family: 'Material Symbols Outlined'!important;" class="material-symbols-outlined">search</span>
                        </button>
                    </span>
                </div>
            </div>
            <!-- Lucas 29022024 - condi��o Administradora -->
            <?php if ($_SESSION['administradora'] == 1) { ?>
            <div class="col-2 text-end">
                <a href="fisatividade_inserir.php" role="button" class="btn btn-success"><i class="bi bi-plus-square"></i>&nbsp Novo</a>
            </div>
            <?php } ?>

        </div>


        <div class="table mt-2 ts-divTabela">
            <table class="table table-hover table-sm align-middle">
                <thead class="ts-headertabelafixo">
                    <tr>
                        <th>Atividade</th>
                        <!-- Lucas 29022024 - condi��o Administradora -->
                        <?php if ($_SESSION['administradora'] == 1) { ?>
                        <th>Ação</th>
                        <?php } ?>
                    </tr>
                </thead>

                <?php
                foreach ($atividades as $atividade) {
                ?>
                    <tr>
                        <td><?php echo $atividade['nomeAtividade'] ?></td>
                        <!-- Lucas 29022024 - condi��o Administradora -->
                        <?php if ($_SESSION['administradora'] == 1) { ?>
                        <td>
                            <a class="btn btn-warning btn-sm" href="fisatividade_alterar.php?idAtividade=<?php echo $atividade['idAtividade'] ?>" role="button"><i class="bi bi-pencil-square"></i></a>
                            <a class="btn btn-danger btn-sm" href="fisatividade_excluir.php?idAtividade=<?php echo $atividade['idAtividade'] ?>" role="button"><i class="bi bi-trash3"></i></a>
                        </td>
                        <?php } ?>
                    </tr>
                <?php } ?>

            </table>
        </div>

    </div>

    <!-- LOCAL PARA COLOCAR OS JS -->

    <?php include_once ROOT . "/vendor/footer_js.php"; ?>

    <!-- LOCAL PARA COLOCAR OS JS -FIM -->

</body>

</html>