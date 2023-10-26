<?php
include_once(__DIR__ . '/../header.php');
include_once '../database/fisnota.php';

$notas = buscarNota($_GET['idNota']);
$produtos = buscarProdutos(null,$notas['chaveNFe']);
?>
<!doctype html>
<html lang="pt-BR">

<head>

    <?php include_once ROOT . "/vendor/head_css.php"; ?>

</head>

<body>
    <div class="card container mt-2">
        <div class="container mt-3">
			<div class="row mt-3">
				<div class="col-sm-10">
					<h5>Informações da Nota Fiscal</h5>
				</div>
				<div class="col-sm-2">
					<button onclick="history.back()" role="button" class="btn btn-primary"><i class="bi bi-arrow-left-square"></i></i>&#32;Voltar</button>
				</div>
			</div>
            <div class="row mt-3">
                <div class="col-md-6">
                    <label class="form-label ts-label">Chave de Acesso</label>
                    <input type="text" class="form-control ts-input" value="<?php echo $notas['chaveNFe'] ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label ts-label">Natureza da operação</label>
                    <input type="text" class="form-control ts-input" value="<?php echo $notas['naturezaOp'] ?>">
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-md-3">
                    <label class="form-label ts-label">Modelo</label>
                    <input type="text" class="form-control ts-input" value="<?php echo $notas['modelo'] ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label ts-label">Série</label>
                    <input type="text" class="form-control ts-input" value="<?php echo $notas['serie'] ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label ts-label">Nota Fiscal</label>
                    <input type="text" class="form-control ts-input" value="<?php echo $notas['NF'] ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label ts-label">Data de Emissão</label>
                    <input type="text" class="form-control ts-input" value="<?php echo date('d/m/Y', strtotime($notas['dtEmissao'])) ?>">
                </div>
            </div>
            <div class="row mt-3">
                <h5>Emitente</h5>
                <div class="col-md-4">
                    <label class="form-label ts-label">CNPJ</label>
                    <input type="text" class="form-control ts-input" value="<?php echo $notas['emitente_cpfCnpj'] ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label ts-label">IE</label>
                    <input type="text" class="form-control ts-input" value="<?php echo $notas['emitente_IE'] ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label ts-label">Razão Social</label>
                    <input type="text" class="form-control ts-input" value="<?php echo $notas['emitente_nome'] ?>">
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-md-4">
                    <label class="form-label ts-label">Municipio</label>
                    <input type="text" class="form-control ts-input" value="<?php echo $notas['emitente_municipio'] ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label ts-label">UF</label>
                    <input type="text" class="form-control ts-input" value="<?php echo $notas['emitente_UF'] ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label ts-label">País</label>
                    <input type="text" class="form-control ts-input" value="<?php echo $notas['emitente_pais'] ?>">
                </div>
            </div>
            <div class="row mt-3">
                <h5>Destinatário</h5>
                <div class="col-md-4">
                    <label class="form-label ts-label">Doc</label>
                    <input type="text" class="form-control ts-input" value="<?php echo $notas['destinatario_cpfCnpj'] ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label ts-label">IE</label>
                    <input type="text" class="form-control ts-input" value="<?php echo $notas['destinatario_IE'] ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label ts-label">Nome</label>
                    <input type="text" class="form-control ts-input" value="<?php echo $notas['destinatario_nome'] ?>">
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-md-4">
                    <label class="form-label ts-label">Municipio</label>
                    <input type="text" class="form-control ts-input" value="<?php echo $notas['destinatario_municipio'] ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label ts-label">UF</label>
                    <input type="text" class="form-control ts-input" value="<?php echo $notas['destinatario_UF'] ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label ts-label">País</label>
                    <input type="text" class="form-control ts-input" value="<?php echo $notas['destinatario_pais'] ?>">
                </div>
            </div>
            <div class="row mt-3">
                <h5>Valores</h5>
                <div class="col-md-3">
                    <label class="form-label ts-label">Base de Cálculo</label>
                    <input type="text" class="form-control ts-input" value="<?php echo number_format($notas['baseCalculo'], 2, ',', '.') ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label ts-label">Valor Produtos</label>
                    <input type="text" class="form-control ts-input" value="<?php echo number_format($notas['valorProdutos'], 2, ',', '.') ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label ts-label">PIS</label>
                    <input type="text" class="form-control ts-input" value="<?php echo number_format($notas['pis'], 2, ',', '.') ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label ts-label">COFINS</label>
                    <input type="text" class="form-control ts-input" value="<?php echo number_format($notas['cofins'], 2, ',', '.') ?>">
                </div>
            </div>
			<div class="table ts-divTabela ts-tableFiltros table-striped table-hover mt-3">
                <h5>Produtos</h5>
                <table class="table table-sm">
                    <thead class="ts-headertabelafixo">
                        <tr class="ts-headerTabelaLinhaCima">
							<th>#</th>
							<th>Código</th>
							<th>Produto</th>
							<th>Quantidade</th>
							<th>Unitario</th>
							<th>Valor Total</th>
							<th>CFOP</th>
							<th>NCM</th>
							<th>CEST</th>
						</tr>
					</thead>

					<?php
                    $nItem = 1;
					foreach($produtos as $produto) { ?>
						<tr>
							<td> <?php echo $nItem ?></td>
							<td> <?php echo $produto['codigoProduto'] ?></td>
							<td> <?php echo $produto['nomeProduto'] ?></td>
							<td> <?php echo number_format($produto['quantidade'], 0, ',', '.'); ?></td>
							<td> <?php echo number_format($produto['valorUnidade'], 2, ',', '.'); ?></td>
							<td> <?php echo number_format($produto['valorTotal'], 2, ',', '.'); ?></td>
							<td> <?php echo $produto['cfop'] ?></td>
							<td> <?php echo $produto['ncm'] ?></td>
							<td> <?php echo $produto['cest'] ?></td>
						</tr>
					<?php $nItem++;
                    } ?>
				</table>
			</div>
		</div>
    </div>

    <!-- LOCAL PARA COLOCAR OS JS -->

    <?php include_once ROOT . "/vendor/footer_js.php"; ?>

    <!-- LOCAL PARA COLOCAR OS JS -FIM -->

</body>

</html>