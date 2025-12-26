<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - {{ $store->name }} - Plaza</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 min-h-screen">
    <nav class="bg-white shadow-md">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center">
                    <h1 class="text-2xl font-bold text-gray-800">Plaza</h1>
                    <span class="ml-4 text-gray-600">|</span>
                    <span class="ml-4 text-gray-700">{{ $store->name }}</span>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('plaza.stores.select') }}" class="text-blue-500 hover:text-blue-700 text-sm">
                        Cambiar Tienda
                    </a>
                    <form method="POST" action="{{ route('plaza.logout') }}">
                        @csrf
                        <button type="submit" class="text-gray-500 hover:text-gray-700 text-sm">
                            Cerrar Sesión
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <div class="container mx-auto px-4 py-8">
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-lg shadow-md p-8 mb-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">Información de la Tienda</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-gray-600">Nombre:</p>
                    <p class="font-semibold">{{ $store->name }}</p>
                </div>
                <div>
                    <p class="text-gray-600">Dominio:</p>
                    <p class="font-semibold">{{ $store->domainUrl }}</p>
                </div>
                <div>
                    <p class="text-gray-600">API Key:</p>
                    <p class="font-semibold text-xs">{{ $store->apiKey ?? 'No configurado' }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Tus Permisos</h2>
            
            @foreach($permissions as $module => $modulePermissions)
                <div class="mb-8">
                    <h3 class="text-xl font-semibold text-gray-700 mb-4 capitalize">{{ $module }}</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($modulePermissions as $slug => $permission)
                            <div class="border rounded-lg p-4 {{ $permission['granted'] ? 'bg-green-50 border-green-200' : 'bg-gray-50 border-gray-200' }}">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="font-medium text-gray-800">{{ $permission['label'] }}</p>
                                        <p class="text-xs text-gray-500 mt-1">{{ $slug }}</p>
                                    </div>
                                    <div>
                                        @if($permission['granted'])
                                            <span class="bg-green-500 text-white text-xs px-2 py-1 rounded">✓ Permitido</span>
                                        @else
                                            <span class="bg-gray-300 text-gray-700 text-xs px-2 py-1 rounded">✗ Denegado</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>

        <div class="bg-white rounded-lg shadow-md p-8 mt-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">Pruebas de Endpoints</h2>
            <div class="space-y-4">
                <div>
                    <p class="text-gray-600 mb-2">Probar permisos específicos:</p>
                    <div class="flex flex-wrap gap-2">
                        <a href="{{ route('plaza.test.permission', 'orders.view') }}" 
                           class="bg-blue-500 hover:bg-blue-700 text-white px-4 py-2 rounded text-sm">
                            Test: orders.view
                        </a>
                        <a href="{{ route('plaza.test.permission', 'orders.manage') }}" 
                           class="bg-blue-500 hover:bg-blue-700 text-white px-4 py-2 rounded text-sm">
                            Test: orders.manage
                        </a>
                        <a href="{{ route('plaza.test.permission', 'products.manage') }}" 
                           class="bg-blue-500 hover:bg-blue-700 text-white px-4 py-2 rounded text-sm">
                            Test: products.manage
                        </a>
                        <a href="{{ route('plaza.test.permission', 'orders.refund') }}" 
                           class="bg-blue-500 hover:bg-blue-700 text-white px-4 py-2 rounded text-sm">
                            Test: orders.refund
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

