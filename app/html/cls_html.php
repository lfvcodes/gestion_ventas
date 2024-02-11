<?php
//#CODE_BY_V3LF
/* CLASE CONTROLADORA PARA EL MAQUETADO HTML RAPIDO */

class Cls_html
{

  public function getHead($titulo){
    ini_set('default_charset','utf-8');  
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    
    if(!isset($_SESSION))
      session_start();

      if($titulo !== 'Login' && $titulo !== 'Configurar Empresa'){
        if($_SESSION['pro']['usr']['log'] !== true || empty($_SESSION['pro']['usr']['user'])){
          header("Location: /gestion_ventas/app/login/login.php");
          exit;
        }
      }
      
    ?>
    <head>
      <meta http-equiv="Content-Type" content="text/html;" charset="utf-8" />
      <meta charset="UTF-8">
      <meta http-equiv="content-language" content="es">
      <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7,IE=8,IE=9,IE=10,IE=11"/>
      <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"/>
      <meta name="author" content="LuisF Vasquez" />
      <title><?php echo $titulo; ?> </title>
      
      <link rel="icon" type="image/png" href="<?php echo APP_ROOT;?>assets/img/logo.png">
      <link rel="stylesheet" href="<?php echo APP_ROOT;?>assets/vendor/fonts/boxicons.css" />

      <link rel="stylesheet" href="<?php echo APP_ROOT;?>assets/vendor/css/core.css" class="template-customizer-core-css" />
      <link rel="stylesheet" href="<?php echo APP_ROOT;?>assets/vendor/css/theme-default.css?r=<?php rand(1,9999) ?>" class="template-customizer-theme-css" />
      <link rel="stylesheet" href="<?php echo APP_ROOT;?>assets/css/demo.css" />
      <link rel="stylesheet" href="<?php echo APP_ROOT;?>assets/css/bootstrap-icons/bi.css" />
      <!--
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
      -->
      <?php if($titulo === 'Login'): ?>
        <link rel="stylesheet" href="<?php echo APP_ROOT;?>assets/vendor/css/pages/page-auth.css" />
      <?php endif ;?>

      <link rel="stylesheet" href="<?php echo APP_ROOT;?>assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
      <link rel="stylesheet" href="<?php echo APP_ROOT;?>assets/vendor/libs/dataTables/datatables.min.css" />
      <link rel="stylesheet" href="<?php echo APP_ROOT;?>assets/vendor/libs/dataTables/responsive.dataTables.min.css" />
      <link rel="stylesheet" href="<?php echo APP_ROOT;?>assets/vendor/libs/select2/select2.min.css" />
      <link rel="stylesheet" href="<?php echo APP_ROOT;?>assets/vendor/libs/select2/select2.b5theme.css" />
      
      <script src="<?php echo APP_ROOT;?>assets/vendor/js/helpers.js"></script>
      <script src="<?php echo APP_ROOT;?>assets/js/config.js"></script>
      <!--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.13.2/themes/base/jquery-ui.min.css" integrity="sha512-ELV+xyi8IhEApPS/pSj66+Jiw+sOT1Mqkzlh8ExXihe4zfqbWkxPRi8wptXIO9g73FSlhmquFlUOuMSoXz5IRw==" crossorigin="anonymous" referrerpolicy="no-referrer" />-->
      <link rel="stylesheet" href="<?php echo APP_ROOT;?>assets/vendor/libs/jquery/jquery_ui.css" />
      
      <style>
        .modal {
          overflow-y:auto;
        }
      </style>

    </head>
    
  <?php 
  }
    
