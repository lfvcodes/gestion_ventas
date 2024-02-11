<?php

	session_start();
	require('fpdf/fpdf.php'); 
	require('../util/cls_connection.php');
	$bd = new Cls_connection;
	$pdf = new FPDF('L','mm','letter'); 
	$pdf->SetTitle('Reporte de Ventas');

	if(isset($_GET['ve']) && !empty($_GET['ve']) ){
		
		$get = filter_var_array($_GET, FILTER_SANITIZE_STRING);
		$idvend = $_GET['ve'];

		if($idvend == 'T'):
			$vend = $bd->prepareRS('SELECT * FROM pro_1vendedor WHERE ?',array(1));
			$pdf->SetTitle('VENTAS DE TODOS LOS VENDEDORES');
		else:
			$vend = $bd->prepareRS('SELECT * FROM pro_1vendedor WHERE id_vendedor = ?',array($idvend));
			$pdf->SetTitle('VENTAS POR VENDEDOR '.$vend['nombre_vendedor']." ".$vend['apellido_vendedor']);
		endif;
		
		
		$pdf->AddPage();
		$pdf->SetFont('Arial','',10);

		$pdf->Cell(28,42,'',0,1);
		$pdf->image('../../assets/img/logo.png',12,5,60,48);
		
		$pdf->SetFont('Arial','B',11);
		$pdf->Text(180,$pdf->GetY()-6,'FECHA DESDE: '.date('d/m/Y',strtotime($get['fi']))." HASTA: ".date('d/m/Y',strtotime($get['ff'])));
		$pdf->setFillColor(255, 136, 0); 
		$pdf->SetTextColor(0,0,0);
		if($idvend == 'T'):
			$pdf->Cell(260,6,'VENTAS DE TODOS LOS VENDEDORES',1,1,'C',true);
		else:
			$pdf->Cell(260,6,'VENTAS POR VENDEDOR(A): '.strtoupper($vend['nombre_vendedor']." ".$vend['apellido_vendedor']),1,1,'C',true);
		endif;
		$pdf->setFillColor(230,230,230); 
		$pdf->SetTextColor(0,0,0);
		if($idvend == 'T'):
			$pdf->Cell(20 ,6,utf8_decode('N°'),1,0,'C',true);
			$pdf->Cell(80 ,6,utf8_decode('CLIENTE'),1,0,'C',true);
			$pdf->Cell(50 ,6,utf8_decode('VENTA / DESCRIPCIÓN'),1,0,'C',true);
			$pdf->Cell(80 ,6,'VENDEDOR(A)',1,0,'C',true);
			$pdf->Cell(30 ,6,'MONTO',1,1,'C',true);
		else:
			$pdf->Cell(25 ,6,utf8_decode('N°'),1,0,'C',true);
			$pdf->Cell(20 ,6,'FECHA',1,0,'C',true);
			$pdf->Cell(90 ,6,utf8_decode('CLIENTE'),1,0,'C',true);
			$pdf->Cell(90 ,6,utf8_decode('VENTA / DESCRIPCIÓN'),1,0,'C',true);
			$pdf->Cell(35 ,6,'MONTO',1,1,'C',true);
		endif;
		$pdf->setFillColor(256,256,256); 
		if($idvend == 'T'):
			$queryVend = 'SELECT *, (SELECT SUM(monto*cant) FROM pro_3dventa dv WHERE dv.id_venta = v.id_venta) AS monto FROM pro_2venta v JOIN pro_1cliente c ON v.id_cliente = c.id_cliente JOIN pro_1vendedor vd ON v.id_vendedor = vd.id_vendedor WHERE DATE(v.fecha_venta) >= ? AND DATE(v.fecha_venta) <= ? ORDER BY v.fecha_venta ASC';
			$rs = $bd->prepareAll($queryVend,array($get['fi'],$get['ff']));
		else:
			$queryVend = 'SELECT *, (SELECT SUM(monto*cant) FROM pro_3dventa dv WHERE dv.id_venta = v.id_venta) AS monto FROM pro_2venta v JOIN pro_1cliente c ON v.id_cliente = c.id_cliente JOIN pro_1vendedor vd ON v.id_vendedor = vd.id_vendedor WHERE DATE(v.fecha_venta) >= ? AND DATE(v.fecha_venta) <= ? AND v.id_vendedor = ? ORDER BY v.fecha_venta ASC';
			$rs = $bd->prepareAll($queryVend,array($get['fi'],$get['ff'],$idvend));
		endif;

		$fin = sizeof($rs);
		if($fin > 0){
			$totald = 0; $totalb = 0;
			$pdf->SetFont('Arial','',9);
			$totalc = $total = 0.0;
			$idx = 1;
			for ($i=0; $i < $fin; $i++) {
				if($idvend == 'T'):
					$pdf->Cell(20 ,6,$idx,1,0,'C',true);
					$pdf->Cell(80 ,6,utf8_decode(strtoupper($rs[$i]['nombre_cliente'])),1,0,'C',true);
					$pdf->Cell(50 ,6,utf8_decode(strtoupper($rs[$i]['descripcion'])),1,0,'C',true);
					$pdf->Cell(80 ,6,utf8_decode(strtoupper($rs[$i]['nombre_vendedor'])." ".strtoupper($rs[$i]['apellido_vendedor'])),1,0,'C',true);
					$pdf->Cell(30 ,6,$rs[$i]['monto'],1,1,'C',true);
				else:
					$pdf->Cell(25 ,6,$idx,1,0,'C',true);
					$pdf->Cell(20 ,6,date('d/m/Y',strtotime($rs[$i]['fecha_venta'])),1,0,'C',true);
					$pdf->Cell(90 ,6,utf8_decode(strtoupper($rs[$i]['nombre_cliente'])),1,0,'C',true);
					$pdf->Cell(90 ,6,utf8_decode(strtoupper($rs[$i]['descripcion'])),1,0,'C',true);
					$pdf->Cell(35 ,6,$rs[$i]['monto'],1,1,'C',true);
				endif;
				$idx++;
				$total+= ($rs[$i]['monto']);
			}

			$pdf->SetFont('Arial','B',11);
			$pdf->Cell(20 ,6,'Registros: '.$fin,0,0,'L');
			$pdf->Cell(210 ,6,'TOTAL VENTA(S):',0,0,'R');
			$pdf->Cell(30 ,6,number_format($total,2,",","."),0,1,'C');
			$pdf->Ln(10);
			$pdf->setFillColor(255, 136, 0); 
			$pdf->SetTextColor(0,0,0);
			if($idvend == 'T'):
				$pdf->Cell(260,6,utf8_decode('PRODUCTOS MÁS VENDIDOS'),1,1,'C',true);
			else:
				$pdf->Cell(260,6,utf8_decode('PRODUCTOS MÁS VENDIDOS POR VENDEDOR(A): '.strtoupper($vend['nombre_vendedor'])." ".strtoupper($vend['apellido_vendedor'])),1,1,'C',true);
			endif;
			$pdf->setFillColor(230,230,230); 
			$pdf->SetTextColor(0,0,0);
			$pdf->Cell(20 ,6,utf8_decode('N°'),1,0,'C',true);
			$pdf->Cell(30 ,6,utf8_decode('CÓDIGO'),1,0,'C',true);
			$pdf->Cell(120 ,6,'PRODUCTO',1,0,'C',true);
			$pdf->Cell(30 ,6,'CANT',1,0,'C',true);
			$pdf->Cell(30 ,6,'PRECIO',1,0,'C',true);
			$pdf->Cell(30 ,6,'TOTAL',1,1,'C',true);
			if($idvend == 'T'):
				$queryProd = 'SELECT p.cod_producto, p.nom_producto, p.p_base, SUM(d.cant) AS total_vendido FROM pro_3dventa d JOIN pro_2venta v ON d.id_venta = v.id_venta JOIN pro_2producto p ON d.cod_producto = p.cod_producto WHERE DATE(v.fecha_venta) >= ? AND DATE(v.fecha_venta) <= ? GROUP BY p.cod_producto ORDER BY total_vendido DESC LIMIT 5';
				$rs2 = $bd->prepare($queryProd,array($get['fi'],$get['ff']));
			else:
				$queryProd = 'SELECT p.cod_producto, p.nom_producto, p.p_base, SUM(d.cant) AS total_vendido FROM pro_3dventa d JOIN pro_2venta v ON d.id_venta = v.id_venta JOIN pro_2producto p ON d.cod_producto = p.cod_producto WHERE DATE(v.fecha_venta) >= ? AND DATE(v.fecha_venta) <= ? AND v.id_vendedor = ? GROUP BY p.cod_producto ORDER BY total_vendido DESC LIMIT 5';
				$rs2 = $bd->prepare($queryProd,array($get['fi'],$get['ff'],$idvend));
			endif;
			$pdf->SetFont('Arial','',9);
			$idx = 1;
			while($row = $rs2->fetch()):
				$pdf->Cell(20 ,6,$idx,1,0,'C',true);
				$pdf->Cell(30 ,6,$row['cod_producto'],1,0,'C',true);
				$pdf->Cell(120 ,6,$row['nom_producto'],1,0,'C',true);
				$pdf->Cell(30 ,6,$row['total_vendido'],1,0,'C',true);
				$pdf->Cell(30 ,6,$row['p_base'],1,0,'C',true);
				$pdf->Cell(30 ,6,($row['p_base'] * $row['total_vendido']),1,1,'C',true);
				$idx++;
			endwhile;


		}else{
			$pdf->SetFont('Arial','I',11);
			$pdf->Cell(260 ,6,'No se encontraron ventas en el rango del fechas solicitado',0,0,'C');
		}
		$pdf->Output();

	}else{
		echo 'Procesando...';
	}
	
?>