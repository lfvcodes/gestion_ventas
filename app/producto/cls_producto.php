<?php
#CODE_BY_LUIZ

#CLASE MANEJADORA DE CONEXION A BD CON PDO::

class Cls_producto{

    private $bd;
    private $query;

    public function __construct(){
        include_once '../util/cls_connection.php';
        $this->bd = new Cls_connection;
        $this->query = array(
            'INSERT_PROD' => 'INSERT INTO pro_2producto (nom_producto,cod_categoria,p_base) VALUES (?,?,?)',
            'UPDATE_PROD' => 'UPDATE pro_2producto SET nom_producto = ?, cod_categoria = ?, p_base = ? WHERE cod_producto = ?',
            'SELECT_PROD' => 'SELECT FROM pro_2producto WHERE cod_producto = ? LIMIT 1',
            'VIEW_PROD' => 'SELECT * FROM pro_2producto d JOIN pro_1categoria c ON d.cod_categoria = c.cod_categoria',
            'DELETE_PROD' => 'DELETE FROM pro_2producto WHERE cod_producto = ?',
        );
    }

    public function setNewProd($params){
        if($rs = $this->bd->prepareInsert($this->query['INSERT_PROD'],$params)):
            return $rs['lastInsertId'];
        else: return null;
        endif;
    }

    public function updateProd($params){
        if($this->bd->prepare($this->query['UPDATE_PROD'],$params) ):
            return true;
        else: return null;
        endif;
    }

    public function getDataProd($id){
        $rs = $this->bd->prepare($this->query['SELECT_PROD'],array($id));
        return ($rs->rowCount() > 0) ? $rs->fetch() : false;
    }

    public function getListProduct($post){
        $sqlQuery = $this->query['VIEW_PROD'];
        if(!empty($post["search"]["value"])){
          $sqlQuery .= ' WHERE cod_producto LIKE "%'.$post["search"]["value"].'%" ';
          $sqlQuery .= ' OR nom_producto  LIKE "%'.$post["search"]["value"].'%" ';					
          $sqlQuery .= ' OR p_base LIKE "%'.$post["search"]["value"].'%" ';		
        }
    
        if(!empty($post["order"])){
          if($post['order']['0']['column'] != 0)$sqlQuery .= 'ORDER BY '.$post['order']['0']['column'].' '.$post['order']['0']['dir'].' ';
          else $sqlQuery .= ' ORDER BY cod_producto '.$post['order']['0']['dir'].' ';
        } else {
          $sqlQuery .= ' ORDER BY cod_producto DESC ';
        }
        
        if($post["length"] != -1){
          $sqlQuery .= 'LIMIT ' . $post['start'] . ', ' . $post['length'];
        }	

        $rs = $this->bd->consultar($sqlQuery);
        $numRowsTotal = $rs->rowCount();
          $productData = array();	
          while($row = $rs->fetch()) {		
            $productRow = array();		
            $productRow[] = $row['cod_producto'];
            $productRow[] = $row['nom_producto'];
            $productRow[] = $row['cod_categoria'];
            $productRow[] = $row['nom_categoria'];
            $productRow[] = $row['p_base'];
            
            $productRow[] = '<div class="dropdown">
            <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
              <i class="bx bx-dots-vertical-rounded bx-md"></i>
            </button>
            <div class="dropdown-menu">
              <a class="dropdown-item"
                  rw="'.base64_encode(json_encode($row)).'"
                onclick="editProd(this)" href="javascript:void(0);">
                <i class="bx bx-edit-alt me-1"></i> Editar 
              </a>
                <a class="dropdown-item" dl="'.$row['cod_producto'].'" onclick="delProd(this)" href="javascript:void(0);">
                  <i class="bx bx-trash-alt me-1"></i> Eliminar
                </a>
            </div>
          </div>';
            $productData[] = $productRow;
          }

        $output = array(
          "draw"	=>	intval($post["draw"]),			
          "iTotalRecords"	=> 	intval($numRowsTotal),
          "iTotalDisplayRecords"	=>  intval($numRowsTotal),
          "data"	=> 	$productData
        );
        echo json_encode($output); 
    }

    public function delProd($params){  
      if( $this->bd->prepare($this->query['DELETE_PROD'],$params) ):
          return true;
      else: return null;
      endif;
    }

    public function getOptionSelectProduct($lk){
        $this->bd = new Cls_connection;
        #$stk = '(SELECT (SELECT SUM(cant) FROM pro_3dcompra dc WHERE dc.cod_producto = d.cod_producto) - IFNULL( (SELECT SUM(cant) FROM pro_3dventa dv WHERE dv.cod_producto = d.cod_producto),0) )';
        $rss = $this->bd->consultar("SELECT d.cod_producto AS id,nom_producto AS text FROM pro_2producto d WHERE d.nom_producto LIKE '%".$lk."%' ORDER BY d.nom_producto ASC");
        echo ($rss->rowCount() > 0) ? json_encode($rss->fetchAll()) : json_encode(`<span>No se encontraron resultados</span>`);
        
    }

    public function getProductPrices($id){
        $price = $this->bd->prepareAll("SELECT p_base AS pcosto FROM pro_2producto d WHERE cod_producto = ? LIMIT 1",array($id));
        return json_encode($price);
    }

}//#END_CLASS

?>