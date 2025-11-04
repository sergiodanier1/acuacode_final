<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ config('app.name', 'Acuacode') }}</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
  @vite(['resources/css/app.css','resources/js/app.js'])

  <style>
    body { 
      font-family: 'Poppins', sans-serif; 
      margin: 0; 
    }

    /* Header */
    .header {
      height: 60px;
      background: #1a2b3c;
      color: #fff;
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 0 20px;
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      z-index: 1100;
    }

    .header-left {
      display: flex;
      align-items: center;
      gap: 15px;
    }

    .header .logo { 
      font-weight: 600; 
      font-size: 18px; 
      display: flex;
      align-items: center;
      gap: 10px;
    }
    
    .header .logo-img {
      height: 40px;
      width: auto;
    }
    
    .header .user { 
      font-size: 14px; 
      opacity: 0.9; 
    }

    /* Botón hamburguesa para móviles */
    .menu-toggle {
      display: none;
      background: none;
      border: none;
      color: white;
      font-size: 24px;
      cursor: pointer;
      padding: 5px;
    }

    /* Menú de usuario desplegable */
    .user-menu {
      position: relative;
      display: inline-block;
    }

    .user-toggle {
      background: rgba(255, 255, 255, 0.1);
      border: 1px solid rgba(255, 255, 255, 0.3);
      color: white;
      padding: 8px 15px;
      border-radius: 6px;
      cursor: pointer;
      font-size: 14px;
      transition: all 0.3s ease;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .user-toggle:hover {
      background: rgba(255, 255, 255, 0.2);
      border-color: rgba(255, 255, 255, 0.5);
    }

    .user-toggle::after {
      content: '▼';
      font-size: 10px;
      transition: transform 0.3s ease;
    }

    .user-toggle.active::after {
      transform: rotate(180deg);
    }

    .dropdown-menu {
      position: absolute;
      top: 100%;
      right: 0;
      background: white;
      border-radius: 8px;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
      min-width: 180px;
      padding: 8px 0;
      margin-top: 5px;
      opacity: 0;
      visibility: hidden;
      transform: translateY(-10px);
      transition: all 0.3s ease;
      z-index: 1200;
    }

    .dropdown-menu.active {
      opacity: 1;
      visibility: visible;
      transform: translateY(0);
    }

    .dropdown-item {
      display: block;
      padding: 10px 16px;
      color: #333;
      text-decoration: none;
      font-size: 14px;
      transition: background 0.2s ease;
      border: none;
      background: none;
      width: 100%;
      text-align: left;
      cursor: pointer;
    }

    .dropdown-item:hover {
      background: #f5f5f5;
    }

    .dropdown-divider {
      height: 1px;
      background: #e0e0e0;
      margin: 5px 0;
    }

    .logout-btn {
      color: #d32f2f;
      font-weight: 600;
    }

    .logout-btn:hover {
      background: #ffebee;
    }

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
      z-index: 1000;
      transition: transform 0.3s ease;
    }

    .sidebar h2 { 
      margin: 0 0 10px 0; 
      text-align: center; 
      font-size: 20px; 
    }

    /* menu scrollable */
    .menu { 
      flex: 1; 
      overflow-y: auto; 
      padding-right: 6px; 
    }

    .menu ul { 
      list-style: none; 
      padding: 0; 
      margin: 0; 
    }
    
    .menu li { 
      margin: 6px 0; 
    }

    .menu a {
      display: block;
      padding: 10px;
      border-radius: 8px;
      color: white;
      text-decoration: none;
      cursor: pointer;
      transition: background .18s;
    }
    
    .menu a:hover { 
      background: rgba(255,255,255,0.12); 
    }

    /* active */
    .menu a.active {
      background: rgba(255,255,255,0.28);
      font-weight: 600;
      color: #002233;
    }

    /* Botón cerrar sesión en el menú móvil */
    .mobile-logout {
      display: none;
      margin-top: 20px;
      padding: 12px;
      text-align: center;
      border-top: 1px solid rgba(255,255,255,0.2);
    }

    .mobile-logout-btn {
      background: rgba(255, 255, 255, 0.1);
      border: 1px solid rgba(255, 255, 255, 0.3);
      color: white;
      padding: 10px 20px;
      border-radius: 6px;
      cursor: pointer;
      font-size: 14px;
      transition: all 0.3s ease;
      text-decoration: none;
      display: inline-block;
      width: 100%;
    }

    .mobile-logout-btn:hover {
      background: rgba(255, 255, 255, 0.2);
    }

    /* Footer dentro del sidebar - SIEMPRE VISIBLE */
    .sidebar-footer {
      font-size: 12px;
      text-align: center;
      padding-top: 10px;
      border-top: 1px solid rgba(255,255,255,0.12);
      line-height: 1.4;
      /* Aseguramos que siempre sea visible */
      display: block !important;
    }

    /* Contenido principal */
    .main {
      margin-left: 280px; /* espacio para sidebar */
      margin-top: 60px;   /* espacio header */
      padding: 22px;
      transition: margin-left 0.3s ease;
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

    /* Overlay para móviles */
    .sidebar-overlay {
      display: none;
      position: fixed;
      top: 60px;
      left: 0;
      right: 0;
      bottom: 0;
      background: rgba(0, 0, 0, 0.5);
      z-index: 999;
    }

    /* responsive */
    @media (max-width: 1024px) {
      .sidebar { 
        width: 240px; 
      }
      
      .main { 
        margin-left: 240px; 
      }
      
      .footer-images {
        flex-direction: column;
        gap: 8px;
      }
      
      .footer-img {
        height: 70px;
      }
    }
    
    @media (max-width: 768px) {
      .menu-toggle {
        display: block;
      }
      
      .sidebar {
        transform: translateX(-100%);
        width: 280px;
      }
      
      .sidebar.active {
        transform: translateX(0);
      }
      
      .main {
        margin-left: 0;
      }
      
      .sidebar-overlay.active {
        display: block;
      }
      
      .footer-img {
        height: 60px;
      }

      /* Ocultar menú de usuario en header y mostrar en menú móvil */
      .user-menu {
        display: none;
      }

      .mobile-logout {
        display: block;
      }

      /* Asegurar que el footer sea visible en móviles */
      .sidebar-footer {
        display: block !important;
        position: relative;
        z-index: 1001;
      }
    }
    
    @media (max-width: 480px) {
      .sidebar {
        width: 100%;
      }
      
      .footer-img {
        height: 50px;
      }
      
      .footer-text {
        font-size: 10px;
      }

      .header .user {
        font-size: 12px;
      }

      /* Ajustes para móviles muy pequeños */
      .sidebar-footer {
        padding: 8px 0;
      }

      .footer-images {
        gap: 10px;
      }
    }

    @media (max-width: 360px) {
      .header {
        padding: 0 10px;
      }

      .header .user {
        display: none;
      }

      .footer-text {
        font-size: 9px;
      }

      .footer-img {
        height: 40px;
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
    <div class="header-left">
      <button class="menu-toggle" id="menuToggle">☰</button>
      <div class="logo">
        <img src="{{ asset('imagenes/acuacode.png') }}" alt="Acuacode Logo" class="logo-img">
      </div>
    </div>
    
    <div class="header-right">
      <div class="user">{{ Auth::user()->name ?? 'Invitado' }}</div>
      
      <!-- Menú de usuario desplegable -->
      <div class="user-menu">
        <button class="user-toggle" id="userToggle">
          Mi Cuenta
        </button>
        <div class="dropdown-menu" id="dropdownMenu">
          <a href="{{ url('/perfil') }}" class="dropdown-item">
            👤 Mi Perfil
          </a>
          <a href="{{ url('/configuracion') }}" class="dropdown-item">
            ⚙️ Configuración
          </a>
          <div class="dropdown-divider"></div>
          <form id="logout-form" action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="dropdown-item logout-btn">
              🚪 Cerrar Sesión
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Overlay para móviles -->
  <div class="sidebar-overlay" id="sidebarOverlay"></div>

  <!-- Sidebar -->
  <div class="sidebar" id="sidebar">
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

          <li>
            <a href="{{ url('/vivo') }}" class="{{ $current === 'vivo' ? 'active' : '' }}">Datos en vivo</a>
          </li>
          
          <li>
            <a href="{{ url('/Admin/Sensores') }}" class="{{ $current === 'admin/sensores' ? 'active' : '' }}">Administrar Sensores</a>
          </li>

          <li>
            <a href="{{ url('/Alertas/Activas') }}" class="{{ $current === 'alertas/activas' ? 'active' : '' }}">Ver alertas activas</a>
          </li>
        </ul>
      </nav>

      <!-- Cerrar sesión en móvil -->
      <div class="mobile-logout">
        <form id="logout-form-mobile" action="{{ route('logout') }}" method="POST">
          @csrf
          <button type="submit" class="mobile-logout-btn">
            🚪 Cerrar Sesión
          </button>
        </form>
      </div>
    </div>

    <!-- Footer fijo abajo del sidebar - SIEMPRE VISIBLE -->
    <div class="sidebar-footer">
      
      <!-- Texto de créditos -->
      <div class="footer-text">
        <a href="{{ url('/creditos') }}" class="footer-link {{ $current === 'creditos' ? 'active' : '' }}">
          Sergio Danier Cordoba Ceron<br>
          Jesus Armando Gomez Garzon<br>
          <strong>© 2025</strong>
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
  <div class="main" id="mainContent">
    @yield('content')
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const menuToggle = document.getElementById('menuToggle');
      const sidebar = document.getElementById('sidebar');
      const sidebarOverlay = document.getElementById('sidebarOverlay');
      const mainContent = document.getElementById('mainContent');
      const userToggle = document.getElementById('userToggle');
      const dropdownMenu = document.getElementById('dropdownMenu');
      
      // Función para abrir/cerrar el menú lateral
      function toggleSidebar() {
        sidebar.classList.toggle('active');
        sidebarOverlay.classList.toggle('active');
        
        // Prevenir scroll del body cuando el menú está abierto
        if (sidebar.classList.contains('active')) {
          document.body.style.overflow = 'hidden';
        } else {
          document.body.style.overflow = '';
        }
      }
      
      // Función para abrir/cerrar el menú de usuario
      function toggleUserMenu() {
        userToggle.classList.toggle('active');
        dropdownMenu.classList.toggle('active');
      }
      
      // Cerrar menú de usuario al hacer clic fuera
      function closeUserMenu(e) {
        if (!userToggle.contains(e.target) && !dropdownMenu.contains(e.target)) {
          userToggle.classList.remove('active');
          dropdownMenu.classList.remove('active');
        }
      }
      
      // Eventos para el menú lateral
      menuToggle.addEventListener('click', toggleSidebar);
      sidebarOverlay.addEventListener('click', toggleSidebar);
      
      // Eventos para el menú de usuario
      userToggle.addEventListener('click', toggleUserMenu);
      document.addEventListener('click', closeUserMenu);
      
      // Cerrar menú lateral al hacer clic en un enlace (en dispositivos móviles)
      const menuLinks = document.querySelectorAll('.menu a');
      menuLinks.forEach(link => {
        link.addEventListener('click', function() {
          if (window.innerWidth <= 768) {
            toggleSidebar();
          }
        });
      });
      
      // Cerrar menús al redimensionar la ventana si se vuelve más grande
      window.addEventListener('resize', function() {
        if (window.innerWidth > 768) {
          sidebar.classList.remove('active');
          sidebarOverlay.classList.remove('active');
          document.body.style.overflow = ''; // Restaurar scroll
        }
      });

      // Confirmación para cerrar sesión (opcional)
      const logoutForms = document.querySelectorAll('form[id^="logout-form"]');
      logoutForms.forEach(form => {
        form.addEventListener('submit', function(e) {
          if (!confirm('¿Estás seguro de que deseas cerrar sesión?')) {
            e.preventDefault();
          }
        });
      });

      // Cerrar menú al presionar Escape
      document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
          if (sidebar.classList.contains('active')) {
            toggleSidebar();
          }
          if (dropdownMenu.classList.contains('active')) {
            toggleUserMenu();
          }
        }
      });
    });
  </script>
</body>
</html>