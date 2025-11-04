@extends('layouts.app')

@section('content')
<div class="monitor-container">
    <div class="panel">
        <!-- Encabezado -->
        <div>
            <h1>Monitor Ambiental con Gráficos</h1>
            <p class="lead">Visualización en tiempo real de datos de sensores</p>
        </div>        
        <!-- Barra de estado -->
        <div class="status-bar">
            <div class="last-update">Última actualización: <span id="update-time">-</span></div>
            <button class="btn-light" id="refresh-btn">
                <i class="fas fa-sync-alt"></i>
                <span>Actualizar Datos</span>
            </button>
        </div>
        
        <!-- Mensaje de error -->
        <div class="error" id="error-message">
            <i class="fas fa-exclamation-circle"></i>
            Error al cargar los datos. Por favor, verifique la conexión e intente nuevamente.
        </div>
        
        <!-- Tarjetas de datos -->
        <div class="data-cards">
            <div class="data-card temperature">
                <div class="data-icon"><i class="fas fa-temperature-high"></i></div>
                <div class="data-title">Temperatura</div>
                <div class="data-value" id="temperature">--</div>
                <div class="data-unit">°C</div>
            </div>
            
            <div class="data-card humidity">
                <div class="data-icon"><i class="fas fa-tint"></i></div>
                <div class="data-title">Humedad</div>
                <div class="data-value" id="humidity">--</div>
                <div class="data-unit">%</div>
            </div>
            
            <div class="data-card ph">
                <div class="data-icon"><i class="fas fa-vial"></i></div>
                <div class="data-title">pH</div>
                <div class="data-value" id="ph">--</div>
                <div class="data-unit">nivel</div>
            </div>
            
            <div class="data-card oxygen">
                <div class="data-icon"><i class="fas fa-wind"></i></div>
                <div class="data-title">Oxígeno Disuelto</div>
                <div class="data-value" id="oxygen">--</div>
                <div class="data-unit">mg/L</div>
            </div>
        </div>
        
        <!-- Dashboard de gráficas -->
        <div class="dashboard">
            <div class="chart-container">
                <div class="chart-title">
                    <i class="fas fa-temperature-high"></i>
                    <span>Temperatura (°C)</span>
                </div>
                <div class="chart-size">
                    <canvas id="tempChart"></canvas>
                </div>
            </div>
            
            <div class="chart-container">
                <div class="chart-title">
                    <i class="fas fa-tint"></i>
                    <span>Humedad (%)</span>
                </div>
                <div class="chart-size">
                    <canvas id="humidityChart"></canvas>
                </div>
            </div>
            
            <div class="chart-container">
                <div class="chart-title">
                    <i class="fas fa-vial"></i>
                    <span>Nivel de pH</span>
                </div>
                <div class="chart-size">
                    <canvas id="phChart"></canvas>
                </div>
            </div>
            
            <div class="chart-container">
                <div class="chart-title">
                    <i class="fas fa-wind"></i>
                    <span>Oxígeno Disuelto (mg/L)</span>
                </div>
                <div class="chart-size">
                    <canvas id="oxygenChart"></canvas>
                </div>
            </div>
        </div>
        
        <footer>
            <p>Sistema de monitoreo con gráficos en tiempo real | Datos obtenidos de sensores del sistema</p>
            <div class="data-source">Usando Chart.js para visualización de datos</div>
        </footer>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Elementos DOM
        const temperatureElement = document.getElementById('temperature');
        const humidityElement = document.getElementById('humidity');
        const phElement = document.getElementById('ph');
        const oxygenElement = document.getElementById('oxygen');
        const updateTimeElement = document.getElementById('update-time');
        const refreshButton = document.getElementById('refresh-btn');
        const errorMessage = document.getElementById('error-message');
        
        // Variables para almacenar datos históricos
        let timeLabels = [];
        let temperatureData = [];
        let humidityData = [];
        let phData = [];
        let oxygenData = [];
        
        // Inicializar gráficos
        const tempChart = new Chart(
            document.getElementById('tempChart'),
            createChartConfig('Temperatura (°C)', 'rgba(255, 126, 95, 0.3)', 'rgb(255, 126, 95)')
        );
        
        const humidityChart = new Chart(
            document.getElementById('humidityChart'),
            createChartConfig('Humedad (%)', 'rgba(0, 205, 172, 0.3)', 'rgb(0, 205, 172)')
        );
        
        const phChart = new Chart(
            document.getElementById('phChart'),
            createChartConfig('Nivel de pH', 'rgba(168, 255, 120, 0.3)', 'rgb(168, 255, 120)')
        );
        
        const oxygenChart = new Chart(
            document.getElementById('oxygenChart'),
            createChartConfig('Oxígeno Disuelto (mg/L)', 'rgba(142, 45, 226, 0.3)', 'rgb(142, 45, 226)')
        );
        
        // Configuración común para los gráficos
        function createChartConfig(label, bgColor, borderColor) {
            return {
                type: 'line',
                data: {
                    labels: [],
                    datasets: [{
                        label: label,
                        data: [],
                        backgroundColor: bgColor,
                        borderColor: borderColor,
                        borderWidth: 2,
                        pointRadius: 3,
                        pointBackgroundColor: borderColor,
                        pointBorderColor: '#fff',
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    animation: {
                        duration: 0
                    },
                    scales: {
                        y: {
                            beginAtZero: false,
                            grid: {
                                color: 'rgba(255, 255, 255, 0.1)'
                            },
                            ticks: {
                                color: '#e6eef8'
                            }
                        },
                        x: {
                            grid: {
                                color: 'rgba(255, 255, 255, 0.1)'
                            },
                            ticks: {
                                color: '#e6eef8',
                                maxTicksLimit: 6
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            };
        }
        
        // Función para formatear la fecha y hora
        function formatDateTime(date) {
            const options = { 
                hour: '2-digit', 
                minute: '2-digit', 
                second: '2-digit' 
            };
            return date.toLocaleTimeString('es-ES', options);
        }
        
        // Función para obtener y mostrar los datos
        function fetchData() {
            errorMessage.style.display = 'none';
            
            // En un caso real, aquí haríamos fetch a la API
            // Pero como es una demostración, simulamos la respuesta
            try {
                // Simulamos una respuesta del servidor
                const responseData = {
                    temperatura: (25 + Math.random() * 2).toFixed(1),
                    humedad: (55 + Math.random() * 10).toFixed(1),
                    ph: (6.8 + Math.random() * 0.8).toFixed(1),
                    oxigeno: (5 + Math.random() * 2).toFixed(1)
                };
                
                // Actualizar los valores en la interfaz
                temperatureElement.textContent = responseData.temperatura;
                humidityElement.textContent = responseData.humedad;
                phElement.textContent = responseData.ph;
                oxygenElement.textContent = responseData.oxigeno;
                
                // Actualizar la hora de la última actualización
                const now = new Date();
                updateTimeElement.textContent = formatDateTime(now);
                
                // Añadir datos a los históricos
                const timeLabel = formatDateTime(now);
                timeLabels.push(timeLabel);
                
                // Mantener solo los últimos 10 datos
                if (timeLabels.length > 10) {
                    timeLabels.shift();
                    temperatureData.shift();
                    humidityData.shift();
                    phData.shift();
                    oxygenData.shift();
                }
                
                // Agregar nuevos datos
                temperatureData.push(parseFloat(responseData.temperatura));
                humidityData.push(parseFloat(responseData.humedad));
                phData.push(parseFloat(responseData.ph));
                oxygenData.push(parseFloat(responseData.oxigeno));
                
                // Actualizar gráficos
                updateChart(tempChart, timeLabels, temperatureData);
                updateChart(humidityChart, timeLabels, humidityData);
                updateChart(phChart, timeLabels, phData);
                updateChart(oxygenChart, timeLabels, oxygenData);
                
            } catch (error) {
                console.error('Error:', error);
                errorMessage.style.display = 'block';
            }
        }
        
        // Función para actualizar un gráfico
        function updateChart(chart, labels, data) {
            chart.data.labels = labels;
            chart.data.datasets[0].data = data;
            chart.update();
        }
        
        // Cargar datos iniciales
        for (let i = 0; i < 5; i++) {
            // Simular algunos datos iniciales
            const mockDate = new Date();
            mockDate.setMinutes(mockDate.getMinutes() - (5 - i));
            
            timeLabels.push(formatDateTime(mockDate));
            temperatureData.push(24.5 + Math.random() * 2);
            humidityData.push(50 + Math.random() * 15);
            phData.push(6.5 + Math.random() * 1.5);
            oxygenData.push(4.5 + Math.random() * 2);
        }
        
        // Inicializar gráficos con datos iniciales
        updateChart(tempChart, timeLabels, temperatureData);
        updateChart(humidityChart, timeLabels, humidityData);
        updateChart(phChart, timeLabels, phData);
        updateChart(oxygenChart, timeLabels, oxygenData);
        
        // Cargar datos actuales
        fetchData();
        
        // Configurar la actualización al hacer clic en el botón
        refreshButton.addEventListener('click', fetchData);
        
        // Actualizar automáticamente cada 5 segundos
        setInterval(fetchData, 5000);
    });
</script>

<style>
    /* ---- Estilos originales actualizados ---- */
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
        text-align: center;
        padding: 20px;
        background: linear-gradient(135deg, rgba(6,182,212,0.1), rgba(124,58,237,0.1));
        border-radius: 12px;
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

    .status-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding: 15px;
        background: rgba(255,255,255,0.02);
        border-radius: 10px;
        border: 1px solid rgba(255,255,255,0.03);
    }

    .last-update {
        font-size: 0.9rem;
        color: var(--muted);
    }

    .btn-light {
        padding: 12px 24px;
        border-radius: 8px;
        background: transparent;
        border: 1px solid rgba(255,255,255,0.06);
        color: var(--muted);
        cursor: pointer;
        transition: all 0.3s ease;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .btn-light:hover {
        background: rgba(255,255,255,0.05);
        border-color: rgba(255,255,255,0.1);
        color: var(--text);
    }

    .error {
        text-align: center;
        padding: 20px;
        color: #ef4444;
        background: rgba(239, 68, 68, 0.1);
        border-radius: 10px;
        margin-bottom: 20px;
        display: none;
        border: 1px solid rgba(239, 68, 68, 0.2);
    }

    .data-cards {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .data-card {
        background: linear-gradient(180deg, rgba(255,255,255,0.01), rgba(255,255,255,0.005));
        border-radius: 12px;
        padding: 20px;
        text-align: center;
        border: 1px solid rgba(255,255,255,0.03);
        transition: all 0.3s ease;
    }

    .data-card:hover {
        transform: translateY(-2px);
        background: rgba(255,255,255,0.02);
        box-shadow: 0 8px 25px rgba(2,6,23,0.3);
    }

    .data-icon {
        font-size: 2.5rem;
        margin-bottom: 15px;
    }

    .data-title {
        font-size: 1rem;
        color: var(--muted);
        margin-bottom: 10px;
        font-weight: 600;
    }

    .data-value {
        font-size: 2rem;
        font-weight: bold;
        margin-bottom: 5px;
        color: var(--text);
    }

    .data-unit {
        font-size: 0.9rem;
        color: var(--muted);
    }

    /* Colores específicos para cada tarjeta */
    .temperature { border-top: 4px solid #ff7e5f; }
    .humidity { border-top: 4px solid #00cdac; }
    .ph { border-top: 4px solid #a8ff78; }
    .oxygen { border-top: 4px solid #8e2de2; }

    .temperature .data-icon { color: #ff7e5f; }
    .humidity .data-icon { color: #00cdac; }
    .ph .data-icon { color: #a8ff78; }
    .oxygen .data-icon { color: #8e2de2; }

    .dashboard {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 25px;
        margin-bottom: 30px;
    }

    .chart-container {
        background: linear-gradient(180deg, rgba(255,255,255,0.01), rgba(255,255,255,0.005));
        border-radius: 12px;
        padding: 20px;
        border: 1px solid rgba(255,255,255,0.03);
    }

    .chart-title {
        text-align: center;
        margin-bottom: 15px;
        color: var(--accent);
        font-weight: 600;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }

    .chart-size {
        height: 250px;
        position: relative;
    }

    footer {
        text-align: center;
        margin-top: 40px;
        color: var(--muted);
        font-size: 0.9rem;
        padding: 15px;
        border-top: 1px solid rgba(255,255,255,0.05);
    }

    .data-source {
        margin-top: 10px;
        font-size: 0.8rem;
        color: var(--accent);
    }

    /* Asegurar que todo el texto sea blanco */
    body, h1, h2, h3, h4, h5, h6, p, span, div, label, button {
        color: var(--text) !important;
    }

    @media (max-width: 900px) {
        .dashboard {
            grid-template-columns: 1fr;
        }
        
        .data-cards {
            grid-template-columns: repeat(2, 1fr);
        }
        
        .panel {
            padding: 20px;
        }
    }

    @media (max-width: 680px) {
        .monitor-container {
            padding: 15px;
        }
        
        .data-cards {
            grid-template-columns: 1fr;
        }
        
        .status-bar {
            flex-direction: column;
            gap: 15px;
            text-align: center;
        }
        
        .header {
            flex-direction: column;
            text-align: center;
            gap: 10px;
        }
        
        .chart-size {
            height: 200px;
        }
    }
</style>
@endsection