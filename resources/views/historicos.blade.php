@extends('layouts.app')

@section('content')
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monitoreo de Calidad del Agua - ThingSpeak</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        /* ---- Estilos base y variables ---- */
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
        body{
          margin:0;min-height:100vh;background:linear-gradient(180deg,#071021 0%, #062033 100%);color:var(--text);display:flex;align-items:center;justify-content:center;padding:36px;
        }
        .panel{width:960px;max-width:95%;background:linear-gradient(180deg, rgba(255,255,255,0.02), rgba(255,255,255,0.01));border-radius:14px;padding:26px;box-shadow:0 8px 30px rgba(2,6,23,0.6)}
        .header{display:flex;align-items:center;gap:16px;margin-bottom:18px}
        .logo{width:56px;height:56px;border-radius:10px;background:linear-gradient(135deg,var(--accent),#7c3aed);display:flex;align-items:center;justify-content:center;font-weight:700;font-size:20px}
        h1{margin:0;font-size:18px}
        p.lead{margin:0;color:var(--muted);font-size:13px}

        .grid{display:grid;grid-template-columns:repeat(4,1fr);gap:var(--gap);margin-top:20px}

        .card{background:linear-gradient(180deg, rgba(255,255,255,0.01), rgba(255,255,255,0.005));border-radius:12px;padding:16px;display:flex;flex-direction:column;align-items:center;gap:12px;min-height:170px;justify-content:center}

        .actuator-btn{width:var(--btn-size);height:var(--btn-size);border-radius:12px;border:3px solid rgba(255,255,255,0.06);display:flex;flex-direction:column;align-items:center;justify-content:center;gap:8px;cursor:pointer;transition:transform .12s ease, box-shadow .12s ease;background:linear-gradient(180deg, rgba(255,255,255,0.012), rgba(255,255,255,0.008));color:var(--text);box-shadow:0 6px 20px rgba(2,6,23,0.45)}
        .actuator-btn:active{transform:translateY(2px) scale(.998)}
        .actuator-btn .icon{width:46px;height:46px;display:flex;align-items:center;justify-content:center}
        .actuator-btn .label{font-weight:600}
        .actuator-btn .small{font-size:12px;color:var(--muted)}

        .actuator-btn.on{background:linear-gradient(180deg, rgba(22,163,74,0.14), rgba(22,163,74,0.06));border-color:rgba(22,163,74,0.45);box-shadow:0 10px 30px rgba(16,185,129,0.08)}
        .actuator-btn.off{background:linear-gradient(180deg, rgba(225,29,72,0.08), rgba(225,29,72,0.03));border-color:rgba(225,29,72,0.45);box-shadow:0 10px 30px rgba(225,29,72,0.06)}

        .status-row{display:flex;align-items:center;justify-content:space-between;margin-top:18px;gap:12px}
        .status-list{display:flex;gap:12px;align-items:center}
        .dot{width:10px;height:10px;border-radius:50%;background:var(--muted)}

        .controls{margin-top:18px;display:flex;align-items:center;gap:10px}
        .btn-light{padding:10px 12px;border-radius:8px;background:transparent;border:1px solid rgba(255,255,255,0.06);color:var(--muted);cursor:pointer}

        footer{margin-top:18px;color:var(--muted);font-size:13px}

        @media (max-width:680px){
          .grid{grid-template-columns:repeat(2,1fr)}
          .actuator-btn{width:120px;height:120px}
        }
        
        /* ---- Estilos adicionales para gráficas ---- */
        .chart-container {
          width: 100%;
          height: 300px;
          margin-top: 20px;
        }
        
        .chart-grid {
          display: grid;
          grid-template-columns: 1fr;
          gap: var(--gap);
          margin-top: 20px;
        }
        
        .chart-card {
          background: linear-gradient(180deg, rgba(255,255,255,0.01), rgba(255,255,255,0.005));
          border-radius: 12px;
          padding: 20px;
          box-shadow: 0 6px 20px rgba(2,6,23,0.45);
        }
        
        .chart-title {
          font-size: 16px;
          margin-bottom: 15px;
          color: var(--text);
          display: flex;
          align-items: center;
          gap: 8px;
        }
        
        .loader {
          width: 24px;
          height: 24px;
          border: 3px solid rgba(255,255,255,0.1);
          border-top: 3px solid var(--accent);
          border-radius: 50%;
          animation: spin 1s linear infinite;
          margin: 20px auto;
        }
        
        @keyframes spin {
          0% { transform: rotate(0deg); }
          100% { transform: rotate(360deg); }
        }
        
        .data-controls {
          display: flex;
          justify-content: space-between;
          align-items: center;
          margin-bottom: 20px;
        }
        
        .filter-select {
          background: rgba(255,255,255,0.03);
          border: 1px solid rgba(255,255,255,0.06);
          border-radius: 8px;
          padding: 8px 12px;
          color: var(--text);
        }
        
        .data-table {
          width: 100%;
          border-collapse: collapse;
          margin-top: 20px;
          font-size: 14px;
        }
        
        .data-table th, .data-table td {
          padding: 10px;
          text-align: left;
          border-bottom: 1px solid rgba(255,255,255,0.06);
        }
        
        .data-table th {
          color: var(--muted);
          font-weight: 600;
        }
        
        .section-title {
          font-size: 20px;
          margin: 30px 0 15px 0;
          color: var(--text);
          border-bottom: 1px solid rgba(255,255,255,0.06);
          padding-bottom: 10px;
        }

        /* ---- Estilos adaptados para el dashboard ---- */
        .dashboard-container {
          width: 100%;
          max-width: 1200px;
          margin: 0 auto;
        }

        .dashboard-header {
          display: flex;
          align-items: center;
          gap: 16px;
          margin-bottom: 24px;
        }

        .dashboard-logo {
          width: 56px;
          height: 56px;
          border-radius: 10px;
          background: linear-gradient(135deg, var(--accent), #7c3aed);
          display: flex;
          align-items: center;
          justify-content: center;
          font-weight: 700;
          font-size: 20px;
        }

        .dashboard-title {
          margin: 0;
          font-size: 24px;
          font-weight: 600;
        }

        .dashboard-subtitle {
          margin: 0;
          color: var(--muted);
          font-size: 14px;
        }

        .config-section {
          background: linear-gradient(180deg, rgba(255,255,255,0.02), rgba(255,255,255,0.01));
          border-radius: 12px;
          padding: 20px;
          margin-bottom: 24px;
          box-shadow: 0 6px 20px rgba(2,6,23,0.45);
        }

        .config-title {
          font-size: 18px;
          margin-bottom: 16px;
          color: var(--text);
        }

        .date-controls {
          display: grid;
          grid-template-columns: 1fr 1fr;
          gap: 16px;
          margin-bottom: 16px;
        }

        .date-group {
         /* display: flex;
          flex-direction: column;
          gap: 8px;*/
        }

        .date-group label {
          font-weight: 600;
          font-size: 14px;
          color: var(--muted);
        }

        .date-group input {
          padding: 10px 12px;
          border-radius: 8px;
          background: rgba(255,255,255,0.03);
          border: 1px solid rgba(255,255,255,0.06);
          color: var(--text);
          font-size: 14px;
        }

        .data-count-controls {
          display: grid;
          grid-template-columns: 1fr 1fr;
          gap: 16px;
          margin-bottom: 16px;
        }

        .data-count-group {
          display: flex;
          flex-direction: column;
          gap: 8px;
        }

        .data-count-group label {
          font-weight: 600;
          font-size: 14px;
          color: var(--muted);
        }

        .data-count-group select {
          padding: 10px 12px;
          border-radius: 8px;
          background: rgba(255,255,255,0.03);
          border: 1px solid rgba(255,255,255,0.06);
          color: var(--text);
          font-size: 14px;
          cursor: pointer;
        }

        /* Estilo del menú desplegable (opciones) */
        .data-count-group select option {
            background-color: #0d1b2a; /* color oscuro del fondo */
            color: #ffffff;            /* texto blanco */
        S}

        .action-buttons {
          display: flex;
          gap: 12px;
          margin-top: 16px;
        }

        .action-btn {
          padding: 10px 16px;
          border-radius: 8px;
          background: transparent;
          border: 1px solid rgba(255,255,255,0.06);
          color: var(--text);
          cursor: pointer;
          display: flex;
          align-items: center;
          gap: 8px;
          transition: all 0.2s ease;
        }

        .action-btn:hover {
          background: rgba(255,255,255,0.03);
        }

        .status-indicator {
          display: flex;
          align-items: center;
          justify-content: center;
          gap: 8px;
          padding: 12px;
          border-radius: 8px;
          background: rgba(255,255,255,0.02);
          margin-bottom: 24px;
          font-size: 14px;
        }

        .current-data-grid {
          display: grid;
          grid-template-columns: repeat(4, 1fr);
          gap: 16px;
          margin-bottom: 24px;
        }

        .data-card {
          background: linear-gradient(180deg, rgba(255,255,255,0.01), rgba(255,255,255,0.005));
          border-radius: 12px;
          padding: 20px;
          display: flex;
          flex-direction: column;
          align-items: center;
          gap: 12px;
          box-shadow: 0 6px 20px rgba(2,6,23,0.45);
        }

        .data-card-title {
          font-size: 14px;
          color: var(--muted);
          display: flex;
          align-items: center;
          gap: 8px;
        }

        .data-card-value {
          font-size: 28px;
          font-weight: 700;
          color: var(--text);
        }

        .data-card-unit {
          font-size: 14px;
          color: var(--muted);
        }

        .data-card-time {
          font-size: 12px;
          color: var(--muted);
        }

        .averages-grid {
          display: grid;
          grid-template-columns: repeat(4, 1fr);
          gap: 16px;
          margin-bottom: 24px;
        }

        .average-card {
          background: linear-gradient(180deg, rgba(255,255,255,0.01), rgba(255,255,255,0.005));
          border-radius: 12px;
          padding: 20px;
          display: flex;
          flex-direction: column;
          align-items: center;
          gap: 12px;
          box-shadow: 0 6px 20px rgba(2,6,23,0.45);
        }

        .average-card-title {
          font-size: 14px;
          color: var(--muted);
        }

        .average-card-value {
          font-size: 24px;
          font-weight: 700;
          color: var(--text);
        }

        .average-card-unit {
          font-size: 14px;
          color: var(--muted);
        }

        .charts-grid {
          display: grid;
          grid-template-columns: 1fr 1fr;
          gap: 16px;
          margin-bottom: 24px;
        }

        .chart-panel {
          background: linear-gradient(180deg, rgba(255,255,255,0.01), rgba(255,255,255,0.005));
          border-radius: 12px;
          padding: 20px;
          box-shadow: 0 6px 20px rgba(2,6,23,0.45);
        }

        .chart-panel-title {
          font-size: 16px;
          margin-bottom: 16px;
          color: var(--text);
          display: flex;
          align-items: center;
          gap: 8px;
        }

        .chart-value {
          font-size: 24px;
          font-weight: 700;
          margin-bottom: 16px;
          color: var(--text);
        }

        .chart-unit {
          font-size: 14px;
          color: var(--muted);
          margin-bottom: 16px;
        }

        .dashboard-controls {
          display: flex;
          justify-content: center;
          gap: 12px;
          margin-bottom: 24px;
        }

        .control-btn {
          padding: 12px 20px;
          border-radius: 8px;
          background: transparent;
          border: 1px solid rgba(255,255,255,0.06);
          color: var(--text);
          cursor: pointer;
          display: flex;
          align-items: center;
          gap: 8px;
          transition: all 0.2s ease;
        }

        .control-btn:hover {
          background: rgba(255,255,255,0.03);
        }

        .dashboard-footer {
          text-align: center;
          color: var(--muted);
          font-size: 13px;
          padding-top: 16px;
          border-top: 1px solid rgba(255,255,255,0.06);
        }

        @media (max-width: 768px) {
          .current-data-grid,
          .averages-grid {
            grid-template-columns: repeat(2, 1fr);
          }
          
          .charts-grid {
            grid-template-columns: 1fr;
          }
          
          .date-controls,
          .data-count-controls {
            grid-template-columns: 1fr;
          }
        }

        @media (max-width: 480px) {
          .current-data-grid,
          .averages-grid {
            grid-template-columns: 1fr;
          }
          
          .dashboard-controls {
            flex-direction: column;
          }
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <div class="dashboard-header">
            <div class="dashboard-logo">💧</div>
            <div>
                <h1 class="dashboard-title">Monitoreo de Calidad del Agua</h1>
                <p class="dashboard-subtitle">Datos en tiempo real desde ThingSpeak - Canal ID: 2964378</p>
            </div>
        </div>
        
        <div class="config-section">
            <h3 class="config-title">Configuración de Datos</h3>
            <div class="date-controls">
                <div class="date-group">
                    <label for="startDate">Fecha de Inicio:</label>
                    <input type="date" id="startDate">
                </div>
                <div class="date-group">
                    <label for="endDate">Fecha de Fin:</label>
                    <input type="date" id="endDate">
                </div>
            </div>
            
            <div class="data-count-controls">
                <div class="data-count-group">
                    <label for="dataCount">Datos a mostrar en gráficos:</label>
                    <select id="dataCount">
                        <option value="20">Últimos 20 datos</option>
                        <option value="50" selected>Últimos 50 datos</option>
                        <option value="100">Últimos 100 datos</option>
                        <option value="200">Últimos 200 datos</option>
                        <option value="500">Últimos 500 datos</option>
                        <option value="8000">Todos los datos disponibles</option>
                    </select>
                </div>
                <div class="data-count-group">
                    <label for="averageCount">Datos para promedios:</label>
                    <select id="averageCount">
                        <option value="20">Últimos 20 datos</option>
                        <option value="50" selected>Últimos 50 datos</option>
                        <option value="100">Últimos 100 datos</option>
                        <option value="200">Últimos 200 datos</option>
                        <option value="500">Últimos 500 datos</option>
                        <option value="8000">Todos los datos disponibles</option>
                    </select>
                </div>
            </div>
            
            <div class="action-buttons">
                <button class="action-btn" id="applyDates">
                    <span>📅</span> Aplicar Configuración
                </button>
                <button class="action-btn" id="resetDates">
                    <span>🔄</span> Restablecer
                </button>
            </div>
        </div>
        
        <div class="status-indicator" id="status">
            <div class="loader"></div>
            <span>Conectando con ThingSpeak...</span>
        </div>

        <!-- Sección de últimos datos -->
        <div class="current-data-grid">
            <div class="data-card">
                <div class="data-card-title">
                    <span>⚡</span> Conductividad Actual
                </div>
                <div class="data-card-value" id="latestConductivity">--</div>
                <div class="data-card-unit">mS/cm</div>
                <div class="data-card-time" id="latestConductivityTime">--</div>
            </div>
            
            <div class="data-card">
                <div class="data-card-title">
                    <span>🧪</span> pH Actual
                </div>
                <div class="data-card-value" id="latestPH">--</div>
                <div class="data-card-unit">pH</div>
                <div class="data-card-time" id="latestPHTime">--</div>
            </div>
            
            <div class="data-card">
                <div class="data-card-title">
                    <span>💧</span> Oxígeno Disuelto Actual
                </div>
                <div class="data-card-value" id="latestOxygen">--</div>
                <div class="data-card-unit">mg/L</div>
                <div class="data-card-time" id="latestOxygenTime">--</div>
            </div>
            
            <div class="data-card">
                <div class="data-card-title">
                    <span>🌡️</span> Temperatura Actual
                </div>
                <div class="data-card-value" id="latestTemperature">--</div>
                <div class="data-card-unit">°C</div>
                <div class="data-card-time" id="latestTemperatureTime">--</div>
            </div>
        </div>
        
        <div class="averages-grid">
            <div class="average-card">
                <div class="average-card-title">Conductividad Promedio</div>
                <div class="average-card-value" id="avgConductivity">--</div>
                <div class="average-card-unit">mS/cm</div>
            </div>
            
            <div class="average-card">
                <div class="average-card-title">pH Promedio</div>
                <div class="average-card-value" id="avgPH">--</div>
                <div class="average-card-unit">pH</div>
            </div>
            
            <div class="average-card">
                <div class="average-card-title">Oxígeno Disuelto Promedio</div>
                <div class="average-card-value" id="avgOxygen">--</div>
                <div class="average-card-unit">mg/L</div>
            </div>
            
            <div class="average-card">
                <div class="average-card-title">Temperatura Promedio</div>
                <div class="average-card-value" id="avgTemperature">--</div>
                <div class="average-card-unit">°C</div>
            </div>
        </div>
        
        <div class="charts-grid">
            <div class="chart-panel">
                <h3 class="chart-panel-title">
                    <span>⚡</span> Conductividad
                </h3>
                <div class="chart-value" id="conductivity">--</div>
                <div class="chart-unit">mS/cm</div>
                <div class="chart-container">
                    <canvas id="chartConductivity"></canvas>
                </div>
            </div>
            
            <div class="chart-panel">
                <h3 class="chart-panel-title">
                    <span>🧪</span> pH
                </h3>
                <div class="chart-value" id="ph">--</div>
                <div class="chart-unit">pH</div>
                <div class="chart-container">
                    <canvas id="chartPH"></canvas>
                </div>
            </div>
            
            <div class="chart-panel">
                <h3 class="chart-panel-title">
                    <span>💧</span> Oxígeno Disuelto
                </h3>
                <div class="chart-value" id="oxygen">--</div>
                <div class="chart-unit">mg/L</div>
                <div class="chart-container">
                    <canvas id="chartOxygen"></canvas>
                </div>
            </div>
            
            <div class="chart-panel">
                <h3 class="chart-panel-title">
                    <span>🌡️</span> Temperatura
                </h3>
                <div class="chart-value" id="temperature">--</div>
                <div class="chart-unit">°C</div>
                <div class="chart-container">
                    <canvas id="chartTemperature"></canvas>
                </div>
            </div>
        </div>
        
        <div class="dashboard-controls">
            <button class="control-btn" id="refreshBtn">
                <span>🔄</span> Actualizar Datos
            </button>
            <button class="control-btn" id="autoRefreshBtn">
                <span>⏱️</span> Auto-Actualizar: ON
            </button>
            <button class="control-btn" id="downloadCSVBtn">
                <span>📥</span> Descargar CSV
            </button>
            <button class="control-btn" id="downloadJSONBtn">
                <span>📥</span> Descargar JSON
            </button>
        </div>
        
        <div class="dashboard-footer">
            <p>Datos obtenidos de ThingSpeak - Canal ID: 2964378 | Auto-actualización cada 10 segundos</p>
        </div>
    </div>

    <script>
        // Configuración
        const channelID = "2964378";
        const apiKey = "J007XMWSBU6301WM";
        const baseURL = "https://api.thingspeak.com/channels";
        const MAX_RESULTS = 8000; // Máximo permitido por ThingSpeak
        
        // Mapeo de campos
        const fieldMapping = {
            conductivity: 1,
            ph: 2,
            oxygen: 3,
            temperature: 4
        };
        
        // Elementos DOM
        const statusElement = document.getElementById('status');
        const refreshBtn = document.getElementById('refreshBtn');
        const autoRefreshBtn = document.getElementById('autoRefreshBtn');
        const downloadCSVBtn = document.getElementById('downloadCSVBtn');
        const downloadJSONBtn = document.getElementById('downloadJSONBtn');
        const applyDatesBtn = document.getElementById('applyDates');
        const resetDatesBtn = document.getElementById('resetDates');
        const startDateInput = document.getElementById('startDate');
        const endDateInput = document.getElementById('endDate');
        const dataCountSelect = document.getElementById('dataCount');
        const averageCountSelect = document.getElementById('averageCount');
        
        // Variables para gráficos
        let charts = {};
        let autoRefreshInterval = null;
        let isAutoRefreshing = true; // Auto-actualización activada por defecto
        let currentData = []; // Todos los datos del rango seleccionado
        let currentStartDate = null;
        let currentEndDate = null;
        let currentDataCount = 50; // Por defecto mostrar 50 datos
        let currentAverageCount = 50; // Por defecto promediar 50 datos
        
        // Establecer fechas por defecto (últimos 7 días)
        function setDefaultDates() {
            const end = new Date();
            const start = new Date();
            start.setDate(start.getDate() - 7);
            
            // Formatear para input date
            const formatDate = (date) => {
                return date.toISOString().split('T')[0];
            };
            
            startDateInput.value = formatDate(start);
            endDateInput.value = formatDate(end);
            
            currentStartDate = start;
            currentEndDate = end;
        }
        
        // Inicializar gráficos
        function initializeCharts() {
            const chartConfig = {
                type: 'line',
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        x: {
                            display: true,
                            grid: {
                                color: 'rgba(255, 255, 255, 0.1)'
                            },
                            ticks: {
                                color: 'rgba(255, 255, 255, 0.7)',
                                maxTicksLimit: 8
                            }
                        },
                        y: {
                            display: true,
                            grid: {
                                color: 'rgba(255, 255, 255, 0.1)'
                            },
                            ticks: {
                                color: 'rgba(255, 255, 255, 0.7)'
                            }
                        }
                    }
                }
            };
            
            // Crear gráficos para cada parámetro
            const chartParams = [
                { id: 'chartConductivity', label: 'Conductividad', color: 0 },
                { id: 'chartPH', label: 'pH', color: 60 },
                { id: 'chartOxygen', label: 'Oxígeno Disuelto', color: 180 },
                { id: 'chartTemperature', label: 'Temperatura', color: 300 }
            ];
            
            chartParams.forEach(param => {
                const ctx = document.getElementById(param.id).getContext('2d');
                charts[param.id] = new Chart(ctx, {
                    ...chartConfig,
                    data: {
                        labels: [],
                        datasets: [{
                            label: param.label,
                            data: [],
                            borderColor: `hsl(${param.color}, 70%, 60%)`,
                            backgroundColor: `hsla(${param.color}, 70%, 60%, 0.1)`,
                            tension: 0.4,
                            fill: true,
                            pointBackgroundColor: `hsl(${param.color}, 70%, 60%)`,
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2,
                            pointRadius: 3
                        }]
                    }
                });
            });
        }
        
        // Obtener datos de ThingSpeak
        async function fetchData() {
            try {
                statusElement.innerHTML = '<div class="loader"></div><span>Cargando datos...</span>';
                
                // Obtener todos los datos disponibles (hasta 8000)
                let url = `${baseURL}/${channelID}/feeds.json?api_key=${apiKey}&results=${MAX_RESULTS}`;
                
                console.log('URL de consulta:', url);
                
                // Obtener datos del feed
                const response = await fetch(url);
                
                if (!response.ok) {
                    throw new Error(`Error HTTP: ${response.status}`);
                }
                
                const data = await response.json();
                
                if (!data.feeds || data.feeds.length === 0) {
                    throw new Error('No hay datos disponibles en el canal');
                }
                
                // Filtrar datos por fecha si se han seleccionado fechas
                let filteredData = data.feeds;
                
                if (currentStartDate && currentEndDate) {
                    filteredData = data.feeds.filter(feed => {
                        const feedDate = new Date(feed.created_at);
                        return feedDate >= currentStartDate && feedDate <= currentEndDate;
                    });
                    
                    if (filteredData.length === 0) {
                        throw new Error('No hay datos disponibles para el rango de fechas seleccionado');
                    }
                }
                
                // Guardar TODOS los datos del rango para CSV y JSON
                currentData = filteredData;
                
                // Procesar datos para visualización
                processData(filteredData);
                
                statusElement.innerHTML = `<span style="color: #16a34a">✓</span> Datos actualizados: ${new Date().toLocaleTimeString()} (${currentData.length} registros en rango, mostrando ${Math.min(currentDataCount, currentData.length)} en gráficos)`;
                
            } catch (error) {
                console.error('Error al obtener datos:', error);
                statusElement.innerHTML = `<span style="color: #e11d48">✗</span> Error: ${error.message}`;
            }
        }
        
        // Procesar y mostrar datos
        function processData(allFeeds) {
            // Tomar solo los últimos N datos para gráficos según la selección
            const displayDataCount = currentDataCount === 8000 ? allFeeds.length : Math.min(currentDataCount, allFeeds.length);
            const displayFeeds = allFeeds.slice(-displayDataCount);
            
            // Preparar datos para gráficos
            const fieldData = {
                conductivity: [],
                ph: [],
                oxygen: [],
                temperature: []
            };
            
            const timestamps = [];
            
            // Recorrer feeds para gráficos
            displayFeeds.forEach(feed => {
                const date = new Date(feed.created_at);
                timestamps.push(date.toLocaleString());
                
                // Extraer valores de cada campo
                for (const [param, fieldId] of Object.entries(fieldMapping)) {
                    const fieldValue = parseFloat(feed[`field${fieldId}`]);
                    if (!isNaN(fieldValue)) {
                        fieldData[param].push(fieldValue);
                    }
                }
            });
            
            // Actualizar valores actuales (último registro)
            if (allFeeds.length > 0) {
                const lastFeed = allFeeds[allFeeds.length - 1];
                const lastDate = new Date(lastFeed.created_at);
                
                // Conductividad
                const conductivityValue = lastFeed[`field${fieldMapping.conductivity}`];
                const condValue = conductivityValue ? parseFloat(conductivityValue) : null;
                document.getElementById('conductivity').textContent = condValue ? condValue.toFixed(2) : '--';
                document.getElementById('latestConductivity').textContent = condValue ? condValue.toFixed(2) : '--';
                document.getElementById('latestConductivityTime').textContent = lastDate.toLocaleTimeString();
                
                // pH
                const phValue = lastFeed[`field${fieldMapping.ph}`];
                const phNumValue = phValue ? parseFloat(phValue) : null;
                document.getElementById('ph').textContent = phNumValue ? phNumValue.toFixed(2) : '--';
                document.getElementById('latestPH').textContent = phNumValue ? phNumValue.toFixed(2) : '--';
                document.getElementById('latestPHTime').textContent = lastDate.toLocaleTimeString();
                
                // Oxígeno Disuelto
                const oxygenValue = lastFeed[`field${fieldMapping.oxygen}`];
                const oxyValue = oxygenValue ? parseFloat(oxygenValue) : null;
                document.getElementById('oxygen').textContent = oxyValue ? oxyValue.toFixed(2) : '--';
                document.getElementById('latestOxygen').textContent = oxyValue ? oxyValue.toFixed(2) : '--';
                document.getElementById('latestOxygenTime').textContent = lastDate.toLocaleTimeString();
                
                // Temperatura
                const temperatureValue = lastFeed[`field${fieldMapping.temperature}`];
                const tempValue = temperatureValue ? parseFloat(temperatureValue) : null;
                document.getElementById('temperature').textContent = tempValue ? tempValue.toFixed(2) : '--';
                document.getElementById('latestTemperature').textContent = tempValue ? tempValue.toFixed(2) : '--';
                document.getElementById('latestTemperatureTime').textContent = lastDate.toLocaleTimeString();
            }
            
            // Calcular y mostrar promedios (según la selección)
            calculateAverages(allFeeds);
            
            // Actualizar gráficos
            updateCharts(timestamps, fieldData);
        }
        
        // Calcular promedios (según la selección)
        function calculateAverages(allFeeds) {
            // Tomar solo los últimos N datos para promedios según la selección
            const averageDataCount = currentAverageCount === 8000 ? allFeeds.length : Math.min(currentAverageCount, allFeeds.length);
            const averageFeeds = allFeeds.slice(-averageDataCount);
            
            const fieldData = {
                conductivity: [],
                ph: [],
                oxygen: [],
                temperature: []
            };
            
            // Extraer valores para promedios
            averageFeeds.forEach(feed => {
                for (const [param, fieldId] of Object.entries(fieldMapping)) {
                    const fieldValue = parseFloat(feed[`field${fieldId}`]);
                    if (!isNaN(fieldValue)) {
                        fieldData[param].push(fieldValue);
                    }
                }
            });
            
            // Calcular y mostrar promedios
            for (const [param, values] of Object.entries(fieldData)) {
                if (values.length > 0) {
                    const sum = values.reduce((a, b) => a + b, 0);
                    const avg = sum / values.length;
                    
                    // Mostrar promedio según el parámetro
                    switch(param) {
                        case 'conductivity':
                            document.getElementById('avgConductivity').textContent = avg.toFixed(2);
                            break;
                        case 'ph':
                            document.getElementById('avgPH').textContent = avg.toFixed(2);
                            break;
                        case 'oxygen':
                            document.getElementById('avgOxygen').textContent = avg.toFixed(2);
                            break;
                        case 'temperature':
                            document.getElementById('avgTemperature').textContent = avg.toFixed(2);
                            break;
                    }
                } else {
                    // Si no hay datos, mostrar --
                    switch(param) {
                        case 'conductivity':
                            document.getElementById('avgConductivity').textContent = '--';
                            break;
                        case 'ph':
                            document.getElementById('avgPH').textContent = '--';
                            break;
                        case 'oxygen':
                            document.getElementById('avgOxygen').textContent = '--';
                            break;
                        case 'temperature':
                            document.getElementById('avgTemperature').textContent = '--';
                            break;
                    }
                }
            }
        }
        
        // Actualizar gráficos
        function updateCharts(timestamps, fieldData) {
            // Conductividad
            if (charts.chartConductivity) {
                charts.chartConductivity.data.labels = timestamps;
                charts.chartConductivity.data.datasets[0].data = fieldData.conductivity;
                charts.chartConductivity.update('none');
            }
            
            // pH
            if (charts.chartPH) {
                charts.chartPH.data.labels = timestamps;
                charts.chartPH.data.datasets[0].data = fieldData.ph;
                charts.chartPH.update('none');
            }
            
            // Oxígeno Disuelto
            if (charts.chartOxygen) {
                charts.chartOxygen.data.labels = timestamps;
                charts.chartOxygen.data.datasets[0].data = fieldData.oxygen;
                charts.chartOxygen.update('none');
            }
            
            // Temperatura
            if (charts.chartTemperature) {
                charts.chartTemperature.data.labels = timestamps;
                charts.chartTemperature.data.datasets[0].data = fieldData.temperature;
                charts.chartTemperature.update('none');
            }
        }
        
        // Aplicar configuración
        function applyConfiguration() {
            const startDateValue = startDateInput.value;
            const endDateValue = endDateInput.value;
            
            if (!startDateValue || !endDateValue) {
                alert('Por favor selecciona ambas fechas');
                return;
            }
            
            currentStartDate = new Date(startDateValue);
            currentEndDate = new Date(endDateValue);
            currentEndDate.setHours(23, 59, 59, 999); // Incluir todo el día final
            
            if (currentStartDate > currentEndDate) {
                alert('La fecha de inicio no puede ser mayor que la fecha de fin');
                return;
            }
            
            // Actualizar conteos de datos
            currentDataCount = parseInt(dataCountSelect.value);
            currentAverageCount = parseInt(averageCountSelect.value);
            
            fetchData();
        }
        
        // Restablecer configuración
        function resetConfiguration() {
            setDefaultDates();
            dataCountSelect.value = '50';
            averageCountSelect.value = '50';
            currentDataCount = 50;
            currentAverageCount = 50;
            fetchData();
        }
        
        // Descargar datos como CSV (TODOS los datos del rango)
        function downloadCSV() {
            if (currentData.length === 0) {
                alert('No hay datos para descargar');
                return;
            }
            
            // Crear cabeceras CSV
            let csv = 'Fecha,Hora,Conductividad (mS/cm),pH,Oxígeno Disuelto (mg/L),Temperatura (°C)\n';
            
            // Agregar TODOS los datos del rango seleccionado
            currentData.forEach(feed => {
                const date = new Date(feed.created_at);
                const fecha = date.toLocaleDateString();
                const hora = date.toLocaleTimeString();
                
                const conductivity = feed.field1 || '';
                const ph = feed.field2 || '';
                const oxygen = feed.field3 || '';
                const temperature = feed.field4 || '';
                
                csv += `"${fecha}","${hora}","${conductivity}","${ph}","${oxygen}","${temperature}"\n`;
            });
            
            // Crear y descargar archivo
            const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
            const link = document.createElement('a');
            const url = URL.createObjectURL(blob);
            link.setAttribute('href', url);
            const dateRange = currentStartDate && currentEndDate ? 
                `_${currentStartDate.toISOString().split('T')[0]}_a_${currentEndDate.toISOString().split('T')[0]}` : 
                `_todos_los_datos`;
            link.setAttribute('download', `datos_calidad_agua${dateRange}.csv`);
            link.style.visibility = 'hidden';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }
        
        // Descargar datos como JSON (TODOS los datos del rango)
        function downloadJSON() {
            if (currentData.length === 0) {
                alert('No hay datos para descargar');
                return;
            }
            
            // Preparar datos para JSON
            const dataToSave = {
                channel: {
                    id: channelID,
                    name: "Canal de Calidad del Agua",
                    description: "Datos de conductividad, pH, oxígeno disuelto y temperatura",
                    last_entry_id: currentData.length > 0 ? currentData[currentData.length - 1].entry_id : null
                },
                feeds: currentData,
                metadata: {
                    export_date: new Date().toISOString(),
                    total_records: currentData.length,
                    date_range: {
                        start: currentStartDate ? currentStartDate.toISOString() : null,
                        end: currentEndDate ? currentEndDate.toISOString() : null
                    }
                }
            };
            
            // Crear JSON
            const jsonContent = JSON.stringify(dataToSave, null, 2);
            
            // Crear y descargar archivo
            const blob = new Blob([jsonContent], { type: 'application/json;charset=utf-8;' });
            const link = document.createElement('a');
            const url = URL.createObjectURL(blob);
            link.setAttribute('href', url);
            const dateRange = currentStartDate && currentEndDate ? 
                `_${currentStartDate.toISOString().split('T')[0]}_a_${currentEndDate.toISOString().split('T')[0]}` : 
                `_todos_los_datos`;
            link.setAttribute('download', `datos_calidad_agua${dateRange}.json`);
            link.style.visibility = 'hidden';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }
        
        // Alternar auto-actualización
        function toggleAutoRefresh() {
            if (isAutoRefreshing) {
                clearInterval(autoRefreshInterval);
                autoRefreshBtn.innerHTML = '<span>⏱️</span> Auto-Actualizar: OFF';
                isAutoRefreshing = false;
            } else {
                autoRefreshInterval = setInterval(fetchData, 10000); // Actualizar cada 10 segundos
                autoRefreshBtn.innerHTML = '<span>⏱️</span> Auto-Actualizar: ON';
                isAutoRefreshing = true;
            }
        }
        
        // Event Listeners
        refreshBtn.addEventListener('click', fetchData);
        autoRefreshBtn.addEventListener('click', toggleAutoRefresh);
        downloadCSVBtn.addEventListener('click', downloadCSV);
        downloadJSONBtn.addEventListener('click', downloadJSON);
        applyDatesBtn.addEventListener('click', applyConfiguration);
        resetDatesBtn.addEventListener('click', resetConfiguration);
        
        // Inicializar aplicación
        function init() {
            setDefaultDates();
            initializeCharts();
            fetchData();
            
            // Iniciar auto-actualización por defecto
            autoRefreshInterval = setInterval(fetchData, 10000); // Actualizar cada 10 segundos
        }
        
        // Iniciar cuando el DOM esté listo
        document.addEventListener('DOMContentLoaded', init);
    </script>
</body>
</html>

@endsection