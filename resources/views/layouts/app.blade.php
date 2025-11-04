<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ config('app.name', 'Acuacode') }}</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
  @vite(['resources/css/app.css','resources/js/app.js'])

  <style>
    body { font-family: 'Poppins', sans-serif; margin: 0; }

    /* Header */
    .header {
      height: 60px;
      background: #1a2b3c;
      color: #fff;
      display:flex;
      align-items:center;
      justify-content:space-between;
      padding: 0 20px;
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      z-index: 1100;
    }

    .header .logo { 
      font-weight: 600; 
      font-size:18px; 
      display: flex;
      align-items: center;
      gap: 10px;
    }
    
    .header .logo-img {
      height: 40px;
      width: auto;
    }
    
    .header .user { font-size:14px; opacity:0.9; }

    /* Sidebar */
    .sidebar {
      width: 280px;
      position: fixed;
      top: 60px;      /* debajo del header */
      left: 0;
      bottom: 0;      /* permite footer abajo */
      background: linear-gradient(to bottom, #003366, #00AEEF);
      color: #fff;
      display: flex;
      flex-direction: column;
      padding: 18px;
      box-sizing: border-box;
      z-index:1000;
    }

    .sidebar h2 { margin:0 0 10px 0; text-align:center; font-size:20px; }

    /* menu scrollable */
    .menu { flex: 1; overflow-y: auto; padding-right:6px; }

    .menu ul { list-style:none; padding:0; margin:0; }
    .menu li { margin:6px 0; }

    .menu a {
      display:block;
      padding:10px;
      border-radius:8px;
      color: white;
      text-decoration: none;
      cursor: pointer;
      transition: background .18s;
    }
    .menu a:hover { background: rgba(255,255,255,0.12); }

    /* active */
    .menu a.active {
      background: rgba(255,255,255,0.28);
      font-weight:600;
      color:#002233;
    }

    /* Footer dentro del sidebar */
    .sidebar-footer {
      font-size:12px;
      text-align:center;
      padding-top:10px;
      border-top: 1px solid rgba(255,255,255,0.12);
      line-height:1.4;
    }

    /* Contenido principal */
    .main {
      margin-left: 280px; /* espacio para sidebar */
      margin-top: 60px;   /* espacio header */
      padding: 22px;
    }

    /* Estilos para las imágenes del footer */
    .footer-images {
      display: flex;
      justify-content: center;
      align-items: center;
      gap: 15px;
      margin-top: 10px;
      margin-bottom: 10px;
    }

    .footer-img {
      height: 90px;
      width: auto;
      opacity: 0.9;
      transition: opacity 0.3s ease;
    }

    .footer-img:hover {
      opacity: 1;
    }

    .footer-text {
      margin-top: 5px;
      font-size: 11px;
    }

    .footer-link {
      color: white;
      text-decoration: none;
      transition: color 0.3s ease;
    }

    .footer-link:hover {
      color: #cce7ff;
    }

    /* responsive simple */
    @media (max-width:900px) {
      .sidebar { width: 220px; }
      .main { margin-left: 220px; }
      
      .footer-images {
        flex-direction: column;
        gap: 8px;
      }
      
      .footer-img {
        height: 35px;
      }
    }
    
    @media (max-width:600px) {
      .sidebar { width: 200px; }
      .main { margin-left: 200px; }
      
      .footer-img {
        height: 30px;
      }
      
      .footer-text {
        font-size: 10px;
      }
    }
  </style>
</head>
<body>
  @php
    // normalizo la ruta actual a minúsculas para comparaciones case-insensitive
    $current = strtolower(request()->path()); // ejemplo: 'sensores' o 'sensores/detallessensores'
  @endphp

  <!-- Header -->
  <div class="header">
    <div class="logo">
      <img src="{{ asset('imagenes/acuacode.png') }}" alt="Acuacode Logo" class="logo-img">
    </div>
    <div class="user">{{ Auth::user()->name ?? 'Invitado' }}</div>
  </div>

  <!-- Sidebar -->
  <div class="sidebar">
    <div>
      <nav class="menu" aria-label="Main menu">
        <ul>
          <li>
            <a href="{{ url('/dashboard') }}" class="{{ $current === 'dashboard' ? 'active' : '' }}">Inicio</a>
          </li>

          <li>
            <a href="{{ url('/Sensores') }}" class="{{ $current === 'sensores' ? 'active' : '' }}">Ver todos los sensores</a>
          </li>

          <li>
            <a href="{{ url('/Sensores/DetallesSensores') }}" class="{{ $current === 'sensores/detallessensores' ? 'active' : '' }}">Simulador de sensores</a>
          </li>

          <li>
            <a href="{{ url('/historicos') }}" class="{{ $current === 'historicos' ? 'active' : '' }}">Históricos</a>
          </li>
<!--          <li>
            <a href="{{ url('/historicos/mes') }}" class="{{ $current === 'historicos/mes' ? 'active' : '' }}">Históricos mes</a>
          </li>
-->
          <li>
            <a href="{{ url('/vivo') }}" class="{{ $current === 'vivo' ? 'active' : '' }}">Datos en vivo</a>
          </li>
          <li>
            <a href="{{ url('/Admin/Sensores') }}" class="{{ $current === 'admin/sensores' ? 'active' : '' }}">Administrar Sensores</a>
          </li>

<!--         <li>
            <a href="{{ url('/Control/bombas') }}" class="{{ $current === 'control/bombas' ? 'active' : '' }}">Activar / Desactivar bombas</a>
          </li>

          <li>
            <a href="{{ url('/Control/luces') }}" class="{{ $current === 'control/luces' ? 'active' : '' }}">Encender luces</a>
          </li>

          <li>
            <a href="{{ url('/Control/Valvulas') }}" class="{{ $current === 'control/valvulas' ? 'active' : '' }}">Ajustar válvulas</a>
          </li> -->

          <li>
            <a href="{{ url('/Alertas/Activas') }}" class="{{ $current === 'alertas/activas' ? 'active' : '' }}">Ver alertas activas</a>
          </li>
<!--
          <li>
            <a href="{{ url('/Alertas/Historial') }}" class="{{ $current === 'alertas/historial' ? 'active' : '' }}">Historial de alertas</a>
          </li>
          <li>
            <a href="{{ url('/Admin/Sistemas') }}" class="{{ strpos($current, 'admin/sistemas') !== false ? 'active' : '' }}">Administrar Sistemas</a>
          </li>

          <li>
            <a href="{{ url('/Admin/Operarios') }}" class="{{ strpos($current, 'admin/operarios') !== false ? 'active' : '' }}">Administrar Operarios</a>
          </li>
          <li>
            <a href="{{ url('/Admin/Granjas') }}" class="{{ strpos($current, 'admin/granjas') !== false ? 'active' : '' }}">Administrar Granjas</a>
          </li>

          <li>
            <a href="{{ url('/Usuarios') }}" class="{{ $current === 'usuarios' ? 'active' : '' }}">Usuarios</a>
          </li>

          <li>
            <a href="{{ url('/Config') }}" class="{{ $current === 'config' ? 'active' : '' }}">Configuración</a>
          </li>-->

        </ul>
      </nav>
    </div>

    <!-- Footer fijo abajo del sidebar -->
    <div class="sidebar-footer">
      
      <!-- Texto de créditos -->
      <div class="footer-text">
        <a href="{{ url('/creditos') }}" class="footer-link {{ $current === 'creditos' ? 'active' : '' }}">
          <strong>© 2025</strong>
          <br>Jesus Armando Gomez Garzon
          <br>Sergio Danier Cordoba Ceron
        </a>
      </div>
            <!-- Imágenes de las instituciones -->
      <div class="footer-images">
        <img src="{{ asset('imagenes/autonoma.png') }}" alt="Universidad Autónoma" class="footer-img">
      </div>
      <div class="footer-images">
        <img src="{{ asset('imagenes/sena.png') }}" alt="SENA" class="footer-img">
      </div>
    </div>
  </div>

  <!-- Contenido principal -->
  <div class="main">
    @yield('content')
  </div>
</body>
</html>