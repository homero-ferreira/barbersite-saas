
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Administrativo - BarberSite</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gray-100 flex items-center justify-center p-4">

    <main class="w-full max-w-md bg-white rounded-xl shadow-lg p-8">
        <h1 class="text-2xl font-bold text-center text-gray-900">
            BarberSite
        </h1>

        <p class="text-center text-gray-500 mt-2 mb-8">
            Acesso administrativo
        </p>

        @if (session('sucesso'))
            <div class="mb-5 rounded-lg bg-green-50 p-3 text-sm text-green-700">
                {{ session('sucesso') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-5 rounded-lg bg-red-50 p-3 text-sm text-red-700">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('painel.login.entrar') }}" class="space-y-5">
            @csrf

            <div>
<label for="email" class="block text-sm font-medium text-gray-700 mb-1">
    E-mail ou usuário
</label>
<input
    id="email"
    name="email"
    type="text"
                    value="{{ old('email') }}"
                    required
                    autocomplete="username"
                    class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-gray-900"
                >
            </div>

            <div>
                <label for="senha" class="block text-sm font-medium text-gray-700 mb-1">
                    Senha
                </label>
                <input
                    id="senha"
                    name="senha"
                    type="password"
                    required
                    autocomplete="current-password"
                    class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-gray-900"
                >
            </div>

            <button
                type="submit"
                class="w-full rounded-lg bg-gray-900 px-4 py-3 font-semibold text-white hover:bg-gray-800 transition cursor-pointer"
            >
                Entrar
            </button>
        </form>
    </main>

</body>
</html>
