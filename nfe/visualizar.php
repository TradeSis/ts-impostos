<?php
include_once(__DIR__ . '/../header.php');
include_once '../database/fisnota.php';


$notas = buscarNota($_GET['idNota']);
$produtos = buscarNotaProduto($_GET['idNota']);
$impostoTotal = buscarNotaImpostos($_GET['idNota']);
?>
<!doctype html>
<html lang="pt-BR">

<head>
    <?php include_once ROOT . "/vendor/head_css.php"; ?>
</head>

<body>
    <div class="card container-fluid mt-2">
        <div class="row mt-3"> <!-- LINHA SUPERIOR A TABLE -->
            <div class="col-3">
                <!-- TITULO -->
                <h2 class="ts-tituloPrincipal">Nota Fiscal <?php echo $notas['NF'] ?></h2>
            </div>
            <div class="col-7">
                <!-- FILTROS -->
            </div>

            <div class="col-2 text-end">
                <a href="nfe.php" role="button" class="btn btn-primary"><i class="bi bi-arrow-left-square"></i></i>&#32;Voltar</a>
            </div>
        </div>
        <div class="container-fluid mt-3">
            <div id="ts-tabs">
                <div class="tab whiteborder" id="tab-nfe">Dados NF-e</div>
                <?php if ($notas['idStatusNota'] != 0) {  ?>
                <div class="tab" id="tab-imposto">Imposto</div>
                <div class="tab" id="tab-produ">Produtos</div>
                <?php } ?>
                
                <div class="line"></div>

                <div class="tabContent">
                <!-- *****************NOTAFISCAL***************** -->
                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label ts-label">Chave de Acesso</label>
                            <input type="text" class="form-control ts-input" value="<?php echo $notas['chaveNFe'] ?>" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label ts-label">Natureza da operação</label>
                            <input type="text" class="form-control ts-input" value="<?php echo $notas['naturezaOp'] ?>" readonly>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <label class="form-label ts-label">Modelo</label>
                            <input type="text" class="form-control ts-input" value="<?php echo $notas['modelo'] ?>" readonly>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label ts-label">Série</label>
                            <input type="text" class="form-control ts-input" value="<?php echo $notas['serie'] ?>" readonly>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label ts-label">Nota Fiscal</label>
                            <input type="text" class="form-control ts-input" value="<?php echo $notas['NF'] ?>" readonly>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label ts-label">Data de Emissão</label>
                            <input type="text" class="form-control ts-input" value="<?php echo date('d/m/Y', strtotime($notas['dtEmissao'])) ?>" readonly>
                        </div>
                    </div>
                    <div class="row">
                        <h5>Emitente</h5>
                        <div class="col-md-4">
                            <label class="form-label ts-label">CNPJ</label>
                            <input type="text" class="form-control ts-input" value="<?php echo $notas['emitente_cpfCnpj'] ?>" readonly>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label ts-label">IE</label>
                            <input type="text" class="form-control ts-input" value="<?php echo $notas['emitente_IE'] ?>" readonly>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label ts-label">Razão Social</label>
                            <input type="text" class="form-control ts-input" value="<?php echo $notas['emitente_nomePessoa'] ?>" readonly>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <label class="form-label ts-label">Municipio</label>
                            <input type="text" class="form-control ts-input" value="<?php echo $notas['emitente_municipio'] ?>" readonly>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label ts-label">UF</label>
                            <input type="text" class="form-control ts-input" value="<?php echo $notas['emitente_codigoEstado'] ?>" readonly>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label ts-label">País</label>
                            <input type="text" class="form-control ts-input" value="<?php echo $notas['emitente_pais'] ?>" readonly>
                        </div>
                    </div>
                    <div class="row">
                        <h5>Destinatário</h5>
                        <div class="col-md-4">
                            <label class="form-label ts-label">Doc</label>
                            <input type="text" class="form-control ts-input" value="<?php echo $notas['destinatario_cpfCnpj'] ?>" readonly>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label ts-label">IE</label>
                            <input type="text" class="form-control ts-input" value="<?php echo $notas['destinatario_IE'] ?>" readonly>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label ts-label">Nome</label>
                            <input type="text" class="form-control ts-input" value="<?php echo $notas['destinatario_nomePessoa'] ?>" readonly>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <label class="form-label ts-label">Municipio</label>
                            <input type="text" class="form-control ts-input" value="<?php echo $notas['destinatario_municipio'] ?>" readonly>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label ts-label">UF</label>
                            <input type="text" class="form-control ts-input" value="<?php echo $notas['destinatario_codigoEstado'] ?>" readonly>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label ts-label">País</label>
                            <input type="text" class="form-control ts-input" value="<?php echo $notas['destinatario_pais'] ?>" readonly>
                        </div>
                    </div>
                    <div class="row">
                        <h5>Valores</h5>
                        <div class="col-md-3">
                            <label class="form-label ts-label">Base de Cálculo</label>
                            <input type="text" class="form-control ts-input" value="<?php echo number_format($notas['baseCalculo'], 2, ',', '.') ?>" readonly>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label ts-label">Valor Produtos</label>
                            <input type="text" class="form-control ts-input" value="<?php echo number_format($notas['valorProdutos'], 2, ',', '.') ?>" readonly>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label ts-label">PIS</label>
                            <input type="text" class="form-control ts-input" value="<?php echo number_format($notas['pis'], 2, ',', '.') ?>" readonly>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label ts-label">COFINS</label>
                            <input type="text" class="form-control ts-input" value="<?php echo number_format($notas['cofins'], 2, ',', '.') ?>" readonly>
                        </div>
                    </div>
                </div>

                <?php if ($notas['idStatusNota'] != 0) {  ?>
                <div class="tabContent">
                <!-- *****************IMPOSTOS***************** -->
                    <div class="row">
                        <div class="col-md-2">
                            <label class="form-label ts-label">Imposto</label>
                            <input type="text" class="form-control ts-input" value="<?php echo $impostoTotal['nomeTotal'] ?>" readonly>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label ts-label">vBC</label>
                            <input type="text" class="form-control ts-input" value="<?php echo $impostoTotal['vBC'] ?>" readonly>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label ts-label">vICMS</label>
                            <input type="text" class="form-control ts-input" value="<?php echo $impostoTotal['vICMS'] ?>" readonly>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label ts-label">vICMSDeson</label>
                            <input type="text" class="form-control ts-input" value="<?php echo $impostoTotal['vICMSDeson'] ?>" readonly>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label ts-label">vFCPUFDest</label>
                            <input type="text" class="form-control ts-input" value="<?php echo $impostoTotal['vFCPUFDest'] ?>" readonly>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label ts-label">vICMSUFDest</label>
                            <input type="text" class="form-control ts-input" value="<?php echo $impostoTotal['vICMSUFDest'] ?>" readonly>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-2">
                            <label class="form-label ts-label">vICMSUFRemet</label>
                            <input type="text" class="form-control ts-input" value="<?php echo $impostoTotal['vICMSUFRemet'] ?>" readonly>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label ts-label">vFCP</label>
                            <input type="text" class="form-control ts-input" value="<?php echo $impostoTotal['vFCP'] ?>" readonly>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label ts-label">vBCST</label>
                            <input type="text" class="form-control ts-input" value="<?php echo $impostoTotal['vBCST'] ?>" readonly>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label ts-label">vST</label>
                            <input type="text" class="form-control ts-input" value="<?php echo $impostoTotal['vST'] ?>" readonly>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label ts-label">vFCPST</label>
                            <input type="text" class="form-control ts-input" value="<?php echo $impostoTotal['vFCPST'] ?>" readonly>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label ts-label">vFCPSTRet</label>
                            <input type="text" class="form-control ts-input" value="<?php echo $impostoTotal['vFCPSTRet'] ?>" readonly>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-2">
                            <label class="form-label ts-label">qBCMono</label>
                            <input type="text" class="form-control ts-input" value="<?php echo $impostoTotal['qBCMono'] ?>" readonly>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label ts-label">vICMSMono</label>
                            <input type="text" class="form-control ts-input" value="<?php echo $impostoTotal['vICMSMono'] ?>" readonly>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label ts-label">qBCMonoReten</label>
                            <input type="text" class="form-control ts-input" value="<?php echo $impostoTotal['qBCMonoReten'] ?>" readonly>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label ts-label">vICMSMonoReten</label>
                            <input type="text" class="form-control ts-input" value="<?php echo $impostoTotal['vICMSMonoReten'] ?>" readonly>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label ts-label">qBCMonoRet</label>
                            <input type="text" class="form-control ts-input" value="<?php echo $impostoTotal['qBCMonoRet'] ?>" readonly>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label ts-label">vICMSMonoRet</label>
                            <input type="text" class="form-control ts-input" value="<?php echo $impostoTotal['vICMSMonoRet'] ?>" readonly>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-2">
                            <label class="form-label ts-label">vProd</label>
                            <input type="text" class="form-control ts-input" value="<?php echo $impostoTotal['vProd'] ?>" readonly>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label ts-label">vFrete</label>
                            <input type="text" class="form-control ts-input" value="<?php echo $impostoTotal['vFrete'] ?>" readonly>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label ts-label">vSeg</label>
                            <input type="text" class="form-control ts-input" value="<?php echo $impostoTotal['vSeg'] ?>" readonly>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label ts-label">vDesc</label>
                            <input type="text" class="form-control ts-input" value="<?php echo $impostoTotal['vDesc'] ?>" readonly>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label ts-label">vII</label>
                            <input type="text" class="form-control ts-input" value="<?php echo $impostoTotal['vII'] ?>" readonly>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label ts-label">vIPI</label>
                            <input type="text" class="form-control ts-input" value="<?php echo $impostoTotal['vIPI'] ?>" readonly>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-2">
                            <label class="form-label ts-label">vIPIDevol</label>
                            <input type="text" class="form-control ts-input" value="<?php echo $impostoTotal['vIPIDevol'] ?>" readonly>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label ts-label">vPIS</label>
                            <input type="text" class="form-control ts-input" value="<?php echo $impostoTotal['vPIS'] ?>" readonly>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label ts-label">vCOFINS</label>
                            <input type="text" class="form-control ts-input" value="<?php echo $impostoTotal['vCOFINS'] ?>" readonly>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label ts-label">vOutro</label>
                            <input type="text" class="form-control ts-input" value="<?php echo $impostoTotal['vOutro'] ?>" readonly>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label ts-label">vNF</label>
                            <input type="text" class="form-control ts-input" value="<?php echo $impostoTotal['vNF'] ?>" readonly>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label ts-label">vTotTrib</label>
                            <input type="text" class="form-control ts-input" value="<?php echo $impostoTotal['vTotTrib'] ?>" readonly>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-2">
                            <label class="form-label ts-label">vServ</label>
                            <input type="text" class="form-control ts-input" value="<?php echo $impostoTotal['vServ'] ?>" readonly>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label ts-label">vISS</label>
                            <input type="text" class="form-control ts-input" value="<?php echo $impostoTotal['vISS'] ?>" readonly>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label ts-label">dCompet</label>
                            <input type="text" class="form-control ts-input" value="<?php echo $impostoTotal['dCompet'] ?>" readonly>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label ts-label">vDeducao</label>
                            <input type="text" class="form-control ts-input" value="<?php echo $impostoTotal['vDeducao'] ?>" readonly>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label ts-label">vDescIncond</label>
                            <input type="text" class="form-control ts-input" value="<?php echo $impostoTotal['vDescIncond'] ?>" readonly>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label ts-label">vDescCond</label>
                            <input type="text" class="form-control ts-input" value="<?php echo $impostoTotal['vDescCond'] ?>" readonly>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-2">
                            <label class="form-label ts-label">vISSRet</label>
                            <input type="text" class="form-control ts-input" value="<?php echo $impostoTotal['vISSRet'] ?>" readonly>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label ts-label">cRegTrib</label>
                            <input type="text" class="form-control ts-input" value="<?php echo $impostoTotal['cRegTrib'] ?>" readonly>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label ts-label">vRetPIS</label>
                            <input type="text" class="form-control ts-input" value="<?php echo $impostoTotal['vRetPIS'] ?>" readonly>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label ts-label">vRetCOFINS</label>
                            <input type="text" class="form-control ts-input" value="<?php echo $impostoTotal['vRetCOFINS'] ?>" readonly>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label ts-label">vRetCSLL</label>
                            <input type="text" class="form-control ts-input" value="<?php echo $impostoTotal['vRetCSLL'] ?>" readonly>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label ts-label">vBCIRRF</label>
                            <input type="text" class="form-control ts-input" value="<?php echo $impostoTotal['vBCIRRF'] ?>" readonly>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-2">
                            <label class="form-label ts-label">vIRRF</label>
                            <input type="text" class="form-control ts-input" value="<?php echo $impostoTotal['vIRRF'] ?>" readonly>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label ts-label">vBCRetPrev</label>
                            <input type="text" class="form-control ts-input" value="<?php echo $impostoTotal['vBCRetPrev'] ?>" readonly>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label ts-label">vRetPrev</label>
                            <input type="text" class="form-control ts-input" value="<?php echo $impostoTotal['vRetPrev'] ?>" readonly>
                        </div>
                    </div>
                </div>

                <div class="tabContent">
                <!-- *****************PRODUTOS***************** -->
                    <div class="table mt-2 ts-divTabela70 ts-tableFiltros">
                        <table class="table table-sm table-hover">
                            <thead class="ts-headertabelafixo">
                                <tr>
                                    <th>#</th>
                                    <th>EAN</th>
                                    <th>refProduto</th>
                                    <th>Produto</th>
                                    <th>Qnt</th>
                                    <th>Un Comercial</th>
                                    <th>Vl Unitario</th>
                                    <th>Vl Total</th>
                                    <th>CFOP</th>
                                    <th>NCM</th>
                                    <th>CEST</th>
                                </tr>
                            </thead>

                            <tbody id='dados' class="fonteCorpo">

                            </tbody>
                        </table>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>

    <?php include 'modalVisualizarProdu'; ?>
   

    <!-- LOCAL PARA COLOCAR OS JS -->

    <?php include_once ROOT . "/vendor/footer_js.php"; ?>

    <script>
        var tab;
        var tabContent;

        window.onload = function () {
            tabContent = document.getElementsByClassName('tabContent');
            tab = document.getElementsByClassName('tab');
            hideTabsContent(1);

            var urlParams = new URLSearchParams(window.location.search);
            var id = urlParams.get('id');
            if (id === 'imposto') {
                showTabsContent(1);
            }
            if (id === 'produ') {
                showTabsContent(2);
            }
        }

        document.getElementById('ts-tabs').onclick = function (event) {
            var target = event.target;
            if (target.className == 'tab') {
                for (var i = 0; i < tab.length; i++) {
                    if (target == tab[i]) {
                        showTabsContent(i);
                        break;
                    }
                }
            }
        }

        function hideTabsContent(a) {
            for (var i = a; i < tabContent.length; i++) {
                tabContent[i].classList.remove('show');
                tabContent[i].classList.add("hide");
                tab[i].classList.remove('whiteborder');
            }
        }

        function showTabsContent(b) {
            if (tabContent[b].classList.contains('hide')) {
                hideTabsContent(0);
                tab[b].classList.add('whiteborder');
                tabContent[b].classList.remove('hide');
                tabContent[b].classList.add('show');
            }
        }
    </script>

    <script>
        buscar(<?php echo $notas['idNota'] ?>);
        
        function buscar(idNota) {
        $.ajax({
            type: 'POST',
            dataType: 'html',
            url: "../database/fisnota.php?operacao=buscarNotaProduto",
            beforeSend: function() {
            $("#dados").html("Carregando...");
            },
            data: {
            idNota: idNota
            },
            success: function(msg) {
            var json = JSON.parse(msg);
            var linha = "";
            for (var $i = 0; $i < json.length; $i++) {
                var object = json[$i];
                        
                linha += "<tr>";  
                linha += "<td class='ts-click' data-idNota='" + object.idNota + "' data-nItem='" + object.nItem + "' data-idProduto='" + object.idProduto + "'>" + object.nItem + "</td>";
                linha += "<td class='ts-click' data-idNota='" + object.idNota + "' data-nItem='" + object.nItem + "' data-idProduto='" + object.idProduto + "'>" + object.eanProduto + "</td>";
                linha += "<td class='ts-click' data-idNota='" + object.idNota + "' data-nItem='" + object.nItem + "' data-idProduto='" + object.idProduto + "'>" + object.refProduto + "</td>";
                linha += "<td class='ts-click' data-idNota='" + object.idNota + "' data-nItem='" + object.nItem + "' data-idProduto='" + object.idProduto + "'>" + object.nomeProduto + "</td>";
                linha += "<td class='ts-click' data-idNota='" + object.idNota + "' data-nItem='" + object.nItem + "' data-idProduto='" + object.idProduto + "'>" + parseFloat(object.quantidade).toLocaleString('pt-BR', { minimumFractionDigits: 0, maximumFractionDigits: 0 }) + "</td>";
                linha += "<td class='ts-click' data-idNota='" + object.idNota + "' data-nItem='" + object.nItem + "' data-idProduto='" + object.idProduto + "'>" + object.unidCom + "</td>";
                linha += "<td class='ts-click' data-idNota='" + object.idNota + "' data-nItem='" + object.nItem + "' data-idProduto='" + object.idProduto + "'>" + parseFloat(object.valorUnidade).toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + "</td>";
                linha += "<td class='ts-click' data-idNota='" + object.idNota + "' data-nItem='" + object.nItem + "' data-idProduto='" + object.idProduto + "'>" + parseFloat(object.valorTotal).toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + "</td>";
                linha += "<td class='ts-click' data-idNota='" + object.idNota + "' data-nItem='" + object.nItem + "' data-idProduto='" + object.idProduto + "'>" + object.cfop + "</td>";
                linha += "<td class='ts-click' data-idNota='" + object.idNota + "' data-nItem='" + object.nItem + "' data-idProduto='" + object.idProduto + "'>" + object.codigoNcm + "</td>";
                linha += "<td class='ts-click' data-idNota='" + object.idNota + "' data-nItem='" + object.nItem + "' data-idProduto='" + object.idProduto + "'>" + object.codigoCest + "</td>";
                linha += "</tr>";
            }

            $("#dados").html(linha);

            }
        });
        }

        $(document).on('click', '.ts-click', function() {
            var idNota = $(this).attr("data-idNota");
            var nItem = $(this).attr("data-nItem");
            var idProduto = $(this).attr("data-idProduto");

            var impostos = ['ICMS', 'PIS', 'COFINS'];

            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: '<?php echo URLROOT ?>/cadastros/database/produtos.php?operacao=buscar',
                data: {
                    idProduto: idProduto
                },
                success: function(data) {
                    $('#idProduto').val(data.idProduto);
                    $('#eanProduto').val(data.eanProduto);
                    $('#nomeProduto').val(data.nomeProduto);
                    $('#valorCompra').val(data.valorCompra);
                    $('#precoProduto').val(data.precoProduto);
                    $('#codigoNcm').val(data.codigoNcm);
                    $('#codigoCest').val(data.codigoCest);
                    $('#imgProduto').val(data.imgProduto);
                    $('#idMarca').val(data.idMarca);
                    $('#ativoProduto').val(data.ativoProduto);
                    $('#propagandaProduto').val(data.propagandaProduto);
                    $('#descricaoProduto').val(data.descricaoProduto);
                    $('#idPessoaFornecedor').val(data.idPessoaFornecedor);
                    $('#refProduto').val(data.refProduto);
                    $('#dataAtualizacaoTributaria').val(data.dataAtualizacaoTributaria);
                    $('#codImendes').val(data.codImendes);
                    $('#codigoGrupo').val(data.codigoGrupo);
                    $('#substICMSempresa').val(data.substICMSempresa);
                    $('#substICMSFornecedor').val(data.substICMSFornecedor);
                    $('#prodZFM').val(data.prodZFM);
                }
            });
            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: '../database/fisnota.php?operacao=buscarProduImposto',
                data: {
                    idNota: idNota,
                    nItem: nItem
                },
                success: function(data) {
                    for (var i = 0; i < impostos.length; i++) {
                        var imposto = impostos[i];

                        var dadosImposto = data.find(item => item.imposto === imposto);

                        $('#' + 'nomeImposto_' + imposto).val(dadosImposto.nomeImposto);
                        $('#' + 'vTotTrib_' + imposto).val(dadosImposto.vTotTrib);
                        $('#' + 'orig_' + imposto).val(dadosImposto.orig);
                        $('#' + 'CSOSN_' + imposto).val(dadosImposto.CSOSN);
                        $('#' + 'modBCST_' + imposto).val(dadosImposto.modBCST);
                        $('#' + 'pMVAST_' + imposto).val(dadosImposto.pMVAST);
                        $('#' + 'vBCST_' + imposto).val(dadosImposto.vBCST);
                        $('#' + 'pICMSST_' + imposto).val(dadosImposto.pICMSST);
                        $('#' + 'vICMSST_' + imposto).val(dadosImposto.vICMSST);
                        $('#' + 'CST_' + imposto).val(dadosImposto.CST);
                        $('#' + 'modBC_' + imposto).val(dadosImposto.modBC_);
                        $('#' + 'vBC_' + imposto).val(dadosImposto.vBC);
                        $('#' + 'pICMS_' + imposto).val(dadosImposto.pICMS);
                        $('#' + 'vICMS_' + imposto).val(dadosImposto.vICMS);
                        $('#' + 'pFCP_' + imposto).val(dadosImposto.pFCP);
                        $('#' + 'vFCP_' + imposto).val(dadosImposto.vFCP);
                        $('#' + 'qBCMono_' + imposto).val(dadosImposto.qBCMono);
                        $('#' + 'vICMSMono_' + imposto).val(dadosImposto.vICMSMono);
                        $('#' + 'vBCFCP_' + imposto).val(dadosImposto.vBCFCP);
                        $('#' + 'pRedBCST_' + imposto).val(dadosImposto.pRedBCST);
                        $('#' + 'vBCFCPST_' + imposto).val(dadosImposto.vBCFCPST);
                        $('#' + 'pFCPST_' + imposto).val(dadosImposto.pFCPST);
                        $('#' + 'vFCPST_' + imposto).val(dadosImposto.vFCPST);
                        $('#' + 'vICMSSTDeson_' + imposto).val(dadosImposto.vICMSSTDeson);
                        $('#' + 'pRedBC_' + imposto).val(dadosImposto.pRedBC);
                        $('#' + 'vICMSDeson_' + imposto).val(dadosImposto.vICMSDeson);
                        $('#' + 'motDesICMS_' + imposto).val(dadosImposto.pMVAST);
                    }
                    $('#tituloProdu').text(data[0].nomeProduto);
                    $('#visualizarModal').modal('show');
                }
            });
        });
    </script>

    <!-- LOCAL PARA COLOCAR OS JS -FIM -->

</body>

</html>