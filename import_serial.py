import serial
import json
from flask import Flask, jsonify
from threading import Thread

# Configuración del puerto (ajusta COM5 si es otro)
ser = serial.Serial('COM5', 115200, timeout=1)

# Variable global para guardar últimos datos
latest_data = {}

app = Flask(__name__)

@app.route("/data")
def get_data():
    return jsonify(latest_data)

def read_serial():
    global latest_data
    while True:
        try:
            line = ser.readline().decode("utf-8").strip()
            if line:
                try:
                    data = json.loads(line)
                    latest_data = data
                except json.JSONDecodeError:
                    pass  # Ignorar líneas incompletas
        except Exception as e:
            print("Error leyendo serial:", e)

if __name__ == "__main__":
    # Hilo para leer continuamente del puerto
    t = Thread(target=read_serial, daemon=True)
    t.start()
    print("Servidor web corriendo en http://127.0.0.1:5000/data")
    app.run(host="0.0.0.0", port=5000)