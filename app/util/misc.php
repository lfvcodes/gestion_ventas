<?php 

function copyFile($from,$to){	
	if (copy($from, $to)) {
			return true;
	} else {
			return false;
	}
}

function dateDifferences($fi,$ff){
	$diferencia = strtotime($ff) - strtotime($fi);
	$nd = floor($diferencia / (60 * 60 * 24));
	return $nd;
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

function getNameMonth($n){
	$tmes = '';
	switch($n){
		case '1': $tmes = 'ENERO'; break;
		case '2': $tmes = 'FEBRERO'; break;
		case '3': $tmes = 'MARZO'; break;
		case '4': $tmes = 'ABRIL'; break;
		case '5': $tmes = 'MAYO'; break;
		case '6': $tmes = 'JUNIO'; break;
		case '7': $tmes = 'JULIO'; break;
		case '8': $tmes = 'AGOSTO'; break;
		case '9': $tmes = 'SEPTIEMBRE'; break;
		case '10': $tmes = 'OCTUBRE'; break;
		case '11': $tmes = 'NOVIEMBRE'; break;
		case '12': $tmes = 'DICIEMBRE'; break;
		default: break;
	}
	return $tmes;
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

function setBitacora($modulo,$accion,$params,$usr){
	require_once 'cls_connection.php';
	$bd = new Cls_connection;
	$query = 'INSERT INTO pro_bitacora.pro_2bitacora (modulo,accion,params,log_usuario) VALUES (?,?,?,?)';
	$p = implode(",",$params);
	$bd->prepare($query,array($modulo,$accion,$p,$usr));
}

function reducirNombres($nombreCompleto) {
  $nombres = explode(" / ", $nombreCompleto); // Dividir los nombres por " / "
  
  foreach($nombres as &$nombre) {
    $partes = explode(" ", $nombre); // Dividir el nombre en partes
     
    if(count($partes) > 2) {
      $nombre = $partes[0] . " " . $partes[count($partes)-1]; // Tomar el primer nombre y el último apellido
    }
  }
  
  return implode(" / ", $nombres); // Unir los nombres reducidos con " / "
}

function smtp_mailer($to,$subject,$msg,$filePath){
	include('smtp/PHPMailerAutoload.php');
	ini_set('max_execution_time', '500'); //maximo de ejecucion 7.5 minutos por carga depende conexion

	$mail = new PHPMailer(); 
	$mail->IsSMTP(); 
	$mail->SMTPAuth = true; 
	$mail->SMTPSecure = 'tls'; 
	$mail->Host = "smtp.gmail.com";
	$mail->Port = 587; 
	$mail->IsHTML(true);
	$mail->CharSet = 'UTF-8';
	//$mail->SMTPDebug = 2; 
	$mail->Username = $_SESSION['dck']['cond']['email'];
	$mail->Password = $_SESSION['dck']['cond']['apikey'];
	$mail->SetFrom($_SESSION['dck']['cond']['email']);
	$mail->Subject = $subject;
	$mail->Body =$msg;

	foreach($to as $to_add){
		$mail->AddAddress($to_add);
	}
	
	$mail->SMTPOptions=array('ssl'=>array(
		'verify_peer'=>false,
		'verify_peer_name'=>false,
		'allow_self_signed'=>false
	));
	$fileTitle = explode("/",$filePath)[2];	
	
	if(!is_null($filePath)):
		$mail->AddAttachment($filePath,$fileTitle);
	endif;

	if(!$mail->Send()){
		echo $mail->ErrorInfo;
		return false;
	}else{
		return true;
	}
}

?>