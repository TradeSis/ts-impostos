<?php
// gabriel 060623 15:06

include_once('../head.php');
include_once('../database/fisprocesso.php');

$processos = buscaProcesso();

?>

<body class="bg-transparent">
    <div class="container" style="margin-top:30px"> 
        
            <div class="row mt-4">
                <div class="col-sm-8">
                        <p class="tituloTabela">Processos Fiscais</p>
                    </div>

                <div class="col-sm-4" style="text-align:right">
                        <a href="fisprocesso_inserir.php" role="button" class="btn btn-primary">Adicionar Processo</a>
                    </div>
          
            </div>
        <div class="card shadow mt-2">
            <table class="table">
                <thead>
                    <tr>
                        <th class="text-center">Processo</th>
                        <th class="text-center">Ação</th>

                    </tr>
                </thead>

                <?php
                foreach ($processos as $processo) {
                ?>
                    <tr>
                        <td class="text-center"><?php echo $processo['nomeProcesso'] ?></td>
                        <td class="text-center">
                            <a class="btn btn-primary btn-sm" href="fisprocesso_alterar.php?idProcesso=<?php echo $processo['idProcesso'] ?>" role="button">Editar</a>
                            <a class="btn btn-danger btn-sm" href="fisprocesso_excluir.php?idProcesso=<?php echo $processo['idProcesso'] ?>" role="button">Excluir</a>
                        </td>
                    </tr>
                <?php } ?>

            </table>
        </div>
    </div>


</body>

</html>