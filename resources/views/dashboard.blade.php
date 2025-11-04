@extends('layouts.app')

@section('content')
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema Acuapónico</title>
    
    <!-- Cargar CSS separado -->
    <link href="/css/dashboard.css" rel="stylesheet">
</head>
<body>
    <div class="panel">

        <!-- Información introductoria -->
        <div class="info-card">
            <h3>¿Qué es la Acuaponía?</h3>
            <p>La acuaponía es un sistema de producción sostenible que combina la acuicultura (cría de peces) con la hidroponía (cultivo de plantas en agua). En este sistema simbiótico, los desechos de los peces proporcionan nutrientes para las plantas, y las plantas ayudan a filtrar y limpiar el agua para los peces.</p>
            <button class="btn">Ver Tutorial</button>
        </div>

        <!-- Sección de métricas principales -->
        <div class="section-title">Métricas Principales</div>
        <div class="stats-grid">
            <div class="metric-card">
                <div class="metric-value">25.0°C</div>
                <div class="metric-label">Temperatura del Agua</div>
                <div class="metric-status">Óptimo</div>
            </div>
            <div class="metric-card">
                <div class="metric-value">6.8</div>
                <div class="metric-label">pH del Agua</div>
                <div class="metric-status warning">Revisar</div>
            </div>
            <div class="metric-card">
                <div class="metric-value">6.2 mg/L</div>
                <div class="metric-label">Oxígeno Disuelto</div>
                <div class="metric-status">Óptimo</div>
            </div>
            <div class="metric-card">
                <div class="metric-value">0.25 ppm</div>
                <div class="metric-label">Nivel de Amoníaco</div>
                <div class="metric-status danger">Alerta</div>
            </div>
        </div>

        <!-- Información sobre parámetros -->
        <div class="info-card">
            <h3>Parámetros Clave del Sistema</h3>
            <p>El éxito de un sistema acuapónico depende del equilibrio de varios parámetros. La temperatura ideal del agua está entre 18-30°C, el pH debe mantenerse entre 6.8-7.2 para un óptimo crecimiento de plantas y peces, y los niveles de amoníaco deben ser mínimos ya que son tóxicos para los peces.</p>
        </div>

        <!-- Sección de controles -->
        <div class="section-title">Estado del Sistema</div>
        <div class="grid">
            <div class="actuator-btn on" id="pump-btn">
                <div class="icon">💧</div>
                <div class="label">Bombas</div>
                <div class="small">Funcionando</div>
            </div>
            <div class="actuator-btn on" id="light-btn">
                <div class="icon">💡</div>
                <div class="label">Iluminación</div>
                <div class="small">85% intensidad</div>
            </div>
            <div class="actuator-btn on" id="aerator-btn">
                <div class="icon">🌀</div>
                <div class="label">Filtros</div>
                <div class="small">Activos</div>
            </div>
            <div class="actuator-btn on" id="feeder-btn">
                <div class="icon">🍽️</div>
                <div class="label">Alimentación</div>
                <div class="small">Programada</div>
            </div>
        </div>

        <div class="controls">
            <button class="btn-light">Ver Detalles</button>
        </div>

        <!-- Información sobre componentes -->
        <div class="info-card">
            <h3>Componentes del Sistema Acuapónico</h3>
            <p>Un sistema acuapónico típico incluye: tanque de peces, bomba de agua, filtro mecánico y biológico, camas de cultivo para plantas, y sistema de aireación. La bomba circula el agua del tanque de peces a las camas de cultivo, donde las plantas absorben los nutrientes y filtran el agua antes de regresar al tanque.</p>
        </div>

        <!-- Próximas tareas -->
        <div class="section-title">Próximas Tareas</div>
        <div class="info-card">
            <p>• Revisar niveles de nutrientes (en 2 días)</p>
            <p>• Limpieza de filtros (en 5 días)</p>
            <p>• Cosecha de lechugas (en 10 días)</p>
            <p>• Revisión general del sistema (en 14 días)</p>
            <button class="btn">Programar Tarea</button>
        </div>

        <!-- Alertas recientes -->
        <div class="section-title">Alertas Recientes</div>
        <div class="info-card">
            <p>• Nivel de amoníaco elevado</p>
            <p>• Temperatura fluctuante</p>
            <p>• Bajo nivel de agua en tanque</p>
            <button class="btn-light">Ver Todas las Alertas</button>
        </div>

        <!-- Beneficios de la acuaponía -->
        <div class="info-card">
            <h3>Beneficios de la Acuaponía</h3>
            <p>La acuaponía ofrece múltiples ventajas: uso eficiente del agua (hasta 90% menos que la agricultura tradicional), producción de alimentos orgánicos sin pesticidas, sistema cerrado que minimiza los desechos, y posibilidad de implementación en espacios urbanos reducidos.</p>
        </div>

        <!-- Actividad reciente -->
        <div class="section-title">Actividad Reciente</div>
        <div class="info-card">
            <ul class="activity-list">
                <li class="activity-item">
                    <div class="activity-time">10:30 AM</div>
                    <div>Sistema de alimentación activado</div>
                </li>
                <li class="activity-item">
                    <div class="activity-time">09:15 AM</div>
                    <div>Ajuste de pH realizado automáticamente</div>
                </li>
                <li class="activity-item">
                    <div class="activity-time">08:00 AM</div>
                    <div>Reporte diario generado</div>
                </li>
                <li class="activity-item">
                    <div class="activity-time">07:45 AM</div>
                    <div>Iluminación incrementada al 100%</div>
                </li>
                <li class="activity-item">
                    <div class="activity-time">06:30 AM</div>
                    <div>Monitoreo de parámetros completado</div>
                </li>
            </ul>
        </div>

        <!-- Estado del sistema -->
        <div class="status-row">
            <div>Estado del Sistema:</div>
            <div class="status-list">
                <div class="dot" style="background:#16a34a"></div>
                <span>Operativo</span>
                <div class="dot" style="background:#f59e0b"></div>
                <span>Advertencias</span>
                <div class="dot" style="background:#ef4444"></div>
                <span>Alertas</span>
            </div>
        </div>

        <footer>
            Sistema Acuapónico v2.1 • Última actualización: Hoy 14:30
        </footer>
    </div>

    <!-- Cargar JavaScript separado -->
    <script src="/js/dashboard.js"></script>
</body>
</html>
@endsection