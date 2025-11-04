<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard Acuacode</title>
  <style>
    body {
      margin: 0;
      font-family: 'Poppins', sans-serif;
      background: #f4f7fa;
    }

.sidebar {
    width: 280px;
    height: 100vh;
    background: linear-gradient(to bottom, #003366, #00AEEF);
    color: white;
    padding: 20px;
    box-sizing: border-box;
    overflow-y: auto;
}

    .sidebar h2 {
      margin: 0 0 20px 0;
      text-align: center;
      font-size: 22px;
    }

    .menu ul {
      list-style: none;
      padding: 0;
      margin: 0;
    }

    .menu li {
      margin: 8px 0;
    }

    .menu a {
      display: block;
      padding: 10px;
      border-radius: 8px;
      color: white;
      text-decoration: none;
      transition: background 0.3s;
      cursor: pointer;
    }

    .menu a:hover {
      background: rgba(255, 255, 255, 0.2);
    }

    .submenu {
      margin-left: 15px;
      display: none;
    }

    .submenu a {
      font-size: 14px;
      padding: 8px 10px;
      background: rgba(255, 255, 255, 0.1);
      margin-top: 5px;
      border-radius: 6px;
    }

    .menu input[type="checkbox"] {
      display: none;
    }

    .menu label {
      display: block;
      padding: 10px;
      border-radius: 8px;
      cursor: pointer;
      transition: background 0.3s;
    }

    .menu label:hover {
      background: rgba(255, 255, 255, 0.2);
    }

    .menu input[type="checkbox"]:checked ~ .submenu {
      display: block;
    }

    /* Área principal */
    .main {
      margin-left: 280px;
      padding: 20px;
    }
  </style>
</head>
<body>
  <div class="sidebar">
    <h2>Dashboard</h2>
    <div class="menu">
      <ul>
        <li><a href="/dashboard">Inicio</a></li>

        <li>
        <input type="checkbox" id="sensores">
        <label for="sensores">Sensores</label>
        <ul class="submenu">
            <li><a href="/Sensores">Ver todos los sensores</a></li>
            <li>
            <input type="checkbox" id="detalles-sensor">
            <label for="detalles-sensor">Ver detalles de un sensor</label>
            <ul class="submenu">
                <li><a href="/Sensores/DetallesSensores">Ver histórico del sensor específico</a></li>
            </ul>
            </li>
        </ul>
        </li>


        <li>
          <input type="checkbox" id="historicos">
          <label for="historicos">Históricos</label>
          <ul class="submenu">
            <li><a href="#">Seleccionar sensor</a></li>
            <li><a href="#">Visualizar gráficas por fecha / tipo de dato</a></li>
          </ul>
        </li>

        <li>
          <input type="checkbox" id="control">
          <label for="control">Control manual</label>
          <ul class="submenu">
            <li><a href="#">Activar / Desactivar bombas</a></li>
            <li><a href="#">Encender luces</a></li>
            <li><a href="#">Ajustar válvulas</a></li>
          </ul>
        </li>

        <li>
          <input type="checkbox" id="alertas">
          <label for="alertas">Alertas</label>
          <ul class="submenu">
            <li><a href="#">Ver alertas activas</a></li>
            <li><a href="#">Ver historial de alertas resueltas</a></li>
          </ul>
        </li>

        <!-- Opciones para Admin y Super Admin -->
        <li>
          <input type="checkbox" id="admin-sensores">
          <label for="admin-sensores">Administrar Sensores</label>
          <ul class="submenu">
            <li><a href="#">Agregar sensor</a></li>
            <li><a href="#">Editar sensor</a></li>
            <li><a href="#">Eliminar sensor</a></li>
          </ul>
        </li>

        <li>
          <input type="checkbox" id="admin-sistemas">
          <label for="admin-sistemas">Administrar Sistemas</label>
          <ul class="submenu">
            <li><a href="#">Agregar sistema</a></li>
            <li><a href="#">Editar sistema</a></li>
            <li><a href="#">Eliminar sistema</a></li>
          </ul>
        </li>

        <li>
          <input type="checkbox" id="admin-operarios">
          <label for="admin-operarios">Administrar Operarios</label>
          <ul class="submenu">
            <li><a href="#">Agregar operario</a></li>
            <li><a href="#">Editar operario</a></li>
            <li><a href="#">Eliminar operario</a></li>
          </ul>
        </li>

        <li>
          <input type="checkbox" id="admin-granjas">
          <label for="admin-granjas">Administrar Granjas</label>
          <ul class="submenu">
            <li><a href="#">Agregar granja</a></li>
            <li><a href="#">Editar granja</a></li>
            <li><a href="#">Eliminar granja</a></li>
          </ul>
        </li>

        <!-- Solo Super Admin -->
        <li>
          <input type="checkbox" id="usuarios">
          <label for="usuarios">Gestión de Usuarios (solo Super Admin)</label>
          <ul class="submenu">
            <li><a href="#">Ver lista de usuarios</a></li>
            <li><a href="#">Crear usuario</a></li>
            <li><a href="#">Editar / Eliminar usuario</a></li>
          </ul>
        </li>

        <li>
          <input type="checkbox" id="config">
          <label for="config">Configuración</label>
          <ul class="submenu">
            <li><a href="#">Umbrales de sensores (pH, temperatura, etc.)</a></li>
            <li><a href="#">Intervalo de muestreo</a></li>
            <li><a href="#">Parámetros de control automático</a></li>
          </ul>
        </li>
      </ul>
    </div>
  </div>
</body>

</html>
