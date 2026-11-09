
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuários - BarberSite</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gray-100" data-demo-mode="{{ session('demo_mode') ? '1' : '0' }}">

    <header class="bg-gray-900 px-6 py-4 text-white">
        <div class="mx-auto flex max-w-6xl flex-wrap items-center justify-between gap-4">
            <a href="{{ route('painel.dashboard') }}">
                <img
                    src="{{ asset('branding/logo-barbersite.png') }}"
                    alt="BarberSite"
                    class="h-10 w-auto max-w-44 object-contain"
                >
            </a>

            <div class="flex items-center gap-3">
                <a
                    href="{{ route('painel.dashboard') }}"
                    class="rounded-lg px-4 py-2 text-sm font-semibold hover:bg-gray-700"
                >
                    Dashboard
                </a>

                <form method="POST" action="{{ route('painel.sair') }}">
                    @csrf
                    <button
                        type="submit"
                        class="cursor-pointer rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold hover:bg-red-700"
                    >
                        Sair
                    </button>
                </form>
            </div>
        </div>
    </header>

    <main class="mx-auto max-w-6xl p-6">

        <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">
                    Usuários
                </h1>
                <p class="mt-1 text-sm text-gray-500">
                    Gerenciamento de clientes cadastrados
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <span id="total-usuarios" class="rounded-lg bg-white px-4 py-2 text-sm font-semibold text-gray-700 shadow-sm">
                    {{ number_format($usuarios->total(), 0, ',', '.') }} registros
                </span>

                @if (session('demo_mode'))
<button
    type="button"
    id="abrir-modal-usuario"
    data-url="{{ route('painel.usuarios.create') }}"
    disabled
    title="Cadastro desabilitado na demonstração"
    class="cursor-not-allowed rounded-lg bg-gray-300 px-4 py-2 text-sm font-semibold text-gray-500"
>
    + Novo usuário
