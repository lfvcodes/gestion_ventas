<?php
#CODE_BY_LFVCODES
class venta
{

  private $bd;
  private $query;

  public function __construct()
  {

    include_once '../util/cls_connection.php';
    $this->bd = new Cls_connection;

    $this->query = array(
      'INSERT_VENTA' => 'INSERT INTO pro_2venta (fecha_venta, id_cliente, id_vendedor, descripcion) VALUES (?, ?, ?, ?)',
      'LIST_VENTA' => 'SELECT id_venta AS cod,fecha_venta AS fechab, DATE_FORMAT(fecha_venta, "%d/%m/%Y") AS fecha,d.id_cliente AS idcli,d.nombre_cliente AS nomcli,v.id_vendedor AS idvend, CONCAT(v.nombre_vendedor," ",v.apellido_vendedor) AS nomvend, descripcion AS descr FROM pro_2venta i JOIN pro_1cliente d ON i.id_cliente = d.id_cliente JOIN pro_1vendedor v ON i.id_vendedor = v.id_vendedor',
      'UPDATE_VENTA' => 'UPDATE pro_2venta SET fecha_venta = ?, id_cliente = ?, id_vendedor = ?, descripcion = ? WHERE id_venta = ?',
      'DELETE_DETAIL_VENTA' => 'DELETE FROM pro_3dventa WHERE id_venta = ?',
      'DELETE_VENTA' => 'DELETE FROM pro_2venta WHERE id_venta = ?',
    );
  }

  public function insertVenta($params)
  {
    $params = $this->validateVentaParams($params, false);
    $result = $this->bd->prepareInsert($this->query['INSERT_VENTA'], [
      $params['freg'],
      $params['optcli'],
      $params['optvend'],
      $params['desc']
    ]);

    if ($result === false) {
      return false;
    }

    $ventaId = (int) $result['lastInsertId'];
    return $this->saveDetails($ventaId, $params['prod'], $params['cant'], $params['monto']);
  }

    public function updateVenta($params)
    {
      $params = $this->validateVentaParams($params, true);
      $result = $this->bd->prepare($this->query['UPDATE_VENTA'], [
        $params['freg'],
        $params['optcli'],
        $params['optvend'],
        $params['desc'],
        $params['id']
      ]);

      if ($result === false) {
        return false;
      }

      if ($this->bd->prepare($this->query['DELETE_DETAIL_VENTA'], [$params['id']]) === false) {
        return false;
      }

      return $this->saveDetails((int) $params['id'], $params['prod'], $params['cant'], $params['monto']);
    }

    private function validateVentaParams($params, $requiresId)
    {
      if (!is_array($params)) {
        throw new InvalidArgumentException('Los parámetros de la venta no son válidos');
      }

      $required = ['freg', 'optcli', 'optvend', 'desc', 'prod', 'cant', 'monto'];
      if ($requiresId) {
        $required[] = 'id';
      }

      foreach ($required as $key) {
        if (!isset($params[$key]) || $params[$key] === '') {
          throw new InvalidArgumentException('Falta el campo: ' . $key);
        }
      }

      if (!is_array($params['prod']) || !is_array($params['cant']) || !is_array($params['monto']) ||
        count($params['prod']) === 0 || count($params['prod']) !== count($params['cant']) ||
        count($params['prod']) !== count($params['monto'])) {
        throw new InvalidArgumentException('El detalle de la venta no es válido');
      }

      return $params;
    }

    private function saveDetails($ventaId, $products, $quantities, $amounts)
    {
      $values = [];
      $detailParams = [];
      foreach ($products as $index => $product) {
        $values[] = '(?, ?, ?, ?)';
        $detailParams[] = $ventaId;
        $detailParams[] = $product;
        $detailParams[] = $quantities[$index];
        $detailParams[] = $amounts[$index];
      }

      $sql = 'INSERT INTO pro_3dventa (id_venta, cod_producto, cant, monto) VALUES ' . implode(', ', $values);
      return $this->bd->prepare($sql, $detailParams) !== false;
    }

