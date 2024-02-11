
<?php

#CODE_BY_LUIZ

class Cls_vendedor{

private $bd;
private $query;

public function __construct(){
    include_once '../util/cls_connection.php';
    $this->bd = new Cls_connection;
    $this->query = array(
        'INSERT_VEND' => 'INSERT INTO pro_1vendedor (sig_idvendedor,id_vendedor,nombre_vendedor,apellido_vendedor,dir_vendedor,email_vendedor,telf_vendedor) VALUES (?,?,?,?,?,?,?)',
        'UPDATE_VEND' => 'UPDATE pro_1vendedor SET nombre_vendedor = ?,apellido_vendedor = ?,dir_vendedor = ?,email_vendedor = ?,telf_vendedor = ? WHERE sig_idvendedor = ? AND id_vendedor = ?',
        'SELECT_VEND' => 'SELECT * FROM pro_1vendedor WHERE sig_idvendedor = ? AND id_vendedor = ? AND activo = 1 LIMIT 1',
        'DELETE_VEND' => 'DELETE FROM pro_1vendedor WHERE sig_idvendedor = ? AND id_vendedor = ?',
        'OPTION_VEND' => 'SELECT id_vendedor AS id, CONCAT(nombre_vendedor," ",apellido_vendedor) AS nom FROM pro_1vendedor WHERE ? AND activo = 1',
        'LIST_VEND' => 'SELECT sig_idvendedor AS nac,id_vendedor AS id, nombre_vendedor AS nom, apellido_vendedor AS ape, CONCAT(nombre_vendedor," ",apellido_vendedor) as vendedor,dir_vendedor AS dir, email_vendedor AS email, telf_vendedor AS tel FROM pro_1vendedor WHERE ? AND activo = 1 ORDER BY CONCAT(nombre_vendedor," ",apellido_vendedor) ASC',
    );
}

public function setCrudVend($type,$params){
    if($this->bd->prepare($this->query[$type],$params) ):
        return true;
    else: return null;
    endif;
}

public function checkRemoveVend($id){
    $r = $this->bd->prepare('SELECT p.id_vendedor FROM pro_2venta v JOIN pro_1vendedor p ON v.id_vendedor = p.id_vendedor WHERE p.id_vendedor = ?',array($id));
    if($r->rowCount() > 0){
        if($this->bd->prepare('UPDATE pro_1vendedor SET activo = ? WHERE id_vendedor = ?',array(0,$id))):
            return true;
        else: return null;
        endif;
    }else{
        return null;        
    }
}

public function getDataVend($cond,$sid,$id){
    $rs = $this->bd->prepare($this->query['SELECT_VEND'],array($cond,$sid,$id));
    return ($rs->rowCount() > 0) ? $rs->fetch() : false;
}

public function getListVend(){
    $rs = $this->bd->prepareAll($this->query['LIST_VEND'],array(1));
    return (sizeof($rs) > 0) ? json_encode($rs): false;
}

public function getOptionVend(){
    $rs = $this->bd->prepare($this->query['OPTION_VEND'],array(1));
    return ($rs->rowCount() > 0) ? $rs : null;
}

public function getOptionSelectVend($lk){
    $this->bd = new Cls_connection;
    $rss = $this->bd->consultar("SELECT id_vendedor AS id, CONCAT(nombre_vendedor,' ',apellido_vendedor) AS text FROM pro_1vendedor WHERE CONCAT(nombre_vendedor,' ',apellido_vendedor) LIKE '%".$lk."%' ORDER BY CONCAT(nombre_vendedor,' ',apellido_vendedor) ASC");
    echo ($rss->rowCount() > 0) ? json_encode($rss->fetchAll()) : json_encode(`<span>No se encontraron resultados, Agregar Nuevo</span>`);
}

}//#END_CLASS

?>

