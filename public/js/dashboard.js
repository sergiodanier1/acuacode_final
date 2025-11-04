// Funcionalidad para los botones de control
document.querySelectorAll('.actuator-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const isOn = this.classList.contains('on');
        
        if (isOn) {
            this.classList.remove('on');
            this.classList.add('off');
            this.querySelector('.small').textContent = 'Desactivado';
        } else {
            this.classList.remove('off');
            this.classList.add('on');
            
            // Texto específico para cada botón
            const label = this.querySelector('.label').textContent;
            if (label === 'Bombas') {
                this.querySelector('.small').textContent = 'Funcionando';
            } else if (label === 'Iluminación') {
                this.querySelector('.small').textContent = '85% intensidad';
            } else if (label === 'Filtros') {
                this.querySelector('.small').textContent = 'Activos';
            } else if (label === 'Alimentación') {
                this.querySelector('.small').textContent = 'Programada';
            }
        }
    });
});

// Simulación de actualización de datos en tiempo real
function updateMetrics() {
    const tempValue = document.querySelector('.stats-grid .metric-card:nth-child(1) .metric-value');
    const phValue = document.querySelector('.stats-grid .metric-card:nth-child(2) .metric-value');
    
    // Simular pequeñas variaciones en los valores
    const currentTemp = parseFloat(tempValue.textContent);
    const currentPh = parseFloat(phValue.textContent);
    
    // Actualizar con pequeñas variaciones (simulación)
    tempValue.textContent = (currentTemp + (Math.random() * 0.4 - 0.2)).toFixed(1) + '°C';
    phValue.textContent = (currentPh + (Math.random() * 0.1 - 0.05)).toFixed(1);
    
    // Actualizar la hora de última actualización
    const now = new Date();
    document.querySelector('footer').textContent = 
        `Sistema Acuapónico v2.1 • Última actualización: ${now.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}`;
}

// Actualizar cada 5 segundos
setInterval(updateMetrics, 5000);

// Funcionalidad para los botones
document.querySelector('.btn').addEventListener('click', function() {
    alert('Tutorial del sistema acuapónico abierto');
});

// Inicialización cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    console.log('Sistema Acuapónico inicializado');
});