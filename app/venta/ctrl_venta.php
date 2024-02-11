<?php 
ob_start();
session_start();

if(isset($_POST) && !empty($_POST) && !empty($_POST['action'])){
  include_once 'cls_venta.php';
  require_once '../util/misc.php';
  $venta = new Cls_venta;
  $post = filter_var_array($_POST, FILTER_SANITIZE_STRING);

  if($post['action'] === 'setVenta'){
    
    $mnt = (empty($post['monto']) || is_null($post['monto'])) ? 0.0 : $post['monto'];
    $end = sizeof($post['cant']);
    
    $ext_query = 'INSERT INTO pro_2venta (fecha_venta,id_cliente,id_vendedor,descripcion) VALUES (?,?,?,?)';
    
    $params = array(
      $post['freg'],
      $post['optcli'],
      $post['optvend'],
      $post['desc']
    );
  
    $venta_id = $venta->setVenta($params,$ext_query);
    $venta_id = ($venta_id < 1) ? 0 : $venta_id;
    if(is_null($venta_id)){
      echo 'false';
    }else{
      $values = array(); $params2 = array();
      $int_query = 'INSERT INTO pro_3dventa (id_venta,cod_producto,cant,monto) VALUES ';
      for ($i=0; $i < $end; $i++) { 
        $values[] = '(?,?,?,?)';
        $params2[] = $venta_id;
        $params2[] = $post['prod'][$i];
        $params2[] = $post['cant'][$i];
        $params2[] = $post['monto'][$i];
      }
      $valuesString = implode(',', $values);
      $int_query .= $valuesString;
      if(!is_null($venta->setDetVenta($params2,$int_query))){
        echo 'true';
      }else{
        echo 'error';
      }
    }
  
  }

  if($post['action'] === 'updateVenta'){
    $end = sizeof($post['cant']);
    $params = array(
      $post['freg'],
      $post['optcli'],
      $post['optvend'],
      $post['desc'],
      $post['id'],
    );
  
    if($venta->setCrudVenta($params,'UPDATE_VENTA') !== true ){
      echo 'false';
    }else{
      $values = array(); $params2 = array();
      $int_query = 'INSERT INTO pro_3dventa (id_venta,cod_producto,cant,monto) VALUES ';
      for ($i=0; $i < $end; $i++) { 
        $values[] = '(?,?,?,?)';
        $params2[] = $post['id'];
        $params2[] = $post['prod'][$i];
        $params2[] = $post['cant'][$i];
        $params2[] = $post['monto'][$i];
      }
      $valuesString = implode(',', $values);
      $int_query .= $valuesString;
      if(!is_null($venta->updateDetailVenta($params2,$int_query,$post['id']))){
        echo 'true';
      }else{
        echo 'false';
      }
    }
    exit;
  }

  if($post['action'] === 'removeVenta'){
    
    $params = array($post['id']);
    if($venta->setCrudVenta($params,'DELETE_VENTA') == true ){
      echo 'true';
    }else{
      echo 'false';
    }
    exit;
  }
  
  if($post['action'] === 'getDetailTable'){
    echo $venta->getTableDetail($post['id']);
  }

  if($post['action'] === 'getDashboard'){
    echo $venta->getDashboard();
    exit;
  }

  if($post['action'] === 'getListVenta'){
    $venta->getListVenta($post);
    exit;
  }
}
ob_end_flush();
?>