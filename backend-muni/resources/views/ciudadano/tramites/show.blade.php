<!doctype html>
<html>
<head>
    <title>Detalles del Trámite - {{ $expediente->numero }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css">
    <style>
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .animate-fade-in {
            animation: fadeIn 0.5s ease-in-out;
        }
        
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        /* Animación de pulso para el paso actual */
        @keyframes pulse {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: .7;
            }
        }
        
        .animate-pulse {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
        
        /* Transiciones suaves para los elementos del workflow */
        .workflow-step {
            transition: all 0.3s ease-in-out;
        }
        
        .workflow-step:hover {
            transform: translateY(-2px);
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="px-4 py-6 sm:px-0">
            
            <!-- Header -->
            <header class="bg-white shadow mb-6">
                <div class="flex justify-between items-center py-4 px-6">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">
                            <i class="fas fa-file-alt mr-2"></i>Detalles del Trámite
                        </h1>
                        <nav class="mt-2 flex" aria-label="Breadcrumb">
                            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                                <li class="inline-flex items-center">
                                    <a href="{{ route('dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                                        </svg>
                                        Inicio
                                    </a>
                                </li>
                                <li>
                                    <div class="flex items-center">
                                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                        <a href="{{ route('ciudadano.tramites.mis-tramites') }}" class="ml-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ml-2">Mis Trámites</a>
                                    </div>
                                </li>
                                <li aria-current="page">
                                    <div class="flex items-center">
                                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                        <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">{{ $expediente->numero }}</span>
                                    </div>
                                </li>
                            </ol>
                        </nav>
                    </div>
                    <div>
                        <a href="{{ route('ciudadano.tramites.mis-tramites') }}" class="px-6 py-3 bg-gray-600 text-white rounded-lg font-medium hover:bg-gray-700 transition shadow-lg">
                            <i class="fas fa-arrow-left mr-2"></i>Volver
                        </a>
                    </div>
                </div>
            </header>

            <!-- Main Content -->
            <main>
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Columna Principal -->
                    <div class="lg:col-span-2 space-y-6">
                        
                        <!-- Información General -->
                        <div class="bg-white rounded-lg shadow overflow-hidden">
                            <div class="px-6 py-4 bg-gradient-to-r from-blue-600 to-purple-600 border-b border-gray-200">
                                <h2 class="text-xl font-bold text-white flex items-center">
                                    <i class="fas fa-info-circle mr-2"></i>
                                    Información General
                                </h2>
                            </div>
                            <div class="p-6">
                                <div class="space-y-4">
                                    <div>
                                        <label class="text-sm font-medium text-gray-500">Asunto</label>
                                        <p class="mt-1 text-lg font-semibold text-gray-900">{{ $expediente->asunto }}</p>
                                    </div>
                                    
                                    <div>
                                        <label class="text-sm font-medium text-gray-500">Descripción</label>
                                        <p class="mt-1 text-gray-700 whitespace-pre-line">{{ $expediente->descripcion }}</p>
                                    </div>

                                    <div class="grid grid-cols-2 gap-4 pt-4 border-t">
                                        <div>
                                            <label class="text-sm font-medium text-gray-500">Número de Expediente</label>
                                            <p class="mt-1 font-mono text-blue-600 font-bold">{{ $expediente->numero }}</p>
                                        </div>
                                        <div>
                                            <label class="text-sm font-medium text-gray-500">Tipo de Trámite</label>
                                            <p class="mt-1 text-gray-900 font-medium">{{ $expediente->tipoTramite->nombre ?? 'N/A' }}</p>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="text-sm font-medium text-gray-500">Gerencia</label>
                                            <p class="mt-1 text-gray-900">{{ $expediente->gerencia->nombre ?? 'Sin asignar' }}</p>
                                        </div>
                                        <div>
                                            <label class="text-sm font-medium text-gray-500">Fecha de Registro</label>
                                            <p class="mt-1 text-gray-900">
                                                {{ optional($expediente->fecha_registro)->format('d/m/Y H:i') ?? optional($expediente->fecha_ingreso)->format('d/m/Y H:i') ?? $expediente->created_at->format('d/m/Y H:i') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Documentos Adjuntos -->
                        <div class="bg-white rounded-lg shadow overflow-hidden">
                            <div class="px-6 py-4 bg-gradient-to-r from-green-600 to-teal-600 border-b border-gray-200">
                                <h2 class="text-xl font-bold text-white flex items-center">
                                    <i class="fas fa-paperclip mr-2"></i>
                                    Documentos Adjuntos
                                    <span class="ml-2 px-2 py-1 bg-white text-green-600 rounded-full text-sm font-bold">
                                        {{ $expediente->documentos->count() }}
                                    </span>
                                </h2>
                            </div>
                            <div class="p-6">
                                @if($expediente->documentos->count() > 0)
                                    <div class="space-y-3">
                                        @foreach($expediente->documentos as $documento)
                                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg border border-gray-200 hover:bg-gray-100 transition">
                                                <div class="flex items-center space-x-3">
                                                    <div class="flex-shrink-0">
                                                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                                            <i class="fas fa-file-pdf text-blue-600"></i>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <p class="font-medium text-gray-900">{{ $documento->nombre }}</p>
                                                        <p class="text-sm text-gray-500">
                                                            Documento {{ $loop->iteration }}
                                                        </p>
                                                    </div>
                                                </div>
                                                <a href="{{ route('ciudadano.tramites.descargar-documento', $documento->id) }}" 
                                                   class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm font-medium">
                                                    <i class="fas fa-download mr-1"></i>
                                                    Descargar
                                                </a>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-center py-8">
                                        <i class="fas fa-folder-open text-gray-300 text-4xl mb-3"></i>
                                        <p class="text-gray-500">No hay documentos adjuntos</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Historial -->
                        <div class="bg-white rounded-lg shadow overflow-hidden">
                            <div class="px-6 py-4 bg-gradient-to-r from-purple-600 to-pink-600 border-b border-gray-200">
                                <h2 class="text-xl font-bold text-white flex items-center">
                                    <i class="fas fa-history mr-2"></i>
                                    Historial de Movimientos
                                </h2>
                            </div>
                            <div class="p-6">
                                @if($expediente->historial->count() > 0)
                                    <div class="flow-root">
                                        <ul class="-mb-8">
                                            @foreach($expediente->historial as $index => $historial)
                                            <li>
                                                <div class="relative pb-8">
                                                    @if(!$loop->last)
                                                    <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                                    @endif
                                                    <div class="relative flex space-x-3">
                                                        <div>
                                                            <span class="h-8 w-8 rounded-full bg-blue-500 flex items-center justify-center ring-8 ring-white">
                                                                <i class="fas fa-circle text-white text-xs"></i>
                                                            </span>
                                                        </div>
                                                        <div class="flex-1 min-w-0">
                                                            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                                                <div class="flex items-center justify-between mb-2">
                                                                    <p class="text-sm font-medium text-gray-900">{{ $historial->accion }}</p>
                                                                    <p class="text-xs text-gray-500">
                                                                        {{ $historial->created_at->format('d/m/Y H:i') }}
                                                                    </p>
                                                                </div>
                                                                @if($historial->descripcion)
                                                                <p class="text-sm text-gray-600">{{ $historial->descripcion }}</p>
                                                                @endif
                                                                @if($historial->usuario)
                                                                <p class="text-xs text-gray-500 mt-2">
                                                                    <i class="fas fa-user mr-1"></i>
                                                                    {{ $historial->usuario->name }}
                                                                </p>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @else
                                    <div class="text-center py-8">
                                        <i class="fas fa-clock text-gray-300 text-4xl mb-3"></i>
                                        <p class="text-gray-500">No hay movimientos registrados</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                    </div>

                    <!-- Columna Lateral -->
                    <div class="lg:col-span-1 space-y-6">
                        
                        <!-- Estado -->
                        <div class="bg-white rounded-lg shadow overflow-hidden">
                            <div class="px-6 py-4 bg-gray-800 border-b border-gray-200">
                                <h2 class="text-lg font-bold text-white flex items-center">
                                    <i class="fas fa-flag mr-2"></i>
                                    Estado Actual
                                </h2>
                            </div>
                            <div class="p-6">
                                @php
                                    $estadoColors = [
                                        'pendiente' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800', 'border' => 'border-yellow-300', 'icon' => 'fa-clock'],
                                        'en_proceso' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-800', 'border' => 'border-blue-300', 'icon' => 'fa-spinner'],
                                        'en_revision' => ['bg' => 'bg-purple-100', 'text' => 'text-purple-800', 'border' => 'border-purple-300', 'icon' => 'fa-search'],
                                        'observado' => ['bg' => 'bg-orange-100', 'text' => 'text-orange-800', 'border' => 'border-orange-300', 'icon' => 'fa-exclamation-triangle'],
                                        'aprobado' => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'border' => 'border-green-300', 'icon' => 'fa-check-circle'],
                                        'rechazado' => ['bg' => 'bg-red-100', 'text' => 'text-red-800', 'border' => 'border-red-300', 'icon' => 'fa-times-circle'],
                                        'finalizado' => ['bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'border' => 'border-gray-300', 'icon' => 'fa-flag-checkered']
                                    ];
                                    $colors = $estadoColors[$expediente->estado] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'border' => 'border-gray-300', 'icon' => 'fa-question'];
                                @endphp
                                
                                <div class="text-center">
                                    <div class="inline-flex items-center justify-center w-20 h-20 {{ $colors['bg'] }} rounded-full mb-4">
                                        <i class="fas {{ $colors['icon'] }} {{ $colors['text'] }} text-3xl"></i>
                                    </div>
                                    <p class="text-2xl font-bold {{ $colors['text'] }} mb-2">
                                        {{ ucfirst(str_replace('_', ' ', $expediente->estado)) }}
                                    </p>
                                    <div class="mt-4 p-3 {{ $colors['bg'] }} border {{ $colors['border'] }} rounded-lg">
                                        <p class="text-sm {{ $colors['text'] }} font-medium">
                                            @switch($expediente->estado)
                                                @case('pendiente')
                                                    Su trámite está pendiente de revisión
                                                    @break
                                                @case('en_proceso')
                                                    Su trámite está siendo procesado
                                                    @break
                                                @case('en_revision')
                                                    Su trámite está en revisión
                                                    @break
                                                @case('observado')
                                                    Su trámite tiene observaciones
                                                    @break
                                                @case('aprobado')
                                                    Su trámite ha sido aprobado
                                                    @break
                                                @case('rechazado')
                                                    Su trámite ha sido rechazado
                                                    @break
                                                @case('finalizado')
                                                    Su trámite ha sido finalizado
                                                    @break
                                                @default
                                                    Estado del trámite
                                            @endswitch
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Información de Pago -->
                        @if(isset($expediente->requiere_pago) && $expediente->requiere_pago)
                        <div class="bg-white rounded-lg shadow overflow-hidden">
                            <div class="px-6 py-4 {{ $expediente->pagado ? 'bg-green-600' : 'bg-yellow-600' }} border-b border-gray-200">
                                <h2 class="text-lg font-bold text-white flex items-center">
                                    <i class="fas fa-dollar-sign mr-2"></i>
                                    Información de Pago
                                </h2>
                            </div>
                            <div class="p-6">
                                @if($expediente->pagado)
                                    <div class="text-center">
                                        <div class="inline-flex items-center justify-center w-16 h-16 bg-green-100 rounded-full mb-4">
                                            <i class="fas fa-check-circle text-green-600 text-3xl"></i>
                                        </div>
                                        <p class="text-lg font-bold text-green-800 mb-2">Pago Realizado</p>
                                        <p class="text-3xl font-bold text-gray-900">S/. {{ number_format($expediente->monto ?? 0, 2) }}</p>
                                    </div>
                                @else
                                    <div class="text-center">
                                        <div class="inline-flex items-center justify-center w-16 h-16 bg-yellow-100 rounded-full mb-4">
                                            <i class="fas fa-exclamation-triangle text-yellow-600 text-3xl"></i>
                                        </div>
                                        <p class="text-lg font-bold text-yellow-800 mb-2">Pago Pendiente</p>
                                        <p class="text-3xl font-bold text-gray-900 mb-4">S/. {{ number_format($expediente->monto ?? 0, 2) }}</p>
                                        <button class="w-full px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition font-medium">
                                            <i class="fas fa-credit-card mr-2"></i>
                                            Realizar Pago
                                        </button>
                                    </div>
                                @endif
                            </div>
                        </div>
                        @endif

                        <!-- Datos del Solicitante -->
                        <div class="bg-white rounded-lg shadow overflow-hidden">
                            <div class="px-6 py-4 bg-gray-800 border-b border-gray-200">
                                <h2 class="text-lg font-bold text-white flex items-center">
                                    <i class="fas fa-user mr-2"></i>
                                    Solicitante
                                </h2>
                            </div>
                            <div class="p-6">
                                <div class="space-y-3">
                                    <div>
                                        <label class="text-xs font-medium text-gray-500 uppercase">Nombre Completo</label>
                                        <p class="mt-1 text-sm text-gray-900 font-medium">
                                            {{ $expediente->solicitante_nombre }} {{ $expediente->solicitante_apellido }}
                                        </p>
                                    </div>
                                    @if($expediente->solicitante_dni)
                                    <div>
                                        <label class="text-xs font-medium text-gray-500 uppercase">DNI</label>
                                        <p class="mt-1 text-sm text-gray-900 font-mono">{{ $expediente->solicitante_dni }}</p>
                                    </div>
                                    @endif
                                    @if($expediente->solicitante_email)
                                    <div>
                                        <label class="text-xs font-medium text-gray-500 uppercase">Email</label>
                                        <p class="mt-1 text-sm text-gray-900">{{ $expediente->solicitante_email }}</p>
                                    </div>
                                    @endif
                                    @if($expediente->solicitante_telefono)
                                    <div>
                                        <label class="text-xs font-medium text-gray-500 uppercase">Teléfono</label>
                                        <p class="mt-1 text-sm text-gray-900">{{ $expediente->solicitante_telefono }}</p>
                                    </div>
                                    @endif
                                    @if($expediente->solicitante_direccion)
                                    <div>
                                        <label class="text-xs font-medium text-gray-500 uppercase">Dirección</label>
                                        <p class="mt-1 text-sm text-gray-900">{{ $expediente->solicitante_direccion }}</p>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </main>
        </div>
    </div>
</body>
</html>
