<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Contraseña - Plaza</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="max-w-md w-full bg-white rounded-lg shadow-md p-8">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800">Recuperar Contraseña</h1>
            <p class="text-gray-600 mt-2">Ingresa tu email para recibir el enlace de recuperación</p>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
                @if(session('dev_token'))
                    <div class="mt-2 p-2 bg-gray-100 rounded text-xs">
                        <strong>Token (solo desarrollo):</strong><br>
                        <code class="break-all">{{ session('dev_token') }}</code><br>
                        <a href="{{ route('plaza.password.reset', session('dev_token')) }}" class="text-blue-500 underline">
                            Ir al formulario de reset
                        </a>
                    </div>
                @endif
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('plaza.password.email') }}">
            @csrf
            
            <div class="mb-6">
                <label for="email" class="block text-gray-700 text-sm font-bold mb-2">
                    Email
                </label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    value="{{ old('email') }}"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    required
                    autofocus
                >
            </div>

            <div class="flex items-center justify-between">
                <button 
                    type="submit" 
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline w-full"
                >
                    Enviar Enlace de Recuperación
                </button>
            </div>

            <div class="mt-4 text-center">
                <a href="{{ route('plaza.login') }}" class="text-sm text-blue-500 hover:text-blue-700">
                    Volver al login
                </a>
            </div>
        </form>
    </div>
</body>
</html>

