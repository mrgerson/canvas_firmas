<?php
// Incluye el autoloader de Composer para cargar automáticamente las clases de Dompdf
require_once 'vendor/autoload.php';

use Dompdf\Dompdf;

if (isset($_POST['image'])) {
    // Guardar la imagen
    $imageData = $_POST['image'];
    // para obtener solo la parte de la imagen encriptada
    $filteredData = substr($imageData, strpos($imageData, ",") + 1);
    // desencriptar la imagen que viene en base 64
    $unencodedData = base64_decode($filteredData);

    $filename = 'pdf/signature_' . uniqid() . '.png '; // Nombre de archivo único

    // Abrir el archivo en modo escritura binaria
    $fp = fopen($filename, 'wb');
    if ($fp !== false) {
        // Escribir los datos binarios de la imagen en el archivo
        fwrite($fp, $unencodedData);
        // Cerrar el archivo después de escribir
        fclose($fp);

        $response = [
            'success' => true,
            'message' => 'Imagen guardada correctamente',
            'filename' => $filename
        ];
    } else {
        $response = [
            'success' => false,
            'message' => 'Error al abrir el archivo para escritura'
        ];
    }

    // Devolver la respuesta como JSON
    header('Content-Type: application/json');
    echo json_encode($response);


    /* // Crear el PDF
    $dompdf = new Dompdf();
    // Establecer el tamaño de la página (en este caso, carta)
    $dompdf->setPaper('letter', 'portrait');

    // Contenido HTML básico con la imagen
    $html = '<html><body><h1>Hello, World!</h1><img src="' . $filename . '" alt="Firma"></body></html>';


    // Permitir que Dompdf acceda a archivos locales
    $dompdf->set_option('isPhpEnabled', true);

    // Renderizar el PDF
    $dompdf->render();

    // Obtener el contenido del PDF como cadena
    $pdfContent = $dompdf->output();

    // Establecer los encabezados para descargar el PDF
    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="archivo.pdf"'); // Establecer el nombre del archivo PDF
    header('Content-Length: ' . strlen($pdfContent)); // Especificar la longitud del contenido

    // Enviar el PDF al navegador
    echo $pdfContent; */
} else {
    echo 'Error: No se recibió ninguna imagen.';
}
