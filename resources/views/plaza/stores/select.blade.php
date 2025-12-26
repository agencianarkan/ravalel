<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seleccionar Tienda - Plaza</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto">
            <div class="bg-white rounded-lg shadow-md p-8">
                <div class="text-center mb-8">
                    <h1 class="text-3xl font-bold text-gray-800">Selecciona una Tienda</h1>
                    <p class="text-gray-600 mt-2">Elige la tienda con la que deseas trabajar</p>
                </div>

                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        {{ session('error') }}
                    </div>
                @endif

                @if(count($stores) > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($stores as $store)
                            <form method="POST" action="{{ route('plaza.stores.set-active', $store->id) }}">
                                @csrf
                                <button 
                                    type="submit"
                                    class="w-full bg-white border-2 border-gray-200 rounded-lg p-6 hover:border-blue-500 hover:shadow-lg transition-all text-left"
                                >
                                    <div class="flex items-center mb-4">
                                        @if($store->logoUrl)
                                            <img src="{{ $store->logoUrl }}" alt="{{ $store->name }}" class="w-12 h-12 rounded mr-3">
                                        @else
                                            <div class="w-12 h-12 bg-blue-500 rounded flex items-center justify-center text-white font-bold text-xl mr-3">
                                                {{ substr($store->name, 0, 1) }}
                                            </div>
                                        @endif
                                        <h3 class="text-xl font-bold text-gray-800">{{ $store->name }}</h3>
                                    </div>
                                    <p class="text-gray-600 text-sm mb-2">{{ $store->domainUrl }}</p>
                                    <p class="text-xs text-gray-500">
                                        @php
                                            $date = $store->createdAt instanceof \DateTime ? $store->createdAt : new \DateTime($store->createdAt);
                                        @endphp
                                        Creada: {{ $date->format('d/m/Y') }}
                                    </p>
                                </button>
                            </form>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <p class="text-gray-600 mb-4">No tienes acceso a ninguna tienda.</p>
                        <a href="{{ route('plaza.logout') }}" class="text-blue-500 hover:text-blue-700">
                            Cerrar sesión
                        </a>
                    </div>
                @endif

                <div class="mt-8 text-center">
                    <form method="POST" action="{{ route('plaza.logout') }}">
                        @csrf
                        <button type="submit" class="text-gray-500 hover:text-gray-700 text-sm">
                            Cerrar Sesión
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

