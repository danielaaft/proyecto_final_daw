<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prueba de Vida</title>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script src="http://localhost/proyecto_daniela/v1/public/js/recognition/face-api.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>

    <style>
        body {
            background-color: #f5f5f5;
            color: #fff;
            margin: 0;
            overflow: hidden; /* Evitar el desplazamiento horizontal */
        }

        header {
            background-color: #323248; /* Color oscuro para el encabezado */
            color: #fff; /* Color claro para el texto del encabezado */
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .left-section {
            display: flex;
            align-items: center;
        }

        .right-section {
            text-align: right;
        }

        .container {
            
            margin: 20px auto;
        }

        video {
            position: absolute;
            z-index: 1;
            width: 100%;
            border: 1px solid #4caf50;
            border-radius: 8px;
        }

        canvas {
            position: relative;
            z-index: 20;
        }
        img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
        }

        .col-md-12:last-child {
            margin-top: 20px;
            font-size: 14px;
        }
        @media (min-width: 992px) {
            .container {
                max-width: 720px;
            }
        }
        @media (min-width: 1600px) {
            .container {
                max-width: 960px;
            }
        }
    </style>
</head>

<body>
    <header>
        <div class="left-section">
            <img src="/PROYECTO_DANIELA/v1/public/assets/media/logos/dis_white.png" alt="">
        </div>
        <div class="right-section">
            <h1>Prueba de vida</h1>
            <p>Selecciona la cámara:</p>
            <select id="cameraSelect"></select>
            <button id="startCamera">Acceder a la cámara</button>
        </div>
    </header>

    <div class="container">
        <div class="col-md-12" style="border:4px solid #fff;position:relative;padding:0px">
            <video onloadedmetadata="onPlay(this)" id="inputVideo" autoplay muted playsinline></video>
            <canvas id="overlay"></canvas>
            <canvas id="aux" style="display:none"></canvas>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>


    <script>
        const video = document.getElementById("inputVideo");
        const canvas = document.getElementById("overlay");
        const canvas_aux = document.getElementById("aux");

        const EXPRESSION_THRESHOLD = 0.2;
        const MODEL_URL = "{{$url}}/public/js/models";
        const SUPPORT_VOICE_MESSAGE = "Sonríe";

        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        canvas_aux.width = video.videoWidth;
        canvas_aux.height = video.videoHeight;

        var time_out;
        var it = 0;
        var previousExpressions;
        var resultado;

        (async () => {
            try {
                const stream = await navigator.mediaDevices.getUserMedia({ video: {} });
                video.srcObject = stream;
            } catch (error) {
                console.error('Error al acceder a la cámara: ', error);
            }
        })();

        async function onPlay() {
    try {
        it += 1;
        console.log(it + ' cargando modelos');
        await faceapi.loadSsdMobilenetv1Model(MODEL_URL);
        await faceapi.loadFaceLandmarkModel(MODEL_URL);
        await faceapi.loadFaceRecognitionModel(MODEL_URL);
        await faceapi.loadFaceExpressionModel(MODEL_URL);
        console.log('terminó de cargar modelos');

        const fullFaceDescriptions = await faceapi
            .detectAllFaces(video)
            .withFaceLandmarks()
            .withFaceDescriptors()
            .withFaceExpressions();

        const dims = faceapi.matchDimensions(canvas, video, true);
        const dims_aux = faceapi.matchDimensions(canvas_aux, video, true);
        const resizedResults = faceapi.resizeResults(fullFaceDescriptions, dims);

        faceapi.draw.drawDetections(canvas, resizedResults);
        faceapi.draw.drawFaceLandmarks(canvas, resizedResults);
        faceapi.draw.drawFaceExpressions(canvas, resizedResults, 0.05);
        console.log(fullFaceDescriptions.length);

        if (fullFaceDescriptions.length === 1) {
            console.log("Reconocida una sola persona");

            checkSupportVoice(SUPPORT_VOICE_MESSAGE);

            const firstFace = fullFaceDescriptions[0];

            if (it === 1) {
                console.log('vuelta uno');
                previousExpressions = firstFace.expressions;
            }

            if (it === 2) {
                console.log('vuelta dos');
                const resultado = checkExpressionChanges(firstFace.expressions);
                if (resultado) {
                    console.log('Sonrisa detectada');
                    clearTimeout(time_out);
                    captureSelfieAutomatically();
                } else {
                    it = 0;
                }
            }

            if (it > 2) {
                it = 0;
            }

            // Asignar las expresiones actuales a las expresiones previas
            previousExpressions = firstFace.expressions || {};
        } else if (fullFaceDescriptions.length >= 2) {
            alert("Se han detectado varias personas (" + fullFaceDescriptions.length + ")");
        }

        time_out = setTimeout(() => onPlay(), 100);
    } catch (error) {
        console.error('Error en onPlay: ', error);
    }
}

        function checkExpressionChanges(currentExpressions) {
            if (it === 1) {
                // No hay cambio en la primera vuelta
                return true;
            } else if (it === 2) {
                // Comparar con las expresiones previas solo después de la primera vuelta
                return Math.abs(currentExpressions["happy"] - previousExpressions["happy"]) >= EXPRESSION_THRESHOLD;
            }
            return false;
        }

        function checkSupportVoice(text) {
            if ("speechSynthesis" in window) {
                const synthesis = window.speechSynthesis;
                const message = new SpeechSynthesisUtterance(text);
                synthesis.speak(message);
                return true;
            } else {
                console.log("Tu navegador no soporta Web Speech API");
                return false;
            }
        }

        function captureSelfieAutomatically() {
            try {
                console.log('Entra a captureSelfieAutomatically');
                const ctx = canvas_aux.getContext("2d");
                ctx.drawImage(video, 0, 0, canvas_aux.width, canvas_aux.height);
                const dataURL = canvas_aux.toDataURL('image/jpeg')

                console.log('Capturando imagen:', dataURL);
                $.ajax({
                    url: '{{$url_post}}',
                    method: 'POST',
                    data: JSON.stringify({ img: dataURL }),
                    contentType: 'application/json',
                    dataType: 'json',
                    success: function (response) {
                        if (response.comparacion_caras === true) {
                            alert('Lectura correcta.  Gracias ' + response.nombres);
                        } else {
                            console.log(response);
                            console.log('La respuesta de la petición fue data = false. Capturando automáticamente...');
                            captureSelfieAutomatically();
                        }
                    },
                    error: function (error) {
                        console.error('Error en la petición POST: ', error.responseText);
                        alert('Error al procesar la prueba de vida');
                    }
                });
            } catch (error) {
                console.error('Error en captureSelfieAutomatically: ', error);
            }
        }

        window.onload = function () {
            // Puedes realizar acciones adicionales al cargar la página aquí
        };
    </script>
</body>

</html>
