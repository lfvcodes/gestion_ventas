<?php
#CODE_BY_LFVCODES
session_start();
define('APP_ROOT', "../../");
define('VIEW_ROOT', "../");
require '../html/cls_html.php';
require_once VIEW_ROOT . 'util/misc.php';
$objHtml = new Cls_html;
?>

<!DOCTYPE html>
<html lang="es">
<?php
$objHtml->getHead('Productos');
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
                <h5 class="col text-primary"><i class="menu-icon tf-icons bi bi-boxes"></i>Producto(s)</h5>
                <div class="col text-end">
                  <button type="button" class="btn btn-primary btn-md" data-bs-toggle="modal" data-bs-target="#mdl-product">
                    <i class="bx bx-folder-plus"></i>Agregar
                  </button>
                </div>
              </div>
              <div class="modal fade" id="mdl-product" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
                <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-lg" role="document">
                  <div class="modal-content">
                      <div class="modal-header p-1 m-1">
                        <h5 class="modal-title"><i class="mb-2 bx bx-folder-plus me-1"></i>Agregar Nuevo Producto</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div style="max-height: calc(100vh - 200px); overflow-y: auto;" class="modal-body">
                        <form id="frm-product">
                        <div class="col mb-3">
                          <div class="input-group">
                            <span class="input-group-text">Nombre del Producto</span><i class="bi bi-barcode"></i>
                            <input required id="nom_producto" name="nom_producto" type="text" class="form-control" placeholder="Nombre del Producto">
                          </div>
                        </div>
                        <div class="mb-3">
                          <div class="input-group">
                            <span class="input-group-text">Categoría</span><i class="bi bi-id-card"></i>
                            <select required name="cod_categoria" id="cod_categoria" class="form-select form-control">
                              <option disabled selected value="">Elegir Departamento / Grupo o Categoría</option>
                            </select>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col mb-3">
                            <div class="input-group">
                              <span class="input-group-text">Precio Base $</span>
                              <input required type="text" min="1" id="p_base" name="p_base" class="form-control" pattern="^\d+(\.\d{1,2})?$" title="Ingresa un monto numérico válido">
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="modal-footer p-1 m-1">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="button" id="btn-save" class="btn btn-primary">Guardar</button>
                      </div>
                      </form>
                  </div>
                </div>
              </div>
              <div class="card-body pt-0 pb-0">
                <table id="tbl-producto" class="table table-striped table-hover display nowrap">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Producto</th>
                      <th>Cod Categoria</th>
                      <th>Categoría</th>
                      <th>Precio Base</th>
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

  <script src="index.js?r=<?= rand(0,99999) ?>"></script>
</body>

</html>