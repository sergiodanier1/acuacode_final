// Funcionalidad para los botones de los sensores
document.querySelectorAll('.btn-primary').forEach(button => {
    button.addEventListener('click', function() {
        const sensorTitle = this.closest('.sensor-card').querySelector('.sensor-title').textContent;
        alert(`Mostrando datos del sensor: ${sensorTitle}`);
    });
});

document.querySelectorAll('.btn-secondary').forEach(button => {
    button.addEventListener('click', function() {
        const sensorTitle = this.closest('.sensor-card').querySelector('.sensor-title').textContent;
        alert(`Abriendo configuración del sensor: ${sensorTitle}`);
    });
});

// Simular actualización de datos en tiempo real para los 4 sensores
function updateSensorReadings() {
    const readings = [
        { 
            selector: '.sensor-card:nth-child(1) .spec-value:last-child', 
            values: ['24.3°C', '24.5°C', '24.7°C', '24.8°C', '24.6°C'] 
        },
        { 
            selector: '.sensor-card:nth-child(2) .spec-value:last-child', 
            values: ['63% HR', '65% HR', '67% HR', '64% HR', '66% HR'] 
        },
        { 
            selector: '.sensor-card:nth-child(3) .spec-value:last-child', 
            values: ['6.7 pH', '6.8 pH', '6.9 pH', '6.85 pH', '6.75 pH'] 
        },
        { 
            selector: '.sensor-card:nth-child(4) .spec-value:last-child', 
            values: ['4.1 mg/L', '4.2 mg/L', '4.3 mg/L', '4.15 mg/L', '4.25 mg/L'] 
        }
    ];

    readings.forEach(reading => {
        const element = document.querySelector(reading.selector);
        if (element) {
            const randomValue = reading.values[Math.floor(Math.random() * reading.values.length)];
            element.textContent = randomValue;
        }
    });
}

// Actualizar cada 10 segundos
setInterval(updateSensorReadings, 10000);

// Inicialización cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    console.log('Sistema de sensores inicializado');
    updateSensorReadings(); // Ejecutar una vez al cargar
});