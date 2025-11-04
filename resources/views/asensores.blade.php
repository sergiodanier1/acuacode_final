@extends('layouts.app')

@section('content')
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Panel Acuapónico - Control de Actuadores</title>
  <style>
    /* ---- Tus estilos originales ---- */
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
      margin:0;min-height:100vh;background:linear-gradient(180deg,#071021 0%, #062033 100%);color:var(--text);display:flex;align-items:center;justify-content:center;padding:36px;
    }
    .panel{width:960px;max-width:95%;background:linear-gradient(180deg, rgba(255,255,255,0.02), rgba(255,255,255,0.01));border-radius:14px;padding:26px;box-shadow:0 8px 30px rgba(2,6,23,0.6)}
    .header{display:flex;align-items:center;gap:16px;margin-bottom:18px}
    .logo{width:56px;height:56px;border-radius:10px;background:linear-gradient(135deg,var(--accent),#7c3aed);display:flex;align-items:center;justify-content:center;font-weight:700;font-size:20px}
    h1{margin:0;font-size:18px}
    p.lead{margin:0;color:var(--muted);font-size:13px}

    .grid{display:grid;grid-template-columns:repeat(4,1fr);gap:var(--gap);margin-top:20px}

    .card{background:linear-gradient(180deg, rgba(255,255,255,0.01), rgba(255,255,255,0.005));border-radius:12px;padding:16px;display:flex;flex-direction:column;align-items:center;gap:12px;min-height:170px;justify-content:center}

    .actuator-btn{width:var(--btn-size);height:var(--btn-size);border-radius:12px;border:3px solid rgba(255,255,255,0.06);display:flex;flex-direction:column;align-items:center;justify-content:center;gap:8px;cursor:pointer;transition:transform .12s ease, box-shadow .12s ease;background:linear-gradient(180deg, rgba(255,255,255,0.012), rgba(255,255,255,0.008));color:var(--text);box-shadow:0 6px 20px rgba(2,6,23,0.45)}
    .actuator-btn:active{transform:translateY(2px) scale(.998)}
    .actuator-btn .icon{width:46px;height:46px;display:flex;align-items:center;justify-content:center}
    .actuator-btn .label{font-weight:600}
    .actuator-btn .small{font-size:12px;color:var(--muted)}

    .actuator-btn.on{background:linear-gradient(180deg, rgba(22,163,74,0.14), rgba(22,163,74,0.06));border-color:rgba(22,163,74,0.45);box-shadow:0 10px 30px rgba(16,185,129,0.08)}
    .actuator-btn.off{background:linear-gradient(180deg, rgba(225,29,72,0.08), rgba(225,29,72,0.03));border-color:rgba(225,29,72,0.45);box-shadow:0 10px 30px rgba(225,29,72,0.06)}

    .status-row{display:flex;align-items:center;justify-content:space-between;margin-top:18px;gap:12px}
    .status-list{display:flex;gap:12px;align-items:center}
    .dot{width:10px;height:10px;border-radius:50%;background:var(--muted)}

    .controls{margin-top:18px;display:flex;align-items:center;gap:10px}
    .btn-light{padding:10px 12px;border-radius:8px;background:transparent;border:1px solid rgba(255,255,255,0.06);color:var(--muted);cursor:pointer}

    footer{margin-top:18px;color:var(--muted);font-size:13px}

    /* ===== MEJORAS RESPONSIVE ===== */
    
    /* Mobile First - Pantallas pequeñas */
    @media (max-width: 480px) {
        body {
            padding: 16px;
            align-items: flex-start;
            min-height: 100vh;
        }
        
        .panel {
            width: 100%;
            max-width: 100%;
            padding: 20px 16px;
            border-radius: 12px;
        }
        
        .grid {
            grid-template-columns: 1fr;
            gap: 16px;
            margin-top: 16px;
        }
        
        .card {
            min-height: 140px;
            padding: 20px;
        }
        
        .actuator-btn {
            width: 100%;
            height: 120px;
            max-width: 200px;
            margin: 0 auto;
        }
        
        .actuator-btn .icon {
            width: 40px;
            height: 40px;
        }
        
        .actuator-btn .label {
            font-size: 16px;
        }
        
        .actuator-btn .small {
            font-size: 13px;
        }
        
        .status-row {
            flex-direction: column;
            gap: 16px;
            text-align: center;
            margin-top: 24px;
        }
        
        .status-list {
            justify-content: center;
            flex-wrap: wrap;
            gap: 16px;
        }
        
        .controls {
            justify-content: center;
            flex-wrap: wrap;
            gap: 12px;
            margin-top: 16px;
        }
        
        .btn-light {
            padding: 12px 20px;
            font-size: 14px;
            min-width: 140px;
        }
        
        footer {
            text-align: center;
            font-size: 14px;
            margin-top: 24px;
        }
    }
    
    /* Tablets y pantallas medianas */
    @media (min-width: 481px) and (max-width: 768px) {
        body {
            padding: 24px;
        }
        
        .panel {
            padding: 24px;
            width: 95%;
        }
        
        .grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }
        
        .actuator-btn {
            width: 100%;
            height: 140px;
            max-width: 160px;
            margin: 0 auto;
        }
        
        .actuator-btn .icon {
            width: 42px;
            height: 42px;
        }
        
        .status-row {
            flex-wrap: wrap;
            justify-content: center;
            gap: 16px;
        }
        
        .controls {
            flex-wrap: wrap;
            justify-content: center;
            gap: 12px;
        }
        
        .btn-light {
            padding: 12px 18px;
        }
    }
    
    /* Pantallas grandes */
    @media (min-width: 769px) and (max-width: 1024px) {
        .grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 24px;
        }
        
        .actuator-btn {
            width: 150px;
            height: 150px;
        }
    }
    
    /* Pantallas muy grandes */
    @media (min-width: 1025px) and (max-width: 1200px) {
        .grid {
            grid-template-columns: repeat(4, 1fr);
        }
        
        .actuator-btn {
            width: 130px;
            height: 130px;
        }
    }
    
    /* Orientación landscape en móviles */
    @media (max-height: 600px) and (orientation: landscape) {
        body {
            padding: 12px;
            align-items: flex-start;
        }
        
        .panel {
            max-height: 95vh;
            overflow-y: auto;
            width: 100%;
        }
        
        .grid {
            grid-template-columns: repeat(4, 1fr);
            gap: 12px;
        }
        
        .card {
            min-height: 120px;
            padding: 12px;
        }
        
        .actuator-btn {
            height: 100px;
            width: 100px;
        }
        
        .actuator-btn .icon {
            width: 32px;
            height: 32px;
        }
        
        .actuator-btn .label {
            font-size: 12px;
        }
        
        .actuator-btn .small {
            font-size: 10px;
        }
    }
    
    /* Mejoras de accesibilidad */
    @media (prefers-reduced-motion: reduce) {
        .actuator-btn {
            transition: none;
        }
    }
    
    /* Mejoras para pantallas táctiles */
    @media (hover: none) and (pointer: coarse) {
        .actuator-btn {
            min-height: 44px;
            min-width: 44px;
        }
        
        .btn-light {
            min-height: 44px;
            min-width: 44px;
            padding: 12px 16px;
        }
    }

    /* Soporte para modo oscuro del sistema */
    @media (prefers-color-scheme: dark) {
        .panel {
            background: linear-gradient(180deg, rgba(255,255,255,0.03), rgba(255,255,255,0.015));
        }
    }

    /* ===== MEDIA QUERY EXISTENTE MANTENIDA ===== */
    @media (max-width:680px){
      .grid{grid-template-columns:repeat(2,1fr)}
      .actuator-btn{width:120px;height:120px}
    }

    /* Mejoras para lectores de pantalla */
    @media speech {
        .actuator-btn::after {
            content: " - Botón de actuador";
            speak: literal-punctuation;
        }
    }
  </style>
