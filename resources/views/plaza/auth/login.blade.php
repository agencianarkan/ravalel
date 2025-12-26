<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Plaza</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="max-w-md w-full bg-white rounded-lg shadow-md p-8">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800">Plaza</h1>
            <p class="text-gray-600 mt-2">Sistema de Gestión Multi-Tienda</p>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
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

        <form method="POST" action="{{ route('plaza.login') }}">
            @csrf
            
            <div class="mb-4">
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

            <div class="mb-6">
                <label for="password" class="block text-gray-700 text-sm font-bold mb-2">
                    Contraseña
                </label>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    required
                >
            </div>

            <div class="flex items-center justify-between">
                <button 
                    type="submit" 
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline w-full"
                >
                    Iniciar Sesión
                </button>
            </div>

            <div class="mt-4 text-center">
                <a href="{{ route('plaza.password.request') }}" class="text-sm text-blue-500 hover:text-blue-700">
                    ¿Olvidaste tu contraseña?
                </a>
            </div>
        </form>

        <div class="mt-8 pt-8 border-t border-gray-200">
            <p class="text-xs text-gray-500 text-center mb-2">Usuarios de prueba:</p>
            <div class="text-xs text-gray-600 space-y-1">
                <div><strong>owner@example.com</strong> / password123</div>
                <div><strong>manager@example.com</strong> / password123</div>
                <div><strong>logistics@example.com</strong> / password123</div>
                <div><strong>editor@example.com</strong> / password123</div>
            </div>
        </div>
    </div>
</body>
</html>

