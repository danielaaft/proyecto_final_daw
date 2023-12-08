<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/proyecto_daniela/v1/public/assets/css/init.css">
    <title>Proceso de Verificación</title>
</head>
<body>
    <header class="header">
        <h1>Proceso de Verificación de Identidad</h1>
    </header>
    <div class="container">
        <p>Escanea el reverso de tu DNI, NIE o Pasaporte, asegurándote de que la imagen se encuentre dentro del recuadro delimitado.</p>
        <div class="imagenes">
            <figure>
                <img src="/PROYECTO_DANIELA/v1/public/assets/img/reverso.png" alt="imagen reverso">
                <figcaption>- DNI -</figcaption>
            </figure>
            <figure>
                <img src="/PROYECTO_DANIELA/v1/public/assets/img/reverso.png" alt="imagen reverso">
                <figcaption>- NIE -</figcaption>
            </figure>
            <figure>
                <img src="/PROYECTO_DANIELA/v1/public/assets/img/reverso.png" alt="imagen reverso">
                <figcaption>- PASAPORTE -</figcaption>
            </figure>
        </div>
        <a href="{{$url_continuar}}" class="accept-button">Acepto</a>
    </div>
    <footer class="footer">
        <p>Este sitio web utiliza cookies para ofrecerte una mejor experiencia. Al continuar navegando, aceptas nuestra <a href="/politica-cookies">política de cookies</a>.</p>
    </footer>
</body>
</html>
