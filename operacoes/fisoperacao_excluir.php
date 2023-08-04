<?php
// gabriel 060623 15:06

include_once('../head.php');
include_once('../database/fisoperacao.php');

$operacao = buscaOperacao($_GET['idOperacao']);

?>

<body class="bg-transparent">

    <div class="container" style="margin-top:10px">

        <div class="col-sm mt-4" style="text-align:right">
            <a href="fisoperacao.php" role="button" class="btn btn-primary"><i
                    class="bi bi-arrow-left-square"></i></i>&#32;Voltar</a>
        </div>
        <div class="col-sm">
            <spam class="col titulo">Alterar Operação</spam>
        </div>
        <div class="container" style="margin-top: 10px">
            <form action="../database/fisoperacao.php?operacao=excluir" method="post">
                
                <input type="text" class="form-control" name="idOperacao" value="<?php echo $operacao['idOperacao'] ?>" style="display: none">

                <label class="labelForm">Nome da operação</label>
                <input type="text" class="form-control" name="nomeOperacao" value="<?php echo $operacao['nomeOperacao'] ?>">

                <div class="row">
                    <div class="col-md form-group-select" style="margin-top: 37px;">
                        <label class="labelForm">Atividade</label>
                        <select class="select form-control" name="idAtividade">
                            <option  value="<?php echo $operacao['idAtividade'] ?>"><?php echo $operacao['nomeAtividade'] ?></option>
                        </select>
                    </div>

                    <div class="col-md form-group-select" style="margin-top: 37px;">
                        <label class="labelForm">Processo</label>
                        <select class="select form-control" name="idProcesso">
                            <option  value="<?php echo $operacao['idProcesso'] ?>"><?php echo $operacao['nomeProcesso'] ?></option>
                        </select>
                    </div>

                    <div class="col-md form-group-select" style="margin-top: 37px;">
                        <label class="labelForm">Natureza</label>
                        <select class="select form-control" name="idNatureza">
                            <option  value="<?php echo $operacao['idNatureza'] ?>"><?php echo $operacao['nomeNatureza'] ?></option>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md form-group" style="margin-top: 37px;">
                        <label class="labelForm">idGrupoOper</label>
                        <input type="text" class="form-control" name="idGrupoOper" value="<?php echo $operacao['idGrupoOper'] ?>">
                    </div>

                    <div class="col-md form-group" style="margin-top: 37px;">
                        <label class="labelForm">idEntSai</label>
                        <input type="text" class="form-control" name="idEntSai" value="<?php echo $operacao['idEntSai'] ?>">
                    </div>

                    <div class="col-md form-group" style="margin-top: 37px;">
                        <label class="labelForm">xfop</label>
                        <input type="text" class="form-control" name="xfop" value="<?php echo $operacao['xfop'] ?>">
                    </div>
                </div>

                <div style="text-align:right;margin-top:30px">
                    <button type="submit" class="btn  btn-danger"><i
                            class="bi bi-sd-card-fill"></i>&#32;Excluir</button>
                </div>
            </form>
        </div>

    </div>


</body>

</html>