<?php if(!class_exists('Rain\Tpl')){exit;}?>  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Editar Produto</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="/admin">Home</a></li>
              <li class="breadcrumb-item active">Produtos</li>
              <li class="breadcrumb-item active">Editar Produto</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

<!-- Main content -->
<section class="content">

  <div class="row">
  	<div class="col-md-12">
  		<div class="box box-primary">
        <!-- /.box-header -->
        <!-- form start -->
        <form role="form" action="/admin/products/<?php echo htmlspecialchars( $product["idproduct"], ENT_COMPAT, 'UTF-8', FALSE ); ?>" method="post" enctype="multipart/form-data">
          <div class="box-body">
            <div class="form-group">
              <label for="desproduct">Nome da produto</label>
              <input type="text" class="form-control" id="desproduct" name="desproduct" placeholder="Digite o nome do produto" value="<?php echo htmlspecialchars( $product["desproduct"], ENT_COMPAT, 'UTF-8', FALSE ); ?>">
            </div>
            <div class="form-group">
              <label for="vlprice">Preço</label>
              <input type="number" class="form-control" id="vlprice" name="vlprice" step="0.01" placeholder="0.00" value="<?php echo htmlspecialchars( $product["vlprice"], ENT_COMPAT, 'UTF-8', FALSE ); ?>">
            </div>
            <div class="form-group">
              <label for="vlwidth">Largura</label>
              <input type="number" class="form-control" id="vlwidth" name="vlwidth" step="0.01" placeholder="0.00" value="<?php echo htmlspecialchars( $product["vlwidth"], ENT_COMPAT, 'UTF-8', FALSE ); ?>">
            </div>
            <div class="form-group">
              <label for="vlheight">Altura</label>
              <input type="number" class="form-control" id="vlheight" name="vlheight" step="0.01" placeholder="0.00" value="<?php echo htmlspecialchars( $product["vlheight"], ENT_COMPAT, 'UTF-8', FALSE ); ?>">
            </div>
            <div class="form-group">
              <label for="vllength">Comprimento</label>
              <input type="number" class="form-control" id="vllength" name="vllength" step="0.01" placeholder="0.00" value="<?php echo htmlspecialchars( $product["vllength"], ENT_COMPAT, 'UTF-8', FALSE ); ?>">
            </div>
            <div class="form-group">
              <label for="vlweight">Peso</label>
              <input type="number" class="form-control" id="vlweight" name="vlweight" step="0.01" placeholder="0.00" value="<?php echo htmlspecialchars( $product["vlweight"], ENT_COMPAT, 'UTF-8', FALSE ); ?>">
            </div>
            <div class="form-group">
              <label for="desurl">URL</label>
              <input type="text" class="form-control" id="desurl" name="desurl" step="0.01" placeholder="0.00" value="<?php echo htmlspecialchars( $product["desurl"], ENT_COMPAT, 'UTF-8', FALSE ); ?>">
            </div>
            <div class="form-group">
              <label for="file">Foto</label>
              <input type="file" class="form-control" id="file" name="file" value="<?php echo htmlspecialchars( $product["desphoto"], ENT_COMPAT, 'UTF-8', FALSE ); ?>">
            <div class="box box-widget">
                <div class="box-body">
                  <img class="img-responsive" id="image-preview" src="<?php echo htmlspecialchars( $product["desphoto"], ENT_COMPAT, 'UTF-8', FALSE ); ?>" alt="Photo">
                </div>
              </div>
            </div>
          </div>
          </div>
          <!-- /.box-body -->
          <div class="box-footer">
            <button type="submit" class="btn btn-primary">Salvar</button>
          </div>
        </form>
      </div>
  	</div>
  </div>

</section>
<!-- /.content -->
</div>
<!-- /.content-wrapper -->
<script>
document.querySelector('#file').addEventListener('change', function(){
  
  var file = new FileReader();

  file.onload = function() {
    
    document.querySelector('#image-preview').src = file.result;

  }

  file.readAsDataURL(this.files[0]);

});
</script>