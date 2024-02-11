<?php 
  session_start();
?>

<!DOCTYPE html5>
<html lang="es">
  <?php
    define('APP_ROOT',"../../");
    require '../html/cls_html.php';
    require '../util/cls_connection.php';
    $objHtml = new Cls_html;
    $objHtml->getHead('Login');
  ?>
  <body>
    <div class="container-xxl">
      <div class="authentication-wrapper authentication-basic container-p-y">
        <div class="authentication-inner">
          <div class="card">
            <div class="card-body">
              <div class="justify-content-center">
                <h4 class="text-center text-primary">Sistema de Ventas</h4>
              </div>

              <div class="justify-content-center">
                <h2 class="text-center text-primary">
                  <i style="font-size: 4rem;" class="bi bi-person-circle"></i>
                </h2>
              </div>

              <form id="formAuthentication" class="mb-3" action="entrar.php" method="POST">
                <div class="mb-3">
                  <label for="email" class="form-label">Usuario</label>
                  <input
                    type="text"
                    class="form-control val-nospc val-only-letters"
                    id="log"
                    name="log-username"
                    placeholder="Digite su Nombre de usuario"
                    autocomplete="off"
                    autofocus
                  />
                </div>
                <div class="mb-3 form-password-toggle">
                  <div class="d-flex justify-content-between">
                    <label class="form-label" for="password">Contraseña</label>
                  </div>
                  <div class="input-group input-group-merge">
                    <input
                      type="password"
                      id="password"
                      class="form-control"
                      name="password"
                      autocomplete="off"
                      placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                      aria-describedby="password"
                    />
                    <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                  </div>
                </div>
                <input type="hidden" value="enter" name="action">
                <div class="mb-3">
                  <button class="btn btn-primary d-grid w-100" type="submit">Iniciar Sesión</button>
                </div>
              </form>

              <p align="justify">
                <span>
                  Suministre los datos solicitados por el sistema para validar su
                  sesión de usuario
                </span>
              </p>
            </div>
          </div>
          <!-- /Register -->
        </div>
      </div>
    </div>

    <?php 
      $objHtml->importJs();
    ?>
    <script>
      <?php if(isset($_SESSION) && !empty($_SESSION['logfail'])): ?>
        alert('Datos de acceso Incorrectos!');
        <?php
          unset($_SESSION['logfail']);
        ?>
      <?php endif; ?>
    </script>
  </body>
</html>