    public function deleteVenta($params)
    {   
        $id = $_POST['id'];
        $result = $this->bd->prepare($this->query['DELETE_VENTA'], [$id]);
        return $result !== false;
        exit;
    }

  public function getListVenta($post)
  {
    $post = $_POST;
    $sqlQuery = $this->query['LIST_VENTA'];
    if (!empty($post["search"]["value"])) {
      $sqlQuery .= ' WHERE nom_VENTAe LIKE "%' . $post["search"]["value"] . '%" ';
      $sqlQuery .= ' OR nom_vendedor  LIKE "%' . $post["search"]["value"] . '%" ';
    }

    if (!empty($post["order"])) {
      if ($post['order']['0']['column'] != 0) $sqlQuery .= 'ORDER BY ' . $post['order']['0']['column'] . ' ' . $post['order']['0']['dir'] . ' ';
      else $sqlQuery .= ' ORDER BY id_venta ' . $post['order']['0']['dir'] . ' ';
    } else {
      $sqlQuery .= ' ORDER BY id_venta DESC ';
    }

    if ($post["length"] != -1) {
      $sqlQuery .= 'LIMIT ' . $post['start'] . ', ' . $post['length'];
    }
    
    $rs = $this->bd->consultar($sqlQuery);
    $numRowsTotal = $rs->rowCount();
    $ventaData = array();
    while ($row = $rs->fetch()) {
      $ventaRow = array();
      $ventaRow[] = $row['cod'];
      $ventaRow[] = $row['fechab'];
      $ventaRow[] = $row['fecha'];
      $ventaRow[] = $row['idcli'];
      $ventaRow[] = $row['nomcli'];
      $ventaRow[] = $row['idvend'];
      $ventaRow[] = $row['nomvend'];
      $ventaRow[] = $row['descr'];

      $ventaRow[] = '<div class="dropdown">
            <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
              <i class="bx bx-dots-vertical-rounded bx-md"></i>
            </button>
            <div class="dropdown-menu">
              <a class="dropdown-item"
                  rw="' . base64_encode(json_encode($row)) . '"
                onclick="editVenta(this)" href="javascript:void(0);">
                <i class="bx bx-edit-alt me-1"></i> Editar 
              </a>
                <a class="dropdown-item" dl="' . $row['cod'] . '" onclick="deleteVenta(this)" href="javascript:void(0);">
                  <i class="bx bx-trash-alt me-1"></i> Eliminar
                </a>
            </div>
          </div>';
      $ventaData[] = $ventaRow;
    }

    $output = array(
      "draw"  =>  intval($post["draw"]),
      "iTotalRecords"  =>   intval($numRowsTotal),
      "iTotalDisplayRecords"  =>  intval($numRowsTotal),
      "data"  =>   $ventaData
    );
    echo json_encode($output);
    exit;
  }

  public function getDetailTable($params)
  {
    $params = $_POST;
    $sql = 'SELECT * FROM pro_3dventa c JOIN pro_2producto p ON c.cod_producto = p.cod_producto WHERE id_venta = ? ORDER BY id_detalle ASC';
    $tableList = $this->bd->prepareAll($sql, array($params['id']));
    echo json_encode($tableList);
    exit;
  }

  public function getDashboard()
  {
    $v = $this->bd->prepareRS('SELECT COUNT(*) AS tventa FROM pro_2venta WHERE ?', array(1));
    $v2 = $this->bd->prepareRS('SELECT COUNT(*) AS tvhoy FROM pro_2venta WHERE DATE(fecha_venta) = ?', array(date('Y-m-d')));
    $data = array('tventa' => $v['tventa'], 'tvhoy' => $v2['tvhoy']);
    echo json_encode($data);
    exit;
  }
}
