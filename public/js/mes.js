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
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
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
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
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