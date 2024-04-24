<?php
if (isset($_POST['image'])) {
    $imageData = $_POST['image'];
    //para obtener solo la parte de la imagen encriptada
    $filteredData = substr($imageData, strpos($imageData, ",") + 1);
    //desencriptar la imagen que viene en base 64
    $unencodedData = base64_decode($filteredData);
    $filename = 'pdf/signature_' . uniqid() . '.png'; // Nombre de archivo único
    $fp = fopen($filename, 'wb');
    fwrite($fp, $unencodedData);
    fclose($fp);
    echo $filename; // Puedes devolver el nombre del archivo o cualquier otra respuesta que desees
} else {
    echo 'Error: No se recibió ninguna imagen.';
}
?>
