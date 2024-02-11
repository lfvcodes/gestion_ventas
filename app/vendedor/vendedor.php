<?php 
  session_start();
  define('APP_ROOT',"../../");
  define('VIEW_ROOT',"../");
  require '../html/cls_html.php';
  require_once VIEW_ROOT.'util/misc.php';
  $objHtml = new Cls_html;
?>

<!DOCTYPE html5>
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
                  <form id="frm-vendedor" action="ctrl_vendedor.php" method="POST">
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
                            <input required type="text" minlength="7" id="id" name="id" class="col form-control" placeholder="#RIF O #Identificación" >
                          </div>      
                        </div>

                        <div class="row">
                          <div class="col mb-3">
                            <div class="input-group">
                              <label class="input-group-text" for="nom">Nombre(s)</label>  
                              <input required type="text" minlength="2" maxlength="116" id="nom" name="nom" class="form-control val-only-acc" aria-label="Nombre(s)" placeholder="Nombre(s) del Cliente" >
                            </div>
                          </div>
                          
                          <div class="col mb-3">
                            <div class="input-group">
                              <label class="input-group-text" for="ape">Apellido(s)</label>  
                              <input required type="text" minlength="2" maxlength="116" id="ape" name="ape" class="form-control val-only-acc" aria-label="Apellido(s)" placeholder="Apellido(s) del Cliente" >
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
                          <label class="form-label" for="tel">Teléfono</label>
                          <div class="input-group input-group-merge">
                            <span class="input-group-text"><i class="bx bx-phone"></i></span>
                            <input type="text" minlength="11" name="tel" class="form-control val-only-numbers" placeholder="9999-9999999" aria-label="9999-9999999" aria-describedby="basic-icon-default-phone">                          </div>
                        </div>

                        <div class="mb-3">
                          <label class="form-label" for="email">Correo Electrónico</label>
                          <div class="input-group input-group-merge">
                            <span class="input-group-text"><i class="bx bx-envelope"></i></span>
                            <input type="text" name="email" id="email" class="form-control" placeholder="ejemplo.ejemplo1" aria-label="john.doe" aria-describedby="basic-icon-default-email2">
                            <span id="basic-icon-default-email2" class="input-group-text">@ejemplo.com</span>
                          </div>
                        </div>

                        <input type="hidden" value="insertVend" name="action">
                      </div>
                      <div class="modal-footer p-1 m-1">
                          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                          <button type="submit" id="btn-save"  class="btn btn-primary">Guardar</button>
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
                  <h5 class="col text-primary"><i class="menu-icon tf-icons bi bi-person-badge"></i>Vendedores</h5>
                  <div class="col text-end">
                    <button type="button" class="btn btn-primary btn-md" data-bs-toggle="modal" data-bs-target="#mdl-vendedor">
                      <i class="bx bx-folder-plus"></i>Agregar
                    </button>
                  </div>
                </div>
                
                <div class="card-body pt-0 pb-1">
                  <?php 
                    require_once 'cls_vendedor.php';
                    $vendedor = new Cls_vendedor;
                   ?>
                  <table id="tbl-Vendedores" class="table table-striped table-hover display nowrap">
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
        $('#tbl-Vendedores').DataTable({
            "responsive": true,
            "fixedHeader": true,
            "scroller":    true,
            "sScrollY" : ((window.innerHeight - scrollStartPosition) - 350) + "px",
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
            "data":<?php echo !empty($vendedor->getListVend()) ? $vendedor->getListVend() : '{}'; ?>,
            "columns":[
              {"data":"nac","visible":false},
              {"data":"id"},
              {"data":"vendedor"},
              {"data":"dir","visible":false},
              {"data":"email"},
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
                        onclick="editVend(this)" href="javascript:void(0);">
                        <i class="bx bx-edit-alt me-1"></i> Editar 
                      </a>
                      <?php if($_SESSION['pro']['usr']['lvl'] === 1): ?>
                        <a class="dropdown-item" sig="`+row['nac']+`" dl="`+row['id']+`" onclick="delVend(this)" href="javascript:void(0);">
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

        $('#tbl-Vendedores_length').addClass('ms-2');
        $('#tbl-Vendedores_filter').addClass('me-2')

        $(window).on('hidden.bs.modal',function(){
          $('#mdl-vendedor .modal-title').html('<i class="mb-2 bx bx-folder-plus"></i>Registar vendedor');
          $('#mdl-vendedor #btn-save').text('Guardar');
          $('#mdl-vendedor #btn-save').removeClass('btn-warning').addClass('btn-primary');
          $('#frm-vendedor')[0].reset();
        });

        $(".menu-inner .menu-item[sc='Vendedores']").addClass('active');

        $('#nac').change(function (e) { 
          e.preventDefault();
          if($(this).val() == 'P'){
            $('#id').removeClass('val-only-numbers');
            $('#id').addClass('val-only-letters');
          }else{
            $('#id').removeClass('val-only-letters');
            $('#id').addClass('val-only-numbers');
          }
        });

      });

      function editVend($btn){
        $jdata = base64Decode($($btn).attr('rw'));
        $dt = JSON.parse($jdata);
        console.log($dt);
        
        $('#mdl-vendedor form .form-control').each(function () {
          this.value = $dt[this.name];
        });  

        $('#nac').val($dt['nac']).trigger('change');
        
        
        $('#mdl-vendedor .modal-title').html('<i class="mb-2 bx bx-edit"></i>Editar vendedor');
        $('#mdl-vendedor #btn-save').text('Editar');
        $('#mdl-vendedor #btn-save').addClass('btn-warning').removeClass('btn-primary');
        
        $('#nac,#id').prop('readonly',true);
        
        $('input[name="action"]').val('updateVend');
        $('#mdl-vendedor').modal('show');
      }

      function delVend(btn){

        if(confirm('¿Está seguro que desea eliminar este Vendedor?')){
          $(btn).wrap('<form id="frm-post" method="POST" action="ctrl_vendedor.php"><input type="hidden" name="action" value="remove"><input type="hidden" name="id" value=""><input type="hidden" name="nac" value=""></form>');
          $('#frm-post input[name="id"]').val($(btn).attr('dl'));
          $('#frm-post input[name="nac"]').val($(btn).attr('sig'));
          $('#frm-post').submit();
        }else return false;
      
      }

    </script>
  </body>
</html>