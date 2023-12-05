<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mostrar cámara en Canvas</title>
</head>

<body>
    <div class="container">
        <h1>Muestra el reverso de tu documento de identidad</h1>
        <p>Selecciona la cámara:</p>
        <select id="cameraSelect"></select>
        <button id="startCamera">Acceder a la cámara</button>
        <canvas id="canvas" width="640" height="480"></canvas>
    </div>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>

    <script>
        const cameraSelect = document.getElementById('cameraSelect');
        const startCameraButton = document.getElementById('startCamera');
        const canvas = document.getElementById('canvas');
        const context = canvas.getContext('2d');
        let videoStream;

        // Obtener la lista de dispositivos de entrada multimedia
        async function getCameraDevices() {
            try {
                const devices = await navigator.mediaDevices.enumerateDevices();
                const cameras = devices.filter(device => device.kind === 'videoinput');

                cameras.forEach(camera => {
                    const option = document.createElement('option');
                    option.value = camera.deviceId;
                    option.text = camera.label || `Camera ${cameraSelect.length + 1}`;
                    cameraSelect.appendChild(option);
                });
            } catch (error) {
                console.error('Error al obtener los dispositivos de entrada multimedia: ', error);
            }
        }

        // Acceder a la cámara seleccionada y mostrar la transmisión de video en el canvas
        async function startCamera() {
            const selectedCamera = cameraSelect.value;
            try {
                videoStream = await navigator.mediaDevices.getUserMedia({
                    video: {
                        deviceId: selectedCamera
                    }
                });
                const videoElement = document.createElement('video');
                videoElement.srcObject = videoStream;

                // Esperar a que el video esté listo para reproducirse
                videoElement.onloadedmetadata = function() {
                    videoElement.play();
                    drawVideoOnCanvas(videoElement);
                };

                startCameraButton.style.display = 'none';

                takeAndPostPhoto();

            } catch (error) {
                console.error('Error al acceder a la cámara: ', error);
            }
        }

        // Dibujar el video en el canvas
        function drawVideoOnCanvas(videoElement) {
            context.drawImage(videoElement, 0, 0, canvas.width, canvas.height);
            requestAnimationFrame(() => drawVideoOnCanvas(videoElement));
        }

        // Detener la cámara
        function stopCamera() {
            if (videoStream) {
                videoStream.getTracks().forEach(track => track.stop());
                startCameraButton.style.display = 'block';
            }
        }

        cameraSelect.addEventListener('change', stopCamera);
        startCameraButton.addEventListener('click', startCamera);

        // Obtener la lista de cámaras al cargar la página
        getCameraDevices();

        function takeAndPostPhoto() {
            // Tomar la foto del canvas
            const dataURL = canvas.toDataURL('image/jpeg');

            
            // Realizar una petición POST en jQuery
            $.ajax({
                url: '{{$url_post}}', // Reemplaza 'TU_URL_AQUI' con la URL a la que deseas hacer la petición
                method: 'POST',
                data: JSON.stringify({
                    img: dataURL
                }),
                contentType: 'application/json',
                dataType: 'json',
                success: function(response) {

                    console.log('entra');
                    console.log(response);
                    if (response.error === false) {
                        alert('Lectura correcta.  Gracias ' + response.datos.first_name + ' vamos al siguiente paso.');
                        window.location.replace(response.url_continuar);
                    } else {
                        //si hay errores, intentamos de nuevo y podemos mostrar mensajes al usuario

                        console.log('La respuesta de la petición fue data = false. Continuando...');
                        takeAndPostPhoto(); // Llamarse a sí misma de forma síncrona
                    }
                },
                error: function(error) {

                    console.log(error.responseText);

                    console.error('Error en la petición POST: ', error);
                }
            });
        }

        window.onload = function() {
            if ('speechSynthesis' in window) {
                const synthesis = window.speechSynthesis;
                const mensaje = new SpeechSynthesisUtterance('Hola, este es un ejemplo de síntesis de voz en JavaScript.');
                synthesis.speak(mensaje);
            } else {
                console.log ("no se puede escuchar voz")
            }
        }
    </script>
</body>

</html>