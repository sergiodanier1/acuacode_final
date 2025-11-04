<!DOCTYPE html>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acuacode</title>
    @vite('resources/css/app.css')
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(to bottom, #003366, #00AEEF);
            color: #ffffff;
            margin: 0;
            padding: 0;
        }
        .container {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            text-align: center;
            padding: 2rem;
        }
        .card {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            padding: 3rem 2rem;
            max-width: 600px;
            width: 100%;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.25);
            backdrop-filter: blur(10px);
            text-align: center;
        }
        .logo img {
            width: 120px;
            height: auto;
            margin-bottom: 1.5rem;
        }
        h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: #39B54A; /* Verde principal */
        }
        h1 span {
            color: #00AEEF; /* Azul claro */
        }
        p {
            font-size: 1.1rem;
            margin-bottom: 2rem;
            color: #e0f7fa;
        }
        a.button {
            display: inline-block;
            padding: 0.8rem 2rem;
            background: #39B54A;
            color: #fff;
            font-weight: 600;
            border-radius: 50px;
            text-decoration: none;
            transition: all 0.3s ease-in-out;
        }
        a.button:hover {
            background: #2d943a;
            transform: scale(1.05);
        }
        .logo {
            text-align: center; /* Centra el contenido */
            margin-bottom: 20px;    
        }

        .logo img {
            width: 50%;   /* Ajusta el tamaño, 50% */
            height: auto;   /* Mantiene proporción */
            display: inline-block;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="logo">
                <img src="{{ asset('imagenes/acuacode.png') }}" alt="Logo Acuacode">
            </div>
            <h1>Bienvenido a <span>Acuacode</span></h1>
            <p>Un sistema innovador de acuaponía que conecta <strong>naturaleza</strong> y <strong>tecnología</strong>.</p>
            <a href="{{ url('/dashboard') }}" class="button">Ir al Dashboard</a>
        </div>
    </div>
</body>
</html>

