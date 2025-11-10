@extends('layouts.app')

@section('content')
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créditos de Desarrollo Web</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
    /* ---- Tus estilos originales ---- */
    /* Quitar el marco blanco del layout */
    body, main, .container {
        background: linear-gradient(180deg, #071021 0%, #062033 100%) !important;
        margin: 0;
        padding: 0;
        border: none;
    }

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
      margin:0;min-height:100vh;background:linear-gradient(180deg,#071021 0%, #062033 100%);color:var(--text);display:flex;align-items:center;justify-content:center;padding:20px;
    }
    .panel{width:100%;max-width:1200px;background:linear-gradient(180deg, rgba(255,255,255,0.02), rgba(255,255,255,0.01));border-radius:14px;padding:20px;box-shadow:0 8px 30px rgba(2,6,23,0.6)}
    .header{display:flex;align-items:center;gap:16px;margin-bottom:18px}
    .logo{width:56px;height:56px;border-radius:10px;background:linear-gradient(135deg,var(--accent),#7c3aed);display:flex;align-items:center;justify-content:center;font-weight:700;font-size:20px}
    h1{margin:0;font-size:18px}
    p.lead{margin:0;color:var(--muted);font-size:13px}

    /* ---- Estilos para la página de créditos ---- */
    .credits-container {
        display: flex;
        flex-direction: column;
        gap: 30px;
    }
    
    .page-title {
        text-align: center;
        margin-bottom: 10px;
        font-size: clamp(24px, 5vw, 32px);
        background: linear-gradient(90deg, var(--accent), #7c3aed);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        line-height: 1.2;
    }
    
    .page-subtitle {
        text-align: center;
        color: var(--muted);
        margin-bottom: 30px;
        font-size: clamp(14px, 3vw, 16px);
        line-height: 1.4;
    }
    
    .developers-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 25px;
    }
    
    .developer-card {
        background: linear-gradient(180deg, rgba(255,255,255,0.01), rgba(255,255,255,0.005));
        border-radius: 14px;
        padding: clamp(20px, 4vw, 25px);
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .developer-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(2,6,23,0.8);
    }
    
    .developer-img {
        width: clamp(100px, 25vw, 140px);
        height: clamp(100px, 25vw, 140px);
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid var(--accent);
        margin-bottom: 20px;
    }
    
    .developer-name {
        font-size: clamp(18px, 4vw, 22px);
        margin: 0 0 8px 0;
        color: var(--text);
        line-height: 1.3;
    }
    
    .developer-role {
        color: var(--accent);
        margin: 0 0 15px 0;
        font-size: clamp(14px, 3vw, 16px);
        line-height: 1.4;
    }
    
    .developer-info {
        color: var(--muted);
        margin: 5px 0;
        font-size: clamp(12px, 2.5vw, 14px);
        word-break: break-word;
    }
    
    .developer-links {
        display: flex;
        gap: 15px;
        margin-top: 15px;
        flex-wrap: wrap;
        justify-content: center;
    }
    
    .developer-link {
        display: flex;
        align-items: center;
        gap: 6px;
        color: var(--accent);
        text-decoration: none;
        font-size: clamp(12px, 2.5vw, 14px);
        transition: color 0.2s;
        padding: 5px 10px;
    }
    
    .developer-link:hover {
        color: #7c3aed;
    }
    
    .technologies-section {
        margin-top: 30px;
    }
    
    .technologies-title {
        text-align: center;
        font-size: clamp(18px, 4vw, 20px);
        margin-bottom: 20px;
        color: var(--text);
    }
    
    .technologies-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
        gap: 15px;
    }
    
    .technology-item {
        background: linear-gradient(180deg, rgba(255,255,255,0.01), rgba(255,255,255,0.005));
        border-radius: 10px;
        padding: 15px;
        text-align: center;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 8px;
    }
    
    .technology-icon {
        font-size: clamp(24px, 6vw, 30px);
        color: var(--accent);
    }
    
    .technology-name {
        font-size: clamp(12px, 2.5vw, 14px);
        color: var(--text);
    }
    
    footer {
        margin-top: 30px;
        color: var(--muted);
        font-size: clamp(12px, 2.5vw, 13px);
        text-align: center;
        padding: 20px 0 10px 0;
    }

    /* Media Queries específicas para diferentes tamaños */
    @media (max-width: 768px) {
        body {
            padding: 15px;
            align-items: flex-start;
            min-height: 100vh;
        }
        
        .panel {
            padding: 15px;
            margin: 10px 0;
        }
        
        .developers-grid {
            grid-template-columns: 1fr;
            gap: 20px;
        }
        
        .developer-card {
            padding: 20px 15px;
        }
        
        .technologies-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
        }
        
        .developer-links {
            gap: 10px;
        }
        
        .developer-link {
            padding: 8px 12px;
            font-size: 13px;
        }
    }
    
    @media (max-width: 480px) {
        body {
            padding: 10px;
        }
        
        .panel {
            padding: 12px;
            border-radius: 12px;
        }
        
        .page-title {
            font-size: 22px;
        }
        
        .page-subtitle {
            font-size: 14px;
            margin-bottom: 20px;
        }
        
        .developers-grid {
            gap: 15px;
        }
        
        .developer-card {
            padding: 15px 12px;
        }
        
        .developer-img {
            width: 90px;
            height: 90px;
            margin-bottom: 15px;
        }
        
        .developer-name {
            font-size: 18px;
        }
        
        .developer-role {
            font-size: 14px;
        }
        
        .technologies-grid {
            grid-template-columns: 1fr;
            gap: 10px;
        }
        
        .technology-item {
            padding: 12px;
        }
        
        .developer-links {
            flex-direction: column;
            gap: 8px;
            width: 100%;
        }
        
        .developer-link {
            justify-content: center;
            padding: 10px;
        }
    }
    
    @media (max-width: 320px) {
        .panel {
            padding: 10px;
        }
        
        .developer-card {
            padding: 12px 8px;
        }
        
        .developer-img {
            width: 80px;
            height: 80px;
        }
        
        .page-title {
            font-size: 20px;
        }
    }

    /* Mejoras para tablets en orientación horizontal */
    @media (min-width: 769px) and (max-width: 1024px) {
        .developers-grid {
            grid-template-columns: repeat(2, 1fr);
        }
        
        .technologies-grid {
            grid-template-columns: repeat(3, 1fr);
        }
    }

    /* Mejoras para pantallas muy grandes */
    @media (min-width: 1400px) {
        .panel {
            max-width: 1400px;
        }
        
        .developers-grid {
            grid-template-columns: repeat(3, 1fr);
        }
    }
    </style>
</head>
<body>
    <div class="panel">
        <div class="credits-container">
            <h1 class="page-title">Créditos de Desarrollo Web</h1>
            <p class="page-subtitle" style="margin-top: -30px;">Proyecto desarrollado por estudiantes de Ingeniería Electrónica</p>
            
            <div class="developers-grid" style="margin-top: -30px;">
                <!-- Tarjeta de Sergio -->
                <div class="developer-card">
                    <img src="{{ asset('imagenes/sergio.jpg') }}" alt="Sergio Danier Córdoba Cerón" class="developer-img">
                    <h2 class="developer-name">Sergio Danier Córdoba Cerón</h2>
                    <p class="developer-role">Estudiante de Ingeniería Electrónica</p>
                    <p class="developer-role">Desarrollo de sofware</p>
                    <p class="developer-info">sergiodanier@gmail.com</p>
                    <div class="developer-links">
                        <a href="https://www.facebook.com/SergioDanier" target="_blank" class="developer-link">
                            <i class="fab fa-facebook"></i> Facebook
                        </a>
                    </div>
                </div>
                <!-- Tarjeta de Jesús -->
                <div class="developer-card">
                    <img src="{{ asset('imagenes/jesus.jfif') }}" alt="Jesús Armando Gómez Garzón" class="developer-img">
                    <h2 class="developer-name">Jesús Armando Gómez Garzón</h2>
                    <p class="developer-role">Estudiante de Ingeniería Electrónica</p>
                    <p class="developer-role">Desarrollo de sofware</p>
                    <p class="developer-info">jesus.gomez.garzon@uniautonoma.edu.co</p>
                    <div class="developer-links">
                        <a href="https://www.facebook.com/jesusarmando.gomez.75" target="_blank" class="developer-link">
                            <i class="fab fa-facebook"></i> Facebook
                        </a>
                    </div>
                </div>
                            
                <!-- Tarjeta de Elian -->
                <div class="developer-card">
                    <img src="{{ asset('imagenes/elian.png') }}" alt="Elian Andrés Oliveros Valencia" class="developer-img">
                    <h2 class="developer-name">Elian Andrés Oliveros Valencia</h2>
                    <p class="developer-role">Estudiante de Ingeniería Electrónica</p>
                    <p class="developer-role">Desarrollo de hadware</p>
                    <p class="developer-info">elian-19-07@hotmail.com</p>
                    <div class="developer-links">
                        <a href="https://www.facebook.com/elianandres.oliveros?mibextid=ZbWKwL" target="_blank" class="developer-link">
                            <i class="fab fa-facebook"></i> Facebook
                        </a>
                    </div>
                </div>
            </div>
            <h1 class="page-title">Docentes e instructores</h1>
            <div class="developers-grid">
                <div class="developer-card">
                    <img src="{{ asset('imagenes/liliana.jpeg') }}" alt="Gloria Liliana López" class="developer-img">
                    <h2 class="developer-name">Gloria Liliana López</h2>
                    <p class="developer-role">Magister en Sistemas Mecatrónicos</p>
                    <p class="developer-role">Docente investigador</p>
                    <p class="developer-info">gllopez@unicauca.edu.co</p>
                </div>
                <!-- Tarjeta de Jesús -->
                <div class="developer-card">
                    <img src="{{ asset('imagenes/yesid.jpeg') }}" swidth="100" height="250" alt="Yesid Enrique Castro Caicedo" class="developer-img">
                    <h2 class="developer-name">Yesid Enrique Castro Caicedo</h2>
                    <p class="developer-role">MsC Ingeniero Físico</p>
                    <p class="developer-role">Docente investigador</p>
                    <p class="developer-info">yesid.castro.c@uniautonoma.edu.co</p>
                </div>
                            
                <!-- Tarjeta de Elian -->
                <div class="developer-card">
                    <img src="{{ asset('imagenes/jose.jpeg') }}" alt="Elian Andrés Oliveros Valencia" class="developer-img">
                    <h2 class="developer-name">José Geovanny Angulo Imbachi</h2>
                    <p class="developer-role">Ingeniero de Sistemas</p>
                    <p class="developer-role">Apoyo a la ejecución del proyecto SGPS 12110</p>
                    <p class="developer-info">jgangulo@misena.edu.co </p>
                </div>
            </div>
            
            <div class="technologies-section">
                <h3 class="technologies-title">Tecnologías Utilizadas</h3>
                <div class="technologies-grid">
                    <div class="technology-item">
                        <i class="fab fa-laravel technology-icon"></i>
                        <span class="technology-name">Laravel</span>
                    </div>
                    <div class="technology-item">
                        <i class="fab fa-js technology-icon"></i>
                        <span class="technology-name">JavaScript</span>
                    </div>
                    <div class="technology-item">
                        <i class="fab fa-bootstrap technology-icon"></i>
                        <span class="technology-name">Bootstrap</span>
                    </div>
                    <div class="technology-item">
                        <i class="fab fa-css3-alt technology-icon"></i>
                        <span class="technology-name">CSS3</span>
                    </div>
                </div>
            </div>
            
            <footer>
                <p>© 2025 - Proyecto de Desarrollo Web - Ingeniería Electrónica</p>
            </footer>
        </div>
    </div>
</body>
</html>
@endsection