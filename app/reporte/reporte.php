<?php
session_start();
define('APP_ROOT', "../../");
define('VIEW_ROOT', "../");
require '../html/cls_html.php';
$objHtml = new Cls_html;
date_default_timezone_set('America/Caracas');
?>

<!DOCTYPE html5>
<html lang="es">
<?php
$objHtml->getHead('Reportes');
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
              <h5 class="card-header p-2 ms-2 text-primary"><i class="bi bi-file-earmark-pdf"></i>Reportes / Estadísticas de Venta</h5>
              <div class="card-body">
                <label for="fi">Desde</label>
                <input class="form-control" max="<?php echo date('Y-m-d'); ?>" type="date" name="fi" id="fi">
                <br>
                <label for="ff">Hasta</label>
                <input class="form-control" max="<?php echo date('Y-m-d'); ?>" type="date" name="ff" id="ff">
                <br>
                <span class="text-primary">Vendedor(es)</span>
                <select id="idvend" class="form-select vend">
                  <option value="T">Todos los Vendedores</option>
                </select>
                
                <!--<button type="button" class="mt-3 btn btn-primary btn-md" data-bs-toggle="modal" data-bs-target="#mdl-rpt">Ver Reporte</button>-->
                <button type="button" onclick="callReport();" class="mt-3 btn btn-primary btn-md">Ver Reporte</button>

                <div class="modal fade" id="mdl-rpt" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-xl" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="modalTitleId">Reporte de Ventas</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div style="height: 600px;" class="modal-body">
                        <iframe id="frame" class="w-100 h-100" src="rpt_vend.php" frameborder="0"></iframe>
                      </div>
                    </div>
                  </div>
                </div>

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

    function callReport(){

      date1Obj = new Date($('#fi').val());
      date2Obj = new Date($('#ff').val());
      if(date1Obj.getTime() > date2Obj.getTime()) {
        alert('La fecha inicial no puede ser mayor que la fecha Final');
        $('#fi,#ff').val("");
        return;
      }

      if(empty($('#fi').val()) || empty($('#ff').val())){
        alert('Por favor Seleccione un rango de Fecha Valido');
        $('#fi,#ff').val("");
        return;
      }
      vend = $('#idvend').val();
      url = 'rpt_vend.php?ve='+vend+'&fi='+$('#fi').val()+'&ff='+$('#ff').val();
      $('#frame').prop('src',url);

      $('#mdl-rpt').modal('show');
    }

    $(document).ready(function() {

      $('.vend').select2({
        theme: 'bootstrap-5',
        ajax: {
          url: "../vendedor/ctrl_vendedor.php",
          type: "post",
          dataType: 'json',
          delay: 250,
          data: function(params) {
            return {
              action: "getListOptionVend",
              lk: params.term // search term
            };
          },
          processResults: function(response) {
            return {
              results: response
            }
          },
        },
        language: {
          searching: function() {
            return "Buscando...";
          },
          noResults: function() {
            return "No se Encontraron Resultados";
          },
        },
      });

      $(window).on('hidden.bs.modal',function(){
        var $miSelect = $('#idvend');
        if ($miSelect.find('option[value="T"]').length) {
          $miSelect.find('option[value="T"]').remove();
        }

        // Agregar la opción "Todos" como seleccionada por defecto
        var option = new Option('Todos los Vendedores', 'T', true, true);
        $miSelect.append(option).trigger('change');
        $('#frame').prop('src',"");
      });

      //$(".menu-inner .menu-link div:contains('Reportes')").parents('li').addClass('active');
      $(".menu-inner .menu-item[sc='Reportes']").addClass('active');
    });
  </script>
</body>

</html>