  public function getMenu(){
    
    ?>
      <aside id="layout-menu" class="layout-menu menu-vertical w-0 menu bg-menu-theme shadow-lg">
        
        <div class="menu-inner-shadow"></div>

        <ul class="menu-inner nav nav-pills nav-flush text-center align-items-center pt-2">
          
          <li title="Inicio" sc="Inicio" data-bs-toggle="tooltip" data-bs-placement="right" class="menu-item active pb-2">
            <a href="<?php echo VIEW_ROOT; ?>inicio.php" class="menu-link pb-3 fw-bold">
              <i class="menu-icon tf-icons bx bx-home-circle bx-md"></i>
            </a>
          </li>
          

          <li title="Ventas" sc="Ventas" data-bs-toggle="tooltip" data-bs-placement="right" class="menu-item">
            <a href="<?php echo VIEW_ROOT; ?>venta/venta.php" class="menu-link pb-3">
              <i class="menu-icon tf-icons bi bi-building-down bx-md"></i>
            </a>
          </li>

          <li title="Productos" sc="Productos" data-bs-toggle="tooltip" data-bs-placement="right" class="menu-item">
            <a href="<?php echo VIEW_ROOT; ?>producto/producto.php" class="menu-link pb-3">
              <i class="menu-icon tf-icons bi bi-boxes bx-md"></i>
            </a>
          </li>

          <li title="Categorías" sc="Categorías" data-bs-toggle="tooltip" data-bs-placement="right" class="menu-item">
            <a href="<?php echo VIEW_ROOT; ?>categoria/categoria.php" class="menu-link pb-3">
              <i class="menu-icon tf-icons bx bx-tag bx-md"></i>
            </a>
          </li>

          <li title="Clientes" sc="Clientes" data-bs-toggle="tooltip" data-bs-placement="right" class="menu-item">
            <a href="<?php echo VIEW_ROOT; ?>cliente/cliente.php" class="menu-link pb-3">
              <i class="menu-icon tf-icons bx bx-user-pin bx-md"></i>
            </a>
          </li>

          <li title="Vendedores" sc="Vendedores" data-bs-toggle="tooltip" data-bs-placement="right" class="menu-item">
            <a href="<?php echo VIEW_ROOT; ?>vendedor/vendedor.php" class="menu-link pb-3">
              <i class="menu-icon tf-icons bi bi-person-badge bx-md"></i>
            </a>
          </li>

          <li title="Reportes / Estadísticas" sc="Reportes" data-bs-toggle="tooltip" data-bs-placement="right" class="menu-item">
            <a href="<?php echo VIEW_ROOT; ?>reporte/reporte.php" class="menu-link pb-3">
              <i class="menu-icon tf-icons bx bx-table bi bi-file-earmark-pdf bx-md"></i>
            </a>
          </li>

        </ul>
      </aside>
    <?php
  }

  public function getNavBar(){ ?>
      <nav
        class="layout-navbar bg-primary container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
        id="layout-navbar">

        <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
          <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
            <i class="bx bx-menu bx-sm"></i>
          </a>
        </div>

        <div class="navbar-nav-right d-flex align-items-center text-white" id="navbar-collapse">
          <div class="navbar-nav align-items-center">
            <div class="nav-item d-flex align-items-center">
            <span>
                <a href="#">
                <span class="text-white">QuimiExpress</span>
               </a>
            </div>
          </div>

          <ul class="navbar-nav flex-row align-items-center ms-auto">
            <li class="nav-item lh-1 me-3 text-white">
              <span id="nav-date"></span>
            </li>

            <!-- User -->
            <li class="nav-item navbar-dropdown dropdown-user dropdown">
              <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                <div class="avatar avatar-online">
                  <h3 class="align-items-center">
                    <i class="bx-md bi bi-person-circle <?php echo ($_SESSION['pro']['usr']['lvl'] === 1) ? 'text-white' : 'text-dark' ?>"></i>
                  </h3>
                </div>
              </a>
              <ul class="dropdown-menu dropdown-menu-end">
                <li>
                  <a class="dropdown-item" href="#">
                    <div class="d-flex">
                      <div class="flex-shrink-0 me-3">
                        <div class="avatar avatar-online">
                          <i class="bx-md bi bi-person-circle text-dark"></i>
                        </div>
                      </div>
                      <div class="flex-grow-1">
                        <span class="fw-semibold d-block"><?php echo ucwords($_SESSION['pro']['usr']['nom']) ?></span>
                        <small class="text-muted"> <?php echo ($_SESSION['pro']['usr']['lvl'] === 1) ? 'Administrador' : 'Operador'; ?></small>
                      </div>
                    </div>
                  </a>
                </li>

                <li>
                  <a id="btn-logout" class="dropdown-item" href="<?php echo VIEW_ROOT; ?>login/logout.php">
                    <i class="bx bx-power-off me-2"></i>
                    <span class="align-middle">Cerrar Sesión</span>
                  </a>
                </li>
              </ul>
            </li>
            <!--/ User -->
          </ul>
        </div>
      </nav>
    <?php 
  }

