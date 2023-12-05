import cv2
import argparse

def extract_face_features(image_path):
    # Cargar la imagen y el clasificador Haar Cascade para la detección de caras
    face_cascade = cv2.CascadeClassifier(cv2.data.haarcascades + 'haarcascade_frontalface_default.xml')
    image = cv2.imread(image_path)
    gray = cv2.cvtColor(image, cv2.COLOR_BGR2GRAY)

    # Detectar caras en la imagen
    faces = face_cascade.detectMultiScale(gray, scaleFactor=1.1, minNeighbors=5, minSize=(30, 30))

    if len(faces) > 0:
        # Devolver las coordenadas de la primera cara detectada
        x, y, w, h = faces[0]
        return gray[y:y+h, x:x+w]
    else:
        return None

def compare_images(image1_path, image2_path):
    face1 = extract_face_features(image1_path)
    face2 = extract_face_features(image2_path)

    if face1 is not None and face2 is not None:
        # Calcular el histograma de colores de ambas caras
        hist1 = cv2.calcHist([face1], [0], None, [256], [0, 256])
        hist2 = cv2.calcHist([face2], [0], None, [256], [0, 256])

        # Comparar los histogramas utilizando la distancia de Bhattacharyya
        similarity = cv2.compareHist(hist1, hist2, cv2.HISTCMP_BHATTACHARYYA)
        return similarity
    else:
        return 0.0  # Si no se detectan caras, se consideran diferentes
    
    
ap = argparse.ArgumentParser()
ap.add_argument("-i1", "--imagen1", required=True, help="path de la imagen1")
ap.add_argument("-i2", "--imagen2", required=True, help="path de la imagen2")
args = vars(ap.parse_args())

image1_path = args['imagen1']
image2_path = args['imagen2']


similarity = compare_images(image1_path, image2_path)

if similarity > 0.7:  # umbral
    print("True")
else:
    print("False")
