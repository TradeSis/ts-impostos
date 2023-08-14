<?php
// gabriel 060623 15:06
include_once(__DIR__ . '/../head.php');
include_once(__DIR__ . '/../database/fisprocesso.php');

$processos = buscaProcesso();

?>

<body class="bg-transparent">
    <div class="container" style="margin-top:30px"> 
        
            <div class="row mt-4">
                <div class="col-sm-8">
                        <h2 class="tituloTabela">Processos Fiscais</h2>
                    </div>

                <div class="col-sm-4" style="text-align:right">
                        <a href="fisprocesso_inserir.php" role="button" class="btn btn-success"><i class="bi bi-plus-square"></i>&nbsp Novo</a>
                    </div>
          
            </div>
        <div class="card mt-2 text-center">
            <table class="table">
                <thead class="cabecalhoTabela">
                    <tr>
                        <th>Processo</th>
                        <th>Ação</th>

                    </tr>
                </thead>

                <?php
                foreach ($processos as $processo) {
                ?>
                    <tr>
                        <td><?php echo $processo['nomeProcesso'] ?></td>
                        <td>
                            <a class="btn btn-warning btn-sm" href="fisprocesso_alterar.php?idProcesso=<?php echo $processo['idProcesso'] ?>" role="button"><i class="bi bi-pencil-square"></i></a>
                            <a class="btn btn-danger btn-sm" href="fisprocesso_excluir.php?idProcesso=<?php echo $processo['idProcesso'] ?>" role="button"><i class="bi bi-trash3"></i></a>
                        </td>
                    </tr>
                <?php } ?>

            </table>
        </div>
    </div>


</body>

</html>