<?php
// gabriel 060623 15:06
include_once(__DIR__ . '/../head.php');
include_once(__DIR__ . '/../database/fisnatureza.php');

$naturezas = buscaNatureza();

?>

<body class="bg-transparent">
    <div class="container" style="margin-top:30px"> 
        
            <div class="row mt-4">
                <div class="col-sm-8">
                        <h2 class="tituloTabela">Naturezas Fiscais</h2>
                    </div>

                <div class="col-sm-4" style="text-align:right">
                        <a href="fisnatureza_inserir.php" role="button" class="btn btn-success"><i class="bi bi-plus-square"></i>&nbsp Novo</a>
                    </div>
          
            </div>
        <div class="card mt-2 text-center">
            <table class="table">
                <thead class="cabecalhoTabela">
                    <tr>
                        <th>Natureza</th>
                        <th>Ação</th>

                    </tr>
                </thead>

                <?php
                foreach ($naturezas as $natureza) {
                ?>
                    <tr>
                        <td><?php echo $natureza['nomeNatureza'] ?></td>
                        <td>
                            <a class="btn btn-warning btn-sm" href="fisnatureza_alterar.php?idNatureza=<?php echo $natureza['idNatureza'] ?>" role="button"><i class="bi bi-pencil-square"></i></a>
                            <a class="btn btn-danger btn-sm" href="fisnatureza_excluir.php?idNatureza=<?php echo $natureza['idNatureza'] ?>" role="button"><i class="bi bi-trash3"></i></a>
                        </td>
                    </tr>
                <?php } ?>

            </table>
        </div>
    </div>


</body>

</html>