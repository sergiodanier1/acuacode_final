@extends('layouts.app')

@section('content')
<div class="alertas-container">
    <div class="panel">
        <!-- Encabezado -->
        <h1>Sistema de Alertas y Notificaciones</h1>
        <p class="lead">Monitoreo y gestión de alertas del sistema acuapónico</p>
        
        <!-- Resumen de alertas -->
        <div class="alertas-resumen">
            <div class="resumen-card critico">
                <div class="resumen-icon">🔥</div>
                <div class="resumen-info">
                    <div class="resumen-cantidad" id="cantidad-critico">0</div>
                    <div class="resumen-label">Críticas</div>
                </div>
            </div>
            <div class="resumen-card advertencia">
                <div class="resumen-icon">⚠️</div>
                <div class="resumen-info">
                    <div class="resumen-cantidad" id="cantidad-advertencia">0</div>
                    <div class="resumen-label">Advertencias</div>
                </div>
            </div>
            <div class="resumen-card info">
                <div class="resumen-icon">ℹ️</div>
                <div class="resumen-info">
                    <div class="resumen-cantidad" id="cantidad-info">0</div>
                    <div class="resumen-label">Informativas</div>
                </div>
            </div>
            <div class="resumen-card total">
                <div class="resumen-icon">📊</div>
                <div class="resumen-info">
                    <div class="resumen-cantidad" id="cantidad-total">0</div>
                    <div class="resumen-label">Total Activas</div>
                </div>
            </div>
        </div>

        <!-- Filtros y controles -->
        <div class="controles-alertas">
            <div class="filtros">
                <button class="filtro-btn active" data-tipo="todas">Todas</button>
                <button class="filtro-btn" data-tipo="critico">Críticas</button>
                <button class="filtro-btn" data-tipo="advertencia">Advertencias</button>
                <button class="filtro-btn" data-tipo="info">Informativas</button>
            </div>
            <div class="acciones">
                <button class="btn-light" id="marcar-todas">
                    <i class="fas fa-check-double"></i> <span class="btn-text">Marcar todas como leídas</span>
                </button>
                <button class="btn-light" id="limpiar-historial">
                    <i class="fas fa-trash"></i> <span class="btn-text">Limpiar historial</span>
                </button>
            </div>
        </div>

        <!-- Lista de alertas activas -->
        <div class="seccion-alertas">
            <h2 class="seccion-titulo">Alertas Activas</h2>
            <div class="alertas-lista" id="alertas-activas">
                <!-- Las alertas se generarán dinámicamente -->
            </div>
        </div>

        <!-- Historial de alertas -->
        <div class="seccion-alertas">
            <h2 class="seccion-titulo">Historial de Alertas</h2>
            <div class="filtros-historial">
                <select id="filtro-fecha" class="filtro-select">
                    <option value="7">Últimos 7 días</option>
                    <option value="30">Últimos 30 días</option>
                    <option value="90">Últimos 3 meses</option>
                    <option value="365">Último año</option>
                    <option value="todo">Todo el historial</option>
                </select>
                <select id="filtro-sensor" class="filtro-select">
                    <option value="todos">Todos los sensores</option>
                    <option value="temperatura">Temperatura</option>
                    <option value="ph">pH</option>
                    <option value="oxigeno">Oxígeno</option>
                    <option value="humedad">Humedad</option>
                    <option value="amoníaco">Amoníaco</option>
                </select>
            </div>
            <div class="alertas-lista" id="historial-alertas">
                <!-- El historial se generará dinámicamente -->
            </div>
        </div>

        <!-- Estadísticas -->
        <div class="estadisticas-alertas">
            <h2 class="seccion-titulo">Estadísticas de Alertas</h2>
            <div class="estadisticas-grid">
                <div class="estadistica-card">
                    <div class="estadistica-valor" id="promedio-diario">0</div>
                    <div class="estadistica-label">Alertas por día</div>
                </div>
                <div class="estadistica-card">
                    <div class="estadistica-valor" id="tiempo-respuesta">--</div>
                    <div class="estadistica-label">Tiempo promedio de respuesta</div>
                </div>
                <div class="estadistica-card">
                    <div class="estadistica-valor" id="sensores-problematicos">0</div>
                    <div class="estadistica-label">Sensores problemáticos</div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Base de datos simulada de alertas
    let alertasActivas = [];
    let historialAlertas = [];

    // Tipos de alertas y sus configuraciones
    const tiposAlerta = {
        critico: {
            nombre: 'Crítico',
            color: '#ef4444',
            icono: '🔥',
            prioridad: 3
        },
        advertencia: {
            nombre: 'Advertencia',
            color: '#f59e0b',
            icono: '⚠️',
            prioridad: 2
        },
        info: {
            nombre: 'Informativa',
            color: '#06b6d4',
            icono: 'ℹ️',
            prioridad: 1
        }
    };

    // Sensores del sistema
    const sensores = [
        'Temperatura del agua',
        'pH del agua',
        'Oxígeno disuelto',
        'Humedad ambiental',
        'Nivel de amoníaco',
        'Conductividad eléctrica',
        'Nivel de agua',
        'Bomba de circulación'
    ];

    // Mensajes de alerta predefinidos
    const mensajesAlerta = {
        temperatura: [
            'Temperatura crítica: valor fuera de rango seguro',
            'Fluctuación rápida de temperatura detectada',
            'Temperatura acercándose a límites peligrosos'
        ],
        ph: [
            'Nivel de pH fuera de rango óptimo',
            'Cambio brusco en el pH del agua',
            'pH en niveles peligrosos para los peces'
        ],
        oxigeno: [
            'Nivel de oxígeno disuelto críticamente bajo',
            'Oxígeno por debajo del nivel recomendado',
            'Sistema de aireación funcionando incorrectamente'
        ],
        humedad: [
            'Humedad ambiental fuera de parámetros',
            'Condiciones de humedad extremas detectadas'
        ],
        amoníaco: [
            'Nivel de amoníaco tóxico detectado',
            'Concentración de amoníaco en aumento'
        ]
    };

    // Inicializar el sistema de alertas
    function inicializarSistemaAlertas() {
        generarAlertasIniciales();
        actualizarResumen();
        renderizarAlertasActivas();
        renderizarHistorial();
        actualizarEstadisticas();
        
        // Simular nuevas alertas cada 30 segundos
        setInterval(simularNuevaAlerta, 30000);
        
        // Simular resolución de alertas cada 45 segundos
        setInterval(simularResolucionAlerta, 45000);
    }

    // Generar alertas iniciales para demostración
    function generarAlertasIniciales() {
        const alertasIniciales = [
            {
                id: 1,
                tipo: 'critico',
                sensor: 'Oxígeno disuelto',
                mensaje: 'Nivel de oxígeno críticamente bajo: 3.2 mg/L',
                timestamp: new Date(Date.now() - 300000), // 5 minutos atrás
                leida: false
            },
            {
                id: 2,
                tipo: 'advertencia',
                sensor: 'Temperatura del agua',
                mensaje: 'Temperatura acercándose a límite superior: 28.5°C',
                timestamp: new Date(Date.now() - 900000), // 15 minutos atrás
                leida: true
            },
            {
                id: 3,
                tipo: 'info',
                sensor: 'pH del agua',
                mensaje: 'pH ligeramente alto: 7.8 - monitorear',
                timestamp: new Date(Date.now() - 1800000), // 30 minutos atrás
                leida: false
            }
        ];

        alertasActivas = alertasIniciales;

        // Generar historial de alertas pasadas
        for (let i = 4; i <= 20; i++) {
            const diasAtras = Math.floor(Math.random() * 30) + 1;
            const tipoKeys = Object.keys(tiposAlerta);
            const tipoAleatorio = tipoKeys[Math.floor(Math.random() * tipoKeys.length)];
            const sensorAleatorio = sensores[Math.floor(Math.random() * sensores.length)];
            
            historialAlertas.push({
                id: i,
                tipo: tipoAleatorio,
                sensor: sensorAleatorio,
                mensaje: generarMensajeAleatorio(sensorAleatorio),
                timestamp: new Date(Date.now() - (diasAtras * 24 * 60 * 60 * 1000)),
                resuelta: true,
                timestampResolucion: new Date(Date.now() - ((diasAtras - 1) * 24 * 60 * 60 * 1000))
            });
        }
    }

    function generarMensajeAleatorio(sensor) {
        const claveSensor = sensor.toLowerCase().split(' ')[0];
        const mensajes = mensajesAlerta[claveSensor] || ['Anomalía detectada en el sistema'];
        return mensajes[Math.floor(Math.random() * mensajes.length)];
    }

    // Actualizar el resumen de alertas
    function actualizarResumen() {
        const critico = alertasActivas.filter(a => a.tipo === 'critico').length;
        const advertencia = alertasActivas.filter(a => a.tipo === 'advertencia').length;
        const info = alertasActivas.filter(a => a.tipo === 'info').length;
        const total = alertasActivas.length;

        document.getElementById('cantidad-critico').textContent = critico;
        document.getElementById('cantidad-advertencia').textContent = advertencia;
        document.getElementById('cantidad-info').textContent = info;
        document.getElementById('cantidad-total').textContent = total;
    }

    // Renderizar alertas activas
    function renderizarAlertasActivas(filtro = 'todas') {
        const contenedor = document.getElementById('alertas-activas');
        let alertasFiltradas = alertasActivas;

        if (filtro !== 'todas') {
            alertasFiltradas = alertasActivas.filter(a => a.tipo === filtro);
        }

        if (alertasFiltradas.length === 0) {
            contenedor.innerHTML = `
                <div class="alerta-vacia">
                    <i class="fas fa-check-circle"></i>
                    <p>No hay alertas ${filtro !== 'todas' ? tiposAlerta[filtro]?.nombre.toLowerCase() : ''} activas</p>
                </div>
            `;
            return;
        }

        contenedor.innerHTML = alertasFiltradas.map(alerta => `
            <div class="alerta-item ${alerta.tipo} ${alerta.leida ? 'leida' : ''}" data-id="${alerta.id}">
                <div class="alerta-icono">${tiposAlerta[alerta.tipo].icono}</div>
                <div class="alerta-contenido">
                    <div class="alerta-header">
                        <span class="alerta-sensor">${alerta.sensor}</span>
                        <span class="alerta-tiempo">${formatearTiempo(alerta.timestamp)}</span>
                    </div>
                    <div class="alerta-mensaje">${alerta.mensaje}</div>
                </div>
                <div class="alerta-acciones">
                    <button class="accion-btn marcar-leida" title="Marcar como leída">
                        <i class="fas fa-check"></i>
                    </button>
                    <button class="accion-btn resolver" title="Resolver alerta">
                        <i class="fas fa-flag-checkered"></i>
                    </button>
                </div>
            </div>
        `).join('');

        // Agregar event listeners a los botones
        document.querySelectorAll('.marcar-leida').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = parseInt(this.closest('.alerta-item').dataset.id);
                marcarComoLeida(id);
            });
        });

        document.querySelectorAll('.resolver').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = parseInt(this.closest('.alerta-item').dataset.id);
                resolverAlerta(id);
            });
        });
    }

    // Renderizar historial de alertas
    function renderizarHistorial() {
        const contenedor = document.getElementById('historial-alertas');
        const alertasOrdenadas = historialAlertas.sort((a, b) => b.timestamp - a.timestamp);

        if (alertasOrdenadas.length === 0) {
            contenedor.innerHTML = `
                <div class="alerta-vacia">
                    <i class="fas fa-history"></i>
                    <p>No hay alertas en el historial</p>
                </div>
            `;
            return;
        }

        contenedor.innerHTML = alertasOrdenadas.map(alerta => `
            <div class="alerta-item historial ${alerta.tipo}" data-id="${alerta.id}">
                <div class="alerta-icono">${tiposAlerta[alerta.tipo].icono}</div>
                <div class="alerta-contenido">
                    <div class="alerta-header">
                        <span class="alerta-sensor">${alerta.sensor}</span>
                        <span class="alerta-tiempo">${formatearFechaCompleta(alerta.timestamp)}</span>
                    </div>
                    <div class="alerta-mensaje">${alerta.mensaje}</div>
                    <div class="alerta-resolucion">
                        <i class="fas fa-check-circle"></i>
                        Resuelta: ${formatearFechaCompleta(alerta.timestampResolucion)}
                    </div>
                </div>
            </div>
        `).join('');
    }

    // Formatear tiempo relativo
    function formatearTiempo(timestamp) {
        const ahora = new Date();
        const diferencia = ahora - timestamp;
        const minutos = Math.floor(diferencia / 60000);
        const horas = Math.floor(diferencia / 3600000);
        const dias = Math.floor(diferencia / 86400000);

        if (minutos < 1) return 'Ahora mismo';
        if (minutos < 60) return `Hace ${minutos} min`;
        if (horas < 24) return `Hace ${horas} h`;
        return `Hace ${dias} d`;
    }

    // Formatear fecha completa
    function formatearFechaCompleta(timestamp) {
        return timestamp.toLocaleDateString('es-ES', {
            year: 'numeric',
            month: 'short',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
    }

    // Simular nueva alerta
    function simularNuevaAlerta() {
        const tipoKeys = Object.keys(tiposAlerta);
        const tipoAleatorio = tipoKeys[Math.floor(Math.random() * tipoKeys.length)];
        const sensorAleatorio = sensores[Math.floor(Math.random() * sensores.length)];
        
        const nuevaAlerta = {
            id: Date.now(),
            tipo: tipoAleatorio,
            sensor: sensorAleatorio,
            mensaje: generarMensajeAleatorio(sensorAleatorio),
            timestamp: new Date(),
            leida: false
        };

        alertasActivas.unshift(nuevaAlerta);
        actualizarResumen();
        renderizarAlertasActivas(document.querySelector('.filtro-btn.active').dataset.tipo);
        
        // Mostrar notificación
        mostrarNotificacion(nuevaAlerta);
    }

    // Simular resolución de alerta
    function simularResolucionAlerta() {
        if (alertasActivas.length > 0) {
            const alertaIndex = Math.floor(Math.random() * alertasActivas.length);
            const alertaResuelta = alertasActivas.splice(alertaIndex, 1)[0];
            
            alertaResuelta.resuelta = true;
            alertaResuelta.timestampResolucion = new Date();
            historialAlertas.unshift(alertaResuelta);
            
            actualizarResumen();
            renderizarAlertasActivas(document.querySelector('.filtro-btn.active').dataset.tipo);
            renderizarHistorial();
            actualizarEstadisticas();
        }
    }

    // Marcar alerta como leída
    function marcarComoLeida(id) {
        const alerta = alertasActivas.find(a => a.id === id);
        if (alerta) {
            alerta.leida = true;
            renderizarAlertasActivas(document.querySelector('.filtro-btn.active').dataset.tipo);
        }
    }

    // Resolver alerta
    function resolverAlerta(id) {
        const alertaIndex = alertasActivas.findIndex(a => a.id === id);
        if (alertaIndex !== -1) {
            const alertaResuelta = alertasActivas.splice(alertaIndex, 1)[0];
            alertaResuelta.resuelta = true;
            alertaResuelta.timestampResolucion = new Date();
            historialAlertas.unshift(alertaResuelta);
            
            actualizarResumen();
            renderizarAlertasActivas(document.querySelector('.filtro-btn.active').dataset.tipo);
            renderizarHistorial();
            actualizarEstadisticas();
        }
    }

    // Mostrar notificación de nueva alerta
    function mostrarNotificacion(alerta) {
        // Crear elemento de notificación
        const notificacion = document.createElement('div');
        notificacion.className = `notificacion ${alerta.tipo}`;
        notificacion.innerHTML = `
            <div class="notificacion-icono">${tiposAlerta[alerta.tipo].icono}</div>
            <div class="notificacion-contenido">
                <div class="notificacion-titulo">Nueva alerta ${tiposAlerta[alerta.tipo].nombre}</div>
                <div class="notificacion-mensaje">${alerta.mensaje}</div>
            </div>
            <button class="notificacion-cerrar">&times;</button>
        `;

        document.body.appendChild(notificacion);

        // Animación de entrada
        setTimeout(() => notificacion.classList.add('mostrar'), 100);

        // Cerrar notificación
        notificacion.querySelector('.notificacion-cerrar').addEventListener('click', () => {
            notificacion.classList.remove('mostrar');
            setTimeout(() => notificacion.remove(), 300);
        });

        // Auto-cerrar después de 5 segundos
        setTimeout(() => {
            if (notificacion.parentNode) {
                notificacion.classList.remove('mostrar');
                setTimeout(() => notificacion.remove(), 300);
            }
        }, 5000);
    }

    // Actualizar estadísticas
    function actualizarEstadisticas() {
        const ultimos7Dias = historialAlertas.filter(a => 
            a.timestamp > new Date(Date.now() - 7 * 24 * 60 * 60 * 1000)
        );
        
        document.getElementById('promedio-diario').textContent = 
            (ultimos7Dias.length / 7).toFixed(1);
        
        document.getElementById('sensores-problematicos').textContent = 
            new Set(historialAlertas.map(a => a.sensor)).size;
    }

    // Inicializar cuando el DOM esté listo
    document.addEventListener('DOMContentLoaded', function() {
        inicializarSistemaAlertas();

        // Configurar filtros
        document.querySelectorAll('.filtro-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.filtro-btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                renderizarAlertasActivas(this.dataset.tipo);
            });
        });

        // Configurar botones de acción
        document.getElementById('marcar-todas').addEventListener('click', function() {
            alertasActivas.forEach(alerta => alerta.leida = true);
            renderizarAlertasActivas(document.querySelector('.filtro-btn.active').dataset.tipo);
        });

        document.getElementById('limpiar-historial').addEventListener('click', function() {
            if (confirm('¿Está seguro de que desea limpiar todo el historial de alertas?')) {
                historialAlertas = [];
                renderizarHistorial();
                actualizarEstadisticas();
            }
        });
    });
