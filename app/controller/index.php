<?php
require_once '../util/misc.php';

class Controller
{

    private $service;
    private $modelName;
    private $HTTP_QUERY;

    public function __construct()
    {   
        $this->HTTP_QUERY = json_decode( file_get_contents( 'php://input' ), true ) ?? $_REQUEST ?? null;

        if (empty($this->HTTP_QUERY)) {
            throw new \Exception('Método no permitido', 405);
        }

        $this->modelName = sanitizeString($this->HTTP_QUERY['service']) ?? '';
        $this->service = null;
    }

    public function handleRequest()
    {
        try {

            if(!file_exists('../models/'.$this->modelName.'.php')){
                echo json_encode(["status" => 400,"messasge" => 'Servicio no encontrado']);
                throw new \Exception("Servicio no encontrado: $this->modelName", 400);
            }

            require_once '../models/'.$this->modelName.'.php';
            $this->service = new $this->modelName($this->HTTP_QUERY);

            $method = sanitizeString($this->HTTP_QUERY['task']) ?? '';

            if (!method_exists($this->service, $method)) {
                echo respondJSON(400,"Servicio no encontrado");
                throw new \Exception("Servicio no encontrado: $method", 400);
            }
            $params = $this->HTTP_QUERY['params'] ?? null;
            $result = $this->service->$method($params);

            if (is_array($result)) {
                echo respondJSON(200, 'success', $result);
            } elseif ($result === true) {
                echo respondJSON(200, 'success');
            } else {
                echo respondJSON(400, 'error');
            }
            
        } catch (\Exception $e) {
            error_log("Error en Controller: " . $e->getMessage());
        }
    }

}

$controller = new Controller;
$controller->handleRequest();
