@extends('layouts.app')

@section('content')
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Panel Acuapónico - Control de Actuadores</title>
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

    @media (max-width:680px){
      .grid{grid-template-columns:repeat(2,1fr)}
      .actuator-btn{width:120px;height:120px}
    }
  </style>
</head>
<body>
  <div class="panel">
    <div class="grid" id="actuators">
      <div class="card"><button class="actuator-btn" data-id="lights"><div class="icon">💡</div><div class="label">Luces</div><div class="small">Estado: <span class="state-text">OFF</span></div></button></div>
      <div class="card"><button class="actuator-btn" data-id="dispenser"><div class="icon">📦</div><div class="label">Dispensador</div><div class="small">Estado: <span class="state-text">OFF</span></div></button></div>
      <div class="card"><button class="actuator-btn" data-id="valve"><div class="icon">🔧</div><div class="label">Válvula</div><div class="small">Estado: <span class="state-text">OFF</span></div></button></div>
      <div class="card"><button class="actuator-btn" data-id="aerator"><div class="icon">💨</div><div class="label">Aereador</div><div class="small">Estado: <span class="state-text">OFF</span></div></button></div>
    </div>

    <div class="status-row">
      <div class="status-list">
        <div class="dot" id="dot-lights"></div>
        <div class="dot" id="dot-dispenser"></div>
        <div class="dot" id="dot-valve"></div>
        <div class="dot" id="dot-aerator"></div>
      </div>
      <div class="controls">
        <button class="btn-light" id="all-on">Encender todos</button>
        <button class="btn-light" id="all-off">Apagar todos</button>
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
      const txt = btn.querySelector('.state-text');
      if(txt) txt.textContent = isOn ? 'ON' : 'OFF';
      const dot = document.getElementById('dot-'+id);
      if(dot) dot.style.background = isOn ? getComputedStyle(document.documentElement).getPropertyValue('--on-color') : getComputedStyle(document.documentElement).getPropertyValue('--muted');
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
  });
</script>

</body>
</html>

@endsection
