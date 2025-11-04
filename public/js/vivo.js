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