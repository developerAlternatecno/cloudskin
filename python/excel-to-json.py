import argparse, logging, pandas as pd, requests
from typing import Optional

class ExcelDataUploader:
    def __init__(self, excel_file_path: str, url: str, latitude: Optional[float] = None, longitude: Optional[float] = None):
        self.excel_file_path = excel_file_path
        self.url = url
        self.latitude = latitude
        self.longitude = longitude

    def process_excel(self):
        df = pd.read_excel(self.excel_file_path)
        for columna in df.columns:
            if pd.api.types.is_datetime64_ns_dtype(df[columna]):
                df[columna] = df[columna].astype(str)

        data_records = df.to_dict(orient='records')
        self._send_data(data_records)

    def _send_data(self, data_records):
        try:
            payload = {
                "data": data_records,
                "latitude": self.latitude,
                "longitude": self.longitude
            }
            print(payload)
            response = requests.post(self.url, json=payload)
            response.raise_for_status()
            logging.info("Data sent successfully")
        except requests.exceptions.RequestException as e:
            logging.error(f"Error sending data: {e}")

def main():
    excel_file_path = r"C:\Users\youne\Desktop\Biyectiva\Proyectos\cloudskin\app\storage\app\public\datasets\d1dc407d-7d47-492a-b774-d13668e88f0b\dataFile\cwb8ZqSRXLNH2ml97wgDIDkoEUmoaMBuuldQNVQe.xlsx"
    url=r"http://localhost:8080/api/datasets/d1dc407d-7d47-492a-b774-d13668e88f0b"
    latitude=39.340795
    longitude=-1.925014

    # Argument parsing
    parser = argparse.ArgumentParser(description='Upload data from an Excel file to a specified URL.')
    parser.add_argument('--file', required=True, help='Path to the Excel file.')
    parser.add_argument('--url', required=True, help='URL to upload the data to.')
    parser.add_argument('--latitude', type=float, help='Optional latitude value.')
    parser.add_argument('--longitude', type=float, help='Optional longitude value.')
    args = parser.parse_args()

    uploader = ExcelDataUploader(args.file, args.url, args.latitude, args.longitude)

    #uploader = ExcelDataUploader(excel_file_path, url, latitude, longitude)
    uploader.process_excel()

if __name__ == "__main__":
    main()