</button>
                @else
                    <button
                        type="button"
                        id="abrir-modal-usuario"
                        data-url="{{ route('painel.usuarios.create') }}"
                        class="cursor-pointer rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700"
                    >
                        + Novo usuário
                    </button>
                @endif
            </div>
        </div>

        <section id="listagem-usuarios" class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">

            <form
                method="GET"
                action="{{ route('painel.usuarios.index') }}"
                class="grid grid-cols-1 items-end gap-3 md:grid-cols-12"
            >
                <div class="md:col-span-5">
                    <label for="busca" class="mb-1 block text-sm font-semibold text-gray-700">
                        Pesquisar
                    </label>
                    <input
                        type="search"
                        id="busca"
                        name="busca"
                        value="{{ $busca }}"
                        placeholder="Nome, e-mail ou ID"
                        maxlength="120"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm outline-none focus:border-blue-500"
                    >
                </div>

                <div class="md:col-span-3">
                    <label for="filtro" class="mb-1 block text-sm font-semibold text-gray-700">
                        Filtrar por
                    </label>
                    <select
                        id="filtro"
                        name="filtro"
                        class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm"
                    >
                        <option value="todos" @selected($filtro === 'todos')>Todos</option>
                        <option value="inadimplentes" @selected($filtro === 'inadimplentes')>Inadimplentes</option>
                        <option value="pausados" @selected($filtro === 'pausados')>Sites pausados</option>
                        <option value="pagamento" @selected($filtro === 'pagamento')>Pagamento</option>
                        <option value="assinatura" @selected($filtro === 'assinatura')>Assinatura</option>
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label for="limite" class="mb-1 block text-sm font-semibold text-gray-700">
                        Por página
                    </label>
                    <select
                        id="limite"
                        name="limite"
                        class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm"
                    >
                        <option value="10" @selected($limite === 10)>10</option>
                        <option value="30" @selected($limite === 30)>30</option>
                        <option value="50" @selected($limite === 50)>50</option>
                    </select>
                </div>

                <button
                    type="submit"
                    class="cursor-pointer rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700 md:col-span-2"
                >
                    Buscar
                </button>
            </form>

            <div class="mt-5 overflow-x-auto">
                <table class="w-full min-w-245 text-left text-sm">
                    <thead class="border-b border-gray-200 bg-gray-50 text-gray-600">
                        <tr>
                            <th class="px-3 py-3 font-semibold">ID</th>
                            <th class="px-3 py-3 font-semibold">Cliente</th>
                            <th class="px-3 py-3 font-semibold">Contato</th>
                            <th class="px-3 py-3 font-semibold">Domínio</th>
                            <th class="px-3 py-3 font-semibold">Status</th>
                            <th class="px-3 py-3 font-semibold">Faturas vencidas</th>
                            <th class="px-3 py-3 font-semibold">Validade</th>
                            <th class="px-3 py-3 font-semibold">Ações</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100">
                        @forelse ($usuarios as $cliente)
                            <tr class="hover:bg-gray-50">
                                <td class="px-3 py-4 font-semibold text-gray-700">
                                    {{ $cliente->id }}
                                </td>

                                <td class="px-3 py-4">
                                    <div class="font-semibold text-gray-900">
                                        {{ $cliente->nome }}
                                    </div>
                                    <div class="mt-1 text-xs text-gray-500">
                                        {{ $cliente->modalidade_pagamento ?: 'Sem modalidade' }}
                                    </div>
                                </td>

                                <td class="px-3 py-4 text-gray-600">
                                    <div>{{ $cliente->email }}</div>
                                    <div class="mt-1">{{ $cliente->whatsapp ?: '—' }}</div>
                                </td>

                                <td class="px-3 py-4 text-gray-600">
                                    {{ $cliente->dominio ?: '—' }}
                                </td>

                                <td class="px-3 py-4">
                                    <div>
                                        @if ($cliente->ativo)
                                            <span class="font-semibold text-green-600">Ativo</span>
                                        @else
                                            <span class="font-semibold text-red-600">Inativo</span>
                                        @endif
                                    </div>

                                    @if ($cliente->site_pausado)
                                        <div class="mt-1 text-xs font-semibold text-amber-600">
                                            Site pausado
                                        </div>
                                    @endif
                                </td>

                                <td class="px-3 py-4">
                                    @if ($cliente->vencidas > 0)
                                        <div class="font-semibold text-red-600">
                                            {{ $cliente->vencidas }} vencida(s)
                                        </div>
                                        <div class="mt-1 text-xs text-red-600">
                                            R$ {{ number_format($cliente->total_vencido, 2, ',', '.') }}
                                        </div>
                                    @else
                                        <span class="text-gray-500">Nenhuma</span>
                                    @endif
                                </td>

                                <td class="px-3 py-4 text-gray-600">
                                    @if ($cliente->data_expiracao)
                                        <div>
                                            {{ \Carbon\Carbon::parse($cliente->data_expiracao)->format('d/m/Y') }}
                                        </div>
                                        <div class="mt-1 text-xs text-gray-500">
                                            Tolerância: {{ (int) $cliente->dias_tolerancia }} dias
                                        </div>
                                    @else
                                        —
                                    @endif
                                </td>

                                <td class="px-3 py-4">
                                    @if ((int) $cliente->id === 1)
                                        <span class="text-xs font-semibold text-gray-500">
                                            Administrador
                                        </span>
                                    @else
                                        <button
                                            type="button"
                                            class="editar-usuario cursor-pointer rounded-lg bg-gray-900 px-3 py-2 text-xs font-semibold text-white hover:bg-gray-700"
                                            data-url="{{ route('painel.usuarios.edit', ['id' => $cliente->id]) }}"
                                        >
                                            Editar
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-3 py-10 text-center text-gray-500">
                                    Nenhum usuário encontrado.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($usuarios->hasPages())
                <div class="mt-5 border-t border-gray-100 pt-5">
                    {{ $usuarios->links() }}
                </div>
            @endif

        </section>
    </main>

    <div
        id="modal-usuario"
        class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 p-4"
        role="dialog"
        aria-modal="true"
        aria-labelledby="modal-usuario-titulo"
        aria-hidden="true"
    >
        <div
            id="modal-usuario-janela"
            class="flex max-h-[90vh] w-full max-w-3xl flex-col overflow-hidden rounded-xl bg-white shadow-2xl"
        >
            <div class="flex items-center justify-between border-b border-gray-200 px-6 py-4">
                <h2 id="modal-usuario-titulo" class="text-lg font-bold text-gray-900">
                    Novo usuário
                </h2>

                <button
                    type="button"
                    id="fechar-modal-usuario"
                    aria-label="Fechar cadastro"
                    class="cursor-pointer rounded-lg px-3 py-1 text-2xl text-gray-500 hover:bg-gray-100"
                >
                    &times;
                </button>
            </div>

            <div id="modal-usuario-conteudo" class="overflow-y-auto p-6">
                <p class="text-sm text-gray-500">Carregando formulário...</p>
            </div>
        </div>
    </div>

</body>
</html>