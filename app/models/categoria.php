
<?php
#CODE_BY_LFVCODES
class categoria{

private $bd;
private $query;

public function __construct(){
    include_once '../util/cls_connection.php';
    $this->bd = new Cls_connection;
    $this->query = array(
        'INSERT_CAT' => 'INSERT INTO pro_1categoria (nom_categoria) VALUES (?)',
        'SELECT_CAT' => 'SELECT cod_categoria,nom_categoria FROM pro_1categoria WHERE cod_categoria = ? LIMIT 1',
        'INDEX_CAT' => 'SELECT (MAX(cod_categoria) + 1) AS idx FROM pro_1categoria',
        'LIST_CAT' => 'SELECT cod_categoria AS cod, nom_categoria AS nom FROM pro_1categoria',
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

public function insertCategoria($params){
    $result = $this->bd->prepare($this->query['INSERT_CAT'],$params);
    return $result !== false;
}

public function updateCategoria($params){
    $result = $this->bd->prepare($this->query['UPDATE_CAT'],$params);
    return $result !== false;
}

public function deleteCategoria($params){
    $result = $this->bd->prepare($this->query['DELETE_CAT'],$params);
    return $result !== false;
}

public function getDataCat($id){
    $rs = $this->bd->prepare($this->query['SELECT_CAT'],array($id));
    return ($rs->rowCount() > 0) ? $rs->fetch() : false;
}

public function getListCategoria(){
    $post = $_POST;
    $sqlQuery = $this->query['LIST_CAT'];
    if (!empty($post["search"]["value"])) {
      $sqlQuery .= ' WHERE cod_categoria LIKE "%' . $post["search"]["value"] . '%" ';
      $sqlQuery .= ' OR nom_categoria  LIKE "%' . $post["search"]["value"] . '%" ';
      $sqlQuery .= ' OR p_base LIKE "%' . $post["search"]["value"] . '%" ';
    }

    if (!empty($post["order"])) {
      if ($post['order']['0']['column'] != 0) $sqlQuery .= 'ORDER BY ' . $post['order']['0']['column'] . ' ' . $post['order']['0']['dir'] . ' ';
      else $sqlQuery .= ' ORDER BY cod_categoria ' . $post['order']['0']['dir'] . ' ';
    } else {
      $sqlQuery .= ' ORDER BY cod_categoria DESC ';
    }

    if ($post["length"] != -1) {
      $sqlQuery .= 'LIMIT ' . $post['start'] . ', ' . $post['length'];
    }

    $rs = $this->bd->consultar($sqlQuery);
    $numRowsTotal = $rs->rowCount();
    $categoriaData = array();
    while ($row = $rs->fetch()) {
      $categoriaRow = array();
      $categoriaRow[] = $row['cod'];
      $categoriaRow[] = $row['nom'];

      $categoriaRow[] = '<div class="dropdown">
            <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
              <i class="bx bx-dots-vertical-rounded bx-md"></i>
            </button>
            <div class="dropdown-menu">
              <a class="dropdown-item" rw="' . base64_encode(json_encode($row)) . '"
                onclick="editCat(this)" href="javascript:void(0);">
                <i class="bx bx-edit-alt me-1"></i> Editar 
              </a>
                <a class="dropdown-item" dl="' . $row['cod'] . '" onclick="delCat(this)" href="javascript:void(0);">
                  <i class="bx bx-trash-alt me-1"></i> Eliminar
                </a>
            </div>
          </div>';
      $categoriaData[] = $categoriaRow;
    }

    $output = array(
      "draw"  =>  intval($post["draw"]),
      "iTotalRecords"  =>   intval($numRowsTotal),
      "iTotalDisplayRecords"  =>  intval($numRowsTotal),
      "data"  =>   $categoriaData
    );
    echo json_encode($output);
    exit;
}

public function getListOptionCat($lk){
    $this->bd = new Cls_connection;
    $rss = $this->bd->consultar("SELECT cod_categoria AS id,nom_categoria AS text FROM pro_1categoria WHERE nom_categoria LIKE '%".$lk."%' ORDER BY nom_categoria ASC");
    echo ($rss->rowCount() > 0) ? json_encode($rss->fetchAll()) : json_encode(`<span>No se encontraron resultados, Agregar Nuevo</span>`);
}

}

?>

