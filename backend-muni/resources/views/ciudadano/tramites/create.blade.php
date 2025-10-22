<!doctype html>
<html>
<head>
    <title>Crear Trámite</title>
</head>
<body>
    <h1>Crear Trámite: {{ $tipoTramite->nombre ?? '' }}</h1>
    <form method="post" enctype="multipart/form-data">
        @csrf
        <input name="tipo_tramite_id" type="hidden" value="{{ $tipoTramite->id ?? '' }}">
        
        <div>
            <label for="asunto">Asunto</label>
            <input id="asunto" name="asunto" type="text" required />
        </div>
        
        <div>
            <label for="descripcion">Descripción</label>
            <textarea id="descripcion" name="descripcion" required></textarea>
        </div>
        
        <div>
            <button type="submit">Enviar</button>
        </div>
    </form>
</body>
</html>