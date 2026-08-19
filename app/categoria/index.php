<?php 
  session_start();
  define('APP_ROOT',"../../");
  define('VIEW_ROOT',"../");
  require '../html/cls_html.php';
  require_once VIEW_ROOT.'util/misc.php';
  $objHtml = new Cls_html;
?>

<!DOCTYPE html>
<html lang="es">
  <?php
    $objHtml->getHead('Categorias de Productos');
  ?>
  <body>
    <div class="layout-wrapper layout-content-navbar">
      <div class="layout-container">

        <?php $objHtml->getMenu(); ?>
        
        <div class="layout-page">

          <?php $objHtml->getNavBar(); ?>

          <div class="content-wrapper">

            <div class="container-xxl">

              <div class="card mt-3">
                <div class="card-header row">
                  <h5 class="col text-primary"><i class="bi bi-tag"></i>Categorias de Producto(s)</h5>
                  <div class="col text-end">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#mdl-categoria">
                      <i class="bx bx-folder-plus"></i>Agregar
                    </button>
                  </div>
                </div>
                <div class="table-responsive text-nowrap pt-0">
                  <div class="modal fade" id="mdl-categoria" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-lg" role="document">
                      <div class="modal-content">
                        <form id="frm-cat" action="#">
                            <div class="modal-header p-1 m-1">
                                <h5 class="modal-title"><i class="mb-2 bx bx-folder-plus"></i>Registrar Categoria</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                              <div class="mb-3">
                                <div class="input-group">
                                  <label class="input-group-text" for="nom">Categoría</label>  
                                  <input type="text" name="nom" class="form-control" placeholder="Nombre de Categoría o Grupo de Producto" >
                                </div>
                              </div>
                            </div>
                            <div class="modal-footer p-1 m-1">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                <button type="button" id="btn-save"  class="btn btn-primary">Guardar</button>
                            </div>
                        </form>
                      </div>
                    </div>
                  </div>
                  
                  <table id="tbl-categoria" class="table table-striped table-hover">
                    <thead>
                      <tr>
                        <th>ID</th>
                        <th>Categoría</th>
                        <th>Acción</th>
                      </tr>
                    </thead>
                    <tbody class="table-border-bottom-0"></tbody>
                  </table>
                </div>
              </div>
            </div>
            </div>
        </div>
      </div>
      <div class="layout-overlay layout-menu-toggle"></div>
    </div>
      
    <?php 
      $objHtml->importJs();
    ?>

    <script src="index.js?r=<?= rand(0,9999) ?>"></script>
  </body>
</html>