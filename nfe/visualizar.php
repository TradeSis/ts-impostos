<?php
include_once(__DIR__ . '/../header.php');
// Transformando arquivo XML em Objeto
$xml = simplexml_load_file($_GET['arquivo']);
$NFe = $xml->NFe;

$xml = $NFe;
?>
<!doctype html>
<html lang="pt-BR">

<head>

    <?php include_once ROOT . "/vendor/head_css.php"; ?>

</head>

<body>
    <div class="card container mt-2">
        <div class="container mt-3">
			<div class="row">
				<div class="col-sm-8">
					<h5>Informações da Nota Fiscal</h5>
				</div>
				<div class="col-sm-4" style="text-align:right">
					<button onclick="history.back()" role="button" class="btn btn-primary"><i class="bi bi-arrow-left-square"></i></i>&#32;Voltar</button>
				</div>
			</div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label ts-label">Chave de Acesso</label>
                        <input type="text" class="form-control ts-input" value="<?php echo str_replace("NFe", "", $xml->infNFe['Id']) ?>">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label ts-label">Natureza da operação</label>
                        <input type="text" class="form-control ts-input" value="<?php echo $xml->infNFe->ide->natOp ?>">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label ts-label">Modelo</label>
                        <input type="text" class="form-control ts-input" value="<?php echo $xml->infNFe->ide->mod ?>">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label ts-label">Série</label>
                        <input type="text" class="form-control ts-input" value="<?php echo $xml->infNFe->ide->serie ?>">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label ts-label">Nota Fiscal</label>
                        <input type="text" class="form-control ts-input" value="<?php echo $xml->infNFe->ide->nNF ?>">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label ts-label">Data de Emissão</label>
                        <input type="text" class="form-control ts-input" value="<?php echo date('d/m/Y', strtotime($xml->infNFe->ide->dEmi)) ?>">
                    </div>
                </div>
            </div>
            <div class="row">
                <h5>Emitente</h5>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label ts-label">CNPJ</label>
                        <input type="text" class="form-control ts-input" value="<?php echo $xml->infNFe->emit->CNPJ ?>">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label ts-label">IE</label>
                        <input type="text" class="form-control ts-input" value="<?php echo $xml->infNFe->emit->IE ?>">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label ts-label">Razão Social</label>
                        <input type="text" class="form-control ts-input" value="<?php echo $xml->infNFe->emit->xNome ?>">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label ts-label">Municipio</label>
                        <input type="text" class="form-control ts-input" value="<?php echo $xml->infNFe->emit->enderEmit->xMun ?>">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label ts-label">UF</label>
                        <input type="text" class="form-control ts-input" value="<?php echo $xml->infNFe->emit->enderEmit->UF ?>">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label ts-label">País</label>
                        <input type="text" class="form-control ts-input" value="<?php echo $xml->infNFe->emit->enderEmit->xPais ?>">
                    </div>
                </div>
            </div>
            <div class="row">
                <h5>Destinatário</h5>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label ts-label">Doc</label>
                        <input type="text" class="form-control ts-input" value="<?php echo $xml->infNFe->dest->CNPJ ?>">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label ts-label">IE</label>
                        <input type="text" class="form-control ts-input" value="<?php echo $xml->infNFe->dest->IE ?>">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label ts-label">Nome</label>
                        <input type="text" class="form-control ts-input" value="<?php echo $xml->infNFe->dest->xNome ?>">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label ts-label">Municipio</label>
                        <input type="text" class="form-control ts-input" value="<?php echo $xml->infNFe->dest->enderDest->xMun ?>">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label ts-label">UF</label>
                        <input type="text" class="form-control ts-input" value="<?php echo $xml->infNFe->dest->enderDest->UF ?>">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label ts-label">País</label>
                        <input type="text" class="form-control ts-input" value="<?php echo $xml->infNFe->dest->enderDest->xPais ?>">
                    </div>
                </div>
            </div>
            <div class="row">
                <h5>Valores</h5>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label ts-label">Base de Cálculo</label>
                        <input type="text" class="form-control ts-input" value="<?php echo $xml->infNFe->total->ICMSTot->vBC ?>">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label ts-label">Valor Produtos</label>
                        <input type="text" class="form-control ts-input" value="<?php echo $xml->infNFe->total->ICMSTot->vProd ?>">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label ts-label">PIS</label>
                        <input type="text" class="form-control ts-input" value="<?php echo $xml->infNFe->total->ICMSTot->vPIS ?>">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label ts-label">COFINS</label>
                        <input type="text" class="form-control ts-input" value="<?php echo $xml->infNFe->total->ICMSTot->vCOFINS ?>">
                    </div>
                </div>
            </div>
            <h5>Produtos</h5>
			<div class="table ts-divTabela ts-tableFiltros table-striped table-hover">
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
					foreach($xml->infNFe->det as $item) { ?>
						<tr>
							<td> <?php echo $item['nItem'] ?></td>
							<td> <?php echo $item->prod->cProd ?></td>
							<td> <?php echo $item->prod->xProd ?></td>
							<td> <?php echo $item->prod->qCom ?></td>
							<td> <?php echo $item->prod->vUnCom ?></td>
							<td> <?php echo $item->prod->vProd ?></td>
							<td> <?php echo $item->prod->CFOP ?></td>
							<td> <?php echo $item->prod->NCM ?></td>
							<td> <?php echo $item->prod->CEST ?></td>
						</tr>
					<?php } ?>

				</table>
			</div>
		</div>
    </div>

    <!-- LOCAL PARA COLOCAR OS JS -->

    <?php include_once ROOT . "/vendor/footer_js.php"; ?>

    <!-- LOCAL PARA COLOCAR OS JS -FIM -->

</body>

</html>