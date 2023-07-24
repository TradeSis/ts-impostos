<style>
  .temp{
    color:black
  }
</style>
<div class="container-fluid">

  <div class="row">
    <div class="col-md-2 mb-3">
      <ul class="nav nav-pills flex-column" id="myTab" role="tablist">

        <li class="nav-item">
          <a class="nav-link active temp" id="fisatividade-tab" data-toggle="tab" href="#fisatividade" role="tab" aria-controls="fisatividade" aria-selected="true">fisatividade</a>
        </li>
        <li class="nav-item">
          <a class="nav-link temp" id="fisnatureza-tab" data-toggle="tab" href="#fisnatureza" role="tab" aria-controls="fisnatureza" aria-selected="true">fisnatureza</a>
        </li>
        <li class="nav-item">
          <a class="nav-link temp" id="fisprocesso-tab" data-toggle="tab" href="#fisprocesso" role="tab" aria-controls="fisprocesso" aria-selected="false">fisprocesso</a>
        </li>
     
        
      </ul>
    </div>
    <!-- /.col-md-4 -->
    <div class="col-md-10">
      <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="fisatividade" role="tabpanel" aria-labelledby="fisatividade-tab">
          <?php include 'configuracao/fisatividade.php' ?>
        </div>
        <div class="tab-pane fade" id="fisnatureza" role="tabpanel" aria-labelledby="fisnatureza-tab">
        <?php include 'configuracao/fisnatureza.php' ?>
        </div>
        <div class="tab-pane fade" id="fisprocesso" role="tabpanel" aria-labelledby="fisprocesso-tab">
        <?php include 'configuracao/fisprocesso.php' ?>
        </div>
    
      
       
      </div>
    </div>
    <!-- /.col-md-8 -->
  </div>



</div>
<!-- /.container -->