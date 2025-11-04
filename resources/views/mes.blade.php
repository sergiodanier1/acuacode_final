@extends('layouts.app')

@section('content')
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monitoreo de Calidad del Agua - Base de Datos</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root {
            --bg: #0f172a;
            --card: #0b1220;
            --text: #e6eef8;
            --accent: #06b6d4;
            --muted: #9aa6bd;
        }
        
        * {
            box-sizing: border-box;
            font-family: Inter, ui-sans-serif, system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial;
        }
        
        body {
            margin: 0;
            min-height: 100vh;
            background: linear-gradient(180deg, #071021 0%, #062033 100%);
            color: var(--text);
            padding: 20px;
        }
        
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
        
        .action-buttons {
            display: flex;
            gap: 12px;
            margin-top: 16px;
            flex-wrap: wrap;
        }
        
        .action-btn {
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
        
        .action-btn:hover {
            background: rgba(255,255,255,0.03);
        }
        
        .action-btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
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
        
        .loader {
            width: 20px;
            height: 20px;
            border: 2px solid rgba(255,255,255,0.1);
            border-top: 2px solid var(--accent);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .data-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
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
        
        .chart-container {
            width: 100%;
            height: 300px;
            margin-top: 10px;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
            margin-bottom: 24px;
        }
        
        .stat-card {
            background: linear-gradient(180deg, rgba(255,255,255,0.01), rgba(255,255,255,0.005));
            border-radius: 12px;
            padding: 20px;
            text-align: center;
        }
        
        .stat-value {
            font-size: 24px;
            font-weight: 700;
            color: var(--text);
            margin-bottom: 8px;
        }
        
        .stat-label {
            font-size: 14px;
            color: var(--muted);
        }
        
        .success-message {
            background: rgba(22, 163, 74, 0.1);
            border: 1px solid rgba(22, 163, 74, 0.3);
            border-radius: 8px;
            padding: 12px;
            margin-top: 12px;
            color: #16a34a;
        }
        
        .error-message {
            background: rgba(225, 29, 72, 0.1);
            border: 1px solid rgba(225, 29, 72, 0.3);
            border-radius: 8px;
            padding: 12px;
            margin-top: 12px;
            color: #e11d48;
        }
        
        @media (max-width: 768px) {
            .charts-grid {
                grid-template-columns: 1fr;
            }
            
            .action-buttons {
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
                <p class="dashboard-subtitle">Datos históricos desde Base de Datos SQLite</p>
            </div>
        </div>
        
        <div class="config-section">
            <h3 class="config-title">Gestión de Datos</h3>
            <div class="action-buttons">
                <button class="action-btn" id="fetchDataBtn">
                    <span>🔄</span> Obtener Datos de ThingSpeak
                </button>
                <button class="action-btn" id="loadFromDbBtn">
                    <span>💾</span> Cargar desde Base de Datos
                </button>
                <button class="action-btn" id="clearDbBtn">
                    <span>🗑️</span> Limpiar Base de Datos
                </button>
                <button class="action-btn" id="downloadCsvBtn">
                    <span>📥</span> Descargar CSV
                </button>
            </div>
        </div>
        
        <div class="status-indicator" id="status">
            <div class="loader"></div>
            <span>Inicializando...</span>
        </div>

        <!-- Estadísticas -->
        <div class="stats-grid" id="statsContainer" style="display: none;">
            <div class="stat-card">
                <div class="stat-value" id="totalRecords">0</div>
                <div class="stat-label">Total de Registros</div>
            </div>
            <div class="stat-card">
                <div class="stat-value" id="dateRange">--</div>
                <div class="stat-label">Rango de Fechas</div>
            </div>
            <div class="stat-card">
                <div class="stat-value" id="lastUpdate">--</div>
                <div class="stat-label">Última Actualización</div>
            </div>
        </div>

        <!-- Datos actuales -->
        <div class="data-grid">
            <div class="data-card">
                <div class="data-card-title">
                    <span>⚡</span> Conductividad Actual
                </div>
                <div class="data-card-value" id="latestConductivity">--</div>
                <div class="data-card-unit">mS/cm</div>
            </div>
            
            <div class="data-card">
                <div class="data-card-title">
                    <span>🧪</span> pH Actual
                </div>
                <div class="data-card-value" id="latestPH">--</div>
                <div class="data-card-unit">pH</div>
            </div>
            
            <div class="data-card">
                <div class="data-card-title">
                    <span>💧</span> Oxígeno Disuelto Actual
                </div>
                <div class="data-card-value" id="latestOxygen">--</div>
                <div class="data-card-unit">mg/L</div>
            </div>
            
            <div class="data-card">
                <div class="data-card-title">
                    <span>🌡️</span> Temperatura Actual
                </div>
                <div class="data-card-value" id="latestTemperature">--</div>
                <div class="data-card-unit">°C</div>
            </div>
        </div>
        
        <!-- Gráficos -->
        <div class="charts-grid">
            <div class="chart-panel">
                <h3 class="chart-panel-title">
                    <span>⚡</span> Conductividad
                </h3>
                <div class="chart-container">
                    <canvas id="chartConductivity"></canvas>
                </div>
            </div>
            
            <div class="chart-panel">
                <h3 class="chart-panel-title">
                    <span>🧪</span> pH
                </h3>
                <div class="chart-container">
                    <canvas id="chartPH"></canvas>
                </div>
            </div>
            
            <div class="chart-panel">
                <h3 class="chart-panel-title">
                    <span>💧</span> Oxígeno Disuelto
                </h3>
                <div class="chart-container">
                    <canvas id="chartOxygen"></canvas>
                </div>
            </div>
            
            <div class="chart-panel">
                <h3 class="chart-panel-title">
                    <span>🌡️</span> Temperatura
                </h3>
                <div class="chart-container">
                    <canvas id="chartTemperature"></canvas>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Configuración
        const channelID = "2964378";
        const apiKey = "J007XMWSBU6301WM";
        const baseURL = "https://api.thingspeak.com/channels";
        const MAX_RESULTS = 8000;
        
        // Elementos DOM
        const statusElement = document.getElementById('status');
        const fetchDataBtn = document.getElementById('fetchDataBtn');
        const loadFromDbBtn = document.getElementById('loadFromDbBtn');
        const clearDbBtn = document.getElementById('clearDbBtn');
        const downloadCsvBtn = document.getElementById('downloadCsvBtn');
        const statsContainer = document.getElementById('statsContainer');
        
        // Variables para gráficos
        let charts = {};
        let currentData = [];
        
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
        
        // Obtener datos de ThingSpeak y guardar en BD
        async function fetchDataFromThingSpeak() {
            try {
                setStatus('Obteniendo datos de ThingSpeak...', 'loading');
                fetchDataBtn.disabled = true;
                
                const response = await fetch('/api/water-quality/fetch-data', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });
                
                const result = await response.json();
                
                if (!response.ok) {
                    throw new Error(result.message || 'Error al obtener datos');
                }
                
                setStatus(`Datos obtenidos y guardados: ${result.saved_count} nuevos registros`, 'success');
                
                // Cargar datos desde la base de datos
                await loadDataFromDatabase();
                
            } catch (error) {
                console.error('Error:', error);
                setStatus(`Error: ${error.message}`, 'error');
            } finally {
                fetchDataBtn.disabled = false;
            }
        }
        
        // Cargar datos desde la base de datos
        async function loadDataFromDatabase() {
            try {
                setStatus('Cargando datos desde la base de datos...', 'loading');
                loadFromDbBtn.disabled = true;
                
                const response = await fetch('/api/water-quality/data');
                const result = await response.json();
                
                if (!response.ok) {
                    throw new Error(result.message || 'Error al cargar datos');
                }
                
                currentData = result.data;
                
                if (currentData.length === 0) {
                    setStatus('No hay datos en la base de datos', 'warning');
                    statsContainer.style.display = 'none';
                    return;
                }
                
                // Procesar y mostrar datos
                processData(currentData);
                updateStats(currentData);
                statsContainer.style.display = 'grid';
                
                setStatus(`Datos cargados: ${currentData.length} registros`, 'success');
                
            } catch (error) {
                console.error('Error:', error);
                setStatus(`Error: ${error.message}`, 'error');
            } finally {
                loadFromDbBtn.disabled = false;
            }
        }
        
        // Limpiar base de datos
        async function clearDatabase() {
            if (!confirm('¿Estás seguro de que quieres eliminar todos los datos de la base de datos?')) {
                return;
            }
            
            try {
                setStatus('Limpiando base de datos...', 'loading');
                clearDbBtn.disabled = true;
                
                const response = await fetch('/api/water-quality/clear-data', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });
                
                const result = await response.json();
                
                if (!response.ok) {
                    throw new Error(result.message || 'Error al limpiar datos');
                }
                
                setStatus('Base de datos limpiada correctamente', 'success');
                currentData = [];
                resetCharts();
                statsContainer.style.display = 'none';
                
            } catch (error) {
                console.error('Error:', error);
                setStatus(`Error: ${error.message}`, 'error');
            } finally {
                clearDbBtn.disabled = false;
            }
        }
        
        // Descargar CSV
        function downloadCSV() {
            if (currentData.length === 0) {
                alert('No hay datos para descargar');
                return;
            }
            
            // Crear cabeceras CSV
            let csv = 'Fecha,Hora,Conductividad (mS/cm),pH,Oxígeno Disuelto (mg/L),Temperatura (°C)\n';
            
            // Agregar datos
            currentData.forEach(item => {
                const date = new Date(item.created_at);
                const fecha = date.toLocaleDateString();
                const hora = date.toLocaleTimeString();
                
                csv += `"${fecha}","${hora}","${item.conductivity || ''}","${item.ph || ''}","${item.oxygen || ''}","${item.temperature || ''}"\n`;
            });
            
            // Crear y descargar archivo
            const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
            const link = document.createElement('a');
            const url = URL.createObjectURL(blob);
            link.setAttribute('href', url);
            link.setAttribute('download', `datos_calidad_agua_${new Date().toISOString().split('T')[0]}.csv`);
            link.style.visibility = 'hidden';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }
        
        // Procesar y mostrar datos
        function processData(data) {
            // Tomar solo los últimos 100 datos para gráficos
            const displayData = data.slice(-100);
            
            // Preparar datos para gráficos
            const fieldData = {
                conductivity: [],
                ph: [],
                oxygen: [],
                temperature: []
            };
            
            const timestamps = [];
            
            // Recorrer datos para gráficos
            displayData.forEach(item => {
                const date = new Date(item.created_at);
                timestamps.push(date.toLocaleString());
                
                if (item.conductivity) fieldData.conductivity.push(parseFloat(item.conductivity));
                if (item.ph) fieldData.ph.push(parseFloat(item.ph));
                if (item.oxygen) fieldData.oxygen.push(parseFloat(item.oxygen));
                if (item.temperature) fieldData.temperature.push(parseFloat(item.temperature));
            });
            
            // Actualizar valores actuales (último registro)
            if (data.length > 0) {
                const lastItem = data[data.length - 1];
                
                document.getElementById('latestConductivity').textContent = lastItem.conductivity ? parseFloat(lastItem.conductivity).toFixed(2) : '--';
                document.getElementById('latestPH').textContent = lastItem.ph ? parseFloat(lastItem.ph).toFixed(2) : '--';
                document.getElementById('latestOxygen').textContent = lastItem.oxygen ? parseFloat(lastItem.oxygen).toFixed(2) : '--';
                document.getElementById('latestTemperature').textContent = lastItem.temperature ? parseFloat(lastItem.temperature).toFixed(2) : '--';
            }
            
            // Actualizar gráficos
            updateCharts(timestamps, fieldData);
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
        
        // Resetear gráficos
        function resetCharts() {
            Object.values(charts).forEach(chart => {
                chart.data.labels = [];
                chart.data.datasets[0].data = [];
                chart.update('none');
            });
            
            document.getElementById('latestConductivity').textContent = '--';
            document.getElementById('latestPH').textContent = '--';
            document.getElementById('latestOxygen').textContent = '--';
            document.getElementById('latestTemperature').textContent = '--';
        }
        
        // Actualizar estadísticas
        function updateStats(data) {
            document.getElementById('totalRecords').textContent = data.length;
            
            if (data.length > 0) {
                const dates = data.map(item => new Date(item.created_at));
                const minDate = new Date(Math.min(...dates));
                const maxDate = new Date(Math.max(...dates));
                
                document.getElementById('dateRange').textContent = 
                    `${minDate.toLocaleDateString()} - ${maxDate.toLocaleDateString()}`;
                
                document.getElementById('lastUpdate').textContent = 
                    maxDate.toLocaleString();
            }
        }
        
        // Establecer estado
        function setStatus(message, type = 'info') {
            const colors = {
                loading: '#06b6d4',
                success: '#16a34a',
                error: '#e11d48',
                warning: '#eab308',
                info: '#6b7280'
            };
            
            const icons = {
                loading: '<div class="loader"></div>',
                success: '✓',
                error: '✗',
                warning: '⚠',
                info: 'ℹ'
            };
            
            statusElement.innerHTML = `
                ${type === 'loading' ? icons.loading : `<span style="color: ${colors[type]}">${icons[type]}</span>`}
                <span>${message}</span>
            `;
        }
        
        // Event Listeners
        fetchDataBtn.addEventListener('click', fetchDataFromThingSpeak);
        loadFromDbBtn.addEventListener('click', loadDataFromDatabase);
        clearDbBtn.addEventListener('click', clearDatabase);
        downloadCsvBtn.addEventListener('click', downloadCSV);
        
        // Inicializar aplicación
        function init() {
            initializeCharts();
            setStatus('Aplicación inicializada. Haz clic en "Cargar desde Base de Datos" para ver los datos.', 'info');
        }
        
        // Iniciar cuando el DOM esté listo
        document.addEventListener('DOMContentLoaded', init);
    </script>
</body>
</html>
@endsection