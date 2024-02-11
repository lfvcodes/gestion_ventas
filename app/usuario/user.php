<?php 
  session_start();
  define('APP_ROOT',"../../");
  define('VIEW_ROOT',"../");
  require_once VIEW_ROOT.'util/misc.php';
  require '../html/cls_html.php';
  $objHtml = new Cls_html;
  require_once 'cls_user.php';
  $user = new Cls_user;
?>

<!DOCTYPE html5>
<html lang="es">
  <?php
    $objHtml->getHead('Usuarios');
  ?>
  <body>
    <div class="layout-wrapper layout-content-navbar">
      <div class="layout-container">

        <?php $objHtml->getMenu(); ?>
        
        <div class="layout-page">

          <?php $objHtml->getNavBar(); ?>

          <div class="content-wrapper">
            
            <div class="container-xxl">
              <div class="modal-header me-2">
                <h4 class="fw-bold py-2 mb-2">
                  <span class="text-muted fw-light">Gestionar Usuarios
                </h4>
                <button type="button" class="btn btn-primary btn-md" data-bs-toggle="modal" data-bs-target="#mdl-user">
                  <i class="bx bx-folder-plus"></i>
                  Agregar Nuevo
                </button>
              </div>

              <?php 
                if(isset($_SESSION['pro_alert'])):
                  $str = (string) $_SESSION['pro_alert'];
                  eval($str); 
                  unset($_SESSION['pro_alert']);
                endif;
              ?>

                <div class="card">
                  <h5 class="card-header">Usuarios</h5>
                  <div class="table-responsive text-nowrap">

                    <div class="modal fade" id="mdl-user" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
                      <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-lg" role="document">
                        <div class="modal-content">
                          <form onsubmit="return validateForm()" action="ctrl_user" method="POST">
                              <div class="modal-header p-1 m-1">
                                <h5 class="modal-title"><i class="mb-2 bx bx-folder-plus"></i>Agregar Usuario</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                              </div>
                              <div class="modal-body">
                              <div class="row">
                                <div class="col-4 mb-3">
                                  <div class="input-group">
                                    <label class="input-group-text" for="txtid">ID</label>  
                                    <input required type="text" id="txtid" name="txtid" maxlength="10" class="form-control val-only-numbers" aria-label="ID" placeholder="N° Identifiación" >
                                  </div>
                                </div>
                              </div>
                              <div class="row">
                                <div class="col-6 mb-3">
                                  <div class="input-group">
                                    <label class="input-group-text" for="loguser">Usuario</label>  
                                    <input required type="text" id="loguser" name="loguser" maxlength="32" class="form-control val-only-letters" aria-label="Usuario" placeholder="Nombre de Usuario" >
                                  </div>
                                </div>
                                <div class="col-6 mb-3">
                                  <div class="input-group">
                                    <label class="input-group-text" for="nom">Nombre Completo</label>  
                                    <input required type="text" id="nom" name="nom" class="form-control val-only-acc" aria-label="Nombre" placeholder="Nombre(s) y Apellido(s)" >
                                  </div>
                                </div>
                              </div>

                              <div class="row">
                                <div class="col mb-3">
                                  <div class="input-group">
                                    <label class="input-group-text" for="email">@ email</label>  
                                    <input required type="email" id="email" name="email" class="form-control" aria-label="email" placeholder="email@email.com" >
                                  </div>
                                </div>

                                <div class="col mb-3">
                                  <div class="input-group">
                                    <label class="input-group-text" for="opt_nivel"> <i class="bi bi-key me-1"></i>Acceso</label>  
                                    <select required class="form-select form-control" name="opt_nivel" id="opt_nivel">
                                      <option selected disabled value="">Seleccionar Nivel</option>  
                                      <option value="1">Administrador</option>
                                      <option value="2">Operador</option>
                                    </select>
                                  </div>
                                </div>
                              </div>
                              <div class="row">
                                <div class="col mb-3">
                                  <div class="input-group">
                                    <label class="input-group-text" for="ps"><i class="bi bi-key"></i></label>  
                                    <input required type="password" maxlength="256" id="ps" name="ps" class="form-control" aria-label="password" 
                                    title="La contraseña debe contener numeros,mayusculas,minusculas y caracteres especiales"
                                   placeholder="Contraseña" >
                                  </div>
                                </div>

                                <div class="col mb-3">
                                  <div class="input-group">
                                    <label class="input-group-text" for="cc">Confirmar</label>  
                                    <input required type="password" maxlength="256" id="cc" name="cc" class="form-control" aria-label="confirm" placeholder="Confirmar Contraseña" >
                                  </div>
                                </div>
                              </div>
                              <input type="hidden" value="insertUser" name="action">
                            </div>
                            <div class="modal-footer p-1 m-1">
                              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                              <button type="submit" id="btn-save" class="btn btn-primary">Guardar</button>
                            </div>
                         </form>
                      </div>
                    </div>
                  </div>
                  
                  <table id="tbl-user" class="table table-striped table-hover">
                    <thead>
                      <tr>
                        <th>ID Usuario</th>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Nivel</th>
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

      function validateForm() {
          var password = document.getElementById("ps").value;
          var confirm_password = document.getElementById("cc").value;
        
          if (password != confirm_password) {
            alert("La confirmación de contraseña no coincide.");
            return false;
          }
        }

      $(document).ready(function () {
        
        $('#tbl-user').DataTable({
          "responsive": true,
          "fixedHeader": true,
          "scroller":    true,
          "sScrollY":     280,
          "pageLength": 10,
          "Sort": true,
          "aaSorting": [],
          "columnDefs":[
            {
              "targets":[4],
              "orderable":false,
            },
          ],
          "columns":[
            {"data":"txtid"},
            {"data":"nom"},
            {"data":"email"},
            {"data":"opt_nivel"},
            {"data": null,
              "render": function(data, type, row){
              return `
              <div class="dropdown">
                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                  <i class="bx bx-dots-vertical-rounded"></i>
                </button>
                <div class="dropdown-menu">
                  <a class="dropdown-item" dl="`+row['txtid']+`" onclick="delUser(this)" href="javascript:void(0);">
                    <i class="bx bx-trash me-1"></i> Borrar
                  </a>
                </div>
              </div>`
              },
            }
          ],
          "data":<?php echo (!empty($user->getListUser())) ? $user->getListUser() : '{}'; ?>,
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
            $('#tbl-user tbody tr').each(function(){
              var str = $(this).find('td:eq(3)').text().trim();
              switch (str) {
                case "1": $(this).find('td:eq(3)').html('<span class="avatar-initial rounded bg-label-danger p-2">Administrador</span>'); break;
                case "2":  $(this).find('td:eq(3)').html('<span class="avatar-initial rounded bg-label-secondary p-2">Operador</span>'); break;
                default: break;
              }
            });
          },
        });

        $('#tbl-user_length').addClass('ms-2');
        $('#tbl-user_filter').addClass('me-2');
       
        $(".menu-inner .menu-link div:contains('Usuarios')").parents('li').addClass('active');

      });

      function delUser(btn){

        if(confirm('¿Está seguro que desea eliminar este usuario?')){
          $(btn).wrap('<form id="frm-post" method="POST" action="ctrl_user.php"><input type="hidden" name="action" value="deleteUser"><input type="hidden" name="id" value=""></form>');
          $('#frm-post input[name="id"]').val($(btn).attr('dl'));
          $('#frm-post').submit();
        }else return false;
       
      }

    </script>
  </body>
</html>