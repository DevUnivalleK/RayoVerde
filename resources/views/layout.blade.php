<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Rayo Verde Admin')</title>
    <style>
        :root {
            --verde-oscuro:   #006c0f;
            --verde-2:        #187a1f;
            --verde-medio:    #37983b;
            --verde-claro:    #64b863;
            --verde-pale:     #8dca89;
            --verde-lighter:  #b4dcb0;
            --verde-lightest: #daeed7;
            --blanco:         #ffffff;
            --gris-claro:     #f4f4f4;
            --gris-tabla:     #e8e8e8;
            --texto:          #1a1a1a;
            --rojo:           #d32f2f;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Segoe UI', sans-serif;
            background: var(--gris-claro);
            color: var(--texto);
            min-height: 100vh;
        }

        .page-wrapper {
            max-width: 1100px;
            margin: 36px auto;
            padding: 0 20px;
        }

        .page-header {
            display: flex;
            align-items: center;
            gap: 14px;
            margin-bottom: 24px;
        }
        .page-header .icon { font-size: 2rem; }
        .page-header h1 {
            font-size: 2rem;
            font-weight: 800;
            color: var(--verde-oscuro);
        }
        .search-box {
            display: flex;
            align-items: center;
            gap: 8px;
            background: var(--blanco);
            border: 2px solid var(--verde-claro);
            border-radius: 20px;
            padding: 6px 14px;
            width: 220px;
            margin-bottom: 16px;
        }
        .search-box input {
            border: none;
            outline: none;
            font-size: 0.9rem;
            background: transparent;
            width: 100%;
        }

        .tabla-card {
            background: var(--blanco);
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 12px rgba(0,108,15,0.10);
            border: 1px solid var(--verde-lighter);
        }
        table { width: 100%; border-collapse: collapse; font-size: 0.95rem; }
        thead tr { background: var(--verde-pale); }
        thead th {
            padding: 12px 16px;
            text-align: left;
            font-weight: 700;
            color: var(--verde-oscuro);
            border-bottom: 2px solid var(--verde-medio);
        }
        tbody tr:nth-child(even) { background: var(--verde-lightest); }
        tbody tr:nth-child(odd)  { background: var(--blanco); }
        tbody tr:hover { background: var(--verde-lighter); transition: background .2s; }
        tbody td { padding: 11px 16px; border-bottom: 1px solid var(--verde-lighter); }

        .badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 12px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        .badge-activo   { background: #c8f0c8; color: #1b6b1b; }
        .badge-inactivo { background: #ffd6d6; color: #8b0000; }

        .btn-accion {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 34px; height: 34px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 1rem;
            margin-right: 4px;
            transition: opacity .2s, transform .15s;
        }
        .btn-accion:hover { opacity: 0.85; transform: scale(1.07); }
        .btn-eliminar { background: var(--rojo); color: var(--blanco); }
        .btn-editar   { background: var(--verde-medio); color: var(--blanco); }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 22px;
            border-radius: 8px;
            border: none;
            font-size: 0.95rem;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            transition: background .2s, transform .15s;
        }
        .btn:hover { transform: translateY(-1px); }
        .btn-primary  { background: var(--verde-oscuro); color: var(--blanco); }
        .btn-primary:hover  { background: var(--verde-2); }
        .btn-secondary { background: var(--gris-tabla); color: var(--texto); border: 1px solid #ccc; }
        .btn-secondary:hover { background: #ddd; }
        .btn-outline { background: var(--blanco); color: var(--verde-oscuro); border: 2px solid var(--verde-oscuro); }
        .btn-outline:hover { background: var(--verde-lightest); }

        .btn-row {
            display: flex;
            gap: 14px;
            justify-content: center;
            margin-top: 28px;
        }

        .section-block {
            background: var(--blanco);
            border-radius: 10px;
            padding: 24px;
            box-shadow: 0 2px 12px rgba(0,108,15,0.08);
            border: 1px solid var(--verde-lighter);
            margin-bottom: 30px;
        }
        .section-block h2 {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--verde-oscuro);
            margin-bottom: 16px;
        }
        .section-footer {
            display: flex;
            justify-content: flex-end;
            margin-top: 16px;
        }
    </style>
    @stack('styles')
</head>
<body>

<div class="page-wrapper">
    @yield('content')
</div>

@stack('scripts')
</body>
</html>