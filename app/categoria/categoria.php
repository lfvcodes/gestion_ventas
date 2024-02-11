<?php 
  session_start();
  define('APP_ROOT',"../../");
  define('VIEW_ROOT',"../");
  require '../html/cls_html.php';
  require_once VIEW_ROOT.'util/misc.php';
  require 'cls_categoria.php';
  $categoria = new cls_categoria;
  $objHtml = new Cls_html;
?>

<!DOCTYPE html5>
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

              <?php 
                if(isset($_SESSION['pro_alert'])):
                  $str = (string) $_SESSION['pro_alert'];
                  eval($str); 
                  unset($_SESSION['pro_alert']);
                endif;
              ?>

              <div class="card mt-3">
                <div class="card-header row">
                  <h5 class="col text-primary"><i class="bi bi-tag"></i>Categorias de Producto(s)</h5>
                  <div class="col text-end">
                    <button type="button" class="btn btn-primary btn-md" data-bs-toggle="modal" data-bs-target="#mdl-cat">
                      <i class="bx bx-folder-plus"></i>Agregar
                    </button>
                  </div>
                </div>
                <div class="table-responsive text-nowrap pt-0">
                  <div class="modal fade" id="mdl-cat" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-lg" role="document">
                      <div class="modal-content">
                        <form id="frm-cat" action="ctrl_categoria.php" method="POST">
                            <div class="modal-header p-1 m-1">
                                <h5 class="modal-title"><i class="mb-2 bx bx-folder-plus"></i>Registrar Categoria</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                          
                              <div class="mb-3">
                                <div class="input-group">
                                  <label class="input-group-text" for="cod">Código</label>  
                                  <input type="number" disabled name="cod" value="<?php echo $categoria->getIndexCat(null); ?>"
                                  class="form-control" placeholder="Codigo de concepto">
                                </div>
                              </div>
                              <div class="mb-3">
                                <div class="input-group">
                                  <label class="input-group-text" for="nom">Categoría</label>  
                                  <input type="text" name="nom" class="form-control" placeholder="Nombre de Categoría o Grupo de Producto" >
                                </div>
                              </div>
                                
                              <input type="hidden" value="createCat" name="action">
                            </div>
                            <div class="modal-footer p-1 m-1">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                <button type="submit" id="btn-save"  class="btn btn-primary">Guardar</button>
                            </div>
                        </form>
                      </div>
                    </div>
                  </div>
                  
                  <table id="tbl-cat" class="table table-striped table-hover">
                    <thead>
                      <tr>
                        <th>Código</th>
                        <th>Categoría</th>
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
        $('#tbl-cat').DataTable({
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
              "targets":[2],
              "orderable":false,
            },
          ],
          "columns":[
            {"data":"cod"},
            {"data":"nom"},
            {"data": null,
              "render": function(data, type, row){
                return `
                <div class="dropdown">
                  <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                    <i class="bx bx-dots-vertical-rounded"></i>
                  </button>
                  <?php if($_SESSION['pro']['usr']['lvl'] === 1): ?>
                    <div class="dropdown-menu">
                      <a class="dropdown-item"
                          rw="`+ base64Encode(JSON.stringify(row))+`"
                        onclick="editCat(this)" href="javascript:void(0);">
                        <i class="bx bx-edit-alt me-1"></i> Editar 
                      </a>
                      <a class="dropdown-item" dl="`+row['cod']+`" onclick="delCat(this)" href="javascript:void(0);">
                        <i class="bx bx-trash-alt me-1"></i> Eliminar
                      </a>
                    </div>
                  <?php endif; ?>
                </div>`;
              },
            }
          ],
          "data":<?php echo json_encode($categoria->getListCat()); ?>,
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
              "sProcessing":"Procesando...",
              "processing":"Procesando...",
          },
          "drawCallback": function( settings ) {
            $('.buttons-excel').addClass('btn-sm mb-2');
            $('.buttons-excel').css('background','#0C7363');
          },
        });

        $('#tbl-cat_length').addClass('ms-2');
        $('#tbl-cat_filter').addClass('me-2');

        $(window).on('hidden.bs.modal',function(){
          $('#mdl-cat .modal-title').html('<i class="mb-2 bx bx-folder-plus"></i>Registar concepto');
          $('#mdl-cat #btn-save').text('Guardar');
          $('#mdl-cat #btn-save').removeClass('btn-warning').addClass('btn-primary');
          $('#frm-cat')[0].reset();
        });

        $(".menu-inner .menu-item[sc='Categorías']").addClass('active');
      });

      function editCat($btn){
        $jdata = base64Decode($($btn).attr('rw'));
        $dt = JSON.parse($jdata);
        console.log($dt);
        $('#mdl-cat form .form-control').each(function () {
          this.value = $dt[this.name];
        });  

        $('#mdl-cat .modal-title').html('<i class="mb-2 bx bx-edit"></i>Editar concepto');
        $('#mdl-cat #btn-save').text('Editar');
        $('#mdl-cat #btn-save').addClass('btn-warning').removeClass('btn-primary');
        $('#frm-cat').append('<input type="hidden" value="'+$dt['cod']+'" name="cod">');
        $('input[name="action"]').val('updatecat');
        $('#mdl-cat').modal('show');
      }

      function delCat(btn){

        if(confirm('¿Está seguro que desea eliminar esta Categoría?')){
          $(btn).wrap('<form id="frm-post" method="POST" action="ctrl_categoria.php"><input type="hidden" name="action" value="removecat"></form>');
          $('#frm-post').append('<input type="hidden" value="'+$(btn).attr('dl')+'" name="cod">');
          $('#frm-post').submit();
        }else return false;
      
      }

    </script>
  </body>
</html>