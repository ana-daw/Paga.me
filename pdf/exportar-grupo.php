<?php

/**
 * Generador de PDF para el balance de grupo usando FPDF
 * 
 * Este script genera un PDF con el balance de un grupo utilizando la librería FPDF.
 * Recibe los datos del grupo y los balances mediante $_POST.
 */

// Incluir la librería FPDF
require('fpdf/fpdf.php');

// Verificar si se han enviado los datos necesarios
if (!isset($_POST['nombreGrupo']) || !isset($_POST['descripcionGrupo']) || !isset($_POST['balance'])) {
   die('Error: Datos insuficientes para generar el PDF');
}

// Obtener los datos del POST
$nombreGrupo = $_POST['nombreGrupo'];
$descripcionGrupo = $_POST['descripcionGrupo'];
$balances = $_POST['balance'];

// Crear una clase extendida de FPDF para personalizar encabezados y pies de página
class BalancePDF extends FPDF
{
   // Variables para almacenar el nombre del grupo y la fecha
   protected $nombreGrupo;
   protected $fecha;

   // Constructor
   function __construct($nombreGrupo)
   {
      parent::__construct();
      $this->nombreGrupo = $nombreGrupo;
      $this->fecha = date('d/m/Y H:i');
   }

   // Cabecera de página
   function Header()
   {
      // Logo o nombre de la aplicación
      $this->SetFont('Arial', 'B', 16);
      $this->SetTextColor(44, 62, 80); // Color #2c3e50 (azul oscuro)
      $this->Cell(0, 10, 'Paga.me', 0, 1, 'C');

      // Línea separadora
      $this->SetDrawColor(44, 62, 80); // Color #2c3e50
      $this->Line(10, $this->GetY(), 200, $this->GetY());
      $this->Ln(5);

      // Título del balance
      $this->SetFont('Arial', 'B', 14);
      $this->Cell(0, 10, utf8_decode('Balance del grupo: ' . $this->nombreGrupo), 0, 1, 'L');
   }

   // Pie de página
   function Footer()
   {
      // Posicionarse a 1.5 cm del final
      $this->SetY(-15);
      // Fuente y color
      $this->SetFont('Arial', 'I', 8);
      $this->SetTextColor(127, 140, 141); // Color #7f8c8d (gris)
      // Texto centrado
      $this->Cell(0, 10, utf8_decode('Documento generado por Paga.me el ' . $this->fecha), 0, 0, 'C');
      // Número de página
      $this->Cell(0, 10, utf8_decode('Página ' . $this->PageNo() . '/{nb}'), 0, 0, 'R');
   }

   // Función para crear la tabla de balances
   function BalanceTable($header, $data)
   {
      // Para que el símbolo del euro se muestre correctamente
      $euro = iconv('UTF-8', 'CP1252//TRANSLIT', '€');
      // Colores, fuente y ancho de línea
      $this->SetFillColor(52, 73, 94); // Color #34495e (azul más oscuro)
      $this->SetTextColor(255);
      $this->SetDrawColor(127, 140, 141); // Color #7f8c8d (gris)
      $this->SetLineWidth(0.3);
      $this->SetFont('Arial', 'B', 10);

      // Ancho de las columnas
      $w = array(15, 100, 35, 40);

      // Encabezado de la tabla
      for ($i = 0; $i < count($header); $i++) {
         $this->Cell($w[$i], 7, utf8_decode($header[$i]), 1, 0, 'C', true);
      }
      $this->Ln();

      // Restaurar colores y fuente
      $this->SetFillColor(224, 235, 255);
      $this->SetTextColor(0);
      $this->SetFont('Arial', '', 10);

      // Datos
      $fill = false;
      $totalPositivo = 0;
      $totalNegativo = 0;

      foreach ($data as $row) {
         // Determinar el color del balance
         if ($row[2] > 0) {
            $this->SetTextColor(46, 204, 113); // Color #2ecc71 (verde)
            $totalPositivo += $row[2];
            $estado = 'A recibir';
         } elseif ($row[2] < 0) {
            $this->SetTextColor(231, 76, 60); // Color #e74c3c (rojo)
            $totalNegativo += abs($row[2]);
            $estado = 'A pagar';
         } else {
            $this->SetTextColor(127, 140, 141); // Color #7f8c8d (gris)
            $estado = 'Saldado';
         }

         $this->Cell($w[0], 6, $row[0], 'LR', 0, 'C', $fill);

         // Restaurar color para el nombre
         $this->SetTextColor(0);
         $this->Cell($w[1], 6, utf8_decode($row[1]), 'LR', 0, 'L', $fill);

         // Color específico para el balance
         if ($row[2] > 0) {
            $this->SetTextColor(46, 204, 113);
         } elseif ($row[2] < 0) {
            $this->SetTextColor(231, 76, 60);
         } else {
            $this->SetTextColor(127, 140, 141);
         }

         $this->Cell($w[2], 6, number_format($row[2], 2) . $euro, 'LR', 0, 'R', $fill);
         $this->Cell($w[3], 6, utf8_decode($estado), 'LR', 0, 'C', $fill);
         $this->Ln();
         $fill = !$fill;

         // Restaurar color de texto
         $this->SetTextColor(0);
      }

      // Línea de cierre
      $this->Cell(array_sum($w), 0, '', 'T');

      // Devolver los totales para usarlos después
      return array($totalPositivo, $totalNegativo);
   }

