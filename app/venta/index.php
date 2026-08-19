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
$objHtml->getHead('Ventas');
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
                <h5 class="col text-primary"><i class="menu-icon tf-icons bi bi-building-down"></i>Ventas</h5>
                <div class="col text-end">
                  <button type="button" class="btn btn-primary btn-md" data-bs-toggle="modal" data-bs-target="#mdl-venta">
                    <i class="bx bx-folder-plus"></i>Agregar
                  </button>
                </div>
              </div>
              <div class="table-responsive text-nowrap">
                <div class="modal fade" id="mdl-venta" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-xl" role="document">
                    <div class="modal-content">
                      <form id="frm-venta" action="#">
                        <div class="modal-header p-1 m-1">
                          <h5 class="modal-title"><i class="mb-2 bx bx-folder-plus"></i>Registrar venta</h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div style="max-height: calc(100vh - 200px); overflow-y: auto;" class="modal-body">

                          <div class="row mb-3">
                            <div class="col input-group">
                              <label class="input-group-text" for="optcli">
                                <i class="menu-icon tf-icons bx bx-user-pin"></i>
                                Cliente
                              </label>
                              <select required class="form-select cli" id="optcli" name="optcli">
                                <option value="">Seleccionar Cliente</option>
                              </select>
                            </div>
                            <div class="col input-group">
                              <label class="input-group-text" for="freg">Fecha de venta</label>
                              <input max="<?php echo date('Y-m-d'); ?>" value="<?php echo date('Y-m-d'); ?>" type="date" name="freg" id="freg" class="form-control">
                            </div>

                          </div>

                          <div class="row mb-3">
                            <div class="col input-group">
                              <label class="input-group-text" for="optcli">
                                <i class="menu-icon tf-icons bx bx-user-pin"></i>
                                Vendedor(a)
                              </label>
                              <select required class="form-select vend" id="optvend" name="optvend">
                                <option value="">Seleccionar Vendedor(a)</option>
                              </select>
                            </div>
                            <div class="col input-group">
                              <label class="input-group-text" for="desc">
                                Concepto
                              </label>
                              <input required class="form-control desc" maxlength="42" type="text" name="desc" id="desc">
                            </div>
                          </div>

                          <label class="form-label text-primary">Detalle de venta</label>

                          <div class="row mb-3">
                            <table class="table table-sm" id="tbl-item">
                              <thead>
                                <th width="50%">Producto / Item</th>
                                <th width="15%">Cantidad</th>
                                <th width="15%">Precio</th>
                                <th width="15%">Total Item</th>
                                <th width="5%"><button onclick="add();" class="btn btn-sm btn-primary" type="button" id="agregarFila"> + </button></th>
                              </thead>
                              <tbody>
                                <tr>
                                  <td>
                                    <select class="form-select form-control prod" name="prod[]"></select>
                                  </td>
                                  <td><input required onkeyup="calcm(this)" onchange="calcm(this)" name="cant[]" class="form-control cant" min="1" step="1" type="number"></td>
                                  <td><input required readonly name="monto[]" class="form-control monto" min="0.01" step="0.01" type="number"></td>
                                  <td><input readonly class="form-control titem" type="number"></td>
                                  <td><button onclick="remove(this);" type="button" class="btn btn-sm btn-danger">-</button></td>
                                </tr>
                              </tbody>
                            </table>
                          </div>
                          <div class="row justify-content-end mb-3">
                            <div class="col input-group offset-7">
                              <label class="input-group-text" for="stotald">
                                Sub Total
                              </label>
                              <input required readonly placeholder="$." type="text" class="form-control fw-bold" name="stotald" id="stotald">
                            </div>
                          </div>
                          <div class="row justify-content-end mb-3">
                            <div class="col input-group offset-7">
                              <label class="input-group-text" for="totald">
                                Total
                              </label>
                              <input required readonly placeholder="$." type="text" class="form-control fw-bold" name="totald" id="totald">
                            </div>
                          </div>

                          <input type="hidden" value="" name="oldid">
                        </div>
                        <div class="modal-footer p-1 m-1">
                          <button type="button" class="btn btn-secondary ms-4" data-bs-dismiss="modal">Cancelar</button>
                          <button type="button" id="btn-save" class="btn btn-primary">Guardar</button>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>

                <table id="tbl-venta" class="table table-striped table-hover">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Fechab</th>
                      <th>Fecha</th>
                      <th>idcliente</th>
                      <th>Cliente</th>
                      <th>idVendedor</th>
                      <th>Vendedor(a)</th>
                      <th>Concepto</th>
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

  <script src="index.js?r=<?= rand(0,999999); ?>"></script>
</body>

</html>