class DetallesSensores {
    constructor() {
        this.valoresMaximos = 15;
        this.tamañoMuestra = 3;
        this.intervaloSimulacion = null;
        this.currentDataSource = 'simulacion';
        
        // Buffers corregidos según orden ThingSpeak
        this.buffers = {
            conductividad: [],
            ph: [],
            oxigeno: [],
            temperatura: []
        };

        this.datos = {
            etiquetas: [],
            conductividad: [],
            ph: [],
            oxigeno: [],
            temperatura: []
        };

        this.graficas = {};
        
        // URLs del repositorio GitHub
        this.repoUrls = {
            thingspeak: 'https://raw.githubusercontent.com/sergiodanier1/datos_acuacode/main/thingspeak_data.json',
            acuaponia: 'https://raw.githubusercontent.com/sergiodanier1/datos_acuacode/main/datos_acuaponia.json'
        };

        this.init();
    }

    init() {
        this.crearGraficas();
        this.setupEventListeners();
        this.updateDataSourceInfo();
    }

    setupEventListeners() {
        // Event listeners para pestañas de fuentes de datos
        document.querySelectorAll('.source-tab').forEach(tab => {
            tab.addEventListener('click', (e) => {
                this.showSource(e.target.dataset.source);
            });
        });

        // Event listeners para botones de simulación
        document.getElementById('btnIniciar').addEventListener('click', () => this.iniciarSimulacion());
        document.getElementById('btnDetener').addEventListener('click', () => this.detenerSimulacion());
        document.getElementById('btnResetear').addEventListener('click', () => this.resetearSimulacion());

        // Event listeners para carga de archivos
        document.getElementById('btnCargarJSON').addEventListener('click', () => this.cargarJSON());
        document.getElementById('btnCargarCSV').addEventListener('click', () => this.cargarCSV());
        document.getElementById('btnCargarGitHub').addEventListener('click', () => this.cargarDesdeGitHub());

        // Event listeners para presets de GitHub
        document.querySelectorAll('.preset-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                this.cargarPreset(e.target.dataset.preset);
            });
        });
    }

    // Función para mostrar/ocultar paneles de fuente de datos
    showSource(source) {
        document.querySelectorAll('.source-panel').forEach(panel => {
            panel.classList.remove('active');
        });
        
        document.querySelectorAll('.source-tab').forEach(tab => {
            tab.classList.remove('active');
        });
        
        document.getElementById(`${source}-panel`).classList.add('active');
        document.querySelector(`[data-source="${source}"]`).classList.add('active');
        
        this.currentDataSource = source;
        this.updateDataSourceInfo();
    }

    // Actualizar información de fuente de datos
    updateDataSourceInfo() {
        const sourceNames = {
            'simulacion': 'Simulación en Tiempo Real',
            'json': 'Archivo JSON',
            'csv': 'Archivo CSV', 
            'github': 'GitHub URL'
        };
        
        document.getElementById('currentDataSource').textContent = sourceNames[this.currentDataSource];
        document.getElementById('dataSourceInfo').textContent = `Fuente: ${sourceNames[this.currentDataSource]}`;
    }

    // Cargar datos desde JSON
    cargarJSON() {
        const fileInput = document.getElementById('jsonFile');
        const file = fileInput.files[0];
        
        if (!file) {
            alert('Por favor selecciona un archivo JSON');
            return;
        }

        const reader = new FileReader();
        reader.onload = (e) => {
            try {
                const jsonData = JSON.parse(e.target.result);
                this.procesarDatosExternos(jsonData);
                document.getElementById('jsonPreview').innerHTML = 
                    `<div class="preview-success">✅ Archivo cargado correctamente</div>`;
            } catch (error) {
                document.getElementById('jsonPreview').innerHTML = 
                    `<div class="preview-error">❌ Error al cargar el archivo: ${error.message}</div>`;
            }
        };
        reader.readAsText(file);
    }

    // Cargar datos desde CSV
    cargarCSV() {
        const fileInput = document.getElementById('csvFile');
        const file = fileInput.files[0];
        
        if (!file) {
            alert('Por favor selecciona un archivo CSV');
            return;
        }

        const reader = new FileReader();
        reader.onload = (e) => {
            try {
                const csvData = e.target.result;
                const jsonData = this.convertirCSVaJSON(csvData);
                this.procesarDatosExternos(jsonData);
                document.getElementById('csvPreview').innerHTML = 
                    `<div class="preview-success">✅ Archivo CSV convertido correctamente</div>`;
            } catch (error) {
                document.getElementById('csvPreview').innerHTML = 
                    `<div class="preview-error">❌ Error al cargar el archivo CSV: ${error.message}</div>`;
            }
        };
        reader.readAsText(file);
    }

    // Convertir CSV a JSON
    convertirCSVaJSON(csv) {
        const lines = csv.split('\n');
        const headers = lines[0].split(',').map(h => h.trim());
        
        return lines.slice(1).filter(line => line.trim()).map(line => {
            const values = line.split(',').map(v => v.trim());
            const obj = {};
            headers.forEach((header, index) => {
                obj[header] = values[index] || '';
            });
            return obj;
        });
    }

    // Cargar datos desde GitHub
    cargarDesdeGitHub() {
        const url = document.getElementById('githubUrl').value.trim() || this.repoUrls.thingspeak;
        
        document.getElementById('githubPreview').innerHTML = '<div class="preview-loading">⏳ Cargando datos desde GitHub...</div>';

        fetch(url)
            .then(response => {
                if (!response.ok) throw new Error(`Error ${response.status}: ${response.statusText}`);
                return response.json();
            })
            .then(data => {
                this.procesarDatosExternos(data);
                document.getElementById('githubPreview').innerHTML = 
                    `<div class="preview-success">✅ Datos cargados correctamente desde GitHub</div>
                     <div style="margin-top: 10px; font-size: 0.85rem; color: var(--muted);">
                        <strong>📊 Datos cargados:</strong> ${data.feeds ? data.feeds.length + ' registros' : 'Array de ' + data.length + ' elementos'}
                     </div>`;
            })
            .catch(error => {
                document.getElementById('githubPreview').innerHTML = 
                    `<div class="preview-error">❌ Error al cargar desde GitHub: ${error.message}</div>
                     <div style="margin-top: 10px; font-size: 0.85rem; color: var(--muted);">
                        <strong>URL intentada:</strong> ${url}
                     </div>`;
            });
    }

    // Cargar datos predefinidos
    cargarPreset(tipo) {
        let url = '';
        
        if (tipo === 'thingspeak') {
            url = this.repoUrls.thingspeak;
        } else if (tipo === 'acuaponia') {
            url = this.repoUrls.acuaponia;
        }
        
        document.getElementById('githubUrl').value = url;
        this.cargarDesdeGitHub();
    }

    // Procesar datos externos (JSON, CSV, GitHub)
    procesarDatosExternos(data) {
        if (this.intervaloSimulacion) {
            this.detenerSimulacion();
        }

        this.resetearDatos();

        // Procesar según el formato de datos
        if (Array.isArray(data)) {
            // Datos en formato array
            data.forEach((item, index) => {
                const lectura = this.extraerLectura(item);
                if (lectura) {
                    this.agregarDatoExterno(lectura, index);
                }
            });
        } else if (data.feeds && Array.isArray(data.feeds)) {
            // Formato ThingSpeak - ORDEN CORREGIDO
            data.feeds.forEach((feed, index) => {
                const lectura = {
                    conductividad: parseFloat(feed.field1 || 0),  // field1 = Conductividad
                    ph: parseFloat(feed.field2 || 0),            // field2 = pH
                    oxigeno: parseFloat(feed.field3 || 0),       // field3 = Oxígeno Disuelto
                    temperatura: parseFloat(feed.field4 || 0)    // field4 = Temperatura
                };
                
                // Solo agregar si los datos son válidos
                if (!isNaN(lectura.conductividad) && !isNaN(lectura.ph) && 
                    !isNaN(lectura.oxigeno) && !isNaN(lectura.temperatura)) {
                    this.agregarDatoExterno(lectura, index);
                }
            });
        }

        this.actualizarGraficas();
        this.actualizarEstado('Datos Externos');
    }

    // Extraer lectura de diferentes formatos de datos
    extraerLectura(item) {
        // Formato ThingSpeak
        if (item.field1 !== undefined) {
            return {
                conductividad: parseFloat(item.field1) || 0,
                ph: parseFloat(item.field2) || 0,
                oxigeno: parseFloat(item.field3) || 0,
                temperatura: parseFloat(item.field4) || 0
            };
        }
        // Formato acuaponia
        else if (item.conductividad !== undefined) {
            return {
                conductividad: parseFloat(item.conductividad) || 0,
                ph: parseFloat(item.ph) || 0,
                oxigeno: parseFloat(item.oxigeno) || 0,
                temperatura: parseFloat(item.temperatura) || 0
            };
        }
        return null;
    }

    // Agregar dato externo al gráfico
    agregarDatoExterno(lectura, index) {
        const timestamp = lectura.created_at ? 
            new Date(lectura.created_at).toLocaleTimeString() : 
            `Dato ${index + 1}`;
        
        this.datos.etiquetas.push(timestamp);
        this.datos.conductividad.push(lectura.conductividad);
        this.datos.ph.push(lectura.ph);
        this.datos.oxigeno.push(lectura.oxigeno);
        this.datos.temperatura.push(lectura.temperatura);

        // Mantener solo los últimos valores
        if (this.datos.etiquetas.length > this.valoresMaximos) {
            this.datos.etiquetas.shift();
            this.datos.conductividad.shift();
            this.datos.ph.shift();
            this.datos.oxigeno.shift();
            this.datos.temperatura.shift();
        }

        // Actualizar último valor mostrado
        if (index === this.datos.etiquetas.length - 1) {
            document.getElementById('valorConductividad').textContent = lectura.conductividad.toFixed(1) + ' µS/cm';
            document.getElementById('valorPH').textContent = lectura.ph.toFixed(2);
            document.getElementById('valorOxigeno').textContent = lectura.oxigeno.toFixed(1) + ' mg/L';
            document.getElementById('valorTemperatura').textContent = lectura.temperatura.toFixed(1) + ' °C';
        }
    }

    // Crear gráficas - CON ESCALA AUTOMÁTICA
    crearGrafica(idCanvas, etiqueta, color) {
        const ctx = document.getElementById(idCanvas).getContext('2d');
        return new Chart(ctx, {
            type: 'line',
            data: {
                labels: this.datos.etiquetas,
                datasets: [{
                    label: etiqueta,
                    data: [],
                    borderColor: color,
                    backgroundColor: color + '20',
                    borderWidth: 2,
                    pointBackgroundColor: color,
                    pointBorderColor: '#fff',
                    pointRadius: 3,
                    pointHoverRadius: 5,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                animation: {
                    duration: 0
                },
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    x: {
                        grid: {
                            color: 'rgba(255,255,255,0.1)'
                        },
                        ticks: {
                            color: '#e6eef8',
                            maxTicksLimit: 6
                        }
                    },
                    y: {
                        grid: {
                            color: 'rgba(255,255,255,0.1)'
                        },
                        ticks: {
                            color: '#e6eef8'
                        },
                        // ESCALA AUTOMÁTICA HABILITADA
                        beginAtZero: false, // No forzar comenzar en cero
                        grace: '5%' // Espacio adicional para que los datos se vean mejor
                    }
                }
            }
        });
    }

    crearGraficas() {
        // Crear gráficas en el orden correcto
        this.graficas.conductividad = this.crearGrafica("graficaConductividad", "Conductividad (µS/cm)", "#06b6d4");
        this.graficas.ph = this.crearGrafica("graficaPH", "pH", "#10b981");
        this.graficas.oxigeno = this.crearGrafica("graficaOxigeno", "Oxígeno Disuelto (mg/L)", "#f59e0b");
        this.graficas.temperatura = this.crearGrafica("graficaTemperatura", "Temperatura (°C)", "#ef4444");
    }

    // Simulación
    iniciarSimulacion() {
        if (this.intervaloSimulacion) clearInterval(this.intervaloSimulacion);
        
        document.getElementById('btnIniciar').style.display = 'none';
        document.getElementById('btnDetener').style.display = 'block';
        this.actualizarEstado('Activo');

        this.intervaloSimulacion = setInterval(() => {
            const lectura = {
                conductividad: parseFloat((Math.random() * 500 + 200).toFixed(1)), // 200-700 µS/cm
                ph: parseFloat((Math.random() * 1.5 + 6.5).toFixed(2)),           // 6.5-8.0 pH
                oxigeno: parseFloat((Math.random() * 3 + 5).toFixed(1)),          // 5-8 mg/L
                temperatura: parseFloat((Math.random() * 5 + 22).toFixed(1))      // 22-27 °C
            };

            // Actualizar valores actuales
            document.getElementById('valorConductividad').textContent = lectura.conductividad + ' µS/cm';
            document.getElementById('valorPH').textContent = lectura.ph;
            document.getElementById('valorOxigeno').textContent = lectura.oxigeno + ' mg/L';
            document.getElementById('valorTemperatura').textContent = lectura.temperatura + ' °C';

            // Acumular en buffers
            this.buffers.conductividad.push(lectura.conductividad);
            this.buffers.ph.push(lectura.ph);
            this.buffers.oxigeno.push(lectura.oxigeno);
            this.buffers.temperatura.push(lectura.temperatura);

            if (this.buffers.conductividad.length === this.tamañoMuestra) {
                const promedio = {
                    conductividad: this.promedioDeArray(this.buffers.conductividad),
                    ph: this.promedioDeArray(this.buffers.ph),
                    oxigeno: this.promedioDeArray(this.buffers.oxigeno),
                    temperatura: this.promedioDeArray(this.buffers.temperatura)
                };

                this.agregarDatos(promedio);

                // Limpiar buffers
                this.buffers.conductividad = [];
                this.buffers.ph = [];
                this.buffers.oxigeno = [];
                this.buffers.temperatura = [];
            }
        }, 2000);
    }

    detenerSimulacion() {
        if (this.intervaloSimulacion) {
            clearInterval(this.intervaloSimulacion);
            this.intervaloSimulacion = null;
        }
        
        document.getElementById('btnIniciar').style.display = 'block';
        document.getElementById('btnDetener').style.display = 'none';
        this.actualizarEstado('Inactivo');
    }

    resetearSimulacion() {
        this.detenerSimulacion();
        this.resetearDatos();
        this.actualizarEstado('Inactivo');
    }

    resetearDatos() {
        this.datos.etiquetas = [];
        this.datos.conductividad = [];
        this.datos.ph = [];
        this.datos.oxigeno = [];
        this.datos.temperatura = [];

        this.buffers.conductividad = [];
        this.buffers.ph = [];
        this.buffers.oxigeno = [];
        this.buffers.temperatura = [];

        this.actualizarGraficas();

        // Resetear valores mostrados
        document.getElementById('valorConductividad').textContent = '-- µS/cm';
        document.getElementById('valorPH').textContent = '--';
        document.getElementById('valorOxigeno').textContent = '-- mg/L';
        document.getElementById('valorTemperatura').textContent = '-- °C';
    }

    promedioDeArray(arr) {
        const suma = arr.reduce((a, b) => a + b, 0);
        return parseFloat((suma / arr.length).toFixed(2));
    }

    agregarDatos(promedio) {
        const timestamp = new Date().toLocaleTimeString();
        this.datos.etiquetas.push(timestamp);
        this.datos.conductividad.push(promedio.conductividad);
        this.datos.ph.push(promedio.ph);
        this.datos.oxigeno.push(promedio.oxigeno);
        this.datos.temperatura.push(promedio.temperatura);

        if (this.datos.etiquetas.length > this.valoresMaximos) {
            this.datos.etiquetas.shift();
            this.datos.conductividad.shift();
            this.datos.ph.shift();
            this.datos.oxigeno.shift();
            this.datos.temperatura.shift();
        }

        this.actualizarGraficas();
    }

    // Actualizar gráficos con datos externos
    actualizarGraficas() {
        if (this.graficas.conductividad) {
            this.graficas.conductividad.data.labels = this.datos.etiquetas;
            this.graficas.conductividad.data.datasets[0].data = this.datos.conductividad;
            this.graficas.conductividad.update('none');
        }

        if (this.graficas.ph) {
            this.graficas.ph.data.labels = this.datos.etiquetas;
            this.graficas.ph.data.datasets[0].data = this.datos.ph;
            this.graficas.ph.update('none');
        }

        if (this.graficas.oxigeno) {
            this.graficas.oxigeno.data.labels = this.datos.etiquetas;
            this.graficas.oxigeno.data.datasets[0].data = this.datos.oxigeno;
            this.graficas.oxigeno.update('none');
        }

        if (this.graficas.temperatura) {
            this.graficas.temperatura.data.labels = this.datos.etiquetas;
            this.graficas.temperatura.data.datasets[0].data = this.datos.temperatura;
            this.graficas.temperatura.update('none');
        }
    }

    actualizarEstado(estado) {
        const statusDot = document.getElementById('statusDot');
        const statusText = document.getElementById('statusText');
        
        statusText.textContent = estado;
        
        if (estado === 'Activo') {
            statusDot.style.background = '#16a34a';
        } else if (estado === 'Datos Externos') {
            statusDot.style.background = '#f59e0b';
        } else {
            statusDot.style.background = '#ef4444';
        }
    }
}

// Inicializar la aplicación cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    new DetallesSensores();
});