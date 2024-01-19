import pandas as pd
import requests
import codecs
import time
import json
import logging
from typing import Optional

def upload_data(excel_file_path, url, latitude: Optional[float]=None, longitude: Optional[float]=None):
    print('Entro a upload_data')
    # logging.basicConfig(level=logging.INFO)
    # logger = logging.getLogger(__name__)
    # logger.info(f'Procesando archivo Excel: {excel_file_path}')
    # Lee el archivo Excel y carga los datos en un DataFrame de pandas
    df = pd.read_excel(excel_file_path)

    # Transforma el DataFrame en un diccionario de Python
    data = df.to_dict(orient='records')

    grouped_data = []

    for objeto in data:
        nuevo_objeto = {}
        ter_key = None
        ter_objeto = None

        for key, value in objeto.items():
            if key.startswith('TER') or key.startswith('MPS'):
                if ter_key is not None:
                    nuevo_objeto[ter_key] = ter_objeto
                ter_key = key
                ter_objeto = {}
                ter_objeto[key] = value
            elif ter_key is not None:
                ter_objeto[key] = value
            else:
                nuevo_objeto[key] = value

        nuevo_objeto[ter_key] = ter_objeto
        grouped_data.append(nuevo_objeto)

    primer_objeto = grouped_data[0]
    resultados = {}
    # url = "http://161.97.169.228:8096/api/datasets/4"

    for objeto in grouped_data[1:]:
        start_time = time.time()
        keys = [clave for clave in objeto.keys() if clave not in ["Unnamed: 0", "Unnamed: 1"]]
        c = 0
        for key in keys:
            if key not in resultados:
                resultados[key] = []
            data_keys = [replace_escape_chars(valor) for valor in primer_objeto[key].values()] + [replace_escape_chars(primer_objeto["Unnamed: 0"]), replace_escape_chars(primer_objeto["Unnamed: 1"])]
            data_values = [valor for valor in objeto[key].values()] + [objeto["Unnamed: 0"], objeto["Unnamed: 1"]]
            data = {k: v for k, v in zip(data_keys, data_values)}

            resultado = {
                "latitude": latitude,
                "longitude": longitude,
                "data": data
            }
            resultados[key].append(resultado)

            if url is not None:
                response = requests.post(url, json=resultado)
                if response.status_code == 200:
                    print("Objeto enviado con éxito \n")
                else:
                    # logger.info(f"Error al enviar el objeto: {response.status_code}, {response.text}")
                    print(f"Error al enviar el objeto: {response.status_code}, {response.text} \n")
                c += 1
                elapsed_time = time.time() - start_time
                print(f"Tiempo de ejecución de petición individual: {elapsed_time} segundos")
        elapsed_time = time.time() - start_time
        print(f"Tiempo de ejecución de bloque entero: {elapsed_time} segundos \n")
    print("Finished file: " + excel_file_path +"\n")
    # logger.info(f'Finalizado procesamiento de archivo Excel: {excel_file_path}')
    if output_json_path is not None:
        with open(output_json_path, 'w') as json_file:
            json_file.write(json.dumps(resultados, indent=4))

def replace_escape_chars(key):
    return codecs.decode(key.encode('latin-1'), 'unicode-escape')

excel_file_path = "/var/www/html/storage/app/public/datasets/b2ba10a6-9566-4b65-bbc9-559d6c84a9d2/dataFile/vPAfSfYn5Xtd2WgnQcENlPLfpZX8Q0DKW9t5dCVN.xlsx"
url = "https://cloudskin.alternatecno.es/api/datasets/50949499-58b3-43d3-8896-711b2fd52984"

upload_data(excel_file_path, url)