import pandas as pd
import requests
import codecs
import logging
import sys
from typing import Optional

def upload_data(excel_file_path, url, latitude: Optional[float] = None, longitude: Optional[float] = None):
    # Configurar el sistema de logs
    logging.basicConfig(filename='python_script.log', level=logging.INFO)
    logging.info('Entrando en la función upload_data')

    try:
        # Lee el archivo Excel y carga los datos en un DataFrame de pandas
        df = pd.read_excel(excel_file_path)

        # Transforma el DataFrame en un diccionario de Python
        data = df.to_dict(orient='records')

        # Resto del código de la función upload_data...

    except Exception as e:
        logging.error('Error en la función upload_data: %s', str(e))

if __name__ == "__main__":
    # Verificar si se proporcionan suficientes argumentos desde Laravel
    if len(sys.argv) >= 5:
        # Obtener argumentos desde Laravel
        excel_file_path = sys.argv[1]
        url = sys.argv[2]
        latitude = float(sys.argv[3]) if sys.argv[3].lower() != "none" else None
        longitude = float(sys.argv[4]) if sys.argv[4].lower() != "none" else None

        # Llamar a la función upload_data con los argumentos proporcionados
        upload_data(excel_file_path, url, latitude, longitude)
        print("Excel file processed successfully")
    else:
        print("Falta de argumentos. Se esperan al menos 4 argumentos desde Laravel: excel_file_path, url, latitude, longitude.")
