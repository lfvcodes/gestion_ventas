
<?php

#CODE_BY_LUIZ

#CLASE MANEJADORA DE CONEXION A BD CON PDO::
#if(!isset($_SESSION))session_start();

class cls_categoria{

private $bd;
private $query;

public function __construct(){
    include_once '../util/cls_connection.php';
    $this->bd = new Cls_connection;
    $this->query = array(
        'INSERT_CAT' => 'INSERT INTO pro_1categoria (nom_categoria) VALUES (?)',
        'SELECT_CAT' => 'SELECT cod_categoria,nom_categoria FROM pro_1categoria WHERE cod_categoria = ? LIMIT 1',
        'INDEX_CAT' => 'SELECT (MAX(cod_categoria) + 1) AS idx FROM pro_1categoria',
        'LIST_CAT' => 'SELECT cod_categoria AS cod, nom_categoria AS nom FROM pro_1categoria ORDER BY cod_categoria DESC',
        'UPDATE_CAT' => 'UPDATE pro_1categoria SET nom_categoria = ? WHERE cod_categoria = ?',
        'DELETE_CAT' => 'DELETE FROM pro_1categoria WHERE cod_categoria = ?',
    );
}

public function getIndexCat($nom){
    if(empty($nom) || is_null($nom)):
        $rs = $this->bd->consultar($this->query['INDEX_CAT'])->fetch();
    else:
        $rs = $this->bd->prepare($this->query['INDEX_NOM_CAT'],array($nom))->fetch();
    endif;

    echo (!is_null($rs['idx'])) ? $rs['idx'] : 1;
}

public function setNewCat($params){
    if($this->bd->prepare($this->query['INSERT_CAT'],$params)):
        return true;
    else: return null;
    endif;
}

public function updateCat($params){
    if($this->bd->prepare($this->query['UPDATE_CAT'],$params)):
        return true;
    else: return null;
    endif;
}

public function getDataCat($id){
    $rs = $this->bd->prepare($this->query['SELECT_CAT'],array($id));
    return ($rs->rowCount() > 0) ? $rs->fetch() : false;
}

public function deleteCat($params){
    #VERIFICAR SI TIENE MOVIMIENTOS ASOCIADOS ESTE CONCEPTO PRIMERO
    if($this->bd->prepare($this->query['DELETE_CAT'],$params) ):
        return true;
    else: return null;
    endif;
}

public function getListCat(){
    $rs = $this->bd->consultar($this->query['LIST_CAT']);
    return ($rs->rowCount() > 0) ? $rs->fetchAll() : false;
}

public function getListOptionCat($lk){
    $this->bd = new Cls_connection;
    $rss = $this->bd->consultar("SELECT cod_categoria AS id,nom_categoria AS text FROM pro_1categoria WHERE nom_categoria LIKE '%".$lk."%' ORDER BY nom_categoria ASC");
    echo ($rss->rowCount() > 0) ? json_encode($rss->fetchAll()) : json_encode(`<span>No se encontraron resultados, Agregar Nuevo</span>`);
}

}//#END_CLASS

?>

