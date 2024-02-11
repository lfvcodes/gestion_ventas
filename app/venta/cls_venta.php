
<?php

class Cls_venta
{

    private $bd;
    private $query;

    public function __construct()
    {        

        $fileExists = file_exists('../util/cls_connection.php');
        if ($fileExists) {
            include_once '../util/cls_connection.php';
        } else {
            include_once './util/cls_connection.php';
        }

        $this->bd = new Cls_connection;

        $this->query = array(
            'LIST_VENTA' => 'SELECT id_venta AS cod,fecha_venta AS fechab, DATE_FORMAT(fecha_venta, "%d/%m/%Y") AS fecha,d.id_cliente AS idcli,d.nombre_cliente AS nomcli,v.id_vendedor AS idvend, CONCAT(v.nombre_vendedor," ",v.apellido_vendedor) AS nomvend, descripcion AS descr FROM pro_2venta i JOIN pro_1cliente d ON i.id_cliente = d.id_cliente JOIN pro_1vendedor v ON i.id_vendedor = v.id_vendedor',
            'UPDATE_VENTA' => 'UPDATE pro_2venta SET fecha_venta = ?, id_cliente = ?, id_vendedor = ?, descripcion = ? WHERE id_venta = ?',
            'UPDATE_VENTA_CXC' => 'UPDATE pro_2venta SET cod_factura = ?, tipo_venta = ?, fecha_venta = ?, id_cliente = ?, descripcion = ?, forma_pago = ?, tasa = ?, log_user = ? WHERE id_venta = ?',
            'DELETE_VENTA' => 'DELETE FROM pro_2venta WHERE id_venta = ?',
        );
    }

    public function setCrudventa($params, $type){
        if ($this->bd->prepare($this->query[$type], $params)) :
            return true;
        else : return null;
        endif;
    }

    public function setVenta($ext_params,$ext_query){
        if ($rs = $this->bd->prepareInsert($ext_query, $ext_params)) :
            return $rs['lastInsertId'];
        else : return null;
        endif;
    }

    public function setDetVenta($int_params,$int_query){
        if ($this->bd->prepare($int_query, $int_params)) :
            return true;
        else : return null;
        endif;
    }

		public function getListVenta($post){
			$sqlQuery = $this->query['LIST_VENTA'];
        if(!empty($post["search"]["value"])){
          $sqlQuery .= ' WHERE nom_cliente LIKE "%'.$post["search"]["value"].'%" ';
          $sqlQuery .= ' OR nom_vendedor  LIKE "%'.$post["search"]["value"].'%" ';					
        }
    
        if(!empty($post["order"])){
          if($post['order']['0']['column'] != 0)$sqlQuery .= 'ORDER BY '.$post['order']['0']['column'].' '.$post['order']['0']['dir'].' ';
          else $sqlQuery .= ' ORDER BY id_venta '.$post['order']['0']['dir'].' ';
        } else {
          $sqlQuery .= ' ORDER BY id_venta DESC ';
        }
        
        if($post["length"] != -1){
          $sqlQuery .= 'LIMIT ' . $post['start'] . ', ' . $post['length'];
        }	

        $rs = $this->bd->consultar($sqlQuery);
        $numRowsTotal = $rs->rowCount();
          $ventaData = array();	
          while($row = $rs->fetch()) {		
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
                  rw="'.base64_encode(json_encode($row)).'"
                onclick="editventa(this)" href="javascript:void(0);">
                <i class="bx bx-edit-alt me-1"></i> Editar 
              </a>
                <a class="dropdown-item" dl="'.$row['cod'].'" onclick="delventa(this)" href="javascript:void(0);">
                  <i class="bx bx-trash-alt me-1"></i> Eliminar
                </a>
            </div>
          </div>';
            $ventaData[] = $ventaRow;
          }

        $output = array(
          "draw"	=>	intval($post["draw"]),			
          "iTotalRecords"	=> 	intval($numRowsTotal),
          "iTotalDisplayRecords"	=>  intval($numRowsTotal),
          "data"	=> 	$ventaData
        );
        echo json_encode($output); 
		}

    public function getTableDetail($id){
        $tableList = $this->bd->prepareAll('SELECT * FROM pro_3dventa c JOIN pro_2producto p ON c.cod_producto = p.cod_producto WHERE id_venta = ? ORDER BY id_detalle ASC',array($id));
        echo json_encode($tableList);
    }

    public function updateDetailVenta($int_params,$int_sql,$idpago){
        if($this->bd->prepare('DELETE FROM pro_3dventa WHERE id_venta = ?',array($idpago))){
            if($this->setDetventa($int_params,$int_sql)):
                return true;
            else:
                return null;
            endif;
        }
    }

    public function getDashboard(){
      $v = $this->bd->prepareRS('SELECT COUNT(*) AS tventa FROM pro_2venta WHERE ?',array(1));
      $v2 = $this->bd->prepareRS('SELECT COUNT(*) AS tvhoy FROM pro_2venta WHERE DATE(fecha_venta) = ?',array(date('Y-m-d')));
      $data = array('tventa' => $v['tventa'],'tvhoy' => $v2['tvhoy']);
      return json_encode($data);
    }

} //#END_CLASS

?>

