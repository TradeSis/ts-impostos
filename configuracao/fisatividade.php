<?php
// gabriel 060623 15:06
include_once(__DIR__ . '/../head.php');
include_once(__DIR__ . '/../database/fisatividade.php');

$atividades = buscaAtividade();
?>

<body class="bg-transparent">
    <div class="container" style="margin-top:30px"> 
        
            <div class="row mt-4">
                <div class="col-sm-8">
                        <h2 class="tituloTabela">Atividades Fiscais</h2>
                    </div>

                <div class="col-sm-4" style="text-align:right">
                        <a href="fisatividade_inserir.php" role="button" class="btn btn-success"><i class="bi bi-plus-square"></i>&nbsp Novo</a>
                    </div>
          
            </div>
        <div class="card mt-2 text-center">
            <table class="table">
                <thead class="cabecalhoTabela">
                    <tr>
                        <th>Atividade</th>
                        <th>Ação</th>

                    </tr>
                </thead>

                <?php
                foreach ($atividades as $atividade) {
                ?>
                    <tr>
                        <td><?php echo $atividade['nomeAtividade'] ?></td>
                        <td>
                            <a class="btn btn-warning btn-sm" href="fisatividade_alterar.php?idAtividade=<?php echo $atividade['idAtividade'] ?>" role="button"><i class="bi bi-pencil-square"></i></a>
                            <a class="btn btn-danger btn-sm" href="fisatividade_excluir.php?idAtividade=<?php echo $atividade['idAtividade'] ?>" role="button"><i class="bi bi-trash3"></i></a>
                        </td>
                    </tr>
                <?php } ?>

            </table>
        </div>
    </div>


</body>

</html>