<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Plaza</title>
    @if(file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
    <style>
        /* Fallback si Vite no está disponible */
        body { font-family: system-ui, -apple-system, sans-serif; }
        .bg-gray-100 { background-color: #f3f4f6; }
        .min-h-screen { min-height: 100vh; }
        .flex { display: flex; }
        .items-center { align-items: center; }
        .justify-center { justify-content: center; }
        .max-w-md { max-width: 28rem; }
        .w-full { width: 100%; }
        .bg-white { background-color: white; }
        .rounded-lg { border-radius: 0.5rem; }
        .shadow-md { box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); }
        .p-8 { padding: 2rem; }
        .text-center { text-align: center; }
        .mb-8 { margin-bottom: 2rem; }
        .text-3xl { font-size: 1.875rem; }
        .font-bold { font-weight: 700; }
        .text-gray-800 { color: #1f2937; }
        .text-gray-600 { color: #4b5563; }
        .mt-2 { margin-top: 0.5rem; }
        .mb-4 { margin-bottom: 1rem; }
        .px-4 { padding-left: 1rem; padding-right: 1rem; }
        .py-3 { padding-top: 0.75rem; padding-bottom: 0.75rem; }
        .rounded { border-radius: 0.25rem; }
        .bg-green-100 { background-color: #d1fae5; }
        .border-green-400 { border-color: #4ade80; }
        .text-green-700 { color: #15803d; }
        .block { display: block; }
        .text-sm { font-size: 0.875rem; }
        .mb-2 { margin-bottom: 0.5rem; }
        .shadow { box-shadow: 0 1px 3px 0 rgba(0,0,0,0.1); }
        .appearance-none { appearance: none; }
        .border { border-width: 1px; border-style: solid; }
        .w-full { width: 100%; }
        .py-2 { padding-top: 0.5rem; padding-bottom: 0.5rem; }
        .px-3 { padding-left: 0.75rem; padding-right: 0.75rem; }
        .text-gray-700 { color: #374151; }
        .leading-tight { line-height: 1.25; }
        .focus\:outline-none:focus { outline: 2px solid transparent; outline-offset: 2px; }
        .focus\:shadow-outline:focus { box-shadow: 0 0 0 3px rgba(66,153,225,0.5); }
        .mb-6 { margin-bottom: 1.5rem; }
        .bg-blue-500 { background-color: #3b82f6; }
        .hover\:bg-blue-700:hover { background-color: #1d4ed8; }
        .text-white { color: white; }
        .font-bold { font-weight: 700; }
        .rounded { border-radius: 0.25rem; }
        .focus\:outline-none:focus { outline: 2px solid transparent; }
        .mt-4 { margin-top: 1rem; }
        .text-blue-500 { color: #3b82f6; }
        .hover\:text-blue-700:hover { color: #1e40af; }
        .mt-8 { margin-top: 2rem; }
        .pt-8 { padding-top: 2rem; }
        .border-t { border-top-width: 1px; }
        .border-gray-200 { border-color: #e5e7eb; }
        .text-xs { font-size: 0.75rem; }
        .text-gray-500 { color: #6b7280; }
        .space-y-1 > * + * { margin-top: 0.25rem; }
        .text-gray-600 { color: #4b5563; }
    </style>
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