</head>
<body>
  <div class="panel">
    <div class="grid" id="actuators">
      <div class="card"><button class="actuator-btn" data-id="lights" aria-label="Control de luces"><div class="icon">💡</div><div class="label">Luces</div><div class="small">Estado: <span class="state-text">OFF</span></div></button></div>
      <div class="card"><button class="actuator-btn" data-id="dispenser" aria-label="Control de dispensador"><div class="icon">📦</div><div class="label">Dispensador</div><div class="small">Estado: <span class="state-text">OFF</span></div></button></div>
      <div class="card"><button class="actuator-btn" data-id="valve" aria-label="Control de válvula"><div class="icon">🔧</div><div class="label">Válvula</div><div class="small">Estado: <span class="state-text">OFF</span></div></button></div>
      <div class="card"><button class="actuator-btn" data-id="aerator" aria-label="Control de aerador"><div class="icon">💨</div><div class="label">Aereador</div><div class="small">Estado: <span class="state-text">OFF</span></div></button></div>
    </div>

    <div class="status-row">
      <div class="status-list">
        <div class="dot" id="dot-lights" aria-label="Estado luces"></div>
        <div class="dot" id="dot-dispenser" aria-label="Estado dispensador"></div>
        <div class="dot" id="dot-valve" aria-label="Estado válvula"></div>
        <div class="dot" id="dot-aerator" aria-label="Estado aerador"></div>
      </div>
      <div class="controls">
        <button class="btn-light" id="all-on" aria-label="Encender todos los actuadores">Encender todos</button>
        <button class="btn-light" id="all-off" aria-label="Apagar todos los actuadores">Apagar todos</button>
      </div>
    </div>

    <footer>Este panel controla LEDs reales en tu ESP32 🌐</footer>
  </div>

