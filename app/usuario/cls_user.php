<?php
#CODE_BY_LUIZ

class Cls_user{

private $bd;
private $query;

public function __construct(){
    include_once '../util/cls_connection.php';
    $this->bd = new Cls_connection;
    $this->query = array(
        'INSERT_USER' => 'INSERT INTO pro_2usuario (id_usuario,log_user,email,nom_usuario,psw,nivel) VALUES (?,?,?,?,?,?)',
        'UPDATE_USER' => 'UPDATE pro_2usuario SET log_usuario = ?,email = ?, nom_usuario = ?,psw = ?, nivel = ? WHERE id_usuario = ?',
        'SELECT_USER' => 'SELECT * FROM pro_2usuario WHERE id_usuario = ? LIMIT 1',
        'DELETE_USER' => 'DELETE FROM pro_2usuario WHERE id_usuario = ?',
        'OPTION_USER' => 'SELECT id_usuario AS id, nom_usuario AS nom FROM pro_2usuario WHERE 1',
        'LIST_USER' => 'SELECT id_usuario AS txtid, nom_usuario AS nom,email,nivel AS opt_nivel FROM pro_2usuario WHERE activo = ? AND email != ? AND log_user != ? ORDER BY nom_usuario ASC',
    );
}

public function setCrudUser($type,$params){
    if($this->bd->prepare($this->query[$type],$params) ):
        return true;
    else: return null;
    endif;
}

public function getDataUser($cond,$sid,$id){
  $rs = $this->bd->prepare($this->query['SELECT_USER'],array($cond,$sid,$id));
  return ($rs->rowCount() > 0) ? $rs->fetch() : false;
}

public function getListUser(){
  $me = $_SESSION['pro']['usr']['email'];
  $rs = $this->bd->prepareAll($this->query['LIST_USER'],array('S',$me,'admin'));
  return (sizeof($rs) > 0) ? json_encode($rs): false;
}

public function getOptionUser($cond){
  $rs = $this->bd->prepare($this->query['OPTION_USER'],array($cond));
  return ($rs->rowCount() > 0) ? $rs : null;
}

}//#END_CLASS

?>

