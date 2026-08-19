
<?php
#CODE_BY_LFVCODES

class vendedor
{

    private $bd;
    private $query;

    public function __construct()
    {
        include_once '../util/cls_connection.php';
        $this->bd = new Cls_connection;
        $this->query = array(
            'INSERT_VEND' => 'INSERT INTO pro_1vendedor (sig_idvendedor,id_vendedor,nombre_vendedor,apellido_vendedor,dir_vendedor,email_vendedor,telf_vendedor) VALUES (?,?,?,?,?,?,?)',
            'UPDATE_VEND' => 'UPDATE pro_1vendedor SET sig_idvendedor = ?,id_vendedor = ?,nombre_vendedor = ?,apellido_vendedor = ?,dir_vendedor = ?,email_vendedor = ?,telf_vendedor = ? WHERE sig_idvendedor = ? AND id_vendedor = ?',
            'SELECT_VEND' => 'SELECT * FROM pro_1vendedor WHERE sig_idvendedor = ? AND id_vendedor = ? AND activo = 1 LIMIT 1',
            'DELETE_VEND' => 'DELETE FROM pro_1vendedor WHERE sig_idvendedor = ? AND id_vendedor = ?',
            'OPTION_VEND' => 'SELECT id_vendedor AS id, CONCAT(nombre_vendedor," ",apellido_vendedor) AS nom FROM pro_1vendedor WHERE ? AND activo = 1',
            'LIST_VEND' => 'SELECT sig_idvendedor AS nac,id_vendedor AS id, nombre_vendedor AS nom, apellido_vendedor AS ape, CONCAT(nombre_vendedor," ",apellido_vendedor) as vendedor,dir_vendedor AS dir, email_vendedor AS email, telf_vendedor AS tel FROM pro_1vendedor WHERE activo = 1',
        );
    }

    public function insertVendedor($params)
    {
        $result = $this->bd->prepare($this->query['INSERT_VEND'], $params);
        return $result !== false;
    }

    public function updateVendedor($params)
    {
        $result = $this->bd->prepare($this->query['UPDATE_VEND'], $params);
        return $result !== false;
    }

    public function deleteVendedor($params)
    {   
        if($this->checkRemoveVendedor($params[1])){
            return false;
        }

        $result = $this->bd->prepare($this->query['DELETE_VEND'], $params);
        return $result !== false;
    }

    public function checkRemoveVendedor($id)
    {
        $r = $this->bd->prepare('SELECT id_vendedor FROM pro_2venta WHERE id_vendedor = ?', array($id));
        if ($r->rowCount() > 0) {
            if ($this->bd->prepare('UPDATE pro_1vendedor SET activo = ? WHERE id_vendedor = ?', array(0, $id))):
                return true;
            else: return null;
            endif;
        } else {
            return null;
        }
    }

    public function getDataVend($cond, $sid, $id)
    {
        $rs = $this->bd->prepare($this->query['SELECT_VEND'], array($cond, $sid, $id));
        return ($rs->rowCount() > 0) ? $rs->fetch() : false;
    }

    public function getListVendedor()
    {
        $post = $_POST;
        $sqlQuery = $this->query['LIST_VEND'];
        if (!empty($post["search"]["value"])) {
            $sqlQuery .= ' WHERE id LIKE "%' . $post["search"]["value"] . '%" ';
            $sqlQuery .= ' OR nom  LIKE "%' . $post["search"]["value"] . '%" ';
            $sqlQuery .= ' OR email LIKE "%' . $post["search"]["value"] . '%" ';
            $sqlQuery .= ' OR tel LIKE "%' . $post["search"]["value"] . '%" ';
        }
        if (!empty($post["order"])) {
            if ($post['order']['0']['column'] != 0)
                $sqlQuery .= 'ORDER BY ' . $post['order']['0']['column'] . ' ' . $post['order']['0']['dir'] . ' ';
            else $sqlQuery .= ' ORDER BY id ' . $post['order']['0']['dir'] . ' ';
        } else {
            $sqlQuery .= ' ORDER BY id DESC ';
        }

        if ($post["length"] != -1) {
            $sqlQuery .= 'LIMIT ' . $post['start'] . ', ' . $post['length'];
        }

        $rs = $this->bd->consultar($sqlQuery);
        $numRowsTotal = $rs->rowCount();
        $vendedorData = array();
        while ($row = $rs->fetch()) {
            $vendedorRow = array();
            $vendedorRow[] = $row['nac'];
            $vendedorRow[] = $row['id'];
            $vendedorRow[] = $row['vendedor'];
            $vendedorRow[] = $row['dir'];
            $vendedorRow[] = $row['email'];
            $vendedorRow[] = $row['tel'];

            $vendedorRow[] = '<div class="dropdown">
            <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
              <i class="bx bx-dots-vertical-rounded bx-md"></i>
            </button>
            <div class="dropdown-menu">
              <a class="dropdown-item" rw="' . base64_encode(json_encode($row)) . '"
                onclick="editVend(this)" href="javascript:void(0);">
                <i class="bx bx-edit-alt me-1"></i> Editar 
              </a>
                <a class="dropdown-item" dl="' . $row['id'] . '" nc="' . $row['nac'] . '" 
                    onclick="deleteVend(this)" href="javascript:void(0);">
                  <i class="bx bx-trash-alt me-1"></i> Eliminar
                </a>
            </div>
          </div>';
            $vendedorData[] = $vendedorRow;
        }

        $output = array(
            "draw"  =>  intval($post["draw"]),
            "iTotalRecords"  =>   intval($numRowsTotal),
            "iTotalDisplayRecords"  =>  intval($numRowsTotal),
            "data"  =>   $vendedorData
        );
        echo json_encode($output);
        exit;
    }

    public function getOptionVend()
    {
        $rs = $this->bd->prepare($this->query['OPTION_VEND'], array(1));
        return ($rs->rowCount() > 0) ? $rs : null;
        exit;
    }

    public function getOptionSelectVend($lk)
    {
        $this->bd = new Cls_connection;
        $rss = $this->bd->consultar("SELECT id_vendedor AS id, CONCAT(nombre_vendedor,' ',apellido_vendedor) AS text FROM pro_1vendedor WHERE CONCAT(nombre_vendedor,' ',apellido_vendedor) LIKE '%" . $lk . "%' ORDER BY CONCAT(nombre_vendedor,' ',apellido_vendedor) ASC");
        echo ($rss->rowCount() > 0) ? json_encode($rss->fetchAll()) : json_encode(`<span>No se encontraron resultados, Agregar Nuevo</span>`);
        exit;
    }
}

?>

