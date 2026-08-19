<?php 

function sanitizeString($str){
    return filter_var($str, FILTER_SANITIZE_SPECIAL_CHARS);
}

function respondJSON($statusCode, $message, $data = null)
{
    $response = [
        'status' => $statusCode,
        'message' => $message
    ];
    
    // Si hay datos, agregarlos a la respuesta
    if ($data !== null) {
        $response['data'] = $data;
    }
    
    // Establecer el código de estado HTTP
    http_response_code($statusCode);
    
    // Retornar el JSON
    return json_encode($response);
}

function HexToRgb($color) {
  $color = ltrim($color, '#'); // Elimina el símbolo '#' si está presente
  $rgb = [];

  if (strlen($color) == 3) {
    // Convierte el formato corto de 3 caracteres a formato completo de 6 caracteres
    $color = str_repeat(substr($color, 0, 1), 2) . str_repeat(substr($color, 1, 1), 2) . str_repeat(substr($color, 2, 1), 2);
  }

  if (strlen($color) == 6) {
    // Divide el color en componentes rojo, verde y azul
    $rgb['red'] = hexdec(substr($color, 0, 2));
    $rgb['green'] = hexdec(substr($color, 2, 2));
    $rgb['blue'] = hexdec(substr($color, 4, 2));
  }

  return $rgb;
}

function getFirstAndEndDate($mes) {
	$yearActual = date('Y');
	$fDay = new DateTime($yearActual . '-' . $mes . '-01');
	$lDay = new DateTime($fDay->format('Y-m-t'));
	return array(
			'initDate' => $fDay->format('Y-m-d'),
			'lastDate' => $lDay->format('Y-m-d')
	);
}

function alert($type,$text){
	$altype = '';
	switch ($type) {
		case 'success': $altype = 'alert-success'; break;
		case 'warning': $altype = 'alert-warning'; break;
		case 'info': $altype = 'alert-info'; break;
		default: break;
	}

	print '
		<div onclick="$(this).remove();"
		 class="mt-2 alert '.$altype.' text-dark" role="alert">
			'.$text.'
		</div>
	';
}

?>