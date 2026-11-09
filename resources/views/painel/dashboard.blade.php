<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Administrativo - BarberSite</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gray-100">

    <header class="bg-gray-900 text-white px-6 py-4">
        <div class="max-w-6xl mx-auto flex flex-col gap-4">

            <div class="flex items-center justify-between gap-4">
                <a href="{{ route('painel.dashboard') }}" class="flex items-center">
                    <img
                        src="{{ asset('branding/logo-barbersite.png') }}"
                        alt="BarberSite"
                        class="h-10 w-auto max-w-44 object-contain"
                    >
                </a>

                <form method="POST" action="{{ route('painel.sair') }}">
                    @csrf

                    <button
                        type="submit"
                        class="rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700 cursor-pointer"
                    >
                        Sair
                    </button>
                </form>
            </div>

            <nav class="flex flex-wrap items-center gap-2 text-sm font-semibold">

                @if (session('demo_mode'))

                    {{-- MENU ATIVO --}}
                    <a
                        href="{{ route('painel.usuarios.index') }}"
                        class="rounded-lg bg-blue-600 px-4 py-2 text-white shadow-sm hover:bg-blue-700 transition"
                    >
                        Cadastro de Usuários
                    </a>

                    {{-- MENUS INATIVOS --}}
                    <span class="cursor-not-allowed rounded-lg px-4 py-2 text-gray-500 opacity-60" title="Disponível na versão administrativa">
                        Dashboard
                    </span>

                    <span class="cursor-not-allowed rounded-lg px-4 py-2 text-gray-500 opacity-60" title="Disponível na versão administrativa">
                        Compartilhar
                    </span>

                    <span class="cursor-not-allowed rounded-lg px-4 py-2 text-gray-500 opacity-60" title="Disponível na versão administrativa">
                        PDF
                    </span>

                    <span class="cursor-not-allowed rounded-lg px-4 py-2 text-gray-500 opacity-60" title="Disponível na versão administrativa">
                        Cadastros ▾
                    </span>

                    <span class="cursor-not-allowed rounded-lg px-4 py-2 text-gray-500 opacity-60" title="Disponível na versão administrativa">
                        Financeiro ▾
                    </span>

                    <span class="cursor-not-allowed rounded-lg px-4 py-2 text-gray-500 opacity-60" title="Disponível na versão administrativa">
                        Mercado Pago
                    </span>

                    <span class="cursor-not-allowed rounded-lg px-4 py-2 text-gray-500 opacity-60" title="Disponível na versão administrativa">
                        Chamados
                    </span>

                    <span class="cursor-not-allowed rounded-lg px-4 py-2 text-gray-500 opacity-60" title="Disponível na versão administrativa">
                        Configurações ▾
                    </span>

                @else

                    <a
                        href="{{ route('painel.dashboard') }}"
                        class="rounded-lg bg-gray-700 px-4 py-2 text-white hover:bg-gray-600"
                    >
                        Dashboard
                    </a>

                    <span class="cursor-not-allowed rounded-lg px-4 py-2 text-gray-400" title="Em desenvolvimento">
                        Compartilhar
                    </span>

                    <span class="cursor-not-allowed rounded-lg px-4 py-2 text-gray-400" title="Em desenvolvimento">
                        PDF
                    </span>

                    <details class="group relative">
                        <summary class="cursor-pointer list-none rounded-lg px-4 py-2 hover:bg-gray-700">
                            Cadastros ▾
                        </summary>

                        <div class="absolute left-0 top-full z-50 mt-2 min-w-48 rounded-xl border border-gray-200 bg-white p-2 text-gray-800 shadow-xl">
                            <a href="{{ route('painel.usuarios.index') }}" class="block rounded-lg px-3 py-2 text-gray-800 hover:bg-gray-100">Usuários</a>
                            <span class="block cursor-not-allowed rounded-lg px-3 py-2 text-gray-400">Equipe</span>
                            <span class="block cursor-not-allowed rounded-lg px-3 py-2 text-gray-400">Planos</span>
                            <span class="block cursor-not-allowed rounded-lg px-3 py-2 text-gray-400">Serviços</span>
                        </div>
                    </details>

                    <details class="group relative">
                        <summary class="cursor-pointer list-none rounded-lg px-4 py-2 hover:bg-gray-700">
                            Financeiro ▾
                        </summary>

                        <div class="absolute left-0 top-full z-50 mt-2 min-w-56 rounded-xl border border-gray-200 bg-white p-2 text-gray-800 shadow-xl">
                            <span class="block cursor-not-allowed rounded-lg px-3 py-2 text-gray-400">Faturas</span>
                            <span class="block cursor-not-allowed rounded-lg px-3 py-2 text-gray-400">Relatório de pagamentos</span>
                        </div>
                    </details>

                    <span class="cursor-not-allowed rounded-lg px-4 py-2 text-gray-400" title="Em desenvolvimento">
                        Mercado Pago
                    </span>

                    <span class="cursor-not-allowed rounded-lg px-4 py-2 text-gray-400" title="Em desenvolvimento">
                        Chamados
                    </span>

                    <details class="group relative">
                        <summary class="cursor-pointer list-none rounded-lg px-4 py-2 hover:bg-gray-700">
                            Configurações ▾
                        </summary>

                        <div class="absolute left-0 top-full z-50 mt-2 min-w-60 rounded-xl border border-gray-200 bg-white p-2 text-gray-800 shadow-xl">
                            <span class="block cursor-not-allowed rounded-lg px-3 py-2 text-gray-400">
                                Scripts e CSS personalizado
                            </span>
                        </div>
                    </details>

                @endif

            </nav>

        </div>
    </header>

    <main class="max-w-6xl mx-auto p-6">

        @if (session('demo_mode'))
            <div class="mb-6 rounded-xl border border-blue-200 bg-blue-50 p-5 text-blue-900">
                <h2 class="text-lg font-bold mb-2">
                    Você entrou com o usuário demo.
                </h2>

                <p class="text-sm leading-relaxed">
                    Nesta conta você não conseguirá alterar nenhuma informação, apenas visualizá-las.
                </p>

                <p class="mt-3 text-sm leading-relaxed">
                    O BarberSite foi desenvolvido para simplificar a criação de sites. O funcionamento principal desta demonstração é o cadastro de usuários, permitindo criar automaticamente um site para cada usuário cadastrado.
                </p>

                <p class="mt-3 text-sm font-semibold">
                    Os demais menus estão indisponíveis nesta demonstração. Você terá acesso apenas ao menu Cadastro de Usuários.
                </p>
            </div>
        @endif

        <h2 class="text-2xl font-bold text-gray-900">
            Painel administrativo
        </h2>

        <p class="mt-2 text-gray-600">
            Bem-vindo, {{ $usuario->nome }}!
        </p>

        <section class="mt-6">
            <h3 class="mb-4 text-lg font-semibold text-gray-900">
                Visão geral
            </h3>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">

                <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                    <p class="text-sm font-medium text-gray-500">Clientes cadastrados</p>
                    <p class="mt-3 text-3xl font-bold text-gray-900">
                        {{ number_format($indicadores['clientes_cadastrados'], 0, ',', '.') }}
                    </p>
                </div>

                <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                    <p class="text-sm font-medium text-gray-500">Planos ativos</p>
                    <p class="mt-3 text-3xl font-bold text-green-600">
                        {{ number_format($indicadores['planos_ativos'], 0, ',', '.') }}
                    </p>
                </div>

                <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                    <p class="text-sm font-medium text-gray-500">Vencem em até 7 dias</p>
                    <p class="mt-3 text-3xl font-bold text-amber-600">
                        {{ number_format($indicadores['vencem_sete_dias'], 0, ',', '.') }}
                    </p>
                </div>

                <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                    <p class="text-sm font-medium text-gray-500">Planos expirados</p>
                    <p class="mt-3 text-3xl font-bold text-red-600">
                        {{ number_format($indicadores['planos_expirados'], 0, ',', '.') }}
                    </p>
                </div>

                <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                    <p class="text-sm font-medium text-gray-500">Sites pausados</p>
                    <p class="mt-3 text-3xl font-bold text-gray-700">
                        {{ number_format($indicadores['sites_pausados'], 0, ',', '.') }}
                    </p>
                </div>

                <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                    <p class="text-sm font-medium text-gray-500">Faturas pendentes</p>
                    <p class="mt-3 text-3xl font-bold text-orange-600">
                        {{ number_format($indicadores['faturas_pendentes'], 0, ',', '.') }}
                    </p>
                </div>

                <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                    <p class="text-sm font-medium text-gray-500">Recebido neste mês</p>
                    <p class="mt-3 text-2xl font-bold text-emerald-600">
                        R$ {{ number_format($indicadores['recebido_mes'], 2, ',', '.') }}
                    </p>
                </div>

                <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                    <p class="text-sm font-medium text-gray-500">Recebido neste ano</p>
                    <p class="mt-3 text-2xl font-bold text-emerald-600">
                        R$ {{ number_format($indicadores['recebido_ano'], 2, ',', '.') }}
                    </p>
                </div>

            </div>
        </section>

        <section class="mt-6">
            <h3 class="mb-4 text-lg font-semibold text-gray-900">
                Central de chamados
            </h3>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">

                <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                    <p class="text-sm font-medium text-gray-500">
                        Chamados pendentes
                    </p>

                    <p class="mt-3 text-3xl font-bold text-amber-600">
                        {{ number_format($indicadoresChamados['pendentes'], 0, ',', '.') }}
                    </p>
                </div>

                <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                    <p class="text-sm font-medium text-gray-500">
                        Em preparação
                    </p>

                    <p class="mt-3 text-3xl font-bold text-blue-600">
                        {{ number_format($indicadoresChamados['em_preparacao'], 0, ',', '.') }}
                    </p>
                </div>

                <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                    <p class="text-sm font-medium text-gray-500">
                        Chamados concluídos
                    </p>

                    <p class="mt-3 text-3xl font-bold text-green-600">
                        {{ number_format($indicadoresChamados['concluidos'], 0, ',', '.') }}
                    </p>
                </div>

            </div>
        </section>

        <section class="mt-6 grid grid-cols-1 gap-5 lg:grid-cols-2">

            <div class="min-w-0 rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                <h3 class="mb-4 text-lg font-semibold text-gray-900">
                    Próximos vencimentos e atrasados
                </h3>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="border-b border-gray-200 bg-gray-50 text-gray-600">
                            <tr>
                                <th class="px-3 py-3 font-semibold">Cliente</th>
                                <th class="px-3 py-3 font-semibold">Validade</th>
                                <th class="px-3 py-3 font-semibold">Situação</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-100">
                            @forelse ($proximosVencimentos as $vencimento)
                                <tr>
                                    <td class="px-3 py-3 text-gray-900">
                                        {{ $vencimento->nome }}
                                    </td>

                                    <td class="whitespace-nowrap px-3 py-3 text-gray-600">
                                        {{ \Carbon\Carbon::parse($vencimento->data_expiracao)->format('d/m/Y') }}
                                    </td>

                                    <td class="whitespace-nowrap px-3 py-3">
                                        @if ($vencimento->dias < 0)
                                            <span class="font-semibold text-red-600">
                                                Expirado
                                            </span>
                                        @elseif ($vencimento->dias == 0)
                                            <span class="font-semibold text-amber-600">
                                                Hoje
                                            </span>
                                        @else
                                            <span class="font-semibold text-amber-600">
                                                {{ $vencimento->dias }} dias
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-3 py-6 text-center text-gray-500">
                                        Nenhum vencimento próximo.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="min-w-0 rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                <h3 class="mb-4 text-lg font-semibold text-gray-900">
                    Últimos pagamentos
                </h3>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="border-b border-gray-200 bg-gray-50 text-gray-600">
                            <tr>
                                <th class="px-3 py-3 font-semibold">Cliente</th>
                                <th class="px-3 py-3 font-semibold">Data</th>
                                <th class="px-3 py-3 font-semibold">Valor</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-100">
                            @forelse ($ultimosPagamentos as $pagamento)
                                <tr>
                                    <td class="px-3 py-3 text-gray-900">
                                        {{ $pagamento->nome }}
                                    </td>

                                    <td class="whitespace-nowrap px-3 py-3 text-gray-600">
                                        {{ \Carbon\Carbon::parse($pagamento->aprovado_em)->format('d/m/Y') }}
                                    </td>

                                    <td class="whitespace-nowrap px-3 py-3 font-semibold text-emerald-600">
                                        R$ {{ number_format($pagamento->recebido, 2, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-3 py-6 text-center text-gray-500">
                                        Nenhum pagamento confirmado.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </section>

        <section class="mt-6 rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
            <h3 class="text-lg font-semibold text-gray-900">
                Recebimentos por mês
            </h3>

            <p class="mt-1 text-sm text-gray-500">
                Pagamentos aprovados nos últimos 12 meses.
            </p>

            @php
                $maiorRecebimento = max(
                    1,
                    ...array_column($graficoRecebimentos, 'total')
                );
            @endphp

            <div class="mt-6 space-y-4">
                @foreach ($graficoRecebimentos as $recebimento)
                    <div class="flex items-center gap-3">

                        <span class="w-16 shrink-0 text-xs font-medium text-gray-600">
                            {{ $recebimento['mes'] }}
                        </span>

                        <div class="h-5 min-w-0 flex-1 overflow-hidden rounded-md bg-gray-100">
                            <div
                                class="h-full rounded-md bg-blue-600"
                                style="width: {{ ($recebimento['total'] / $maiorRecebimento) * 100 }}%"
                            ></div>
                        </div>

                        <span class="w-28 shrink-0 text-right text-xs font-semibold text-gray-700">
                            R$ {{ number_format($recebimento['total'], 2, ',', '.') }}
                        </span>

                    </div>
                @endforeach
            </div>

            @if (array_sum(array_column($graficoRecebimentos, 'total')) == 0)
                <p class="mt-5 text-center text-sm text-gray-500">
                    Nenhum recebimento registrado neste período.
                </p>
            @endif
        </section>

        <section class="mt-6">
            <h3 class="mb-4 text-lg font-semibold text-gray-900">
                Resumo operacional
            </h3>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">

                <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                    <p class="text-sm font-medium text-gray-500">
                        Faturas pagas
                    </p>

                    <p class="mt-3 text-3xl font-bold text-emerald-600">
                        {{ number_format($resumoOperacional['faturas_pagas'], 0, ',', '.') }}
                    </p>
                </div>

                <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                    <p class="text-sm font-medium text-gray-500">
                        Planos disponíveis
                    </p>

                    <p class="mt-3 text-3xl font-bold text-blue-600">
                        {{ number_format($resumoOperacional['planos_disponiveis'], 0, ',', '.') }}
                    </p>
                </div>

                <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                    <p class="text-sm font-medium text-gray-500">
                        Produtos cadastrados
                    </p>

                    <p class="mt-3 text-3xl font-bold text-gray-900">
                        {{ number_format($resumoOperacional['produtos_cadastrados'], 0, ',', '.') }}
                    </p>
                </div>

            </div>
        </section>
    </main>

</body>
</html>