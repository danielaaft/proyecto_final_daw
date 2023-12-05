import cv2
import sys

# Verificar si se proporcionó la ruta de la imagen como argumento
if len(sys.argv) != 2:
    print("Uso: python detectar_caras.py ruta_de_la_imagen")
    sys.exit(1)

# Obtener la ruta de la imagen desde los argumentos de la línea de comandos
ruta_imagen = sys.argv[1]

# Cargar el clasificador de detección de caras pre-entrenado
face_cascade = cv2.CascadeClassifier(cv2.data.haarcascades + 'haarcascade_frontalface_default.xml')

# Cargar la imagen en escala de grises
image = cv2.imread(ruta_imagen)
gray = cv2.cvtColor(image, cv2.COLOR_BGR2GRAY)

# Detectar caras en la imagen
faces = face_cascade.detectMultiScale(gray, scaleFactor=1.1, minNeighbors=5, minSize=(120, 120))

# Verificar si se encontró al menos una cara
if len(faces) > 0:
    print("True")
else:
    print("False")
