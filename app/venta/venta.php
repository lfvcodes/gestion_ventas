<?php 
  session_start();
  define('APP_ROOT',"../../");
  define('VIEW_ROOT',"../");
  require '../html/cls_html.php';
  require_once VIEW_ROOT.'util/misc.php';
  require_once 'cls_venta.php';
  $venta = new Cls_venta;
  $objHtml = new Cls_html;
?>

<!DOCTYPE html5>
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
              <?php 
                if(isset($_SESSION['pro_alert'])):
                  $str = (string) $_SESSION['pro_alert'];
                  eval($str); 
                  unset($_SESSION['pro_alert']);
                endif;
              ?>

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
                        <form id="frm-venta" action="ctrl_venta.php" method="POST">
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
                                <table class="table table-sm" id="tbl-cond">
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

                              <input type="hidden" value="setVenta" name="action">
                            </div>
                            <div class="modal-footer p-1 m-1">
                              <button type="button" class="btn btn-secondary ms-4" data-bs-dismiss="modal">Cancelar</button>
                              <button type="submit" id="btn-save" class="btn btn-primary">Guardar</button>
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

      var productosSeleccionados = [];

      function editventa($btn) {
        
        $jdata = base64Decode($($btn).attr('rw'));
        console.log($jdata);
        $dt = JSON.parse($jdata);
       
        $('#optcli').append('<option selected value="'+$dt['idcli']+'">'+$dt['nomcli']+'</option>');
        $('.cli').select2('destroy');
        $('.cli').val($dt['idcli']).trigger('change');
        $('.cli').select2({
          theme: 'bootstrap-5',
          dropdownParent: $("#mdl-venta"),
          ajax: { 
            url: "../cliente/ctrl_cliente.php",
            type: "post",
            dataType: 'json',
            delay: 250,
            data: function (params) {
              return {
                action:"getListOptionCli",
                lk: params.term // search term
              };
            },
            processResults: function (response) {
              return {results: response}
            },
          },
          language: {
            searching: function() { return "Buscando...";},
            noResults: function() {
              var $bt = `<li><button style="width: 100%" type="button" class="btn btn-primary" onClick="newProv()"><i class="fa fa-plus"></i> Agregar Nuevo</button></li>`;
              $('.select2-results .select2-results__options').append($bt);
              return "No se Encontraron Resultados";
            },
          },
        });

        $('#optvend').append('<option selected value="'+$dt['idvend']+'">'+$dt['nomvend']+'</option>');
        $('.vend').select2('destroy');
        $('.vend').val($dt['idvend']).trigger('change');
        $('.vend').select2({
          theme: 'bootstrap-5',
          dropdownParent: $("#mdl-venta"),
          ajax: { 
            url: "../vendedor/ctrl_vendedor.php",
            type: "post",
            dataType: 'json',
            delay: 250,
            data: function (params) {
              return {
                action:"getListOptionVend",
                lk: params.term // search term
              };
            },
            processResults: function (response) {
              return {results: response}
            },
          },
          language: {
            searching: function() { return "Buscando...";},
            noResults: function() {
              var $bt = `<li><button style="width: 100%" type="button" class="btn btn-primary" onClick="newProv()"><i class="fa fa-plus"></i> Agregar Nuevo</button></li>`;
              $('.select2-results .select2-results__options').append($bt);
              return "No se Encontraron Resultados";
            },
          },
        });

        $('#freg').val($dt['fechab']).trigger('change');
        $('.desc').val($dt['descr']);
        
        $dt2 = response('ctrl_venta.php',{action:"getDetailTable",id:$dt['cod']});
        $tbl = JSON.parse($dt2);
        
        $('#tbl-cond tbody').html("");
        stotald = 0;
        totald = 0;
        var t = sessionStorage.getItem('tipo');
        for (let i = 0; i < $tbl.length; i++) {
          var idtr = $('#tbl-cond tbody tr').length + 1; 
          let titem = ($tbl[i]['cant'] * $tbl[i]['monto']);
          titem = titem.toFixed(2);
          var tprecio = (t == 1) ? $tbl[i]['p_venta'] : $tbl[i]['p_venta'+t];
          $('#tbl-cond tbody').append(`
            <tr>
              <td>
                <select class="form-select form-control prod" name="prod[]">
                  <option selected value="`+$tbl[i]['cod_producto']+`">`+$tbl[i]['nom_producto']+`</option>
                </select>
              </td>
              <td><input required onkeyup="calcm(this)" onchange="calcm(this)" name="cant[]" value="`+$tbl[i]['cant']+`" class="form-control cant" min="1" step="1" type="number"></td>
              <td><input required readonly name="monto[]" class="form-control monto" value="`+$tbl[i]['monto']+`" min="0.01" step="0.01" type="number"></td>
              <td><input readonly class="form-control titem" type="number" value="`+titem+`"></td>
              <td><button onclick="remove(this);" type="button" class="btn btn-sm btn-danger">-</button></td>
            </tr>
          `);
          stotald += ($tbl[i]['cant'] * $tbl[i]['monto']);
          productosSeleccionados.push($tbl[i]['cod_producto']);
        }
        
        $('#stotald').val(stotald.toLocaleString('es-ES', { minimumFractionDigits: 2, maximumFractionDigits: 2, useGrouping: true }));
        stotald = (stotald * 1.16);
        $('#totald').val(stotald.toLocaleString('es-ES', { minimumFractionDigits: 2, maximumFractionDigits: 2, useGrouping: true }));
        initSelect();

        $('#mdl-venta .modal-title').html('<i class="mb-2 bx bx-edit"></i>Editar venta');
        $('#mdl-venta #btn-save').text('Editar');
        $('#mdl-venta form').append('<input type="hidden" name="id">');
        $('input[name="id"]').val($dt['cod']);
        $('#mdl-venta #btn-save').addClass('btn-warning').removeClass('btn-primary');
        $('input[name="action"]').val('updateVenta');
        $('#mdl-venta').modal('show');
      }

      function delventa(btn) {

        if (confirm('¿Está seguro que desea eliminar este Registro de venta?')) {
          //$(btn).wrap('<form id="frm-post" method="POST" action="ctrl_venta.php"><input type="hidden" name="action" value="removeVenta"><input type="hidden" name="tventa" value="C"><input type="hidden" name="id" value=""></form>');
          vl = $(btn).attr('dl');
          ajx = response('ctrl_venta.php',{action:'removeVenta',id:vl});
          alert(ajx.trim());
          if(ajx.trim() == 'true'){
            alert('Registro de Venta Eliminado Correctamente');
          }else{
            alert('Error al intentar Eliminar Venta,Intente Nuevamente.');
          }
        } else return false;
        $('#tbl-venta').DataTable().ajax.reload();

      }

      function newCrud(url){
        popupWindow = window.open(url,'popUpWindow','height=500,width=500,left=100,top=100,resizable=yes,scrollbars=yes,toolbar=yes,menubar=no,location=no,directories=no, status=yes');
      }

      function initSelect(){
        $('.prod').select2({
          theme: 'bootstrap-5',
          dropdownParent: $("#mdl-venta"),
          ajax: { 
            url: "../producto/ctrl_producto.php",
            type: "POST",
            dataType: 'json',
            delay: 250,
            data: function (params) {
              return {
                action:"getListOptionProduct",
                lk: params.term // search term
              };
            },
            processResults: function (response) {
              return {results: response}
            },
            cache: true,
          },
          language: {
            searching: function() { return "Buscando...";},
            noResults: function() {
              var $bt = `<li><button style="width: 100%" type="button" class="btn btn-primary" onClick="newCrud('../producto/producto.php')"><i class="fa fa-plus"></i> Agregar Nuevo</button></li>`;
              $('.select2-results .select2-results__options').append($bt);
              return "No se Encontraron Resultados";
            },
          },
        });

        $('.prod').change(function (e) { 
          var nuevoProducto = $(this).val();
          var filaActual = $(this).closest('tr');
          var filaIndex = filaActual.index();
          var productosSeleccionadosTemp = [...productosSeleccionados]; // Crear una copia temporal del array de productos seleccionados

          // Eliminar el producto previamente seleccionado de la lista de productos seleccionados
          var productoAnterior = filaActual.data('producto-seleccionado');
          if (productoAnterior) {
            var indexProductoAnterior = productosSeleccionadosTemp.indexOf(productoAnterior);
            if (indexProductoAnterior > -1) {
              productosSeleccionadosTemp.splice(indexProductoAnterior, 1);
            }
          }

          // Validar si el producto ya ha sido seleccionado en otra fila
          if (productosSeleccionadosTemp.includes(nuevoProducto)) {
            alert('¡El producto seleccionado ya ha sido agregado en otra fila!');
            $(this).val(productoAnterior).trigger('change'); // Revertir la selección al producto anterior
            return;
          }

          // Actualizar el producto seleccionado en la fila actual
          filaActual.data('producto-seleccionado', nuevoProducto);

          // Actualizar el array de productos seleccionados después de la validación
          if (productoAnterior) {
            var indexProductoAnterior = productosSeleccionados.indexOf(productoAnterior);
            if (indexProductoAnterior > -1) {
              productosSeleccionados.splice(indexProductoAnterior, 1);
            }
          }
          if (nuevoProducto) {
            var vl = $(this).val();
            $rs = response('../producto/ctrl_producto.php',{action:"getProductPrices",id:vl});
            $rs = JSON.parse($rs)[0];
            $(this).parent('td').parent('tr').find('td .monto').val($rs['pcosto']);
            //$(this).parent('td').parent('tr').find('td .cant').attr('max',$rs['stockreal']);
            productosSeleccionados.push(nuevoProducto);
          }
          $(this).parent('td').parent('tr').find('td .cant').val(null).trigger('change');
        });
      }

      function add(){
          $('#tbl-cond tbody').append(`
          <tr>
            <td>
              <select class="form-select form-control prod" name="prod[]"></select>
            </td>
            <td><input required onkeyup="calcm(this)" onchange="calcm(this)" name="cant[]" class="form-control cant" min="1" step="1" type="number"></td>
            <td><input required readonly name="monto[]" class="form-control monto" min="0.01" step="0.01" type="number"></td>
            <td><input readonly class="form-control titem" type="number"></td>
            <td><button onclick="remove(this);" type="button" class="btn btn-sm btn-danger">-</button></td>
          </tr>
          `);
          initSelect();
      }

      function remove(btn){
        $(btn).parent('td').parent('tr').remove(); 
        if($('#tbl-cond tbody tr').length < 1){
          $('#stotald,#totald').val("");
        }
        calcm($('#tbl-cond tbody tr:last td:eq(1) .form-control'));
      }

      function calcm(me){
        
        var stotald = 0.0;
        var totald = 0.0;
        var precio = $(me).parent('td').parent('tr').find('td .monto').val();
       
        var titem = $(me).parent('td').parent('tr').find('td .titem');
        var item = (precio * Number($(me).val()));
        titem.val(item.toFixed(2));
        
        $('.titem').each(function () {
          stotald += Number($(this).val());
        });

        totald = (stotald * 1.16);
        $('#stotald').val(stotald.toFixed(2));
        $('#totald').val(totald.toLocaleString('es-ES', { minimumFractionDigits: 2, maximumFractionDigits: 2, useGrouping: true }));
      }

      $(document).ready(function () {

        $('#frm-venta').submit(function(e){
          e.preventDefault();
          var form = $(this);
          stotald = 0;
          $('.titem').each(function () {
            stotald += Number($(this).val());
          });

          if(stotald <= 0 ){
            alert('El monto total de la venta debe ser mayor a cero');
            return false;
          }
          
          let resp = confirm('¿Está seguro(a) de Guardar y Generar Registro de Venta?');
          if(!resp){
            return false;
          }else{

            ajx = response('ctrl_venta.php',form.serialize());
            if(ajx.trim() == 'true'){
              alert('Venta Registrada Correctamente!');
              form[0].reset();
              $('#mdl-venta').modal('hide');
              $('#tbl-venta').DataTable().ajax.reload();
              return;
            }else{
              alert('ha ocurrido un error al intentar registrar Venta, Intente nuevamente');
              return false;
            }
            return;
          }

        });
        
        var scrollStartPosition = document.body.scrollTop || document.documentElement.scrollTop;

        $('#optcli').select2({
          theme: 'bootstrap-5',
          dropdownParent: $("#mdl-venta"),
          ajax: { 
            url: "../cliente/ctrl_cliente.php",
            type: "post",
            dataType: 'json',
            delay: 250,
            data: function (params) {
              return {
                action:"getListOptionCli",
                lk: params.term // search term
              };
            },
            processResults: function (response) {
              return {results: response}
            },
            cache: true
          },
          language: {
            searching: function() { return "Buscando...";},
            noResults: function() {
              var $bt = `<li><button style="width: 100%" type="button" class="btn btn-primary" onClick="newCrud('../cliente/cliente.php')"><i class="fa fa-plus"></i> Agregar Nuevo</button></li>`;
              $('.select2-results .select2-results__options').append($bt);
              return "No se Encontraron Resultados";
            },
          },
        });

        $('#optvend').select2({
          theme: 'bootstrap-5',
          dropdownParent: $("#mdl-venta"),
          ajax: { 
            url: "../vendedor/ctrl_vendedor.php",
            type: "post",
            dataType: 'json',
            delay: 250,
            data: function (params) {
              return {
                action:"getListOptionVend",
                lk: params.term // search term
              };
            },
            processResults: function (response) {
              return {results: response}
            },
            cache: true
          },
          language: {
            searching: function() { return "Buscando...";},
            noResults: function() {
              var $bt = `<li><button style="width: 100%" type="button" class="btn btn-primary" onClick="newCrud('../vendedor/vendedor.php')"><i class="fa fa-plus"></i> Agregar Nuevo</button></li>`;
              $('.select2-results .select2-results__options').append($bt);
              return "No se Encontraron Resultados";
            },
          },
        });

        initSelect();

        $('#tbl-venta').DataTable({
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
            url:"ctrl_venta.php",
            type:"POST",
            data:{action:'getListVenta'},
            error: function(data){
              //console.log('error');
              //console.log(data.responseText);
            }
          },
          "Sort": true,
          "aaSorting": [],
          "columnDefs":[
            {
              "targets":[8],
              "orderable":false,
            },
            {
              "targets":[1,3,5],
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
          $('#stotal').prop('hidden',true);
          $('#frm-venta')[0].reset();
          $('#optcli,#optvend').select2('destroy');
          $('#optcli').val(null).trigger('change');
          $('#optvend').val(null).trigger('change');

          $('#optcli').select2({
            theme: 'bootstrap-5',
            dropdownParent: $("#mdl-venta"),
            ajax: { 
              url: "../cliente/ctrl_cliente.php",
              type: "post",
              dataType: 'json',
              delay: 250,
              data: function (params) {
                return {
                  action:"getListOptionCli",
                  lk: params.term // search term
                };
              },
              processResults: function (response) {
                return {results: response}
              },
              cache: true
            },
            language: {
              searching: function() { return "Buscando...";},
              noResults: function() {
                return "No se Encontraron Resultados";
              },
            },
          });

          $('#optvend').select2({
            theme: 'bootstrap-5',
            dropdownParent: $("#mdl-venta"),
            ajax: { 
              url: "../vendedor/ctrl_vendedor.php",
              type: "post",
              dataType: 'json',
              delay: 250,
              data: function (params) {
                return {
                  action:"getListOptionVend",
                  lk: params.term // search term
                };
              },
              processResults: function (response) {
                return {results: response}
              },
              cache: true
            },
            language: {
              searching: function() { return "Buscando...";},
              noResults: function() {
                return "No se Encontraron Resultados";
              },
            },
          });

          $('#mdl-venta .modal-title').html('<i class="mb-2 bx bx-folder-plus"></i>Registar venta');
          $('#mdl-venta #btn-save').text('Guardar');
          $('#mdl-venta #btn-save').removeClass('btn-warning').addClass('btn-primary');
          
          $('#tbl-cond tbody').html("");
          productosSeleccionados = [];
        });

        $('#tbl-venta_length').addClass('ms-2');
        $('#tbl-venta_filter').addClass('me-2');

        $(".menu-inner .menu-item[sc='Ventas']").addClass('active');

      });

    </script>
  </body>
</html>