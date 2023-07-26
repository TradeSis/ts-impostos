<?php
include_once __DIR__ . "/../config.php";
include_once ROOT . "/sistema/painel.php";
include_once ROOT . "/sistema/database/montaMenu.php";
$montamenu = buscaMontaMenu('Impostos', $_SESSION['idUsuario']);
//echo json_encode($montamenu);

$menus = $montamenu['menu'];
if (!empty($montamenu['menuAtalho'])) {
    $menusAtalho = $montamenu['menuAtalho'];
}
if (!empty($montamenu['menuHeader'])) {
    $menuHeader = $montamenu['menuHeader'][0];
}
//echo json_encode($menusAtalho);
$configuracao = 1; 

$nivelUsuario   =   4;



?>

<style>
    .nav-link.active {
        border-bottom: 3px solid #2E59D9;
        border-radius: 3px 3px 0 0;
        color: #1B4D60;
        background-color: transparent;
    }
</style>

<div class="container-fluid mt-1">
    <div class="row">
        <div class="col-md-12 d-flex justify-content-center">
            <ul class="nav a" id="myTabs">


                <?php
                    $tab = '';

                    if (isset($_GET['tab'])) {$tab = $_GET['tab'];}
               
                ?>    


            <?php if ($nivelUsuario>=3) { ?>
                <li class="nav-item ">
                    <a class="nav-link <?php if ($tab=="ncm") {echo " active ";} ?>" 
                        href="?tab=ncm" 
                        role="tab"                        
                        style="color:black">NCM/CEST </a>
                </li>
            <?php } if ($nivelUsuario>=3) { ?>
                <li class="nav-item ">
                    <a class="nav-link <?php if ($tab=="operacoes") {echo " active ";} ?>" 
                        href="?tab=operacoes" 
                        role="tab"                        
                        style="color:black">Operações</a>
                </li>
            <?php } if ($nivelUsuario>=4) { ?>
                <li class="nav-item ">
                    <a class="nav-link <?php if ($tab=="configuracao") {echo " active ";} ?>" 
                        href="?tab=configuracao" 
                        role="tab"                        
                        data-toggle="tooltip" data-placement="top" title="Configurações"                   
                        style="color:black"><i class="bi bi-gear" style="font-size: 18px;"></i></a>
                </li>
            <?php } ?>

                           
            </ul>


        </div>

    </div>

</div>

<?php
    $src="";

    if ($tab=="ncm") {$src="ncm/ncm_table.php";}
    if ($tab=="operacoes") {$src="operacoes/fisoperacao.php";}
    if ($tab=="configuracao") {
            $src="configuracao/";
            if (isset($_GET['stab'])) {
                $src = $src . "?stab=".$_GET['stab'];
            }

            
    }
    
if ($src!=="") {
    //echo URLROOT ."/impostos/". $src;
?>
    <div class="diviFrame" style="overflow:hidden; height: 85vh">
        <iframe class="iFrame container-fluid " id="iFrameTab" src="<?php echo URLROOT ?>/impostos/<?php echo $src ?>"></iframe>
    </div>
<?php
}
?>

</body>

</html>