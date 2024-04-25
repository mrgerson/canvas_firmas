window.onload = function () {
  let canvas = document.getElementById("canvas");
  let context = canvas.getContext("2d");
  let clearButton = document.getElementById("clearButton");
  let saveButton = document.getElementById("saveButton");
  let isDrawing = false;

  //detecta cuando tengo presionado el mouse sobre la cuperficie canvas
  canvas.addEventListener("mousedown", function (e) {
    isDrawing = true;
    let x = e.clientX - canvas.offsetLeft;
    let y = e.clientY - canvas.offsetTop;
    context.beginPath();
    context.moveTo(x, y);
  });

  //detecta cuando el mouse se encuantra en movimiento dentro del canvas
  canvas.addEventListener("mousemove", function (e) {
    if (isDrawing) {
      let x = e.clientX - canvas.offsetLeft;
      let y = e.clientY - canvas.offsetTop;
      //dubujar las lineas
      context.lineTo(x, y);
      context.stroke();
    }
  });

  //evento para cuando el usuario suelta el mouse (deja de dar clik)
  canvas.addEventListener("mouseup", function () {
    isDrawing = false;
  });

  clearButton.addEventListener("click", function () {
    context.clearRect(0, 0, canvas.width, canvas.height);
  });

  saveButton.addEventListener("click", function () {
    //imagen en cadena base64
    let dataURL = canvas.toDataURL(); // Convertir canvas a imagen

    fetch("save_signature.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded",
      },
      body: "image=" + encodeURIComponent(dataURL),
    })
      .then((response) => {
        if (!response.ok) {
          throw new Error("Error en la solicitud");
        }
        //return response.text()
        return response.blob(); // Devuelve el contenido como un blob binario
      })
      .then((blob) => {

        console.log('respuesta del servidor', blob)

        // Crear un objeto URL para el blob
        const url = window.URL.createObjectURL(blob);
        // Crear un enlace <a> temporal para descargar el archivo
        const a = document.createElement("a");
        a.href = url;
        a.download = "archivo.pdf"; // Establecer el nombre del archivo
        // Simular un clic en el enlace para iniciar la descarga
        document.body.appendChild(a);
        a.click();
        // Limpiar después de la descarga
        window.URL.revokeObjectURL(url);
        document.body.removeChild(a);
      })
      .catch((error) => {
        console.error("Error:", error);
      });
  });
};
