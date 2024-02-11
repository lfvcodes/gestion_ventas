<!DOCTYPE html5>
<html lang="es">
  <?php
    define('APP_ROOT',"../");
    define('VIEW_ROOT',"./");
    require './html/cls_html.php';
    require_once './util/misc.php';
    require_once './util/cls_connection.php';
    $objHtml = new Cls_html;
    $bds = new Cls_connection;
    $objHtml->getHead('Inicio');

    $fechaActual = date('Y-m-d');

  ?>
  <body>
  <div class="layout-wrapper layout-content-navbar">
      <div class="layout-container">

        <?php $objHtml->getMenu(); ?>
        
        <div class="layout-page">

          <?php $objHtml->getNavBar(); ?>
          <div class="content-wrapper">

            <div class="container-xxl flex-grow-1 container-p-y">
              <div class="row">

                <div class="col-lg-4 col-md-12">
                  <div class="row m-2">
                    <div class="card col mt-1">
                      <div class="card-body">
                        <img class="img-fluid"  src="../assets/img/logo.png" alt="">
                      </div>
                    </div>
                  </div>
                </div>

                <div class="col-lg-8 col-md-12">
                  <div class="row m-2">  
                    
                    <div class="card col m-1">
                      <div class="card-body p-3">
                        <h4>
                          <span class="fw-semibold d-block mb-3">Ventas Realizadas</span>
                        </h4>
                        <div class="d-flex">
                          <h4 id="tcxc" class="card-title mb-1 text-dark"></h4>
                        </div>
                      </div>
                
                    </div>

                    <div class="card col m-1">
                      <div class="card-body p-3">
                        
                        <h4>
                          <span class="fw-semibold d-block mb-3">Ventas Hoy</span>
                        </h4>
                        <div class="d-flex">
                          <h4 id="tcxp" class="card-title mb-2 text-dark"></h4>
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
      $(document).ready(function () {
        $(".menu-inner .menu-item a:contains('Inicio')").parent('li').addClass('active');
        $rs = response('venta/ctrl_venta.php',{action:"getDashboard"});
        $rs = JSON.parse($rs);
        $('#tcxc').text($rs['tventa']);
        $('#tcxp').text($rs['tvhoy']);
      });
    </script>
  </body>
</html>
