@extends('layouts.app')

@section('content')
<div class="sensores-container">
    <!-- Encabezado de la página -->
    <div class="page-header">
        <h1>Sensores del Sistema Acuapónico</h1>
        <p>Monitoreo integral de todos los parámetros críticos para el óptimo funcionamiento de tu sistema acuapónico</p>
    </div>
    
    <!-- Resumen estadístico -->
    <div class="stats-overview">
        <div class="stat-card">
            <div class="stat-number">4</div>
            <div class="stat-label">Sensores Activos</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">95%</div>
            <div class="stat-label">Eficiencia General</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">1</div>
            <div class="stat-label">Alerta Activa</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">24/7</div>
            <div class="stat-label">Monitoreo Continuo</div>
        </div>
    </div>

    <!-- Sensores Principales -->
    <div class="category-section">
        <h2 class="category-title">Sensores Principales del Sistema</h2>
        <div class="sensor-grid">
            <!-- Sensor de Temperatura -->
            <div class="sensor-card">
                <div class="sensor-image">
                    <div class="sensor-icon">🌡️</div>
                </div>
                <div class="sensor-header">
                    <h3 class="sensor-title">Sensor de Temperatura</h3>
                    <span class="sensor-type-badge type-water">Ambiental</span>
                    <span class="sensor-status status-active">Activo</span>
                </div>
                <p class="sensor-description">
                    Controla la temperatura del agua para garantizar condiciones óptimas para la vida acuática y el crecimiento de plantas.
                </p>
                <div class="sensor-specs">
                    <div class="spec-item">
                        <span class="spec-label">Rango:</span>
                        <span class="spec-value">-10°C a 60°C</span>
                    </div>
                    <div class="spec-item">
                        <span class="spec-label">Precisión:</span>
                        <span class="spec-value">±0.1°C</span>
                    </div>
                    <div class="spec-item">
                        <span class="spec-label">Ubicación:</span>
                        <span class="spec-value">Tanque principal</span>
                    </div>
                    <div class="spec-item">
                        <span class="spec-label">Última lectura:</span>
                        <span class="spec-value">24.5°C</span>
                    </div>
                </div>
                <div class="sensor-actions">
                    <button class="btn btn-primary">Ver Datos</button>
                    <button class="btn btn-secondary">Configurar</button>
                </div>
            </div>

            <!-- Sensor de Humedad -->
            <div class="sensor-card">
                <div class="sensor-image">
                    <div class="sensor-icon">💧</div>
                </div>
                <div class="sensor-header">
                    <h3 class="sensor-title">Sensor de Humedad</h3>
                    <span class="sensor-type-badge type-air">Ambiental</span>
                    <span class="sensor-status status-active">Activo</span>
                </div>
                <p class="sensor-description">
                    Mide la humedad relativa del ambiente para optimizar las condiciones de crecimiento de las plantas.
                </p>
                <div class="sensor-specs">
                    <div class="spec-item">
                        <span class="spec-label">Rango:</span>
                        <span class="spec-value">0-100% HR</span>
                    </div>
                    <div class="spec-item">
                        <span class="spec-label">Precisión:</span>
                        <span class="spec-value">±2%</span>
                    </div>
                    <div class="spec-item">
                        <span class="spec-label">Ubicación:</span>
                        <span class="spec-value">Invernadero</span>
                    </div>
                    <div class="spec-item">
                        <span class="spec-label">Última lectura:</span>
                        <span class="spec-value">65% HR</span>
                    </div>
                </div>
                <div class="sensor-actions">
                    <button class="btn btn-primary">Ver Datos</button>
                    <button class="btn btn-secondary">Configurar</button>
                </div>
            </div>

            <!-- Sensor de pH -->
            <div class="sensor-card">
                <div class="sensor-image">
                    <div class="sensor-icon">🧪</div>
                </div>
                <div class="sensor-header">
                    <h3 class="sensor-title">Sensor de pH</h3>
                    <span class="sensor-type-badge type-water">Calidad Agua</span>
                    <span class="sensor-status status-active">Activo</span>
                </div>
                <p class="sensor-description">
                    Mide la acidez o alcalinidad del agua. Fundamental para la salud de peces y absorción de nutrientes.
                </p>
                <div class="sensor-specs">
                    <div class="spec-item">
                        <span class="spec-label">Rango:</span>
                        <span class="spec-value">0-14 pH</span>
                    </div>
                    <div class="spec-item">
                        <span class="spec-label">Precisión:</span>
                        <span class="spec-value">±0.01 pH</span>
                    </div>
                    <div class="spec-item">
                        <span class="spec-label">Ubicación:</span>
                        <span class="spec-value">Tanque principal</span>
                    </div>
                    <div class="spec-item">
                        <span class="spec-label">Última lectura:</span>
                        <span class="spec-value">6.8 pH</span>
                    </div>
                </div>
                <div class="sensor-actions">
                    <button class="btn btn-primary">Ver Datos</button>
                    <button class="btn btn-secondary">Configurar</button>
                </div>
            </div>

            <!-- Sensor de Oxígeno Disuelto -->
            <div class="sensor-card">
                <div class="sensor-image">
                    <div class="sensor-icon">💨</div>
                </div>
                <div class="sensor-header">
                    <h3 class="sensor-title">Sensor de Oxígeno Disuelto</h3>
                    <span class="sensor-type-badge type-water">Calidad Agua</span>
                    <span class="sensor-status status-critical">Crítico</span>
                </div>
                <p class="sensor-description">
                    Mide los niveles de oxígeno en el agua, esencial para la supervivencia de los peces y bacterias benéficas.
                </p>
                <div class="sensor-specs">
                    <div class="spec-item">
                        <span class="spec-label">Rango:</span>
                        <span class="spec-value">0-20 mg/L</span>
                    </div>
                    <div class="spec-item">
                        <span class="spec-label">Precisión:</span>
                        <span class="spec-value">±0.1 mg/L</span>
                    </div>
                    <div class="spec-item">
                        <span class="spec-label">Ubicación:</span>
                        <span class="spec-value">Tanque de peces</span>
                    </div>
                    <div class="spec-item">
                        <span class="spec-label">Última lectura:</span>
                        <span class="spec-value">4.2 mg/L</span>
                    </div>
                </div>
                <div class="sensor-actions">
                    <button class="btn btn-primary">Ver Datos</button>
                    <button class="btn btn-secondary">Configurar</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Cargar CSS separado -->
<link href="/css/sensores.css" rel="stylesheet">

<!-- Cargar JavaScript separado -->
<script src="/js/sensores.js"></script>
@endsection