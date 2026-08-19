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
$objHtml->getHead('Vendedores');
?>

<body>
  <div class="layout-wrapper layout-content-navbar">
    <div class="layout-container">

      <?php $objHtml->getMenu(); ?>

      <div class="layout-page">

        <?php $objHtml->getNavBar(); ?>

        <div class="content-wrapper">

          <div class="modal fade" id="mdl-vendedor" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-lg" role="document">
              <div class="modal-content">
                <form id="frm-vendedor" action="#">
                  <div class="modal-header p-1 m-1">
                    <h5 class="modal-title"><i class="mb-2 bx bx-folder-plus"></i>Registrar vendedor</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">

                    <div class="mb-3">
                      <label class="form-label" for="basic-icon-default-phone">Datos Personales</label>
                      <div class="input-group">
                        <label class="input-group-text" for="nac">Tipo Id</label>
                        <select class="form-select" name="nac" id="nac">
                          <option value="V">Venezolano(a)</option>
                          <option value="E">Extranjero(a)</option>
                        </select>
                        <input required type="text" minlength="7" id="id" name="id" class="col form-control" placeholder="#RIF O #Identificación">
                      </div>
                    </div>

                    <div class="row">
                      <div class="col mb-3">
                        <div class="input-group">
                          <label class="input-group-text" for="nom">Nombre(s)</label>
                          <input required type="text" minlength="2" maxlength="116" id="nom" name="nom" class="form-control val-only-acc" aria-label="Nombre(s)" placeholder="Nombre(s) del Cliente">
                        </div>
                      </div>

                      <div class="col mb-3">
                        <div class="input-group">
                          <label class="input-group-text" for="ape">Apellido(s)</label>
                          <input required type="text" minlength="2" maxlength="116" id="ape" name="ape" class="form-control val-only-acc" aria-label="Apellido(s)" placeholder="Apellido(s) del Cliente">
                        </div>
                      </div>
                    </div>

                    <div class="mb-3">
                      <div class="input-group">
                        <label class="form-label" for="dir">Dirección</label>
                        <div class="input-group input-group-merge">
                          <span class="input-group-text"><i class="bx bx-map"></i></span>
                          <input type="text" id="dir" name="dir" class="form-control" placeholder="Dirección del Vendedor">
                        </div>
                      </div>
                    </div>

                    <div class="mb-3">
                      <label class="form-label" for="email">Correo Electrónico</label>
                      <div class="input-group input-group-merge">
                        <span class="input-group-text"><i class="bx bx-envelope"></i></span>
                        <input type="text" name="email" id="email" class="form-control" placeholder="ejemplo.ejemplo1" aria-label="john.doe" aria-describedby="basic-icon-default-email2">
                        <span id="basic-icon-default-email2" class="input-group-text">@ejemplo.com</span>
                      </div>
                    </div>

                    <div class="mb-3">
                      <label class="form-label" for="tel">Teléfono</label>
                      <div class="input-group input-group-merge">
                        <span class="input-group-text"><i class="bx bx-phone"></i></span>
                        <input type="text" minlength="11" name="tel" class="form-control val-only-numbers" placeholder="9999-9999999" aria-label="9999-9999999" aria-describedby="basic-icon-default-phone">
                      </div>
                    </div>

                    <input type="hidden" value="" name="oldid">
                    <input type="hidden" value="" name="oldnac">
                  </div>
                  <div class="modal-footer p-1 m-1">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" id="btn-save" class="btn btn-primary">Guardar</button>
                  </div>
                </form>
              </div>
            </div>
          </div>

          <div class="container-xxl">

            <div class="card mt-3">
              <div class="card-header row">
                <h5 class="col text-primary"><i class="menu-icon tf-icons bi bi-person-badge"></i>Vendedores</h5>
                <div class="col text-end">
                  <button type="button" class="btn btn-primary btn-md" data-bs-toggle="modal" data-bs-target="#mdl-vendedor">
                    <i class="bx bx-folder-plus"></i>Agregar
                  </button>
                </div>
              </div>

              <div class="card-body pt-0 pb-1">
                <table id="tbl-vendedor" class="table table-striped table-hover display nowrap">
                  <thead>
                    <tr>
                      <th>J/V/P</th>
                      <th>ID</th>
                      <th>Vendedor</th>
                      <th>Dirección</th>
                      <th>Correo</th>
                      <th>Teléfono</th>
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

  <script src="index.js?r=<?= rand(0,9999); ?>"></script>
</body>

</html>