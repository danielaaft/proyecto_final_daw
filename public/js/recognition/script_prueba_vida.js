const video = document.getElementById("inputVideo");
const canvas = document.getElementById("overlay");

var time_out;
var soporte;
var previousExpressions;
var currentExpressions;
var it = 0;
var resultado;

(async () => {
  const stream = await navigator.mediaDevices.getUserMedia({ video: {} });
  video.srcObject = stream;
})();

async function onPlay() {

  const MODEL_URL = "http://localhost/proyecto_daniela/v1/public/js/models";
  it += 1;
  console.log(it + ' cargando modelos');
  await faceapi.loadSsdMobilenetv1Model(MODEL_URL);
  await faceapi.loadFaceLandmarkModel(MODEL_URL);
  await faceapi.loadFaceRecognitionModel(MODEL_URL);
  await faceapi.loadFaceExpressionModel(MODEL_URL);
  console.log('terminó de cargar modelos')

  fullFaceDescriptions = await faceapi
    .detectAllFaces(video)
    .withFaceLandmarks()
    .withFaceDescriptors()
    .withFaceExpressions();

  const dims = faceapi.matchDimensions(canvas, video, true);
  const resizedResults = faceapi.resizeResults(fullFaceDescriptions, dims);

  faceapi.draw.drawDetections(canvas, resizedResults);
  faceapi.draw.drawFaceLandmarks(canvas, resizedResults);
  faceapi.draw.drawFaceExpressions(canvas, resizedResults, 0.05);

  if (fullFaceDescriptions.length === 1) {
    console.log("Reonocida una sola persona");

      checkSupportVoice('Sonríe');

      const firstFace = fullFaceDescriptions[0];
      
      //primera vuelta se recoge la primera expresión
      if (it === 1) {
        console.log('vuelta uno')
        previousExpressions = firstFace.expressions;
      }

      //segunda vuelta, se recoge la segunda expresión
      //se comparan ambas, si hubo cambio, el resultado es correcto.
      if (it === 2) {
        console.log('vuelta dos')
        currentExpressions = firstFace.expressions;

         resultado = checkExpressionChanges(previousExpressions,currentExpressions);

         if (resultado) {
          console.log('sonrisa detectada')
          clearTimeout(time_out);
          captureSelfieAutomatically();
          //alert('ok');
         } else {
          it = 0;
         }
      }

      //si la vuelta es mayor a dos
      if(it > 2) {
        it = 0;
      }


     
      
    

  } else if (fullFaceDescriptions.length >= 2) {
    alert(
      "Se han detectado varias personas (" + fullFaceDescriptions.length + ")"
      
    );
  }

  time_out = setTimeout(() => onPlay(), 100);
}

//detecta si hubo un cambio en las dos expresiones
function checkExpressionChanges(previousExpressions, currentExpressions) {
  const expressionThreshold = 0.2;

  return (
    Math.abs(currentExpressions["happy"] - previousExpressions["happy"]) >=
    expressionThreshold
  );
}

function checkSupportVoice(text) {
  if ("speechSynthesis" in window) {
    const synthesis = window.speechSynthesis;
    const message = new SpeechSynthesisUtterance(text);
    synthesis.speak(message);
    return true;
  } else {
    console.log("tu ordenador no soporta Web Speech API");
    return false;
  }
}


function captureSelfieAutomatically() {
  console.log('entra');
  const dataURL = canvas.toDataURL('image/jpeg');
  // Realizar una petición POST en jQuery para la prueba de vida
  $.ajax({
    url: '{{$url_post}}',
    method: 'POST',
    data: JSON.stringify({ img: dataURL }),
    contentType: 'application/json',
    dataType: 'json',
    success: function (response) {
      // Manejar la respuesta del servidor
      if (response.error === false) {
        alert('Lectura correcta.  Gracias+ uuid'+response.uuid);
        //window.location.replace(response.url_continuar);
      } else {
        console.log(response);
        // Si hay errores, intentamos de nuevo
        console.log('La respuesta de la petición fue data = false. Capturando automáticamente...');
        captureSelfieAutomatically(); // Llamarse a sí misma de forma síncrona
        //console.log('COmentado temporalmente. Ejecutar: captureAnversoAutomatically()');
      }
    },
    error: function (error) {
      console.error('Error en la petición POST: ', error.responseText);
      alert('error');
    }
  });
}

window.onload = function () {



};