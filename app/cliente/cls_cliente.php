
<?php

#CODE_BY_LUIZ

#CLASE MANEJADORA DE CONEXION A BD CON PDO::
#if(!isset($_SESSION))session_start();

class Cls_cliente{

private $bd;
private $query;

public function __construct(){
    include_once '../util/cls_connection.php';
    $this->bd = new Cls_connection;
    $this->query = array(
        'INSERT_CLIENT' => 'INSERT INTO pro_1cliente (sig_idcliente,id_cliente,nombre_cliente,dir_cliente,email_cliente,telf_cliente) VALUES (?,?,?,?,?,?)',
        'UPDATE_CLIENT' => 'UPDATE pro_1cliente SET sig_idcliente = ?,id_cliente = ?,nombre_cliente = ?,dir_cliente = ?,email_cliente = ?,telf_cliente = ? WHERE sig_idcliente = ? AND id_cliente = ?',
        'SELECT_CLIENT' => 'SELECT * FROM pro_1cliente WHERE sig_idcliente = ? AND id_cliente = ? LIMIT 1',
        'DELETE_CLIENT' => 'DELETE FROM pro_1cliente WHERE sig_idcliente = ? AND id_cliente = ?',
        'OPTION_CLIENT' => 'SELECT CONCAT(sig_idcliente,id_cliente) AS id, nombre_cliente AS nom FROM pro_1cliente WHERE 1',
        'LIST_CLIENT' => 'SELECT sig_idcliente AS nac, c.id_cliente AS id,nombre_cliente AS nom, dir_cliente AS dir, email_cliente AS email, telf_cliente AS tel FROM pro_1cliente c WHERE ? ORDER BY nombre_cliente ASC ',
    );
}

public function setCrudClient($type,$params){
    if($this->bd->prepare($this->query[$type],$params) ):
        return true;
    else: return null;
    endif;
}

public function checkRemoveClient($id){
    $r = $this->bd->prepare('SELECT id_cliente FROM pro_2venta WHERE id_cliente = ?',array($id));
    if($r->rowCount() > 0){
        if($this->bd->prepare('UPDATE pro_1cliente SET activo = ? WHERE id_cliente = ?',array(0,$id))):
            return true;
        else: return null;
        endif;
    }else{
        return null;        
    }
}

public function getDataClient($cond,$sid,$id){
    $rs = $this->bd->prepare($this->query['SELECT_CLIENT'],array($cond,$sid,$id));
    return ($rs->rowCount() > 0) ? $rs->fetch() : false;
}

public function getListClient(){
    $rs = $this->bd->prepareAll($this->query['LIST_CLIENT'],array(1));
    return (sizeof($rs) > 0) ? json_encode($rs): false;
}

public function getListClientAll(){
    $rs = $this->bd->prepareAll($this->query['LIST_CLIENT'],array(1));
    return (sizeof($rs) > 0) ? $rs : null;
}


public function getListOptionCli($lk){
    $this->bd = new Cls_connection;
    $rss = $this->bd->consultar("SELECT id_cliente AS id,nombre_cliente AS text FROM pro_1cliente WHERE nombre_cliente LIKE '%".$lk."%' ORDER BY nombre_cliente ASC");
    echo ($rss->rowCount() > 0) ? json_encode($rss->fetchAll()) : json_encode(`<span>No se encontraron resultados</span>`);
}


}//#END_CLASS

?>

