
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
    $objHtml->getHead('Clientes');
  ?>
  <body>
    <div class="layout-wrapper layout-content-navbar">
      <div class="layout-container">

        <?php $objHtml->getMenu(); ?>
        
        <div class="layout-page">

          <?php $objHtml->getNavBar(); ?>

          <div class="content-wrapper">

            <div class="modal fade" id="mdl-cliente" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
              <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                  <form id="frm-cliente" action="#">
                      <div class="modal-header p-1 m-1">
                          <h5 class="modal-title"><i class="mb-2 bx bx-folder-plus"></i>Agregar Nuevo Cliente</h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div style="max-height: calc(100vh - 200px); overflow-y: auto;" class="modal-body">

                        <div class="mb-3">
                          <label class="form-label text-primary" for="">Datos Personales</label>
                          <div class="input-group">
                            <label class="input-group-text" for="nac">Nacionalidad</label>  
                            <select class="form-select form-control" required name="nac" id="nac">
                              <option value="V">Venezolano(a)</option>
                              <option value="E">Extranjero(a)</option> 
                              <option value="J">Juridico(a)</option> 
                            </select>
                            <input type="text" minlength="7" maxlength="20" required id="id" name="id" class="form-control" aria-label="identificacion" placeholder="#Identificación" >
                          </div>      
                        </div>
                        <div class="row mb-3">
                          <div class="col input-group">
                            <label class="form-label text-primary" for="nombre">Nombre(s)</label>
                            <div class="input-group input-group-merge">
                              <span class="input-group-text"><i class="bi bi-card-text"></i></span>
                              <input required type="text" id="nom" name="nom" minlength="2" maxlength="116" class="form-control" placeholder="Nombre del Cliente"> 
                            </div>
                          </div>
                        </div>
                        <div class="row mb-3">
                          <div class="col input-group">
                            <label class="form-label text-primary" for="dir">Dirección</label>
                            <div class="input-group input-group-merge">
                              <span class="input-group-text"><i class="bx bx-map"></i></span>
                              <input type="text" id="dir" name="dir" class="form-control" placeholder="Dirección del Cliente"> 
                            </div>
                          </div>
                          <div class="col">
                            <label class="form-label text-primary" for="email">Correo Electrónico</label>
                            <div class="input-group input-group-merge">
                              <span class="input-group-text"><i class="bx bx-envelope"></i></span>
                              <input type="email" id="email" name="email" class="form-control" placeholder="ejemplo.ejemplo1" aria-label="correo@correo.com" aria-describedby="basic-icon-default-email2">
                              <span id="basic-icon-default-email2" class="input-group-text">@ejemplo.com</span>
                            </div>
                          </div>
                        </div>
                        
                        <div class="row mb-3">
                          <div class="col">
                            <label class="form-label text-primary" for="tel">Teléfono</label>
                            <div class="input-group input-group-merge">
                              <span class="input-group-text"><i class="bx bx-phone"></i></span>
                              <input type="text" minlength="11" id="tel" name="tel" class="form-control val-only-numbers" placeholder="9999-9999999" aria-label="9999-9999999" aria-describedby="basic-icon-default-phone"> 
                            </div>
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
                  <h5 class="col text-primary"><i class="menu-icon tf-icons bx bx-user-pin"></i>Clientes</h5>
                  <div class="col text-end">
                    <button type="button" class="btn btn-primary btn-md" data-bs-toggle="modal" data-bs-target="#mdl-cliente">
                      <i class="bx bx-folder-plus"></i>Agregar
                    </button>
                  </div>
                </div>
                
                <div class="card-body pt-0 pb-1">
                  <table id="tbl-cliente" class="table table-striped table-hover display nowrap">
                    <thead>
                      <tr>
                        <th>V/E</th>  
                        <th>ID</th>
                        <th>Nombre</th>
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
    
    <script src="index.js?r=<?= rand(0,99999) ?>"></script>
  </body>
</html>