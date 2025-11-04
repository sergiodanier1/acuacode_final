@extends('layouts.app')

@section('content')
<div class="monitor-container">
    <div class="panel">

        <!-- Sección de Carga de Datos -->
        <div class="data-source-section">
            <h3>📁 Fuente de Datos</h3>
            <div class="source-controls">
                <div class="source-tabs">
                    <button class="source-tab active" data-source="simulacion">🔄 Simulación</button>
                    <button class="source-tab" data-source="json">📄 JSON</button>
                    <button class="source-tab" data-source="csv">📊 CSV</button>
                    <button class="source-tab" data-source="github">🌐 GitHub</button>
                </div>

                <!-- Panel de Simulación -->
                <div id="simulacion-panel" class="source-panel active">
                    <div class="panel-content">
                        <p>Generación automática de datos de sensores en tiempo real</p>
                        <div class="simulation-controls">
                            <button class="btn-light" id="btnIniciar">▶️ Iniciar Simulación</button>
                            <button class="btn-light" id="btnDetener" style="display: none;">⏹️ Detener Simulación</button>
                            <button class="btn-light" id="btnResetear">🔄 Reiniciar</button>
                        </div>
                    </div>
                </div>

                <!-- Panel de JSON -->
                <div id="json-panel" class="source-panel">
                    <div class="panel-content">
                        <p>Cargar datos desde archivo JSON</p>
                        <div class="file-controls">
                            <input type="file" id="jsonFile" accept=".json" class="file-input">
                            <button class="btn-light" id="btnCargarJSON">📁 Cargar JSON</button>
                        </div>
                        <div class="file-preview" id="jsonPreview"></div>
                    </div>
                </div>

                <!-- Panel de CSV -->
                <div id="csv-panel" class="source-panel">
                    <div class="panel-content">
                        <p>Cargar datos desde archivo CSV</p>
                        <div class="file-controls">
                            <input type="file" id="csvFile" accept=".csv" class="file-input">
                            <button class="btn-light" id="btnCargarCSV">📁 Cargar CSV</button>
                        </div>
                        <div class="file-preview" id="csvPreview"></div>
                    </div>
                </div>

                <!-- Panel de GitHub -->
                <div id="github-panel" class="source-panel">
                    <div class="panel-content">
                        <p>Cargar datos desde repositorio GitHub</p>
                        <div class="url-controls">
                            <input type="text" id="githubUrl" placeholder="https://raw.githubusercontent.com/usuario/repo/archivo.json" class="url-input" value="https://raw.githubusercontent.com/sergiodanier1/datos_acuacode/main/thingspeak_data.json">
                            <button class="btn-light" id="btnCargarGitHub">🌐 Cargar desde GitHub</button>
                        </div>
                        <div class="github-presets">
                            <button class="preset-btn" data-preset="thingspeak">📊 Datos ThingSpeak</button>
                            <button class="preset-btn" data-preset="acuaponia">💧 Datos Acuaponia</button>
                        </div>
                        <div class="file-preview" id="githubPreview"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Grid de gráficas -->
        <div class="grid-container">
            <div class="grafica-card">
                <div class="grafica-header">
                    <h3>⚡ Conductividad</h3>
                    <span class="valor-actual" id="valorConductividad">-- µS/cm</span>
                </div>
                <div class="grafica-container">
                    <canvas id="graficaConductividad"></canvas>
                </div>
            </div>

            <div class="grafica-card">
                <div class="grafica-header">
                    <h3>🧪 pH del Agua</h3>
                    <span class="valor-actual" id="valorPH">--</span>
                </div>
                <div class="grafica-container">
                    <canvas id="graficaPH"></canvas>
                </div>
            </div>

            <div class="grafica-card">
                <div class="grafica-header">
                    <h3>💨 Oxígeno Disuelto</h3>
                    <span class="valor-actual" id="valorOxigeno">-- mg/L</span>
                </div>
                <div class="grafica-container">
                    <canvas id="graficaOxigeno"></canvas>
                </div>
            </div>

            <div class="grafica-card">
                <div class="grafica-header">
                    <h3>🌡️ Temperatura del Agua</h3>
                    <span class="valor-actual" id="valorTemperatura">-- °C</span>
                </div>
                <div class="grafica-container">
                    <canvas id="graficaTemperatura"></canvas>
                </div>
            </div>
        </div>

        <!-- Estado del sistema -->
        <div class="status-row">
            <div>Estado del Monitoreo:</div>
            <div class="status-list">
                <div class="dot" id="statusDot" style="background:#ef4444"></div>
                <span id="statusText">Inactivo</span>
                <span id="dataSourceInfo" style="margin-left: 20px; color: var(--muted); font-size: 13px;"></span>
            </div>
        </div>

        <footer>
            Sistema de Monitoreo Acuapónico • Fuente de datos: <span id="currentDataSource">Simulación</span>
        </footer>
    </div>
</div>

<!-- Incluir CSS -->
<link href="{{ asset('css/DetallesSensores.css') }}" rel="stylesheet">

<!-- Incluir JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="{{ asset('js/DetallesSensores.js') }}"></script>
@endsection
