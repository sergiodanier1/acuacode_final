@extends('layouts.app')

@section('content')
<div class="monitor-container">
    <div class="panel">
        <!-- Sección de Carga de Datos -->
        <div class="data-source-section">
            <h3>📁 Fuente de Datos</h3>
            <div class="source-controls">
                <div class="source-tabs">
                    <button class="source-tab active" onclick="showSource('simulacion')">🔄 Simulación</button>
                    <button class="source-tab" onclick="showSource('json')">📄 JSON</button>
                    <button class="source-tab" onclick="showSource('csv')">📊 CSV</button>
                </div>

                <!-- Panel de Simulación -->
                <div id="simulacion-panel" class="source-panel active">
                    <div class="panel-content">
                        <p>Generación automática de datos de sensores en tiempo real</p>
                        <div class="simulation-controls">
                            <button class="btn-light" onclick="iniciarSimulacion()" id="btnIniciar">▶️ Iniciar Simulación</button>
                            <button class="btn-light" onclick="detenerSimulacion()" id="btnDetener" style="display: none;">⏹️ Detener Simulación</button>
                            <button class="btn-light" onclick="resetearSimulacion()">🔄 Reiniciar</button>
                            <button class="btn-light" onclick="descargarDatos('csv')">📥 Descargar CSV</button>
                            <button class="btn-light" onclick="descargarDatos('json')">📥 Descargar JSON</button>
                        </div>
                    </div>
                </div>

                <!-- Panel de JSON -->
                <div id="json-panel" class="source-panel">
                    <div class="panel-content">
                        <p>Cargar datos desde archivo JSON</p>
                        <div class="file-controls">
                            <input type="file" id="jsonFile" accept=".json" class="file-input">
                            <button class="btn-light" onclick="cargarJSON()" id="btnCargarJSON">📁 Cargar JSON</button>
                        </div>
                        <div class="file-preview" id="jsonPreview"></div>
                    </div>
                </div>

                <!-- Panel de CSV -->
                <div id="csv-panel" class="source-panel">
                    <div class="panel-content">
                        <p>Cargar datos desde archivo CSV</p>
                        <div class="file-controls">
                            <input type="file" id="csvFile" accept=".csv" class="file-input">
                            <button class="btn-light" onclick="cargarCSV()" id="btnCargarCSV">📁 Cargar CSV</button>
                        </div>
                        <div class="file-preview" id="csvPreview"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Grid de gráficas -->
        <div class="grid-container">
            <div class="grafica-card">
                <div class="grafica-header">
                    <h3>⚡ Conductividad</h3>
                    <span class="valor-actual" id="valorConductividad">-- µS/cm</span>
                </div>
                <div class="grafica-container">
                    <canvas id="graficaConductividad"></canvas>
                </div>
            </div>

            <div class="grafica-card">
                <div class="grafica-header">
                    <h3>🧪 pH del Agua</h3>
                    <span class="valor-actual" id="valorPH">--</span>
                </div>
                <div class="grafica-container">
                    <canvas id="graficaPH"></canvas>
                </div>
            </div>

            <div class="grafica-card">
                <div class="grafica-header">
                    <h3>💨 Oxígeno Disuelto</h3>
                    <span class="valor-actual" id="valorOxigeno">-- mg/L</span>
                </div>
                <div class="grafica-container">
                    <canvas id="graficaOxigeno"></canvas>
                </div>
            </div>

            <div class="grafica-card">
                <div class="grafica-header">
                    <h3>🌡️ Temperatura del Agua</h3>
                    <span class="valor-actual" id="valorTemperatura">-- °C</span>
                </div>
                <div class="grafica-container">
                    <canvas id="graficaTemperatura"></canvas>
                </div>
            </div>
        </div>

        <!-- Estado del sistema -->
        <div class="status-row">
            <div>Estado del Monitoreo:</div>
            <div class="status-list">
                <div class="dot" id="statusDot" style="background:#ef4444"></div>
                <span id="statusText">Inactivo</span>
                <span id="dataSourceInfo" style="margin-left: 20px; color: var(--muted); font-size: 13px;"></span>
            </div>
        </div>

        <footer>
            Sistema de Monitoreo Acuapónico • Fuente de datos: <span id="currentDataSource">Simulación</span>
            • <span id="totalDatos">0</span> lecturas cargadas
        </footer>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const valoresMaximos = 15;
    const tamañoMuestra = 3;

    // Buffers para datos
    const buffers = {
        conductividad: [],
        ph: [],
        oxigeno: [],
        temperatura: []
    };

    const datos = {
        etiquetas: [],
        conductividad: [],
        ph: [],
        oxigeno: [],
        temperatura: []
    };

    let graficas = {};
    let intervaloSimulacion;
    let currentDataSource = 'simulacion';
    let datosCargados = [];

    // Función para mostrar/ocultar paneles de fuente de datos
    function showSource(source) {
        document.querySelectorAll('.source-panel').forEach(panel => {
            panel.classList.remove('active');
        });
        
        document.querySelectorAll('.source-tab').forEach(tab => {
            tab.classList.remove('active');
        });
        
        document.getElementById(`${source}-panel`).classList.add('active');
        event.target.classList.add('active');
        
        currentDataSource = source;
        updateDataSourceInfo();
    }

    // Actualizar información de fuente de datos
    function updateDataSourceInfo() {
        const sourceNames = {
            'simulacion': 'Simulación en Tiempo Real',
            'json': 'Archivo JSON',
            'csv': 'Archivo CSV'
        };
        
        document.getElementById('currentDataSource').textContent = sourceNames[currentDataSource];
        document.getElementById('dataSourceInfo').textContent = `Fuente: ${sourceNames[currentDataSource]}`;
        document.getElementById('totalDatos').textContent = datos.etiquetas.length;
    }

    // Cargar datos desde JSON - MEJORADO
    function cargarJSON() {
        const fileInput = document.getElementById('jsonFile');
        const file = fileInput.files[0];
        
        if (!file) {
            alert('Por favor selecciona un archivo JSON');
            return;
        }

        const reader = new FileReader();
        reader.onload = function(e) {
            try {
                const jsonData = JSON.parse(e.target.result);
                const procesado = procesarDatosExternos(jsonData);
                
                if (procesado) {
                    document.getElementById('jsonPreview').innerHTML = 
                        `<div class="preview-success">✅ Archivo cargado correctamente</div>
                         <div style="margin-top: 10px; font-size: 0.85rem; color: var(--muted);">
                            <strong>📊 Registros procesados:</strong> ${datosCargados.length}
                         </div>`;
                } else {
                    throw new Error('No se pudieron procesar los datos del archivo JSON');
                }
            } catch (error) {
                document.getElementById('jsonPreview').innerHTML = 
                    `<div class="preview-error">❌ Error al cargar el archivo: ${error.message}</div>`;
                console.error('Error JSON:', error);
            }
        };
        reader.readAsText(file);
    }

    // Cargar datos desde CSV - MEJORADO
    function cargarCSV() {
        const fileInput = document.getElementById('csvFile');
        const file = fileInput.files[0];
        
        if (!file) {
            alert('Por favor selecciona un archivo CSV');
            return;
        }

        const reader = new FileReader();
        reader.onload = function(e) {
            try {
                const csvData = e.target.result;
                const jsonData = convertirCSVaJSON(csvData);
                
                if (jsonData.length === 0) {
                    throw new Error('El archivo CSV está vacío o no tiene el formato correcto');
                }
                
                const procesado = procesarDatosExternos(jsonData);
                
                if (procesado) {
                    document.getElementById('csvPreview').innerHTML = 
                        `<div class="preview-success">✅ Archivo CSV cargado correctamente</div>
                         <div style="margin-top: 10px; font-size: 0.85rem; color: var(--muted);">
                            <strong>📊 Registros procesados:</strong> ${datosCargados.length}
                         </div>`;
                } else {
                    throw new Error('No se pudieron procesar los datos del archivo CSV');
                }
            } catch (error) {
                document.getElementById('csvPreview').innerHTML = 
                    `<div class="preview-error">❌ Error al cargar el archivo CSV: ${error.message}</div>`;
                console.error('Error CSV:', error);
            }
        };
        reader.readAsText(file);
    }

    // Convertir CSV a JSON - MEJORADO
    function convertirCSVaJSON(csv) {
        const lines = csv.split('\n').filter(line => line.trim());
        
        if (lines.length < 2) {
            throw new Error('El archivo CSV debe tener al menos una fila de encabezados y una fila de datos');
        }
        
        const headers = lines[0].split(',').map(h => h.trim().toLowerCase());
        
        // Verificar que tenga las columnas necesarias
        const columnasRequeridas = ['conductividad', 'ph', 'oxigeno', 'temperatura'];
        const columnasEncontradas = columnasRequeridas.filter(col => headers.includes(col));
        
        if (columnasEncontradas.length === 0) {
            throw new Error(`El CSV debe contener al menos una de estas columnas: ${columnasRequeridas.join(', ')}`);
        }

        const datos = [];
        
        for (let i = 1; i < lines.length; i++) {
            const line = lines[i].trim();
            if (!line) continue;
            
            const values = line.split(',').map(v => v.trim());
            const obj = {};
            
            headers.forEach((header, index) => {
                if (values[index] && values[index] !== '') {
                    obj[header] = values[index];
                }
            });
            
            // Solo agregar si tiene datos válidos
            if (Object.keys(obj).length > 0) {
                datos.push(obj);
            }
        }
        
        return datos;
    }

    // Procesar datos externos (JSON, CSV) - MEJORADO
    function procesarDatosExternos(data) {
        if (intervaloSimulacion) {
            detenerSimulacion();
        }

        resetearDatos();
        datosCargados = [];

        let registrosProcesados = 0;

        // Procesar según el formato de datos
        if (Array.isArray(data)) {
            // Datos en formato array (CSV o JSON simple)
            data.forEach((item, index) => {
                const lectura = extraerLectura(item);
                if (lectura && esLecturaValida(lectura)) {
                    agregarDatoExterno(lectura, index);
                    datosCargados.push(lectura);
                    registrosProcesados++;
                }
            });
        } else if (data.feeds && Array.isArray(data.feeds)) {
            // Formato ThingSpeak
            data.feeds.forEach((feed, index) => {
                const lectura = {
                    conductividad: parseFloat(feed.field1 || 0),
                    ph: parseFloat(feed.field2 || 0),
                    oxigeno: parseFloat(feed.field3 || 0),
                    temperatura: parseFloat(feed.field4 || 0),
                    timestamp: feed.created_at || new Date().toISOString()
                };
                
                if (esLecturaValida(lectura)) {
                    agregarDatoExterno(lectura, index);
                    datosCargados.push(lectura);
                    registrosProcesados++;
                }
            });
        }

        if (registrosProcesados > 0) {
            actualizarGraficas();
            actualizarEstado('Datos Externos');
            updateDataSourceInfo();
            return true;
        } else {
            throw new Error('No se encontraron datos válidos en el archivo');
        }
    }

    // Verificar si una lectura es válida
    function esLecturaValida(lectura) {
        return !isNaN(lectura.conductividad) && !isNaN(lectura.ph) && 
               !isNaN(lectura.oxigeno) && !isNaN(lectura.temperatura) &&
               lectura.conductividad > 0 && lectura.ph > 0;
    }

    // Extraer lectura de diferentes formatos de datos - MEJORADO
    function extraerLectura(item) {
        const lectura = {
            conductividad: 0,
            ph: 0,
            oxigeno: 0,
            temperatura: 0,
            timestamp: item.timestamp || item.created_at || new Date().toISOString()
        };

        // Buscar en diferentes nombres de columnas
        if (item.conductividad !== undefined) {
            lectura.conductividad = parseFloat(item.conductividad) || 0;
        } else if (item.field1 !== undefined) {
            lectura.conductividad = parseFloat(item.field1) || 0;
        }

        if (item.ph !== undefined) {
            lectura.ph = parseFloat(item.ph) || 0;
        } else if (item.field2 !== undefined) {
            lectura.ph = parseFloat(item.field2) || 0;
        }

        if (item.oxigeno !== undefined) {
            lectura.oxigeno = parseFloat(item.oxigeno) || 0;
        } else if (item.oxigeno_disuelto !== undefined) {
            lectura.oxigeno = parseFloat(item.oxigeno_disuelto) || 0;
        } else if (item.field3 !== undefined) {
            lectura.oxigeno = parseFloat(item.field3) || 0;
        }

        if (item.temperatura !== undefined) {
            lectura.temperatura = parseFloat(item.temperatura) || 0;
        } else if (item.temp !== undefined) {
            lectura.temperatura = parseFloat(item.temp) || 0;
        } else if (item.field4 !== undefined) {
            lectura.temperatura = parseFloat(item.field4) || 0;
        }

        return lectura;
    }

    // Agregar dato externo al gráfico - MEJORADO
    function agregarDatoExterno(lectura, index) {
        const timestamp = lectura.timestamp ? 
            new Date(lectura.timestamp).toLocaleTimeString() : 
            `Dato ${index + 1}`;
        
        datos.etiquetas.push(timestamp);
        datos.conductividad.push(lectura.conductividad);
        datos.ph.push(lectura.ph);
        datos.oxigeno.push(lectura.oxigeno);
        datos.temperatura.push(lectura.temperatura);

        // Mantener solo los últimos valores
        if (datos.etiquetas.length > valoresMaximos) {
            datos.etiquetas.shift();
            datos.conductividad.shift();
            datos.ph.shift();
            datos.oxigeno.shift();
            datos.temperatura.shift();
        }

        // Actualizar último valor mostrado
        if (index === datos.etiquetas.length - 1) {
            document.getElementById('valorConductividad').textContent = lectura.conductividad.toFixed(1) + ' µS/cm';
            document.getElementById('valorPH').textContent = lectura.ph.toFixed(2);
            document.getElementById('valorOxigeno').textContent = lectura.oxigeno.toFixed(1) + ' mg/L';
            document.getElementById('valorTemperatura').textContent = lectura.temperatura.toFixed(1) + ' °C';
        }
    }

    // Actualizar gráficos con datos externos
    function actualizarGraficas() {
        if (graficas.conductividad && datos.conductividad.length > 0) {
            graficas.conductividad.data.labels = datos.etiquetas;
            graficas.conductividad.data.datasets[0].data = datos.conductividad;
            graficas.conductividad.update();
        }

        if (graficas.ph && datos.ph.length > 0) {
            graficas.ph.data.labels = datos.etiquetas;
            graficas.ph.data.datasets[0].data = datos.ph;
            graficas.ph.update();
        }

        if (graficas.oxigeno && datos.oxigeno.length > 0) {
            graficas.oxigeno.data.labels = datos.etiquetas;
            graficas.oxigeno.data.datasets[0].data = datos.oxigeno;
            graficas.oxigeno.update();
        }

        if (graficas.temperatura && datos.temperatura.length > 0) {
            graficas.temperatura.data.labels = datos.etiquetas;
            graficas.temperatura.data.datasets[0].data = datos.temperatura;
            graficas.temperatura.update();
        }
    }

    // Descargar datos en CSV o JSON
    function descargarDatos(formato) {
        let contenido = '';
        let nombreArchivo = '';
        let tipoMIME = '';

        if (formato === 'csv') {
            // Crear CSV
            contenido = 'timestamp,conductividad,ph,oxigeno,temperatura\n';
            datos.etiquetas.forEach((etiqueta, index) => {
                contenido += `${etiqueta},${datos.conductividad[index]},${datos.ph[index]},${datos.oxigeno[index]},${datos.temperatura[index]}\n`;
            });
            nombreArchivo = `datos_acuaponia_${new Date().toISOString().split('T')[0]}.csv`;
            tipoMIME = 'text/csv';
        } else if (formato === 'json') {
            // Crear JSON
            const datosJSON = datos.etiquetas.map((etiqueta, index) => ({
                timestamp: etiqueta,
                conductividad: datos.conductividad[index],
                ph: datos.ph[index],
                oxigeno: datos.oxigeno[index],
                temperatura: datos.temperatura[index]
            }));
            contenido = JSON.stringify(datosJSON, null, 2);
            nombreArchivo = `datos_acuaponia_${new Date().toISOString().split('T')[0]}.json`;
            tipoMIME = 'application/json';
        }

        // Crear y descargar archivo
        const blob = new Blob([contenido], { type: tipoMIME });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = nombreArchivo;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        URL.revokeObjectURL(url);
    }

    // Crear gráficas
    function crearGrafica(idCanvas, etiqueta, color) {
        const ctx = document.getElementById(idCanvas).getContext('2d');
        return new Chart(ctx, {
            type: 'line',
            data: {
                labels: datos.etiquetas,
                datasets: [{
                    label: etiqueta,
                    data: [],
                    borderColor: color,
                    backgroundColor: color + '20',
                    borderWidth: 2,
                    pointBackgroundColor: color,
                    pointBorderColor: '#fff',
                    pointRadius: 3,
                    pointHoverRadius: 5,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                animation: {
                    duration: 0
                },
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    x: {
                        grid: {
                            color: 'rgba(255,255,255,0.1)'
                        },
                        ticks: {
                            color: '#e6eef8',
                            maxTicksLimit: 6
                        }
                    },
                    y: {
                        grid: {
                            color: 'rgba(255,255,255,0.1)'
                        },
                        ticks: {
                            color: '#e6eef8'
                        },
                        beginAtZero: false,
                        grace: '5%'
                    }
                }
            }
        });
    }

    window.onload = function () {
        // Crear gráficas en el orden correcto
        graficas.conductividad = crearGrafica("graficaConductividad", "Conductividad (µS/cm)", "#06b6d4");
        graficas.ph = crearGrafica("graficaPH", "pH", "#10b981");
        graficas.oxigeno = crearGrafica("graficaOxigeno", "Oxígeno Disuelto (mg/L)", "#f59e0b");
        graficas.temperatura = crearGrafica("graficaTemperatura", "Temperatura (°C)", "#ef4444");
        updateDataSourceInfo();
    };

    // Simulación
    function iniciarSimulacion() {
        if (intervaloSimulacion) clearInterval(intervaloSimulacion);
        
        document.getElementById('btnIniciar').style.display = 'none';
        document.getElementById('btnDetener').style.display = 'block';
        actualizarEstado('Activo');

        intervaloSimulacion = setInterval(() => {
            const lectura = {
                conductividad: parseFloat((Math.random() * 500 + 200).toFixed(1)),
                ph: parseFloat((Math.random() * 1.5 + 6.5).toFixed(2)),
                oxigeno: parseFloat((Math.random() * 3 + 5).toFixed(1)),
                temperatura: parseFloat((Math.random() * 5 + 22).toFixed(1))
            };

            // Actualizar valores actuales
            document.getElementById('valorConductividad').textContent = lectura.conductividad + ' µS/cm';
            document.getElementById('valorPH').textContent = lectura.ph;
            document.getElementById('valorOxigeno').textContent = lectura.oxigeno + ' mg/L';
            document.getElementById('valorTemperatura').textContent = lectura.temperatura + ' °C';

            // Acumular en buffers
            buffers.conductividad.push(lectura.conductividad);
            buffers.ph.push(lectura.ph);
            buffers.oxigeno.push(lectura.oxigeno);
            buffers.temperatura.push(lectura.temperatura);

            if (buffers.conductividad.length === tamañoMuestra) {
                const promedio = {
                    conductividad: promedioDeArray(buffers.conductividad),
                    ph: promedioDeArray(buffers.ph),
                    oxigeno: promedioDeArray(buffers.oxigeno),
                    temperatura: promedioDeArray(buffers.temperatura)
                };

                agregarDatos(promedio);

                // Limpiar buffers
                buffers.conductividad = [];
                buffers.ph = [];
                buffers.oxigeno = [];
                buffers.temperatura = [];
            }
        }, 2000);
    }

    function detenerSimulacion() {
        if (intervaloSimulacion) {
            clearInterval(intervaloSimulacion);
            intervaloSimulacion = null;
        }
        
        document.getElementById('btnIniciar').style.display = 'block';
        document.getElementById('btnDetener').style.display = 'none';
        actualizarEstado('Inactivo');
    }

    function resetearSimulacion() {
        detenerSimulacion();
        resetearDatos();
        actualizarEstado('Inactivo');
        updateDataSourceInfo();
    }

    function resetearDatos() {
        datos.etiquetas = [];
        datos.conductividad = [];
        datos.ph = [];
        datos.oxigeno = [];
        datos.temperatura = [];

        buffers.conductividad = [];
        buffers.ph = [];
        buffers.oxigeno = [];
        buffers.temperatura = [];

        datosCargados = [];

        actualizarGraficas();

        // Resetear valores mostrados
        document.getElementById('valorConductividad').textContent = '-- µS/cm';
        document.getElementById('valorPH').textContent = '--';
        document.getElementById('valorOxigeno').textContent = '-- mg/L';
        document.getElementById('valorTemperatura').textContent = '-- °C';
    }

    function promedioDeArray(arr) {
        const suma = arr.reduce((a, b) => a + b, 0);
        return parseFloat((suma / arr.length).toFixed(2));
    }

    function agregarDatos(promedio) {
        const timestamp = new Date().toLocaleTimeString();
        datos.etiquetas.push(timestamp);
        datos.conductividad.push(promedio.conductividad);
        datos.ph.push(promedio.ph);
        datos.oxigeno.push(promedio.oxigeno);
        datos.temperatura.push(promedio.temperatura);

        if (datos.etiquetas.length > valoresMaximos) {
            datos.etiquetas.shift();
            datos.conductividad.shift();
            datos.ph.shift();
            datos.oxigeno.shift();
            datos.temperatura.shift();
        }

        actualizarGraficas();
        updateDataSourceInfo();
    }

    function actualizarEstado(estado) {
        const statusDot = document.getElementById('statusDot');
        const statusText = document.getElementById('statusText');
        
        statusText.textContent = estado;
        
        if (estado === 'Activo') {
            statusDot.style.background = '#16a34a';
        } else if (estado === 'Datos Externos') {
            statusDot.style.background = '#f59e0b';
        } else {
            statusDot.style.background = '#ef4444';
        }
    }
</script>

<style>
     :root{
      --bg:#0f172a;
      --card:#0b1220;
      --text:#e6eef8;
      --accent:#06b6d4;
      --gap:18px;
      --btn-size:140px;
      --on-color:#16a34a;
      --off-color:#e11d48;
      --muted:#9aa6bd;
    }
    *{box-sizing:border-box;font-family:Inter,ui-sans-serif,system-ui,-apple-system,"Segoe UI",Roboto,"Helvetica Neue",Arial}
    
    /* Quitar el marco blanco del layout */
    body, main, .container {
        background: linear-gradient(180deg, #071021 0%, #062033 100%) !important;
        margin: 0;
        padding: 0;
        border: none;
    }
    
    .monitor-container {
        min-height: calc(100vh - 60px);
        background: transparent !important;
        color: var(--text);
        padding: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .panel {
        width: 100%;
        max-width: 1200px;
        background: linear-gradient(180deg, rgba(255,255,255,0.02), rgba(255,255,255,0.01));
        border-radius: 14px;
        padding: 26px;
        box-shadow: 0 8px 30px rgba(2,6,23,0.6);
        border: 1px solid rgba(255,255,255,0.03);
    }

    .header {
        display: flex;
        align-items: center;
        gap: 16px;
        margin-bottom: 18px;
    }

    .logo {
        width: 56px;
        height: 56px;
        border-radius: 10px;
        background: linear-gradient(135deg, var(--accent), #7c3aed);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 24px;
    }

    h1 {
        margin: 0;
        font-size: 24px;
        color: var(--text);
    }

    p.lead {
        margin: 0;
        color: var(--muted);
        font-size: 14px;
    }

    /* ---- Estilos para la sección de fuentes de datos ---- */
    .data-source-section {
        margin: 30px 0;
        background: rgba(255,255,255,0.02);
        border-radius: 12px;
        padding: 20px;
        border: 1px solid rgba(255,255,255,0.03);
    }

    .data-source-section h3 {
        margin: 0 0 20px 0;
        color: var(--accent);
        font-size: 1.2rem;
    }

    .source-tabs {
        display: flex;
        gap: 8px;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }

    .source-tab {
        padding: 10px 16px;
        border-radius: 8px;
        background: rgba(255,255,255,0.03);
        border: 1px solid rgba(255,255,255,0.06);
        color: var(--muted);
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 0.9rem;
    }

    .source-tab:hover {
        background: rgba(255,255,255,0.05);
        border-color: rgba(255,255,255,0.1);
    }

    .source-tab.active {
        background: rgba(6, 182, 212, 0.1);
        border-color: rgba(6, 182, 212, 0.3);
        color: var(--accent);
    }

    .source-panel {
        display: none;
    }

    .source-panel.active {
        display: block;
    }

    .panel-content {
        padding: 20px;
        background: rgba(255,255,255,0.01);
        border-radius: 8px;
        border: 1px solid rgba(255,255,255,0.03);
    }

    .panel-content p {
        margin: 0 0 15px 0;
        color: var(--muted);
        font-size: 0.95rem;
    }

    .simulation-controls,
    .file-controls,
    .url-controls {
        display: flex;
        gap: 10px;
        align-items: center;
        flex-wrap: wrap;
    }

    .github-presets {
        display: flex;
        gap: 10px;
        margin: 15px 0;
    }

    .preset-btn {
        padding: 8px 12px;
        border-radius: 6px;
        background: rgba(139, 92, 246, 0.1);
        border: 1px solid rgba(139, 92, 246, 0.3);
        color: #8b5cf6;
        cursor: pointer;
        font-size: 0.85rem;
        transition: all 0.3s ease;
    }

    .preset-btn:hover {
        background: rgba(139, 92, 246, 0.2);
    }

    .file-input,
    .url-input {
        padding: 10px 12px;
        border-radius: 6px;
        background: rgba(255,255,255,0.03);
        border: 1px solid rgba(255,255,255,0.06);
        color: var(--text);
        font-size: 0.9rem;
        min-width: 300px;
    }

    .file-input::file-selector-button {
        background: rgba(6, 182, 212, 0.1);
        border: 1px solid rgba(6, 182, 212, 0.3);
        color: var(--accent);
        padding: 6px 12px;
        border-radius: 4px;
        cursor: pointer;
        margin-right: 10px;
    }

    .file-preview {
        margin-top: 15px;
        padding: 12px;
        border-radius: 6px;
        font-size: 0.9rem;
    }

    .preview-success {
        color: #16a34a;
        background: rgba(22, 163, 74, 0.1);
        padding: 10px;
        border-radius: 6px;
        border: 1px solid rgba(22, 163, 74, 0.2);
    }

    .preview-error {
        color: #e11d48;
        background: rgba(225, 29, 72, 0.1);
        padding: 10px;
        border-radius: 6px;
        border: 1px solid rgba(225, 29, 72, 0.2);
    }

    .preview-loading {
        color: #f59e0b;
        background: rgba(245, 158, 11, 0.1);
        padding: 10px;
        border-radius: 6px;
        border: 1px solid rgba(245, 158, 11, 0.2);
    }

    /* ---- Estilos existentes para gráficas ---- */
    .grid-container {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: var(--gap);
        margin: 30px 0;
    }

    .grafica-card {
        background: linear-gradient(180deg, rgba(255,255,255,0.01), rgba(255,255,255,0.005));
        border-radius: 12px;
        padding: 20px;
        border: 1px solid rgba(255,255,255,0.03);
    }

    .grafica-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
    }

    .grafica-header h3 {
        margin: 0;
        font-size: 1.1rem;
        color: var(--accent);
    }

    .valor-actual {
        background: rgba(6, 182, 212, 0.1);
        padding: 5px 12px;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.9rem;
        color: #06b6d4;
    }

    .grafica-container {
        width: 100%;
        height: 200px;
        position: relative;
    }

    .controls {
        display: flex;
        align-items: center;
        gap: 10px;
        margin: 30px 0;
    }

    .btn-light {
        padding: 10px 16px;
        border-radius: 8px;
        background: transparent;
        border: 1px solid rgba(255,255,255,0.06);
        color: var(--muted);
        cursor: pointer;
        transition: all 0.3s ease;
        font-weight: 600;
        font-size: 0.9rem;
    }

    .btn-light:hover {
        background: rgba(255,255,255,0.05);
        border-color: rgba(255,255,255,0.1);
        color: var(--text);
    }

    .status-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-top: 25px;
        padding-top: 20px;
        border-top: 1px solid rgba(255,255,255,0.05);
        color: var(--text);
    }

    .status-list {
        display: flex;
        gap: 12px;
        align-items: center;
        color: var(--text);
    }

    .dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: var(--muted);
    }

    footer {
        margin-top: 20px;
        color: var(--muted);
        font-size: 13px;
        text-align: center;
        padding-top: 20px;
        border-top: 1px solid rgba(255,255,255,0.05);
    }

    /* Asegurar que todo el texto sea blanco */
    body, h1, h2, h3, h4, h5, h6, p, span, div, label, button {
        color: var(--text) !important;
    }

    /* Específicamente para los números y texto de Chart.js */
    .chartjs-render-monitor {
        color: var(--text) !important;
    }

    @media (max-width: 900px) {
        .grid-container {
            grid-template-columns: 1fr;
        }
        
        .panel {
            padding: 20px;
        }
        
        .grafica-container {
            height: 180px;
        }
        
        .source-tabs {
            flex-direction: column;
        }
        
        .file-input,
        .url-input {
            min-width: 100%;
        }
        
        .simulation-controls,
        .file-controls,
        .url-controls {
            flex-direction: column;
            align-items: stretch;
        }
    }

    @media (max-width: 680px) {
        .monitor-container {
            padding: 15px;
        }
        
        .grafica-header {
            flex-direction: column;
            gap: 10px;
            align-items: flex-start;
        }
        
        .valor-actual {
            align-self: flex-end;
        }
        
        .header {
            flex-direction: column;
            text-align: center;
            gap: 10px;
        }
        
        .status-row {
            flex-direction: column;
            gap: 10px;
            align-items: flex-start;
        }
    }
</style>
@endsection