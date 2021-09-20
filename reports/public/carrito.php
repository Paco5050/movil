<?php
// Se verifica si existe el parámetro id en la url, de lo contrario se direcciona a la página web de origen.
if (isset($_GET['id'])) {
    require('http://34.125.94.31/var/www/html/movil/helpers/report.php');
    require('http://34.125.94.31/var/www/html/movil/models/ordenes.php');

    // Se instancia el módelo Categorias para procesar los datos.
    $categoria = new Ordenes;

    // Se verifica si el parámetro es un valor correcto, de lo contrario se direcciona a la página web de origen.
    if ($categoria->setId($_GET['id'])) {
        // Se verifica si la categoría del parametro existe, de lo contrario se direcciona a la página web de origen.
        if ($rowCategoria = $categoria->readOne() ) {
            // Se instancia la clase para crear el reporte.
            $pdf = new Report;
            // Se inicia el reporte con el encabezado del documento.
            $pdf->startReport('Cliente/a: '.$rowCategoria['nombres_cliente']);
            // Se verifica si existen registros (productos) para mostrar, de lo contrario se imprime un mensaje.
            if ($dataProductos = $categoria->readOrdenes()) {
                // Se establece un color de relleno para los encabezados.
                $pdf->SetFillColor(225);
                // Se establece la fuente para los encabezados.
                $pdf->SetFont('Arial', 'B', 11);
                $pdf->Cell(34, 15, utf8_decode('No. de factura - '.$rowCategoria['id_pedido']), 0, 1, 'C');
                // Se imprimen las celdas con los encabezados.
                $pdf->Cell(48, 10, utf8_decode('Nombre producto'), 1, 0, 'C', 1);
                $pdf->Cell(46, 10, utf8_decode('Precio (US$) unitario'), 1, 0, 'C', 1);
                $pdf->Cell(46, 10, utf8_decode('Cantidad'), 1, 0, 'C', 1);
                $pdf->Cell(46, 10, utf8_decode('Subtotal'), 1, 1, 'C', 1);
                // Se establece la fuente para los datos de los productos.
                $pdf->SetFont('Arial', '', 11);
                // Se recorren los registros ($dataProductos) fila por fila ($rowProducto).
                foreach ($dataProductos as $rowCategoriass) {
                    // Se imprimen las celdas con los datos de los productos.
                    $pdf->Cell(48, 10, utf8_decode($rowCategoriass['nombre_producto']), 1, 0, 'C', 1);
                    $pdf->Cell(46, 10, utf8_decode($rowCategoriass['precio_producto']), 1, 0, 'C', 1);
                    $pdf->Cell(46, 10, utf8_decode($rowCategoriass['cantidad_producto']), 1, 0, 'C', 1);
                    $pdf->Cell(46, 10, utf8_decode($rowCategoriass['subtotal']), 1, 1, 'C', 1);
                    
                    
                }
                if ($rowCategorias = $categoria->total()) {
                    $pdf->SetFont('Arial', 'B', 15);
                    $pdf->Cell(295, 30, utf8_decode('Total de compra: $'.$rowCategorias['total']), 0, 1, 'C');
                    $pdf->Cell(280, 5, utf8_decode('Total de compra (+IVA): $'.$rowCategorias['totaliva']), 0, 1, 'C');
                }
            } 
            // Se envía el documento al navegador y se llama al método Footer()
            $pdf->Output();
        } else {
            header('location: ../../../views/dashboard/ordenes.php');
        }
    } else {
        header('location: ../../../views/dashboard/ordenes.php');
    }
} else {
    header('location: ../../../views/dashboard/ordenes.php');
}
?>
