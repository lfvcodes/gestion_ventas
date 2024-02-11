<?php 
#CONTROLADOR DE TODOS LOS CATALOGOS
ob_start();
session_start();

if(isset($_POST) && !empty($_POST) && !empty($_POST['action'])){
  
  include_once 'cls_user.php';
  require_once '../util/misc.php';
  $user = new Cls_user;
  $post = filter_var_array($_POST, FILTER_SANITIZE_STRING);
  extract($post);

  switch ($action) {
    case 'insertUser':
      
      if($cc !== $ps){
        $_SESSION['pro_alert'] = "alert('warning','Error al intentar Guardar \n Las Confirmación de contraseña no coincide');";
        break;
      }

      $cc = password_hash($cc,PASSWORD_DEFAULT);
      $params = array($txtid,$loguser,$email,$nom,$cc,$opt_nivel);
      if($user->setCrudUser('INSERT_USER',$params) ){
        $_SESSION['pro_alert'] = "alert('success','Usuario Agregado Correctamente!');";
      }else{
        $_SESSION['pro_alert'] = "alert('warning','Error al intentar Guardar');";
        setBitacora('USUARIOS','AGREGAR USUARIO '.$id,$params,$_SESSION['dck']['usr']['user']);
      }
    break;

    case 'deleteUser':
      if($user->setCrudUser('DELETE_USER',array($id)) ){
        $_SESSION['pro_alert'] = "alert('success','Usuario Borrado/Desactivado Correctamente!');";
      }else{
        $_SESSION['pro_alert'] = "alert('warning','Error al intentar Guardar');";
        setBitacora('USUARIOS','BORRAR/DESACTIVAR USUARIO '.$id,array($id),$_SESSION['dck']['usr']['user']);
      }
    break;
    
    default: header("Location: user"); break;
  }

  header("Location: user");

}
ob_end_flush();
?>