</script>

<style>
    /* ---- Estilos base responsivos ---- */
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
      margin:0;min-height:100vh;background:linear-gradient(180deg,#071021 0%, #062033 100%);color:var(--text);
    }

    .alertas-container {
        min-height: calc(100vh - 60px);
        background: transparent !important;
        color: var(--text);
        padding: 20px;
        display: flex;
        align-items: flex-start;
        justify-content: center;
    }

    .panel{
        width: min(1200px, 95%);
        background:linear-gradient(180deg, rgba(255,255,255,0.02), rgba(255,255,255,0.01));
        border-radius:14px;
        padding: clamp(16px, 4vw, 26px);
        box-shadow:0 8px 30px rgba(2,6,23,0.6);
        margin: 0 auto;
    }

    h1{
        margin:0 0 8px 0;
        font-size: clamp(1.5rem, 4vw, 2rem);
        text-align: center;
        color: var(--text);
    }

    p.lead{
        margin:0 0 24px 0;
        color:var(--muted);
        font-size: clamp(0.9rem, 2.5vw, 1.1rem);
        text-align: center;
    }

    /* ---- Resumen de alertas responsivo ---- */
    .alertas-resumen {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(min(200px, 100%), 1fr));
        gap: clamp(12px, 3vw, var(--gap));
        margin-bottom: 30px;
    }

    .resumen-card {
        background: linear-gradient(180deg, rgba(255,255,255,0.01), rgba(255,255,255,0.005));
        border-radius: 12px;
        padding: clamp(12px, 3vw, 16px);
        display: flex;
        align-items: center;
        gap: clamp(8px, 2vw, 12px);
        min-height: 80px;
        justify-content: center;
        border: 1px solid rgba(255,255,255,0.03);
        transition: transform .12s ease, box-shadow .12s ease;
    }

    .resumen-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(2,6,23,0.45);
    }

    .resumen-card.critico { border-left: 4px solid #ef4444; }
    .resumen-card.advertencia { border-left: 4px solid #f59e0b; }
    .resumen-card.info { border-left: 4px solid #06b6d4; }
    .resumen-card.total { border-left: 4px solid #8b5cf6; }

    .resumen-icon {
        font-size: clamp(1.5rem, 4vw, 2rem);
        opacity: 0.8;
    }

    .resumen-cantidad {
        font-size: clamp(1.5rem, 4vw, 2.2rem);
        font-weight: bold;
        line-height: 1;
    }

    .resumen-card.critico .resumen-cantidad { color: #ef4444; }
    .resumen-card.advertencia .resumen-cantidad { color: #f59e0b; }
    .resumen-card.info .resumen-cantidad { color: #06b6d4; }
    .resumen-card.total .resumen-cantidad { color: #8b5cf6; }

    .resumen-label {
        color: var(--muted);
        font-size: clamp(0.8rem, 2vw, 0.9rem);
    }

    /* ---- Controles y filtros responsivos ---- */
    .controles-alertas {
        display: flex;
        flex-direction: column;
        gap: 16px;
        margin-bottom: 30px;
        padding: clamp(12px, 3vw, 16px);
        background: linear-gradient(180deg, rgba(255,255,255,0.01), rgba(255,255,255,0.005));
        border-radius: 12px;
        border: 1px solid rgba(255,255,255,0.03);
    }

    @media (min-width: 768px) {
        .controles-alertas {
            flex-direction: row;
            justify-content: space-between;
            align-items: center;
        }
    }

    .filtros {
        display: flex;
        gap: 6px;
        flex-wrap: wrap;
        justify-content: center;
    }

    .filtro-btn {
        padding: 8px 12px;
        border: 1px solid rgba(255,255,255,0.06);
        background: transparent;
        color: var(--muted);
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: clamp(0.8rem, 2vw, 0.9rem);
        white-space: nowrap;
    }

    .filtro-btn.active,
    .filtro-btn:hover {
        background: rgba(6,182,212,0.1);
        border-color: #06b6d4;
        color: #06b6d4;
    }

    .acciones {
        display: flex;
        gap: 8px;
        justify-content: center;
        flex-wrap: wrap;
    }

    .btn-light {
        padding: 10px 12px;
        border-radius: 8px;
        background: transparent;
        border: 1px solid rgba(255,255,255,0.06);
        color: var(--muted);
        cursor: pointer;
        font-size: clamp(0.8rem, 2vw, 0.9rem);
        display: flex;
        align-items: center;
        gap: 6px;
        transition: all 0.3s ease;
    }

    .btn-light:hover {
        background: rgba(255,255,255,0.05);
        color: var(--text);
    }

    /* ---- Lista de alertas responsiva ---- */
    .seccion-alertas {
        margin-bottom: 30px;
    }

    .seccion-titulo {
        font-size: clamp(1.1rem, 3vw, 1.3rem);
        margin-bottom: 16px;
        color: var(--accent);
        padding-bottom: 8px;
        border-bottom: 1px solid rgba(255,255,255,0.1);
    }

    .filtros-historial {
        display: flex;
        flex-direction: column;
        gap: 10px;
        margin-bottom: 16px;
    }

    @media (min-width: 480px) {
        .filtros-historial {
            flex-direction: row;
            gap: 12px;
        }
    }

    .filtro-select {
        padding: 10px 12px;
        border: 1px solid rgba(255,255,255,0.06);
        background: linear-gradient(180deg, rgba(255,255,255,0.01), rgba(255,255,255,0.005));
        color: var(--text);
        border-radius: 8px;
        font-size: clamp(0.8rem, 2vw, 0.9rem);
        flex: 1;
    }

    .alertas-lista {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .alerta-item {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        padding: 12px;
        background: linear-gradient(180deg, rgba(255,255,255,0.01), rgba(255,255,255,0.005));
        border-radius: 8px;
        border: 1px solid rgba(255,255,255,0.03);
        transition: all 0.3s ease;
    }

    @media (min-width: 768px) {
        .alerta-item {
            align-items: center;
        }
    }

    .alerta-item:hover {
        background: rgba(255,255,255,0.02);
    }

    .alerta-item.leida {
        opacity: 0.7;
    }

    .alerta-item.critico { border-left: 4px solid #ef4444; }
    .alerta-item.advertencia { border-left: 4px solid #f59e0b; }
    .alerta-item.info { border-left: 4px solid #06b6d4; }

    .alerta-icono {
        font-size: 1.3rem;
        width: 40px;
        text-align: center;
        flex-shrink: 0;
    }

    .alerta-contenido {
        flex: 1;
        min-width: 0; /* Permite que el contenido se ajuste */
    }

    .alerta-header {
        display: flex;
        flex-direction: column;
        gap: 4px;
        margin-bottom: 6px;
    }

    @media (min-width: 480px) {
        .alerta-header {
            flex-direction: row;
            justify-content: space-between;
            align-items: center;
        }
    }

    .alerta-sensor {
        font-weight: 600;
        color: var(--text);
        font-size: clamp(0.85rem, 2vw, 0.95rem);
    }

    .alerta-tiempo {
        font-size: clamp(0.75rem, 1.5vw, 0.8rem);
        color: var(--muted);
    }

    .alerta-mensaje {
        color: var(--text);
        line-height: 1.4;
        font-size: clamp(0.8rem, 2vw, 0.9rem);
        word-wrap: break-word;
    }

    .alerta-resolucion {
        font-size: clamp(0.75rem, 1.5vw, 0.8rem);
        color: #10b981;
        margin-top: 4px;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .alerta-acciones {
        display: flex;
        gap: 6px;
        flex-shrink: 0;
        margin-top: 8px;
    }

    @media (min-width: 768px) {
        .alerta-acciones {
            margin-top: 0;
        }
    }

    .accion-btn {
        padding: 8px;
        border: 1px solid rgba(255,255,255,0.06);
        background: transparent;
        color: var(--muted);
        border-radius: 6px;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 0.8rem;
        min-width: 36px;
        min-height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .accion-btn:hover {
        background: rgba(255,255,255,0.05);
        color: var(--text);
    }

    .alerta-vacia {
        text-align: center;
        padding: 40px 20px;
        color: var(--muted);
    }

    .alerta-vacia i {
        font-size: clamp(2rem, 6vw, 2.5rem);
        margin-bottom: 12px;
        opacity: 0.5;
    }

    .alerta-vacia p {
        font-size: clamp(0.9rem, 2.5vw, 1rem);
    }

    /* ---- Estadísticas responsivas ---- */
    .estadisticas-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(min(200px, 100%), 1fr));
        gap: clamp(12px, 3vw, var(--gap));
    }

    .estadistica-card {
        background: linear-gradient(180deg, rgba(255,255,255,0.01), rgba(255,255,255,0.005));
        border-radius: 12px;
        padding: clamp(16px, 3vw, 20px);
        text-align: center;
        border: 1px solid rgba(255,255,255,0.03);
    }

    .estadistica-valor {
        font-size: clamp(1.5rem, 4vw, 2.2rem);
        font-weight: bold;
        color: var(--accent);
        margin-bottom: 8px;
    }

    .estadistica-label {
        color: var(--muted);
        font-size: clamp(0.8rem, 2vw, 0.9rem);
        line-height: 1.3;
    }

    /* ---- Notificaciones responsivas ---- */
    .notificacion {
        position: fixed;
        top: 20px;
        right: 20px;
        left: 20px;
        background: rgba(15,23,42,0.95);
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: 8px;
        padding: 12px;
        display: flex;
        align-items: flex-start;
        gap: 12px;
        max-width: 400px;
        transform: translateX(400px);
        transition: transform 0.3s ease;
        z-index: 1000;
        backdrop-filter: blur(10px);
    }

    @media (min-width: 480px) {
        .notificacion {
            left: auto;
            right: 20px;
        }
    }

    .notificacion.mostrar {
        transform: translateX(0);
    }

    .notificacion.critico { border-left: 4px solid #ef4444; }
    .notificacion.advertencia { border-left: 4px solid #f59e0b; }
    .notificacion.info { border-left: 4px solid #06b6d4; }

    .notificacion-contenido {
        flex: 1;
        min-width: 0;
    }

    .notificacion-titulo {
        font-weight: 600;
        margin-bottom: 4px;
        font-size: clamp(0.9rem, 2vw, 0.95rem);
    }

    .notificacion-mensaje {
        font-size: clamp(0.8rem, 2vw, 0.85rem);
        line-height: 1.3;
    }

    .notificacion-cerrar {
        background: none;
        border: none;
        color: var(--muted);
        font-size: 1.1rem;
        cursor: pointer;
        padding: 0;
        width: 20px;
        height: 20px;
        flex-shrink: 0;
    }

    /* ---- Mejoras de accesibilidad y touch ---- */
    @media (prefers-reduced-motion: reduce) {
        .resumen-card,
        .alerta-item,
        .notificacion {
            transition: none;
        }
    }

    @media (hover: none) and (pointer: coarse) {
        .filtro-btn,
        .btn-light,
        .accion-btn {
            min-height: 44px;
            min-width: 44px;
        }
        
        .alerta-item {
            padding: 16px 12px;
        }
    }

    /* ---- Textos responsivos para botones ---- */
    .btn-text {
        display: inline;
    }

    @media (max-width: 480px) {
        .btn-text {
            display: none;
        }
        
        .btn-light {
            padding: 10px;
        }
    }

    /* ---- Soporte para orientación landscape ---- */
    @media (max-height: 500px) and (orientation: landscape) {
        .alertas-container {
            padding: 10px;
        }
        
        .panel {
            max-height: 90vh;
            overflow-y: auto;
        }
        
        .alertas-resumen {
            grid-template-columns: repeat(4, 1fr);
        }
        
        .resumen-card {
            min-height: 60px;
            padding: 8px;
        }
    }
</style>
@endsection