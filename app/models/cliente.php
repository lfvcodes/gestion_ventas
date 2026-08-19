
<?php
#CODE_BY_LFVCODES
class cliente
{

    private $bd;
    private $query;

    public function __construct()
    {
        include_once '../util/cls_connection.php';
        $this->bd = new Cls_connection;
        $this->query = array(
            'INSERT_CLIENT' => 'INSERT INTO pro_1cliente (sig_idcliente,id_cliente,nombre_cliente,dir_cliente,email_cliente,telf_cliente) VALUES (?,?,?,?,?,?)',
            'UPDATE_CLIENT' => 'UPDATE pro_1cliente SET sig_idcliente = ?,id_cliente = ?,nombre_cliente = ?,dir_cliente = ?,email_cliente = ?,telf_cliente = ? WHERE sig_idcliente = ? AND id_cliente = ?',
            'SELECT_CLIENT' => 'SELECT * FROM pro_1cliente WHERE sig_idcliente = ? AND id_cliente = ? LIMIT 1',
            'DELETE_CLIENT' => 'DELETE FROM pro_1cliente WHERE sig_idcliente = ? AND id_cliente = ?',
            'OPTION_CLIENT' => 'SELECT CONCAT(sig_idcliente,id_cliente) AS id, nombre_cliente AS nom FROM pro_1cliente WHERE 1',
            'LIST_CLIENT' => 'SELECT sig_idcliente AS nac, c.id_cliente AS id,nombre_cliente AS nom, dir_cliente AS dir, email_cliente AS email, telf_cliente AS tel FROM pro_1cliente c',
        );
    }

    public function insertCliente($params)
    {
        $result = $this->bd->prepare($this->query['INSERT_CLIENT'], $params);
        return $result !== false;
    }

    public function updateCliente($params)
    {
        $result = $this->bd->prepare($this->query['UPDATE_CLIENT'], $params);
        return $result !== false;
    }

    public function deleteCliente($params)
    {   
        if($this->checkRemoveClient($params[1])){
            return false;
        }

        $result = $this->bd->prepare($this->query['DELETE_CLIENT'], $params);
        return $result !== false;
    }

    public function checkRemoveClient($id)
    {
        $r = $this->bd->prepare('SELECT id_cliente FROM pro_2venta WHERE id_cliente = ?', array($id));
        if ($r->rowCount() > 0) {
            if ($this->bd->prepare('UPDATE pro_1cliente SET activo = ? WHERE id_cliente = ?', array(0, $id))):
                return true;
            else: return null;
            endif;
        } else {
            return null;
        }
    }

    public function getListCliente()
    {
        $post = $_POST;
        $sqlQuery = $this->query['LIST_CLIENT'];
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
        $ClienteData = array();
        while ($row = $rs->fetch()) {
            $ClienteRow = array();
            $ClienteRow[] = $row['nac'];
            $ClienteRow[] = $row['id'];
            $ClienteRow[] = $row['nom'];
            $ClienteRow[] = $row['dir'];
            $ClienteRow[] = $row['email'];
            $ClienteRow[] = $row['tel'];

            $ClienteRow[] = '<div class="dropdown">
            <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
              <i class="bx bx-dots-vertical-rounded bx-md"></i>
            </button>
            <div class="dropdown-menu">
              <a class="dropdown-item" rw="' . base64_encode(json_encode($row)) . '"
                onclick="editClient(this)" href="javascript:void(0);">
                <i class="bx bx-edit-alt me-1"></i> Editar 
              </a>
                <a class="dropdown-item" dl="' . $row['id'] . '" nc="' . $row['nac'] . '" 
                    onclick="deleteClient(this)" href="javascript:void(0);">
                  <i class="bx bx-trash-alt me-1"></i> Eliminar
                </a>
            </div>
          </div>';
            $ClienteData[] = $ClienteRow;
        }

        $output = array(
            "draw"  =>  intval($post["draw"]),
            "iTotalRecords"  =>   intval($numRowsTotal),
            "iTotalDisplayRecords"  =>  intval($numRowsTotal),
            "data"  =>   $ClienteData
        );
        echo json_encode($output);
        exit;
    }

    public function getListClientAll()
    {
        $rs = $this->bd->prepareAll($this->query['LIST_CLIENT'], array(1));
        return (sizeof($rs) > 0) ? $rs : null;
    }

    public function getListOptionCli($lk)
    {
        $lk = $_POST['params'] ?? null;
        $this->bd = new Cls_connection;
        $sql = "SELECT id_cliente AS id,nombre_cliente AS text FROM pro_1cliente WHERE nombre_cliente LIKE '%" . $lk . "%' ORDER BY nombre_cliente ASC";
        $rss = $this->bd->consultar($sql);
        echo ($rss->rowCount() > 0) ? json_encode($rss->fetchAll()) : json_encode(`<span>No se encontraron resultados</span>`);
        exit;
    }
}

?>

