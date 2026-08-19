
<?php
#CODE_BY_LFVCODES
class Cls_connection
{

  private $servername;
  private $username;
  private $password;
  private $dbname;
  private $pdo = '';
  private $dsnPdo = '';
  private $optionsPdo = '';

  public function __construct()
  {

    date_default_timezone_set("America/Caracas");
    // Cargar variables de entorno desde .env si existe
    $envPath = __DIR__ . '/../../.env';
    if (file_exists($envPath)) {
      $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
      foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        list($name, $value) = array_map('trim', explode('=', $line, 2));
        $_ENV[$name] = $value;
      }
      $this->servername = $_ENV['DB_HOST'];
      $this->username = $_ENV['DB_USER'];
      $this->password = $_ENV['DB_PASS'];
      $this->dbname   = $_ENV['DB_NAME'];

      if (!$this->servername || !$this->username || !$this->password || !$this->dbname) {
        die("Error: Las variables de entorno no están configuradas correctamente en el archivo .env.");
      }
    } else {
      die("Error: El archivo .env no existe o no se puede leer.");
    }
    $this->dsnPdo = "mysql:host=" . $this->servername . ";dbname=" . $this->dbname . ";charset=utf8";

    $this->optionsPdo = [
      PDO::ATTR_EMULATE_PREPARES   => false, // turn off emulation mode for "real" prepared statements
      PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, //turn on errors in the form of exceptions
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, //make the default fetch be an associative array
    ];

    try {
      $this->pdo = new PDO($this->dsnPdo, $this->username, $this->password, $this->optionsPdo);
    } catch (PDOException $e) {
      echo "Error de Conexion: " . $e->getMessage();
    }
  } #END_CONSTRUCTOR

  public function consultar($sql)
  {
    #CONSULTA RAPIDA SIN PARAMETROS
    $this->pdo = new PDO($this->dsnPdo, $this->username, $this->password, $this->optionsPdo);
    $rs = $stmt = $this->pdo->query($sql);
    $stmt = null;
    $this->pdo = null;
    return $rs;
  }

  public function prepare($sql, $data)
  {
    #CONSULTA PREPARADA STATMENTS TIPO CONSULTAR SIN FECHAR
    $this->optionsPdo = [
      PDO::ATTR_EMULATE_PREPARES   => false, // turn off emulation mode for "real" prepared statements
      PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, //turn on errors in the form of exceptions
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_BOTH, //make the default fetch be an associative array
    ];

    $this->pdo = new PDO($this->dsnPdo, $this->username, $this->password, $this->optionsPdo);
    try {
      $stmt = $this->pdo->prepare($sql);
      $stmt->execute($data);
      
      $rowCount = $stmt->rowCount();
      $rs = $stmt;
      $stmt = null;
      
      // Para UPDATE, retornar el rowCount
      if (stripos($sql, 'UPDATE') === 0) {
          return $rowCount;
      }
        
      return $rs; // Para SELECT, retornar el statement
    } catch (PDOException $e) {
      echo "\nERROR: " . $e->getMessage();
      exit;
      $this->pdo = null;
      return false;
    }
  }

  public function prepareRS($sql, $data)
  {
    #CONSULTA PREPARADA STATMENTS
    $this->pdo = new PDO($this->dsnPdo, $this->username, $this->password, $this->optionsPdo);
    try {
      $stmt = $this->pdo->prepare($sql);
      $stmt->execute($data);
      $rs = $stmt->fetch();
      #$this->pdo->query('KILL CONNECTION_ID()');
      $stmt = null;
      return $rs;
    } catch (PDOException $e) {
      echo "\nERROR: " . $e->getMessage();
      $this->pdo = null;
      return false;
    }
  }

  public function prepareAll($sql, $data)
  {
    #CONSULTA PREPARADA STATMENTS
    $this->pdo = new PDO($this->dsnPdo, $this->username, $this->password, $this->optionsPdo);
    try {
      $stmt = $this->pdo->prepare($sql);
      $stmt->execute($data);
      $rs = $stmt->fetchAll();
      $stmt = null;
      return $rs;
    } catch (PDOException $e) {
      echo "\nERROR: " . $e->getMessage();
      $this->pdo = null;
      return false;
    }
  }

  public function prepareInsert($sql, $data)
  {
    #CONSULTA PREPARADA STATMENTS
    $this->pdo = new PDO($this->dsnPdo, $this->username, $this->password, $this->optionsPdo);
    try {
      $stmt = $this->pdo->prepare($sql);
      $stmt->execute($data);
      $lastInsertId = $this->pdo->lastInsertId();
      $rs = $stmt->fetch();
      $stmt = null;
      return [
        'result' => $rs,
        'lastInsertId' => $lastInsertId
      ];
    } catch (PDOException $e) {
      echo "\nERROR: " . $e->getMessage();
      $this->pdo = null;
      return false;
    }
  }

  public function getOptionPrepare($query, $params)
  {
    $rs = $this->prepare($query, $params);
    $data = '<option value="">Elegir uno</option>';
    while ($row = $rs->fetch()):
      $data .= '<option value="' . $row['cod'] . '">' . $row['nom'] . '</option>';
    endwhile;
    return $data;
  }
} //#END_CLASS

?>

