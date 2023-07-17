<?php
// gabriel 060623 15:06

include_once('../head.php');
include_once('../database/fisnatureza.php');

$naturezas = buscaNatureza();

?>

<body class="bg-transparent">
    <div class="container" style="margin-top:30px"> 
        
            <div class="row mt-4">
                <div class="col-sm-8">
                        <p class="tituloTabela">Naturezas Fiscais</p>
                    </div>

                <div class="col-sm-4" style="text-align:right">
                        <a href="fisnatureza_inserir.php" role="button" class="btn btn-primary">Adicionar Natureza</a>
                    </div>
          
            </div>
        <div class="card shadow mt-2">
            <table class="table">
                <thead>
                    <tr>
                        <th class="text-center">Natureza</th>
                        <th class="text-center">Ação</th>

                    </tr>
                </thead>

                <?php
                foreach ($naturezas as $natureza) {
                ?>
                    <tr>
                        <td class="text-center"><?php echo $natureza['nomeNatureza'] ?></td>
                        <td class="text-center">
                            <a class="btn btn-primary btn-sm" href="fisnatureza_alterar.php?idNatureza=<?php echo $natureza['idNatureza'] ?>" role="button">Editar</a>
                            <a class="btn btn-danger btn-sm" href="fisnatureza_excluir.php?idNatureza=<?php echo $natureza['idNatureza'] ?>" role="button">Excluir</a>
                        </td>
                    </tr>
                <?php } ?>

            </table>
        </div>
    </div>


</body>

</html>