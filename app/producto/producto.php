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
                
                <?php 
                  if(isset($_SESSION['pro_alert'])):
                    $str = (string) $_SESSION['pro_alert'];
                    eval($str); 
                    unset($_SESSION['pro_alert']);
                  endif;
                ?>

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
                      <form id="frm-product" action="ctrl_producto.php" method="POST">
                          <div class="modal-header p-1 m-1">
                            <h5 class="modal-title"><i class="mb-2 bx bx-folder-plus me-1"></i>Agregar Nuevo Producto</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                          </div>
                          <div style="max-height: calc(100vh - 200px); overflow-y: auto;" class="modal-body">
                            
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
                                    <?php 
                                      require_once '../categoria/cls_categoria.php';
                                      $cat = new cls_categoria;
                                      $tcat = $cat->getListCat();
                                      $end = sizeof($tcat);
                                      for ($i=0; $i < $end; $i++) { ?>
                                        <option value="<?php echo $tcat[$i]['cod']; ?>"><?php echo $tcat[$i]['nom'] ?></option>

                                      <?php } ?>
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
                              
                              <input type="hidden" id="cod_producto" value="" name="id">
                              <input type="hidden" value="insertProduct" name="action">
                          </div>
                          <div class="modal-footer p-1 m-1">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            <button type="submit" id="btn-save" class="btn btn-primary">Guardar</button>
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
                    <tbody class="table-border-bottom-0">
                      
                    </tbody>
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

    <script>
      $(document).ready(function () {

        var inputt = $('form input, form select, form checkbox');
        $(inputt).on('invalid', (e)=>{
          $('#btn-save').prop('disabled',true);
        });

        $('.form-control,input,select').change(function (e) { 
          e.preventDefault();
          $('#btn-save').prop('disabled',false);
        });

        var scrollStartPosition = document.body.scrollTop || document.documentElement.scrollTop;
        
        $('#tbl-producto').DataTable({
          "processing": true,
          "sProcessing": true,
          "serverSide":true,
          "order":[],
          "ordering": true,
          "responsive": true,
          "fixedHeader": true,
          "scroller":    true,
          "sScrollY" : ((window.innerHeight - scrollStartPosition) - 340) + "px",
          "pageLength": 25,
          dom: "<'row px-2 px-md-4 pt-2'<'col-md-3'l><'col-md-5 text-center'><'col-md-4'f>>" +
          "<'row'<'col-md-12'tr>>" +
          "<'row px-2 px-md-4 py-3'<'col-md-5'i><'col-md-7'p>>",
          "ajax":{
            url:"ctrl_producto.php",
            type:"POST",
            data:{action:'getListProduct'},
            error: function(data){
              //console.log('error');
              console.log(data.responseText);
            }
          },
          "Sort": true,
          "aaSorting": [],
          "columnDefs":[
            {
              "targets":[5],
              "orderable":false,
            },
            {
              "targets":[2],
              "visible": false,
            },
          ],
          "lengthMenu": [ [5,10, 25, 50, 100, -1], [5,10, 25, 50, 100, "Todos"] ],
          "language": {
              "buttons":{
                "print":"Imprimir",
              },
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
        });

        $(window).on('hidden.bs.modal',function(){
          $('input[name="stock"]').parents('.col').prop('hidden',false);
          $('#mdl-product .modal-title').html('<i class="mb-2 bx bx-folder-plus"></i>Registar Nuevo Producto');
          $('#mdl-product #btn-save').text('Guardar');
          $('#mdl-product #btn-save').removeClass('btn-warning').addClass('btn-primary');
          $('#frm-product')[0].reset();
          $('input[name="action"]').val("insertProduct");
        });

        $(".menu-inner .menu-item[sc='Productos']").addClass('active');

        });

        function editProd($json){
          console.log(base64Decode($($json).attr('rw')));
          $dt = JSON.parse( base64Decode($($json).attr('rw')));
          console.log($dt);
          
          $('#mdl-product form .form-control').each(function (i) {
            this.value = $dt[this.id];
          });  
          

          $('#mdl-product .modal-title').html('<i class="mb-2 bx bx-edit"></i>Editar Producto(s)');
          $('#mdl-product #btn-save').text('Editar');
          $('#mdl-product #btn-save').addClass('btn-warning').removeClass('btn-primary');
          $('input[name="id"]').val($dt['cod_producto']);
          $('input[name="action"]').val('updateProduct');
          $('#mdl-product').modal('show');
          
        }

        function delProd(btn){
          var id = $(btn).attr('dl');
          $.ajax({
            type: "POST",
            url: "ctrl_producto.php",
            data: {action:'checkProduct',cod_product:id},
            success: function (resp) {
              if(resp.trim() == 'true'){
                
                if(confirm('Este producto tiene transacciones asociadas, ¿Desea Desactivarlo?')){
                  $(btn).wrap('<form id="frm-post" method="POST" action="ctrl_producto.php"><input type="hidden" name="action" value="desactivateProduct"></form>');
                  $('#frm-post').append('<input type="hidden" value="'+id+'" name="cod_product">');
                  $('#frm-post').submit();
                }else return false;

              }else{

                if(confirm('¿Está seguro que desea eliminar este Producto?')){
                  $(btn).wrap('<form id="frm-post" method="POST" action="ctrl_producto.php"><input type="hidden" name="action" value="deleteProduct"><input type="hidden" value="'+id+'" name="cod_product"></form>');
                  $('#frm-post').submit();
                }else return false;

              }
            }
          });

        }
    </script>
  </body>
</html>