<script>
  // IP del ESP32 (incluye protocolo). Cambia por la IP que te muestre el monitor serie.
  const ESP_IP = "http://192.168.1.16";

  // mapeo de ids del UI a índices que espera el ESP (0..3)
  const idToIndex = {
    lights: 0,
    dispenser: 1,
    valve: 2,
    aerator: 3
  };

  const defaultState = { lights:false, dispenser:false, valve:false, aerator:false };
  const storageKey = 'aq_actuator_states_v1';

  function loadState(){
    try{
      const raw = localStorage.getItem(storageKey);
      return raw ? Object.assign({}, defaultState, JSON.parse(raw)) : {...defaultState};
    } catch(e){
      console.warn('loadState error', e);
      return {...defaultState};
    }
  }
  function saveState(s){ try{ localStorage.setItem(storageKey, JSON.stringify(s)); } catch(e){ console.warn('saveState', e); } }

  const state = loadState();

  function updateUI(){
    document.querySelectorAll('.actuator-btn').forEach(btn=>{
      const id = btn.dataset.id;
      const isOn = Boolean(state[id]);
      btn.classList.toggle('on', isOn);
      btn.classList.toggle('off', !isOn);
      btn.setAttribute('aria-pressed', String(isOn));
      btn.setAttribute('aria-label', `Control de ${btn.querySelector('.label').textContent} - Estado: ${isOn ? 'ENCENDIDO' : 'APAGADO'}`);
      const txt = btn.querySelector('.state-text');
      if(txt) txt.textContent = isOn ? 'ON' : 'OFF';
      const dot = document.getElementById('dot-'+id);
      if(dot) {
        dot.style.background = isOn ? getComputedStyle(document.documentElement).getPropertyValue('--on-color') : getComputedStyle(document.documentElement).getPropertyValue('--muted');
        dot.setAttribute('aria-label', `${id} ${isOn ? 'activo' : 'inactivo'}`);
      }
    });
  }

  // envía al ESP usando el índice correcto (0..3)
  function sendToESPByIndex(index, isOn){
    // index must be 0..3
    if(typeof index !== 'number' || index < 0 || index > 3) {
      console.warn('Índice inválido para enviar al ESP:', index);
      return Promise.reject('invalid-index');
    }
    const estado = isOn ? 1 : 0;
    return fetch(`${ESP_IP}/led?pin=${index}&state=${estado}`)
      .then(response => response.text())
      .then(txt => {
        console.log(`ESP32 response (pin ${index} -> ${estado}):`, txt);
        return txt;
      });
  }

  // toggle desde UI (se mapea a índice y se envía)
  function toggle(id){
    const idx = idToIndex[id];
    if(idx === undefined){
      console.warn('ID desconocido:', id);
      return;
    }

    // actualizar estado UI de forma optimista
    state[id] = !state[id];
    saveState(state);
    updateUI();

    // enviar al ESP (no bloqueamos UI)
    sendToESPByIndex(idx, state[id]).catch(err=>{
      console.error('Error enviando al ESP:', err);
      // opcional: revertir UI si quieres (aquí solo mostramos error)
    });
  }

  // Encender/apagar todos usando action para una sola petición al ESP
  function allOn(){
    // actualizar UI
    Object.keys(state).forEach(k => state[k] = true);
    saveState(state);
    updateUI();

    // enviar acción única
    fetch(`${ESP_IP}/led?action=on_all`)
      .then(r => r.text())
      .then(t => console.log('ESP (on_all):', t))
      .catch(e => console.error('Error on_all:', e));
  }
  function allOff(){
    Object.keys(state).forEach(k => state[k] = false);
    saveState(state);
    updateUI();

    fetch(`${ESP_IP}/led?action=off_all`)
      .then(r => r.text())
      .then(t => console.log('ESP (off_all):', t))
      .catch(e => console.error('Error off_all:', e));
  }

  // Inicialización de listeners
  document.addEventListener('DOMContentLoaded', ()=>{
    updateUI();

    document.querySelectorAll('.actuator-btn').forEach(btn=>{
      btn.addEventListener('click', ()=> toggle(btn.dataset.id) );

      // accesibilidad: Enter/Space
      btn.addEventListener('keydown', (e)=>{
        if(e.key === 'Enter' || e.key === ' '){
          e.preventDefault();
          btn.click();
        }
      });
    });

    document.getElementById('all-on').addEventListener('click', allOn);
    document.getElementById('all-off').addEventListener('click', allOff);

    // Mejora responsive: ajustar dinámicamente en redimensionamiento
    window.addEventListener('resize', function() {
      // Forzar actualización de layout si es necesario
      document.body.style.overflow = 'hidden';
      setTimeout(() => {
        document.body.style.overflow = '';
      }, 100);
    });
  });

  // Prevenir zoom en dispositivos móviles con doble tap
  document.addEventListener('touchend', function(e) {
    if (e.touches && e.touches.length > 1) {
      e.preventDefault();
    }
  }, { passive: false });
</script>

</body>
</html>

@endsection