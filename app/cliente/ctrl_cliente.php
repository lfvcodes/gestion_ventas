<?php 
session_start();

if(isset($_POST) && !empty($_POST) && !empty($_POST['action'])){
  
  $post = filter_var_array($_POST, FILTER_SANITIZE_STRING);
  
  include_once 'cls_cliente.php';
  require_once '../util/misc.php';
  $cliente = new Cls_cliente;

  if($post['action'] === 'insertClient'){
    
    $params = array(
      $post['nac'],
      $post['id'],
      $post['nom'],
      $post['dir'],
      $post['email'],
      $post['tel'],
    );
    
    if($cliente->setCrudClient('INSERT_CLIENT',$params) !== true ){
      $_SESSION['pro_alert'] = "alert('warning','Error al intentar Guardar');";
    }else{
      $_SESSION['pro_alert'] = "alert('success','Cliente Agregado Correctamente!');";
      setBitacora('CLIENTES','AGREGAR CLIENTE: '.$post['nac'].$post['id'],$params,$_SESSION['pro']['usr']['user']);
    }
    header("Location: cliente.php");

  }

  if($post['action'] === 'updateClient'){
    $params = array(
      $post['nac'],
      $post['id'],
      $post['nom'],
      $post['dir'],
      $post['email'],
      $post['tel'],
      $post['nnac'],
      $post['nid'],
    );
    
    if($cliente->setCrudClient('UPDATE_CLIENT',$params) !== true ){
      $_SESSION['pro_alert'] = "alert('warning','Hubo un error al intentar Modificar');";
    }else{
      $_SESSION['pro_alert'] = "alert('success','Cliente Modificado Correctamente!');";
    }

    header("Location: cliente.php");
  }

  if($post['action'] === 'remove'){
    $params = array(
      $post['nac'],
      $post['id'],
    );
    if(is_null($cliente->checkRemoveClient($post['id']))){
      if($cliente->setCrudClient('DELETE_CLIENT',$params) !== true ){
        $_SESSION['pro_alert'] = "alert('warning','Error al intentar Borrar');";
      }else{
        $_SESSION['pro_alert'] = "alert('success','Cliente Borrado Correctamente!');";
      }
    }else{
      $_SESSION['pro_alert'] = "alert('success','Cliente Desactivado');";
    }

    header("Location: cliente.php");

  }

  if($post['action'] === 'getListOptionCli'){
    $lk = (!isset($post['lk'])) ? null : $post['lk'];
    $cliente->getListOptionCli($lk);
    exit;
  }

}

?>