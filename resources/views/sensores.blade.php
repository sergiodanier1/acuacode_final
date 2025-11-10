@extends('layouts.app')

@section('content')
<div class="sensores-container" style="margin-top: -40px;">
    <!-- Encabezado de la página -->
    <div class="page-header info-card">
        <h1>Sensores del Sistema Acuapónico</h1>
        <p class="lead">Monitoreo integral de todos los parámetros críticos para el óptimo funcionamiento de tu sistema acuapónico</p>
    </div>
    
    <!-- Sensores Principales -->
    <div class="info-card">
        <h3 class="section-title">Sensores Principales del Sistema</h3>
        <div class="grid">
            <!-- Sensor de Temperatura -->
            <div class="card">
                <div class="sensor-header">
                    <div class="sensor-icon">🌡️</div>
                    <h4 class="sensor-title">Sensor de Temperatura</h4>
                    <div class="status-list">
                        <span class="metric-status">Ambiental</span>
                        <span class="metric-status">Activo</span>
                    </div>
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
                </div>
                <div class="controls">
                    <button class="btn"><a href='/historicos'>Ver Datos</a></button>
                </div>
            </div>

            <!-- Sensor de Humedad -->
            <div class="card">
                <div class="sensor-header">
                    <div class="sensor-icon">💧</div>
                    <h4 class="sensor-title">Sensor de Humedad</h4>
                    <div class="status-list">
                        <span class="metric-status">Ambiental</span>
                        <span class="metric-status">Activo</span>
                    </div>
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
                </div>
                <div class="controls">
                    <button class="btn"><a href='/historicos'>Ver Datos</a></button>
                </div>
            </div>

            <!-- Sensor de pH -->
            <div class="card">
                <div class="sensor-header">
                    <div class="sensor-icon">🧪</div>
                    <h4 class="sensor-title">Sensor de pH</h4>
                    <div class="status-list">
                        <span class="metric-status">Calidad Agua</span>
                        <span class="metric-status">Activo</span>
                    </div>
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
                </div>
                <div class="controls">
                    <button class="btn"><a href='/historicos'>Ver Datos</a></button>
                </div>
            </div>

            <!-- Sensor de Oxígeno Disuelto -->
            <div class="card">
                <div class="sensor-header">
                    <div class="sensor-icon">💨</div>
                    <h4 class="sensor-title">Sensor de Oxígeno Disuelto</h4>
                    <div class="status-list">
                        <span class="metric-status">Calidad Agua</span>
                        <span class="metric-status danger">Crítico</span>
                    </div>
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
                </div>
                <div class="controls">
                    <button class="btn"><a href='/historicos'>Ver Datos</a></button>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Cargar CSS separado -->
<link href="/css/sergio.css" rel="stylesheet">

<!-- Cargar JavaScript separado -->
<script src="/js/sensores.js"></script>
@endsection