   // Función para crear la tabla de resumen
   function ResumenTable($totalPositivo, $totalNegativo)
   {
      // Para que el símbolo del euro se muestre correctamente
      $euro = iconv('UTF-8', 'CP1252//TRANSLIT', '€');

      $this->Ln(10);
      $this->SetFont('Arial', 'B', 12);
      $this->Cell(0, 10, utf8_decode('Resumen del balance'), 0, 1, 'L');

      // Colores, fuente y ancho de línea
      $this->SetFillColor(52, 73, 94); // Color #34495e
      $this->SetTextColor(255);
      $this->SetDrawColor(127, 140, 141);
      $this->SetLineWidth(0.3);
      $this->SetFont('Arial', 'B', 10);

      // Encabezados
      $this->Cell(100, 7, utf8_decode('Concepto'), 1, 0, 'C', true);
      $this->Cell(50, 7, utf8_decode('Cantidad'), 1, 0, 'C', true);
      $this->Ln();

      // Restaurar colores y fuente
      $this->SetFillColor(224, 235, 255);
      $this->SetTextColor(0);
      $this->SetFont('Arial', '', 10);

      // Datos
      $fill = false;

      // Total a recibir
      $this->Cell(100, 6, utf8_decode('Total a recibir'), 1, 0, 'L', $fill);
      $this->SetTextColor(46, 204, 113); // Verde
      $this->Cell(50, 6, number_format($totalPositivo, 2) . ' '. $euro, 1, 0, 'R', $fill);
      $this->Ln();
      $fill = !$fill;

      // Total a pagar
      $this->SetTextColor(0);
      $this->Cell(100, 6, utf8_decode('Total a pagar'), 1, 0, 'L', $fill);
      $this->SetTextColor(231, 76, 60); // Rojo
      $this->Cell(50, 6, number_format($totalNegativo, 2) . $euro, 1, 0, 'R', $fill);
      $this->Ln();
   }
}

// Crear nueva instancia de PDF
$pdf = new BalancePDF($nombreGrupo);
$pdf->AliasNbPages(); // Para mostrar el número total de páginas
$pdf->AddPage();
$pdf->SetFont('Arial', '', 11);

// Descripción del grupo
$pdf->SetTextColor(127, 140, 141); // Gris
$pdf->MultiCell(0, 6, utf8_decode('Descripción: ' . $descripcionGrupo), 0, 'L');
$pdf->Ln(5);

// Preparar datos para la tabla
$header = array('ID', 'Usuario', 'Balance', 'Estado');
$data = array();

foreach ($balances as $balance) {
   $data[] = array(
      $balance['id'],
      $balance['nombre'],
      $balance['balance'],
      '' // El estado se calcula en la función BalanceTable
   );
}

// Añadir tabla de balances
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 10, 'Balance de los miembros', 0, 1, 'L');
list($totalPositivo, $totalNegativo) = $pdf->BalanceTable($header, $data);

// Añadir tabla de resumen
$pdf->ResumenTable($totalPositivo, $totalNegativo);

// Generar el PDF
$pdf->Output('I', 'balance_' . preg_replace('/[^a-zA-Z0-9]/', '_', $nombreGrupo) . '.pdf');
exit;