<?php 
#CONTROLADOR DE TODOS LOS CATALOGOS
session_start();

if(isset($_POST) && !empty($_POST) && !empty($_POST['action'])){
  include 'cls_producto.php';
  require_once '../util/misc.php';

  $prod = new Cls_producto;
  $post = filter_var_array($_POST, FILTER_SANITIZE_STRING);

  if($post['action'] === 'insertProduct'){

    $params = array(
      strtoupper($post['nom_producto']),
      $post['cod_categoria'],
      $post['p_base'],
    );

    $cod_prod = $prod->setNewProd($params);
 
    if(is_null($cod_prod)){
      $_SESSION['pro_alert'] = "alert('warning','Error al intentar Guardar');";
    }else{
      $_SESSION['pro_alert'] = "alert('success','Producto Agregado Correctamente!');";
    }
  }

  if($post['action'] === 'updateProduct'){
    $params = array(
      strtoupper($post['nom_producto']),
      $post['cod_categoria'],
      $post['p_base'],
      $post['id'],
    );
    if($prod->updateProd($params) !== true ){
      $_SESSION['pro_alert'] = "alert('warning','Hubo un error al intentar Modificar');";
    }else{
      $_SESSION['pro_alert'] = "alert('success','Producto Modificado Correctamente!');";
    }
  }

  if($post['action'] === 'deleteProduct'){
    
    $params = array($post['cod_product']);

    if($prod->delProd($params) !== true ){
      $_SESSION['pro_alert'] = "alert('warning','Hubo un error al intentar Borrar Producto');";
    }else{
      $_SESSION['pro_alert'] = "alert('success','Producto Borrado Correctamente!');";
    }
  }

  if($post['action'] === 'getListProduct'){
    $prod->getListProduct($post);
    exit;
  }

  if($post['action'] === 'getListOptionProduct'){
    $lk = (!isset($post['lk'])) ? null : $post['lk'];
    $prod->getOptionSelectProduct($lk);
    exit;
  }

  if($post['action'] == 'getProductPrices'){
    echo $prod->getProductPrices($post['id']);
    exit;
  }
  
  header("Location: producto.php");

}
?>