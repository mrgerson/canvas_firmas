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
    //$filename = 'pdf/signature_' . uniqid() . '.png '; // Nombre de archivo único

    $fill = '/pdf/signature_' . uniqid() . '.jpg';
    $filename  = __DIR__ . $fill;
    $fp = fopen($filename, 'wb');
    // Verificar si se pudo abrir el archivo para escritura
    if ($fp = fopen($filename, 'wb')) {
        fwrite($fp, $unencodedData);
        // Operación de escritura exitosa, cerrar el archivo
        fclose($fp);

        // Ahora proceder con la generación del PDF y la operación de guardado
        // Crear el PDF
        $dompdf = new Dompdf();
        // Establecer el tamaño de la página (en este caso, carta)
        $dompdf->setPaper('letter', 'portrait');

        // Contenido HTML básico con la imagen
        $html = '<html>
            <body>
                <h1>Hello, World!</h1>
                <img src="'.$filename.'" alt="">
            </body>
        </html>';

        // Cargar el HTML en Dompdf
        $dompdf->loadHtml($html);

        // Opción para habilitar PHP
        $dompdf->set_option('isPhpEnabled', true);

        // Renderizar el PDF
        $dompdf->render();

        // Obtener el contenido del PDF como cadena
        $pdfContent = $dompdf->output();

        // Guardar el PDF en el servidor
        $pdfFilename = __DIR__ . '/pdf/archivo.pdf'; // Ruta donde se guardará el PDF
        if (file_put_contents($pdfFilename, $pdfContent) !== false) {
            // Si se guardó correctamente el PDF
            $response = ['success' => true, 'message' => 'PDF y firma guardados correctamente.', 'html' => $html];
        } else {
            // Si hubo un error al guardar el PDF
            $response = ['success' => false, 'message' => 'Error al guardar el PDF.'];
        }
    } else {
        // Si hubo un error al abrir el archivo para escritura
        $response = ['success' => false, 'message' => 'Error al crear el archivo de imagen.'];
    }

    // Devolver la respuesta JSON
    header('Content-Type: application/json');
    echo json_encode($response);
} else {
    echo 'Error: No se recibió ninguna imagen.';
}
