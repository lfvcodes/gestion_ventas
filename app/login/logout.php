<?php 
  session_start();  
  require_once '../util/misc.php';
  setBitacora('LOGIN',"CERRAR SESION",array(),$_SESSION['pro']['usr']['user']);
  unset($_SESSION['pro']);
  $_SESSION = array();
  header("Location: login.php");
?>