<?php
// gabriel 060623 15:06

include_once('../head.php');
include_once('../database/fisatividade.php');

$atividades = buscaAtividade();

?>

<body class="bg-transparent">
    <div class="container" style="margin-top:30px"> 
        
            <div class="row mt-4">
                <div class="col-sm-8">
                        <p class="tituloTabela">Atividades Fiscais</p>
                    </div>

                <div class="col-sm-4" style="text-align:right">
                        <a href="fisatividade_inserir.php" role="button" class="btn btn-primary">Adicionar Atividade</a>
                    </div>
          
            </div>
        <div class="card shadow mt-2">
            <table class="table">
                <thead>
                    <tr>
                        <th class="text-center">Atividade</th>
                        <th class="text-center">Ação</th>

                    </tr>
                </thead>

                <?php
                foreach ($atividades as $atividade) {
                ?>
                    <tr>
                        <td class="text-center"><?php echo $atividade['nomeAtividade'] ?></td>
                        <td class="text-center">
                            <a class="btn btn-primary btn-sm" href="fisatividade_alterar.php?idAtividade=<?php echo $atividade['idAtividade'] ?>" role="button">Editar</a>
                            <a class="btn btn-danger btn-sm" href="fisatividade_excluir.php?idAtividade=<?php echo $atividade['idAtividade'] ?>" role="button">Excluir</a>
                        </td>
                    </tr>
                <?php } ?>

            </table>
        </div>
    </div>


</body>

</html>