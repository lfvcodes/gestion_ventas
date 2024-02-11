<?php 
ob_start();
session_start();

if(isset($_POST) && !empty($_POST) && !empty($_POST['action'])){
  $post = $_POST;
  $usr = filter_var($_POST['log-username'], FILTER_SANITIZE_STRING);
  $psw = filter_var($post['password'], FILTER_SANITIZE_STRING);

  if($post['action'] === 'enter'){
    require_once '../util/cls_connection.php';
    $bd = new Cls_connection;

    $rs = $bd->prepare('SELECT email, nom_usuario, nivel, log_user, psw FROM pro_2usuario WHERE log_user = ? AND activo = ? LIMIT 1', array($usr,'S'));
    
    if($rs->rowCount() > 0){
      $row = $rs->fetch();
      
      $storedPsw = $row['psw'];
      if(password_verify($psw, $storedPsw)){
        require_once '../util/misc.php';

        $_SESSION['pro']['usr'] = array(
          'log' => true,
          'user' => $row['log_user'],
          'email' => $row['email'],
          'nom' => $row['nom_usuario'],
          'lvl' => $row['nivel'],
        );

        setBitacora('LOGIN',"INICIAR SESION",array(),$row['log_user']);
        echo '<script>
          window.location.replace("../inicio.php");
        </script>';
      } else {
        unset($_SESSION['pro']);
        echo '<script>
                alert("Datos incorrectos");
                window.location.replace("login.php");
              </script>';
      }
    } else {
      unset($_SESSION['pro']);
      echo '<script>
              alert("Datos incorrectos");
              window.location.replace("login.php");
            </script>';
    }
  }


}
ob_end_flush();
?>