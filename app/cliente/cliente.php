
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
                  <form id="frm-cliente" action="ctrl_cliente.php" method="POST">
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

                        
                        <input type="hidden" value="" name="actualid">
                        <input type="hidden" value="" name="nid">
                        <input type="hidden" value="" name="nnac">
                        <input type="hidden" value="insertClient" name="action">
                      </div>
                      <div class="modal-footer p-1 m-1">
                          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                          <button type="submit" id="btn-save" class="btn btn-primary">Guardar</button>
                      </div>
                  </form>
                </div>
              </div>
            </div>

            <div class="container-xxl">

              <?php 
                if(isset($_SESSION['pro_alert'])):
                  $str = (string) $_SESSION['pro_alert'];
                  eval($str); 
                  unset($_SESSION['pro_alert']);
                endif;
              ?>

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
                  <?php 
                    require_once 'cls_cliente.php';
                    $Cliente = new Cls_Cliente;
                   ?>
                  <table id="tbl-clientes" class="table table-striped table-hover display nowrap">
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
                    <tbody class="table-border-bottom-0">
                     
                    </tbody>
                  </table>
                </div>
              </div>

            </div>
            <!-- / Content -->
            </div>
          <!-- Content wrapper -->
        </div>

      </div>

      <div class="layout-overlay layout-menu-toggle"></div>
    </div>
      
    <?php 
      $objHtml->importJs();
    ?>
    
    <script>

      $(document).ready(function () {
        var scrollStartPosition = document.body.scrollTop || document.documentElement.scrollTop;
        $('#tbl-clientes').DataTable({
            "responsive": true,
            "fixedHeader": true,
            "scroller":    true,
            "sScrollY" : "400px",
            "pageLength": 25,
            "Sort": true,
            "aaSorting": [],
            dom: "<'row px-2 px-md-4 pt-2'<'col-md-3' l><'col-md-5 text-center'><'col-md-4'f>>" +
            "<'row'<'col-md-12'trip>>",
            "columnDefs":[
              {
                "targets":[6],
                "orderable":false,
              },
            ],
            "data":<?php echo (!empty($Cliente->getListCLient())) ? $Cliente->getListClient() : '{}'; ?>,
            "columns":[
              {"data":"nac"},
              {"data":"id"},
              {"data":"nom"},
              {"data":"dir"},
              {
                "data":"email",
                "visible":false,
              },
              {"data":"tel"},
              {"data": null,
                "render": function(data, type, row){
                  return `
                  <div class="dropdown">
                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                      <i class="bx bx-dots-vertical-rounded"></i>
                    </button>
                    <div class="dropdown-menu">
                      <a class="dropdown-item"
                          rw="`+ base64Encode(JSON.stringify(row))+`"
                        onclick="editClient(this)" href="javascript:void(0);">
                        <i class="bx bx-edit-alt me-1"></i> Editar 
                      </a>
                      <?php if($_SESSION['pro']['usr']['lvl'] === 1): ?>
                        <a class="dropdown-item" sig="`+row['nac']+`" dl="`+row['id']+`" onclick="delClient(this)" href="javascript:void(0);">
                          <i class="bx bx-trash-alt me-1"></i> Eliminar
                        </a>
                      <?php endif; ?>
                    </div>
                  </div>`;
                },
              }
            ],
            "language": {
                "lengthMenu": "Mostrar _MENU_ registros",
                "zeroRecords": "No se encontraron resultados",
                "info": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                "infoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
                "infoFiltered": "<br>(filtrado de un total de _MAX_ registros)",
                "sSearch": "Buscar:",
                "oPaginate": {
                  "sFirst": "Primero",
                  "sLast":"Último",
                  "sNext":"Siguiente",
                  "sPrevious": "Anterior"
                },
            },
            "drawCallback": function( settings ) {
              $('.buttons-excel').addClass('btn-sm mb-2');
              $('.buttons-excel').css('background','#0C7363');
            },
        });

        $('#tbl-clientes_length').addClass('ms-2');
        $('#tbl-clientes_filter').addClass('me-2');

        $(window).on('hidden.bs.modal',function(){
          $('#mdl-cliente .modal-title').html('<i class="mb-2 bx bx-folder-plus"></i>Registar Cliente');
          $('#mdl-cliente #btn-save').text('Guardar');
          $('#mdl-cliente #btn-save').removeClass('btn-warning').addClass('btn-primary');
          $('#frm-cliente')[0].reset();
        });

        $(".menu-inner .menu-item[sc='Clientes']").addClass('active');

      });

      function editClient($btn){
        $jdata = base64Decode($($btn).attr('rw'));
        $dt = JSON.parse($jdata);
        console.log($dt);
        //$('#nac').val($nac).trigger('change')
        $('#mdl-cliente form .form-control').each(function () {
          this.value = $dt[this.name];
        });  

        $('#optvendedor option[value="'+$dt['idvend']+'"]').prop('selected',true);
        $('#opttipo').val($dt['tipo']);

        $('input[name="nid"]').val($dt['id']);
        $('input[name="nnac"]').val($dt['nac']);

        $('#mdl-cliente .modal-title').html('<i class="mb-2 bx bx-edit"></i>Editar Cliente');
        $('#mdl-cliente #btn-save').text('Editar');
        $('#mdl-cliente #btn-save').addClass('btn-warning').removeClass('btn-primary');
        $('input[name="action"]').val('updateClient');
        $('#mdl-cliente').modal('show');
      }

      function delClient(btn){

        if(confirm('¿Está seguro que desea eliminar este Cliente?')){
          $(btn).wrap('<form id="frm-post" method="POST" action="ctrl_cliente.php"><input type="hidden" name="action" value="remove"><input type="hidden" name="id" value=""><input type="hidden" name="nac" value=""></form>');
          $('#frm-post input[name="id"]').val($(btn).attr('dl'));
          $('#frm-post input[name="nac"]').val($(btn).attr('sig'));
          $('#frm-post').submit();
        }else return false;
       
      }

    </script>
  </body>
</html>