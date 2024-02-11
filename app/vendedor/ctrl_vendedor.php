<?php 
session_start();

if(isset($_POST) && !empty($_POST) && !empty($_POST['action'])){
  
  $post = filter_var_array($_POST, FILTER_SANITIZE_STRING);
  include_once 'cls_vendedor.php';
  require_once '../util/misc.php';
  $vendedor = new Cls_vendedor;

  if($post['action'] === 'insertVend'){
    
    $params = array(
      $post['nac'],
      $post['id'],
      $post['nom'],
      $post['ape'],
      isset($post['dir']) ? $post['dir'] : null,
      isset($post['email']) ? $post['email'] : null,
      isset($post['tel']) ? $post['tel'] : null,
    );
    
    if($vendedor->setCrudVend('INSERT_VEND',$params) !== true ){
      $_SESSION['pro_alert'] = "alert('warning','Error al intentar Guardar');";
    }else{
      $_SESSION['pro_alert'] = "alert('success','Vendedor Agregado Correctamente!');";
    }
  }

  if($post['action'] === 'updateVend'){
    
    $params = array(
      $post['nom'],
      $post['ape'],
      $post['dir'],
      $post['email'],
      $post['tel'],
      $post['nac'],
      $post['id'],
    );

    if($vendedor->setCrudVend('UPDATE_VEND',$params) !== true ){
      $_SESSION['pro_alert'] = "alert('warning','Hubo un error al intentar Modificar');";
    }else{
      $_SESSION['pro_alert'] = "alert('success','Vendedor Modificado Correctamente!');";
    }
  }

  if($post['action'] === 'remove'){
    $params = array(
      $post['nac'],
      $post['id'],
    );
    if(is_null($vendedor->checkRemoveVend($post['id']))):
      if($vendedor->setCrudVend('DELETE_VEND',$params) !== true ){
        $_SESSION['pro_alert'] = "alert('warning','Error al intentar Borrar');";
      }else{
        $_SESSION['pro_alert'] = "alert('success','Vendedor Borrado Correctamente!');";
      }
    else:
      $_SESSION['pro_alert'] = "alert('success','Vendedor Desactivado');";
    endif;
  }

  if($post['action'] === 'getListOptionVend'){
    $lk = (!isset($post['lk'])) ? null : $post['lk'];
    $vendedor->getOptionSelectVend($lk);
    exit;
  }

  header("Location: vendedor.php");

}
?>