<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Captura del Anverso</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
    <style>
        body{
            background-color:#000;
            color:#fff;
            margin: 0;
        overflow: hidden; /* Evitar el desplazamiento horizontal */
    }

    .camera-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        height: 100vh;
    }

    canvas {
        display: block;
        width: 100%;
        height: 100%;
}

.line {
            position: absolute;
            width: 100%;
            height: 4px;
            background-color: green;
            display:none;
        }

        
.container {
    max-width: 960px;
}
        
    </style>
</head>
<body>
<div class="container">
        <div class="row">
            <div class="col-md-12">
                <img src="/PROYECTO_DANIELA/v1/public/assets/media/logos/dis_white.png" alt="">
            </div>
            <div class="col-md-12">
                <h4>Muestra el anverso de tu documento de identidad</h4>
            </div>

            <div class="col-md-12">
                <p>Selecciona la cámara:</p>
                <select id="cameraSelect"></select>
                <button id="startCamera">Acceder a la cámara</button>
            </div>

            <div class="col-md-12" style="border:4px solid #fff;position:relative;padding:0px">
                <canvas id="canvas" width="640" height="480"></canvas>
                <div class="line"></div>


            </div>
            <div class="col-md-12">
                Copyright proyecto daniela (c) {{date('Y')}}
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
    <script>
        const cameraSelect = document.getElementById('cameraSelect');
        const startCameraButton = document.getElementById('startCamera');
        const canvas = document.getElementById('canvas');
        const context = canvas.getContext('2d');
        const line = document.querySelector('.line');
        let videoStream;
        let capturingAnverso = false;

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

        async function startCamera() {
            const selectedCamera = cameraSelect.value;
            try {
                videoStream = await navigator.mediaDevices.getUserMedia({ video: { deviceId: selectedCamera } });
                const videoElement = document.createElement('video');
                videoElement.srcObject = videoStream;

                videoElement.onloadedmetadata = function () {
                    videoElement.play();
                    drawVideoOnCanvas(videoElement);
                };

                startCameraButton.style.display = 'none';
                animateLine();
                $('.line').show();
                captureAnversoAutomatically();
            } catch (error) {
                console.error('Error al acceder a la cámara: ', error);
            }
        }

        function drawVideoOnCanvas(videoElement) {
            context.drawImage(videoElement, 0, 0, canvas.width, canvas.height);
            requestAnimationFrame(() => drawVideoOnCanvas(videoElement));
        }

        function stopCamera() {
            if (videoStream) {
                videoStream.getTracks().forEach(track => track.stop());
                startCameraButton.style.display = 'block';
            }
        }

        cameraSelect.addEventListener('change', stopCamera);
        startCameraButton.addEventListener('click', startCamera);

        getCameraDevices();

        function captureAnversoAutomatically() {
            const dataURL = canvas.toDataURL('image/jpeg');
            // Realizar una petición POST en jQuery para el anverso
            $.ajax({
                url: '{{$url_post}}', 
                method: 'POST',
                data: JSON.stringify({ img: dataURL }),
                contentType: 'application/json',
                dataType: 'json',
                success: function (response) {
                    // Manejar la respuesta del servidor
                    if (response.error === false) {
                        alert('Lectura correcta.  Gracias ' + response.datos.first_name + ' vamos al siguiente paso.');
                        window.location.replace(response.url_continuar);
                    } else {
                        console.log(response);
                        // Si hay errores, intentamos de nuevo
                        console.log('La respuesta de la petición fue data = false. Capturando automáticamente...');
                       captureAnversoAutomatically(); // Llamarse a sí misma de forma síncrona
                        console.log('COmentado temporalmente. Ejecutar: captureAnversoAutomatically()');
                    }
                },
                error: function (error) {
                    console.error('Error en la petición POST: ', error.responseText);
                }
            });
        }

        window.onload = function() {
            if ('speechSynthesis' in window) {
                const synthesis = window.speechSynthesis;
                const mensaje = new SpeechSynthesisUtterance('Ahora,, Escanea el reverso de tu DNI.');
                synthesis.speak(mensaje);
            } else {
                console.log ("no se puede escuchar voz")
            }
        }

        function ajustarCanvasSize() {
    const containerWidth = canvas.parentNode.clientWidth;
    const containerHeight = canvas.parentNode.clientHeight;

    const originalAspectRatio = canvas.width / canvas.height;
    let newWidth, newHeight;

    if (containerWidth / originalAspectRatio > containerHeight) {
        // Limitado por la altura
        newHeight = containerHeight;
        newWidth = containerHeight * originalAspectRatio;
    } else {
        // Limitado por el ancho
        newWidth = containerWidth;
        newHeight = containerWidth / originalAspectRatio;
    }

    canvas.width = newWidth;
    canvas.height = newHeight;
}

ajustarCanvasSize();
window.addEventListener('resize', ajustarCanvasSize);

function animateLine() {

            let direction = 1; // 1: hacia abajo, -1: hacia arriba
            let position = 0;

            function updateLinePosition() {
                position += direction;
                if (position >= 100 || position <= 0) {
                    direction *= -1; // Cambiar la dirección cuando llega al límite
                }

                line.style.top = `${position}%`;

                requestAnimationFrame(updateLinePosition);
            }

            updateLinePosition();
        }
    </script>
</body>
</html>
