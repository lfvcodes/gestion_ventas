<?php
#CODE_BY_LFVCODES
ob_start();
session_start();

if (isset($_POST) && !empty($_POST) && !empty($_POST['action'])) {
  $post = $_POST;
  $usr = filter_var($_POST['log-username'], FILTER_SANITIZE_STRING);
  $psw = filter_var($post['password'], FILTER_SANITIZE_STRING);

  if ($post['action'] === 'enter') {
    require_once '../util/cls_connection.php';
    $bd = new Cls_connection;
    $sql = 'SELECT email, nom_usuario, nivel, log_user, psw FROM pro_2usuario WHERE log_user = ? AND activo = ? LIMIT 1';
    $rs = $bd->prepare($sql, array($usr, 'S'));

    if ($rs->rowCount() > 0) {
      $row = $rs->fetch();

      $storedPsw = $row['psw'];
      if (password_verify($psw, $storedPsw)) {

        $_SESSION['pro']['usr'] = array(
          'log' => true,
          'user' => $row['log_user'],
          'email' => $row['email'],
          'nom' => $row['nom_usuario'],
          'lvl' => $row['nivel'],
        );

        header("Location: ../inicio");
      } else {
        unset($_SESSION['pro']);
        header("Location: ../login");
      }
    } else {
      unset($_SESSION['pro']);
      header("Location: ../login");
    }
  }
}
ob_end_flush();
