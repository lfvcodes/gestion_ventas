<?php 
session_start();

if(isset($_POST) && !empty($_POST) && !empty($_POST['action'])){
  require_once 'cls_categoria.php';
  require_once '../util/misc.php';
  $categoria = new Cls_categoria;
  $post = filter_var_array($_POST, FILTER_SANITIZE_STRING);
  
  if($post['action'] === 'createFromInput'){
    echo !is_null($categoria->setNewCat(array($post['nom']))) ? $categoria->getIndexCat($post['nom']) : 'fail';
  }
  
  if($post['action'] === 'createCat'){
      
    if($categoria->setNewCat(array($post['nom'])) !== true ){
      $_SESSION['pro_alert'] = "alert('warning','Hubo un error al intentar Agregar');";
    }else{
      $_SESSION['pro_alert'] = "alert('success','Categoría Agregada Correctamente!');";
    }
    header("Location: categoria.php");
    
  }

  if($post['action'] === 'removecat'){
    if($categoria->deleteCat(array($post['cod'])) !== true ){
      $_SESSION['pro_alert'] = "alert('warning','Error al intentar Borrar');";
    }else{
      $_SESSION['pro_alert'] = "alert('success','Categoría Borrada Correctamente!');";
    }
    header("Location: categoria.php");
  }
 
  if($post['action'] === 'updatecat'){
    $params = array($post['nom'],$post['cod']);

    if($categoria->updateCat($params) !== true ){
      $_SESSION['pro_alert'] = "alert('warning','Hubo un error al intentar Modificar');";
    }else{
      $_SESSION['pro_alert'] = "alert('success','Categoría Modificada Correctamente!');";
    }

    header("Location: categoria.php");

  }

  if($post['action'] === 'getListOptionCat'){
    $lk = (!isset($post['lk'])) ? null : $post['lk'];
    $categoria->getListOptionCat($lk);
    exit;
  }
}
?>