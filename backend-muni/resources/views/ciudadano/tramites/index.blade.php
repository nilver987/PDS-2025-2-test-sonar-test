<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tipos de Trámite</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f9fafb;
            margin: 0;
            padding: 0;
        }
        
        h1 {
            font-size: 24px;
            font-weight: 500;
            color: #111827;
            margin: 24px 0;
            text-align: center;
        }
        
        ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }
        
        li {
            background-color: #fff;
            margin: 8px 0;
            padding: 16px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s;
        }
        
        li:hover {
            transform: translateY(-2px);
        }
        
        @media (max-width: 600px) {
            h1 {
                font-size: 20px;
            }
            
            li {
                padding: 12px;
            }
        }
    </style>
</head>
<body>
    <h1>Tipos de Trámite</h1>
    @if(isset($tiposTramite) && count($tiposTramite) > 0)
        <ul>
        @foreach($tiposTramite as $t)
            <li>{{ $t->nombre }}</li>
        @endforeach
        </ul>
    @else
        <p style="text-align: center; color: #6b7280;">No hay tipos de trámite disponibles en este momento.</p>
    @endif
</body>
</html>