  public function importJs(){?>

    <!-- build:js assets/vendor/js/core.js -->
    <script src="<?php echo APP_ROOT;?>assets/vendor/libs/jquery/jquery.js"></script>
    <script src="<?php echo APP_ROOT;?>assets/vendor/libs/popper/popper.js"></script>
    <script src="<?php echo APP_ROOT;?>assets/vendor/js/bootstrap.js"></script>
    <script src="<?php echo APP_ROOT;?>assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>

    <script src="<?php echo APP_ROOT;?>assets/vendor/libs/dataTables/jquery.datatables.min.js"></script>
    <script src="<?php echo APP_ROOT;?>assets/vendor/libs/momentjs/moment.min.js"></script>
    <script src="<?php echo APP_ROOT;?>assets/vendor/libs/dataTables/datatables.min.js"></script>
    <script src="<?php echo APP_ROOT;?>assets/vendor/libs/dataTables/datatables.datetime.min.js"></script>
    <script src="<?php echo APP_ROOT;?>assets/vendor/libs/dataTables/dataTables.responsive.min.js"></script>

    <script src="<?php echo APP_ROOT;?>assets/vendor/libs/dataTables/buttons/js/dataTables.buttons.min.js"></script>
    <script src="<?php echo APP_ROOT;?>assets/vendor/libs/dataTables/buttons/js/buttons.bootstrap4.min.js"></script>
    <script src="<?php echo APP_ROOT;?>assets/vendor/libs/dataTables/buttons/js/buttons.jszip.min.js"></script>
    <script src="<?php echo APP_ROOT;?>assets/vendor/libs/dataTables/buttons/js/buttons.pdfmake.min.js"></script>
    <script src="<?php echo APP_ROOT;?>assets/vendor/libs/dataTables/buttons/js/buttons.vs_fonts.min.js"></script>
    <script src="<?php echo APP_ROOT;?>assets/vendor/libs/dataTables/buttons/js/buttons.html5.min.js"></script>
    <script src="<?php echo APP_ROOT;?>assets/vendor/libs/dataTables/buttons/js/buttons.print.min.js"></script>

    <script src="<?php echo APP_ROOT;?>assets/vendor/js/menu.js"></script>
    <script src="<?php echo APP_ROOT;?>assets/vendor/libs/chartJs/chart.min.js"></script>
    <script src="<?php echo APP_ROOT;?>assets/vendor/libs/jspdf/jspdf.min.js"></script>
    <script src="<?php echo APP_ROOT;?>assets/vendor/libs/select2/select2.min.js"></script>

    <!-- Main JS -->
    <script src="<?php echo APP_ROOT;?>assets/js/main.js"></script>
    <script src="<?php echo APP_ROOT;?>assets/js/util.js"></script>
    <script src="<?php echo APP_ROOT;?>assets/vendor/libs/jquery/jquery_ui.min.js"></script>
    
    <script src="<?php echo APP_ROOT;?>assets/js/monthpicker.js"></script>


  <?php }
  
}#END CLASS
?>
