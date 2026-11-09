<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instalação | BarberSite</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-gray-100 flex items-center justify-center p-4">

    <main class="w-full max-w-md bg-white rounded-xl shadow-lg p-8">

        <h1 class="text-2xl font-bold text-gray-900 text-center mb-2">
            BarberSite
        </h1>

        <p class="text-gray-500 text-center mb-6">
            Configuração do administrador principal
        </p>

        @if ($errors->any())
            <div class="bg-red-50 text-red-700 rounded-lg p-3 mb-5 text-sm">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $erro)
                        <li>{{ $erro }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('instalar.salvar') }}" class="space-y-4">
            @csrf

            <div>
                <label for="nome" class="block text-sm font-semibold mb-1">Nome</label>
                <input id="nome" name="nome" type="text" value="{{ old('nome') }}"
                       required maxlength="150" autocomplete="name"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2">
            </div>

            <div>
                <label for="email" class="block text-sm font-semibold mb-1">E-mail</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}"
                       required maxlength="190" autocomplete="email"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2">
            </div>

            <div>
                <label for="whatsapp" class="block text-sm font-semibold mb-1">WhatsApp</label>
                <input id="whatsapp" name="whatsapp" type="tel" value="{{ old('whatsapp') }}"
                       required maxlength="30" autocomplete="tel"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2">
            </div>

            <div>
                <label for="senha" class="block text-sm font-semibold mb-1">Senha</label>
                <input id="senha" name="senha" type="password"
                       required minlength="8" autocomplete="new-password"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2">
            </div>

            <div>
                <label for="senha_confirmation" class="block text-sm font-semibold mb-1">
                    Confirmar senha
                </label>
                <input id="senha_confirmation" name="senha_confirmation" type="password"
                       required minlength="8" autocomplete="new-password"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2">
            </div>

            <button type="submit"
                    class="w-full bg-blue-700 hover:bg-blue-800 text-white font-semibold rounded-lg py-3 cursor-pointer">
                Criar administrador
            </button>
        </form>

    </main>

</body>
</html>