
<?php

  #CODE_BY_LUIZ

  #CLASE MANEJADORA DE CONEXION A BD CON PDO::
  #if(!isset($_SESSION))session_start();
  
  class Cls_connection{

    private $servername;
    private $username;
    private $password;
    private $dbname;
    private $pdo = '';
    private $dsnPdo = '';
    private $optionsPdo = '';

  public function __construct(){
    
    date_default_timezone_set("America/Caracas");
    $this->servername = 'localhost';
    $this->username = 'root';
	  $this->password = '12345678';
    $this->dbname = 'gestion_ventas';
    $this->dsnPdo = "mysql:host=".$this->servername.";dbname=".$this->dbname.";charset=utf8";
    
    $this->optionsPdo = [
      PDO::ATTR_EMULATE_PREPARES   => false, // turn off emulation mode for "real" prepared statements
      PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, //turn on errors in the form of exceptions
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, //make the default fetch be an associative array
    ];

    try { $this->pdo = new PDO($this->dsnPdo, $this->username, $this->password, $this->optionsPdo); }
    catch(PDOException $e){ echo "Error de Conexion: " . $e->getMessage(); }
    
  }#END_CONSTRUCTOR

  public function consultar($sql){
    #CONSULTA RAPIDA SIN PARAMETROS
    $this->pdo = new PDO($this->dsnPdo, $this->username, $this->password, $this->optionsPdo);
    $rs = $stmt = $this->pdo->query($sql);
    $stmt = null;
    $this->pdo = null;
    return $rs;
  }

  public function prepare($sql,$data){
    #CONSULTA PREPARADA STATMENTS TIPO CONSULTAR SIN FECHAR
    $this->optionsPdo = [
      PDO::ATTR_EMULATE_PREPARES   => false, // turn off emulation mode for "real" prepared statements
      PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, //turn on errors in the form of exceptions
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_BOTH, //make the default fetch be an associative array
    ];

    $this->pdo = new PDO($this->dsnPdo, $this->username, $this->password, $this->optionsPdo);
    try{
      $stmt = $this->pdo->prepare($sql);
      $stmt->execute($data);
      $rs = $stmt;
      $stmt = null;
      return $rs;
    }catch(PDOException $e){
      echo "\nERROR: ".$e->getMessage();
      $this->pdo = null;
      return false;
    }
  }

  public function prepareRS($sql,$data){
    #CONSULTA PREPARADA STATMENTS
    $this->pdo = new PDO($this->dsnPdo, $this->username, $this->password, $this->optionsPdo);
    try{
      $stmt = $this->pdo->prepare($sql);
      $stmt->execute($data);
      $rs = $stmt->fetch();
      #$this->pdo->query('KILL CONNECTION_ID()');
      $stmt = null;
      return $rs;
    }catch(PDOException $e){
      echo "\nERROR: ".$e->getMessage();
      $this->pdo = null;
      return false;
    }
  }

  public function prepareAll($sql,$data){
    #CONSULTA PREPARADA STATMENTS
    $this->pdo = new PDO($this->dsnPdo, $this->username, $this->password, $this->optionsPdo);
    try{
      $stmt = $this->pdo->prepare($sql);
      $stmt->execute($data);
      $rs = $stmt->fetchAll();
      $stmt = null;
      return $rs;
    }catch(PDOException $e){
      echo "\nERROR: ".$e->getMessage();
      $this->pdo = null;
      return false;
    }
  }

  public function prepareInsert($sql,$data){
    #CONSULTA PREPARADA STATMENTS
    $this->pdo = new PDO($this->dsnPdo, $this->username, $this->password, $this->optionsPdo);
    try{
      $stmt = $this->pdo->prepare($sql);
      $stmt->execute($data);
      $lastInsertId = $this->pdo->lastInsertId();
      $rs = $stmt->fetch();
      $stmt = null;
      return [
        'result' => $rs,
        'lastInsertId' => $lastInsertId
      ];
    }catch(PDOException $e){
      echo "\nERROR: ".$e->getMessage();
      $this->pdo = null;
      return false;
    }
  }

  public function getOptionPrepare($query,$params){
    $rs = $this->prepare($query,$params);
    $data = '<option value="">Elegir uno</option>';
    while($row = $rs->fetch()):
      $data.= '<option value="'.$row['cod'].'">'.$row['nom'].'</option>';
    endwhile;
    return $data;
  }

}//#END_CLASS

?>

