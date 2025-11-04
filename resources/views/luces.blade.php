<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generador de Miniaturas YouTube</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, #1a2a6c, #b21f1f, #fdbb2d);
            color: white;
            min-height: 100vh;
            padding: 20px;
        }
        
        .container {
            max-width: 1400px;
            margin: 0 auto;
        }
        
        header {
            text-align: center;
            margin-bottom: 30px;
            padding: 20px;
            background: rgba(0, 0, 0, 0.3);
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }
        
        h1 {
            font-size: 2.5rem;
            margin-bottom: 10px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
        }
        
        .subtitle {
            font-size: 1.2rem;
            opacity: 0.9;
        }
        
        .main-content {
            display: flex;
            flex-wrap: wrap;
            gap: 30px;
            margin-bottom: 30px;
        }
        
        .controls {
            flex: 1;
            min-width: 300px;
            background: rgba(0, 0, 0, 0.3);
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            max-height: 80vh;
            overflow-y: auto;
        }
        
        .preview {
            flex: 2;
            min-width: 500px;
            background: rgba(0, 0, 0, 0.3);
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        
        .control-group {
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .control-group h3 {
            margin-bottom: 15px;
            color: #ffcc00;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
        }
        
        input, select, button, textarea {
            width: 100%;
            padding: 12px;
            border-radius: 8px;
            border: none;
            margin-bottom: 10px;
            font-size: 1rem;
        }
        
        input[type="color"] {
            height: 50px;
            padding: 5px;
        }
        
        input[type="range"] {
            padding: 0;
        }
        
        button {
            background: #ff6b6b;
            color: white;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 10px;
        }
        
        button:hover {
            background: #ff5252;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        
        .canvas-container {
            width: 100%;
            max-width: 640px;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
            margin-bottom: 20px;
            position: relative;
            cursor: move;
        }
        
        canvas {
            width: 100%;
            display: block;
        }
        
        .download-btn {
            background: #4CAF50;
            width: 80%;
            max-width: 300px;
        }
        
        .download-btn:hover {
            background: #45a049;
        }
        
        .file-inputs {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }
        
        .file-inputs > div {
            flex: 1;
        }
        
        .range-value {
            display: inline-block;
            width: 40px;
            text-align: center;
        }
        
        .slider-container {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .slider-container input {
            flex: 1;
        }
        
        .text-controls {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }
        
        .text-controls > div {
            margin-bottom: 0;
        }
        
        .element-info {
            background: rgba(255, 255, 255, 0.1);
            padding: 10px;
            border-radius: 8px;
            margin-top: 10px;
            font-size: 0.9rem;
        }
        
        .instructions {
            background: rgba(255, 255, 255, 0.1);
            padding: 15px;
            border-radius: 10px;
            margin-top: 20px;
            font-size: 0.9rem;
        }
        
        .instructions h4 {
            color: #ffcc00;
            margin-bottom: 10px;
        }
        
        .instructions ul {
            padding-left: 20px;
        }
        
        .instructions li {
            margin-bottom: 5px;
        }
        
        @media (max-width: 1100px) {
            .main-content {
                flex-direction: column;
            }
            
            .controls {
                max-height: none;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>Generador de Miniaturas YouTube Sergio Danier</h1>
        </header>
        
        <div class="main-content">
            <div class="controls">
                <div class="file-inputs">
                    <div class="control-group">
                        <label for="person-image">Imagen de Persona:</label>
                        <input type="file" id="person-image" accept="image/*">
                    </div>
                    
                    <div class="control-group">
                        <label for="background-image">Imagen de Fondo:</label>
                        <input type="file" id="background-image" accept="image/*">
                    </div>
                </div>
                
                <div class="control-group">
                    <h3>Texto Principal</h3>
                    <textarea id="text-input1" placeholder="Escribe tu texto principal aquí">$1.000.000</textarea>
                    
                    <div class="text-controls">
                        <div>
                            <label for="font-family1">Fuente:</label>
                            <select id="font-family1">
                                <option value="Arial, sans-serif">Arial</option>
                                <option value="'Times New Roman', serif">Times New Roman</option>
                                <option value="'Courier New', monospace">Courier New</option>
                                <option value="'Impact', sans-serif" selected>Impact</option>
                                <option value="'Comic Sans MS', cursive">Comic Sans MS</option>
                                <option value="'Georgia', serif">Georgia</option>
                            </select>
                        </div>
                        
                        <div>
                            <label for="font-size1">Tamaño (px):</label>
                            <div class="slider-container">
                                <input type="range" id="font-size1" min="20" max="120" value="70">
                                <span id="font-size-value1" class="range-value">70</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="text-controls">
                        <div>
                            <label for="font-color1">Color:</label>
                            <input type="color" id="font-color1" value="#ffffff">
                        </div>
                        
                        <div>
                            <label for="text-border-color1">Borde:</label>
                            <input type="color" id="text-border-color1" value="#000000">
                        </div>
                    </div>
                    
                    <div class="text-controls">
                        <div>
                            <label for="text-border-width1">Grosor Borde:</label>
                            <div class="slider-container">
                                <input type="range" id="text-border-width1" min="0" max="10" value="3">
                                <span id="text-border-width-value1" class="range-value">3</span>
                            </div>
                        </div>
                        
                        <div>
                            <label for="text-rotation1">Rotación (°):</label>
                            <div class="slider-container">
                                <input type="range" id="text-rotation1" min="-45" max="45" value="0">
                                <span id="text-rotation-value1" class="range-value">0</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="control-group">
                    <h3>Texto Secundario</h3>
                    <textarea id="text-input2" placeholder="Escribe tu texto secundario aquí">¡GANE ESTO!</textarea>
                    
                    <div class="text-controls">
                        <div>
                            <label for="font-family2">Fuente:</label>
                            <select id="font-family2">
                                <option value="Arial, sans-serif">Arial</option>
                                <option value="'Times New Roman', serif">Times New Roman</option>
                                <option value="'Courier New', monospace">Courier New</option>
                                <option value="'Impact', sans-serif" selected>Impact</option>
                                <option value="'Comic Sans MS', cursive">Comic Sans MS</option>
                                <option value="'Georgia', serif">Georgia</option>
                            </select>
                        </div>
                        
                        <div>
                            <label for="font-size2">Tamaño (px):</label>
                            <div class="slider-container">
                                <input type="range" id="font-size2" min="20" max="100" value="50">
                                <span id="font-size-value2" class="range-value">50</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="text-controls">
                        <div>
                            <label for="font-color2">Color:</label>
                            <input type="color" id="font-color2" value="#ffff00">
                        </div>
                        
                        <div>
                            <label for="text-border-color2">Borde:</label>
                            <input type="color" id="text-border-color2" value="#000000">
                        </div>
                    </div>
                    
                    <div class="text-controls">
                        <div>
                            <label for="text-border-width2">Grosor Borde:</label>
                            <div class="slider-container">
                                <input type="range" id="text-border-width2" min="0" max="10" value="3">
                                <span id="text-border-width-value2" class="range-value">3</span>
                            </div>
                        </div>
                        
                        <div>
                            <label for="text-rotation2">Rotación (°):</label>
                            <div class="slider-container">
                                <input type="range" id="text-rotation2" min="-45" max="45" value="0">
                                <span id="text-rotation-value2" class="range-value">0</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="control-group">
                    <h3>Efectos de Imagen</h3>
                    
                    <div class="text-controls">
                        <div>
                            <label for="glow-color">Resplandor:</label>
                            <input type="color" id="glow-color" value="#ff0000">
                        </div>
                        
                        <div>
                            <label for="glow-intensity">Intensidad:</label>
                            <div class="slider-container">
                                <input type="range" id="glow-intensity" min="0" max="50" value="15">
                                <span id="glow-intensity-value" class="range-value">15</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="text-controls">
                        <div>
                            <label for="person-rotation">Rotar Persona (°):</label>
                            <div class="slider-container">
                                <input type="range" id="person-rotation" min="-45" max="45" value="0">
                                <span id="person-rotation-value" class="range-value">0</span>
                            </div>
                        </div>
                        
                        <div>
                            <label for="person-scale">Tamaño Persona:</label>
                            <div class="slider-container">
                                <input type="range" id="person-scale" min="10" max="200" value="70">
                                <span id="person-scale-value" class="range-value">70%</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <button id="generate-btn">Generar Miniatura</button>
                
                <div class="instructions">
                    <h4>Instrucciones:</h4>
                    <ul>
                        <li><strong>Arrastrar elementos:</strong> Haz clic y arrastra el texto o la imagen para moverlos</li>
                        <li><strong>Rotar elementos:</strong> Usa los controles deslizantes de rotación</li>
                        <li><strong>Tamaño YouTube:</strong> La miniatura está optimizada para 1280x720 píxeles</li>
                        <li><strong>Texto impactante:</strong> Usa fuentes grandes y colores contrastantes</li>
                    </ul>
                </div>
            </div>
            
            <div class="preview">
                <div class="canvas-container" id="canvas-container">
                    <canvas id="preview-canvas" width="1280" height="720"></canvas>
                </div>
                
                <button id="download-btn" class="download-btn">Descargar Miniatura</button>
                
                <div class="element-info">
                    <p>💡 <strong>Consejo:</strong> Arrastra los textos y la imagen para colocarlos donde quieras</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Elementos del DOM
            const personImageInput = document.getElementById('person-image');
            const backgroundImageInput = document.getElementById('background-image');
            const textInput1 = document.getElementById('text-input1');
            const textInput2 = document.getElementById('text-input2');
            const fontFamilySelect1 = document.getElementById('font-family1');
            const fontFamilySelect2 = document.getElementById('font-family2');
            const fontSizeSlider1 = document.getElementById('font-size1');
            const fontSizeValue1 = document.getElementById('font-size-value1');
            const fontSizeSlider2 = document.getElementById('font-size2');
            const fontSizeValue2 = document.getElementById('font-size-value2');
            const fontColorInput1 = document.getElementById('font-color1');
            const fontColorInput2 = document.getElementById('font-color2');
            const textBorderColorInput1 = document.getElementById('text-border-color1');
            const textBorderColorInput2 = document.getElementById('text-border-color2');
            const textBorderWidthSlider1 = document.getElementById('text-border-width1');
            const textBorderWidthValue1 = document.getElementById('text-border-width-value1');
            const textBorderWidthSlider2 = document.getElementById('text-border-width2');
            const textBorderWidthValue2 = document.getElementById('text-border-width-value2');
            const textRotationSlider1 = document.getElementById('text-rotation1');
            const textRotationValue1 = document.getElementById('text-rotation-value1');
            const textRotationSlider2 = document.getElementById('text-rotation2');
            const textRotationValue2 = document.getElementById('text-rotation-value2');
            const glowColorInput = document.getElementById('glow-color');
            const glowIntensitySlider = document.getElementById('glow-intensity');
            const glowIntensityValue = document.getElementById('glow-intensity-value');
            const personRotationSlider = document.getElementById('person-rotation');
            const personRotationValue = document.getElementById('person-rotation-value');
            const personScaleSlider = document.getElementById('person-scale');
            const personScaleValue = document.getElementById('person-scale-value');
            const generateBtn = document.getElementById('generate-btn');
            const downloadBtn = document.getElementById('download-btn');
            const previewCanvas = document.getElementById('preview-canvas');
            const canvasContainer = document.getElementById('canvas-container');
            const ctx = previewCanvas.getContext('2d');
            
            // Estado de la aplicación
            let personImage = null;
            let backgroundImage = null;
            
            // Posiciones y estados de los elementos
            let elements = {
                text1: { x: 640, y: 250, text: "$1.000.000", dragging: false },
                text2: { x: 640, y: 450, text: "¡GANE ESTO!", dragging: false },
                person: { x: 640, y: 360, dragging: false }
            };
            
            // Inicializar canvas con tamaño para YouTube
            previewCanvas.width = 1280;
            previewCanvas.height = 720;
            
            // Configurar eventos de arrastre
            function setupDragEvents() {
                canvasContainer.addEventListener('mousedown', startDrag);
                canvasContainer.addEventListener('mousemove', drag);
                canvasContainer.addEventListener('mouseup', stopDrag);
                canvasContainer.addEventListener('mouseleave', stopDrag);
                
                canvasContainer.addEventListener('touchstart', startDrag);
                canvasContainer.addEventListener('touchmove', drag);
                canvasContainer.addEventListener('touchend', stopDrag);
            }
            
            // Iniciar arrastre
            function startDrag(e) {
                e.preventDefault();
                const rect = previewCanvas.getBoundingClientRect();
                const scaleX = previewCanvas.width / rect.width;
                const scaleY = previewCanvas.height / rect.height;
                
                const clientX = e.clientX || (e.touches && e.touches[0].clientX);
                const clientY = e.clientY || (e.touches && e.touches[0].clientY);
                
                const x = (clientX - rect.left) * scaleX;
                const y = (clientY - rect.top) * scaleY;
                
                // Verificar si se hizo clic en algún elemento
                for (const element in elements) {
                    if (isPointInElement(x, y, element)) {
                        elements[element].dragging = true;
                        break;
                    }
                }
            }
            
            // Arrastrar elemento
            function drag(e) {
                e.preventDefault();
                const rect = previewCanvas.getBoundingClientRect();
                const scaleX = previewCanvas.width / rect.width;
                const scaleY = previewCanvas.height / rect.height;
                
                const clientX = e.clientX || (e.touches && e.touches[0].clientX);
                const clientY = e.clientY || (e.touches && e.touches[0].clientY);
                
                const x = (clientX - rect.left) * scaleX;
                const y = (clientY - rect.top) * scaleY;
                
                // Mover el elemento que se está arrastrando
                for (const element in elements) {
                    if (elements[element].dragging) {
                        elements[element].x = x;
                        elements[element].y = y;
                        generateImage();
                        break;
                    }
                }
            }
            
            // Detener arrastre
            function stopDrag() {
                for (const element in elements) {
                    elements[element].dragging = false;
                }
            }
            
            // Verificar si un punto está dentro de un elemento
            function isPointInElement(x, y, element) {
                const el = elements[element];
                
                if (element === 'person' && personImage) {
                    const scale = (parseInt(personScaleSlider.value) / 100) * 0.7;
                    const width = personImage.width * scale;
                    const height = personImage.height * scale;
                    
                    return x >= el.x - width/2 && x <= el.x + width/2 &&
                           y >= el.y - height/2 && y <= el.y + height/2;
                }
                
                // Para texto, usar un área aproximada
                if (element.includes('text')) {
                    const text = element === 'text1' ? textInput1.value : textInput2.value;
                    const fontSize = element === 'text1' ? parseInt(fontSizeSlider1.value) : parseInt(fontSizeSlider2.value);
                    const approxWidth = text.length * fontSize * 0.6;
                    const approxHeight = fontSize * 1.2;
                    
                    return x >= el.x - approxWidth/2 && x <= el.x + approxWidth/2 &&
                           y >= el.y - approxHeight/2 && y <= el.y + approxHeight/2;
                }
                
                return false;
            }
            
            // Actualizar textos en elementos
            function updateTexts() {
                elements.text1.text = textInput1.value;
                elements.text2.text = textInput2.value;
            }
            
            // Aplicar efecto de resplandor a la imagen
            function applyGlowEffect(image, color, intensity) {
                return new Promise((resolve) => {
                    const tempCanvas = document.createElement('canvas');
                    const tempCtx = tempCanvas.getContext('2d');
                    
                    const padding = intensity;
                    tempCanvas.width = image.width + padding * 2;
                    tempCanvas.height = image.height + padding * 2;
                    
                    // Dibujar el resplandor
                    tempCtx.shadowColor = color;
                    tempCtx.shadowBlur = intensity;
                    tempCtx.drawImage(image, padding, padding);
                    
                    // Dibujar la imagen original encima
                    tempCtx.shadowBlur = 0;
                    tempCtx.drawImage(image, padding, padding);
                    
                    const resultImage = new Image();
                    resultImage.onload = function() {
                        resolve(resultImage);
                    };
                    resultImage.src = tempCanvas.toDataURL('image/png');
                });
            }
            
            // Cargar imagen de persona
            personImageInput.addEventListener('change', async function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = async function(event) {
                        personImage = new Image();
                        personImage.onload = function() {
                            generateImage();
                        };
                        personImage.src = event.target.result;
                    };
                    reader.readAsDataURL(file);
                }
            });
            
            // Cargar imagen de fondo
            backgroundImageInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(event) {
                        backgroundImage = new Image();
                        backgroundImage.onload = function() {
                            generateImage();
                        };
                        backgroundImage.src = event.target.result;
                    };
                    reader.readAsDataURL(file);
                }
            });
            
            // Actualizar controles
            function setupEventListeners() {
                textInput1.addEventListener('input', function() {
                    updateTexts();
                    generateImage();
                });
                
                textInput2.addEventListener('input', function() {
                    updateTexts();
                    generateImage();
                });
                
                fontSizeSlider1.addEventListener('input', function() {
                    fontSizeValue1.textContent = fontSizeSlider1.value;
                    generateImage();
                });
                
                fontSizeSlider2.addEventListener('input', function() {
                    fontSizeValue2.textContent = fontSizeSlider2.value;
                    generateImage();
                });
                
                textBorderWidthSlider1.addEventListener('input', function() {
                    textBorderWidthValue1.textContent = textBorderWidthSlider1.value;
                    generateImage();
                });
                
                textBorderWidthSlider2.addEventListener('input', function() {
                    textBorderWidthValue2.textContent = textBorderWidthSlider2.value;
                    generateImage();
                });
                
                textRotationSlider1.addEventListener('input', function() {
                    textRotationValue1.textContent = textRotationSlider1.value;
                    generateImage();
                });
                
                textRotationSlider2.addEventListener('input', function() {
                    textRotationValue2.textContent = textRotationSlider2.value;
                    generateImage();
                });
                
                glowIntensitySlider.addEventListener('input', function() {
                    glowIntensityValue.textContent = glowIntensitySlider.value;
                    generateImage();
                });
                
                personRotationSlider.addEventListener('input', function() {
                    personRotationValue.textContent = personRotationSlider.value;
                    generateImage();
                });
                
                personScaleSlider.addEventListener('input', function() {
                    personScaleValue.textContent = personScaleSlider.value + '%';
                    generateImage();
                });
                
                // Otros eventos de cambio
                const changeEvents = [
                    fontFamilySelect1, fontFamilySelect2, fontColorInput1, fontColorInput2,
                    textBorderColorInput1, textBorderColorInput2, glowColorInput
                ];
                
                changeEvents.forEach(element => {
                    element.addEventListener('input', generateImage);
                });
            }
            
            // Generar imagen
            generateBtn.addEventListener('click', generateImage);
            
            async function generateImage() {
                ctx.clearRect(0, 0, previewCanvas.width, previewCanvas.height);
                
                // Dibujar fondo
                if (backgroundImage) {
                    const scale = Math.max(
                        previewCanvas.width / backgroundImage.width,
                        previewCanvas.height / backgroundImage.height
                    );
                    const width = backgroundImage.width * scale;
                    const height = backgroundImage.height * scale;
                    const x = (previewCanvas.width - width) / 2;
                    const y = (previewCanvas.height - height) / 2;
                    
                    ctx.drawImage(backgroundImage, x, y, width, height);
                } else {
                    // Fondo por defecto
                    const gradient = ctx.createLinearGradient(0, 0, previewCanvas.width, previewCanvas.height);
                    gradient.addColorStop(0, '#1a2a6c');
                    gradient.addColorStop(0.5, '#b21f1f');
                    gradient.addColorStop(1, '#fdbb2d');
                    ctx.fillStyle = gradient;
                    ctx.fillRect(0, 0, previewCanvas.width, previewCanvas.height);
                }
                
                // Dibujar imagen de persona con efectos
                if (personImage) {
                    const personWithGlow = await applyGlowEffect(
                        personImage, 
                        glowColorInput.value, 
                        parseInt(glowIntensitySlider.value)
                    );
                    
                    const scale = (parseInt(personScaleSlider.value) / 100) * 0.7;
                    const width = personWithGlow.width * scale;
                    const height = personWithGlow.height * scale;
                    
                    // Rotar la imagen de la persona
                    ctx.save();
                    ctx.translate(elements.person.x, elements.person.y);
                    ctx.rotate(parseInt(personRotationSlider.value) * Math.PI / 180);
                    ctx.drawImage(personWithGlow, -width/2, -height/2, width, height);
                    ctx.restore();
                }
                
                // Dibujar texto 1
                drawText(
                    elements.text1.text,
                    elements.text1.x,
                    elements.text1.y,
                    fontFamilySelect1.value,
                    parseInt(fontSizeSlider1.value),
                    fontColorInput1.value,
                    textBorderColorInput1.value,
                    parseInt(textBorderWidthSlider1.value),
                    parseInt(textRotationSlider1.value)
                );
                
                // Dibujar texto 2
                drawText(
                    elements.text2.text,
                    elements.text2.x,
                    elements.text2.y,
                    fontFamilySelect2.value,
                    parseInt(fontSizeSlider2.value),
                    fontColorInput2.value,
                    textBorderColorInput2.value,
                    parseInt(textBorderWidthSlider2.value),
                    parseInt(textRotationSlider2.value)
                );
            }
            
            // Función para dibujar texto con efectos
            function drawText(text, x, y, fontFamily, fontSize, color, borderColor, borderWidth, rotation) {
                if (!text) return;
                
                ctx.save();
                ctx.translate(x, y);
                ctx.rotate(rotation * Math.PI / 180);
                
                ctx.textAlign = 'center';
                ctx.textBaseline = 'middle';
                ctx.font = `bold ${fontSize}px ${fontFamily}`;
                
                // Dibujar borde
                if (borderWidth > 0) {
                    ctx.strokeStyle = borderColor;
                    ctx.lineWidth = borderWidth;
                    ctx.strokeText(text, 0, 0);
                }
                
                // Dibujar texto
                ctx.fillStyle = color;
                ctx.fillText(text, 0, 0);
                
                ctx.restore();
            }
            
            // Descargar imagen
            downloadBtn.addEventListener('click', function() {
                const link = document.createElement('a');
                link.download = 'miniatura-youtube.png';
                link.href = previewCanvas.toDataURL('image/png');
                link.click();
            });
            
            // Inicializar aplicación
            setupDragEvents();
            setupEventListeners();
            updateTexts();
            generateImage();
        });
    </script>
</body>
</html>