<!DOCTYPE html>
<html lang="pt-BR" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ ($produto->seo_titulo ?: $produto->titulo) . ' | ' . ($cliente->titulo_site ?: $cliente->nome) }}</title>
    <meta name="description" content="{{ $produto->seo_descricao ?: strip_tags($produto->descricao ?? '') }}">

    @if(!empty($cliente->favicon))
        <link rel="icon" href="{{ asset(ltrim($cliente->favicon, '/')) }}">
    @endif

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-white text-gray-900">

@php
    $nomeSite = $cliente->razao_social ?: $cliente->nome;

    $corPrincipal = preg_match('/^#[0-9a-fA-F]{6}$/', $cliente->cor_principal ?? '')
        ? $cliente->cor_principal : '#0066FF';

    $corSecundaria = preg_match('/^#[0-9a-fA-F]{6}$/', $cliente->cor_secundaria ?? '')
        ? $cliente->cor_secundaria : '#071D49';

    $imagemProduto = $produto->imagem_detalhe ?: $produto->imagem_capa;

    $imagemProduto = $imagemProduto
        ? asset('img/produtos/' . basename($imagemProduto))
        : null;

    $enderecoCompleto = implode(', ', array_filter([
        $cliente->endereco ?? '',
        $cliente->numero ?? '',
        $cliente->bairro ?? '',
        $cliente->cidade ?? '',
        $cliente->uf ?? '',
    ]));

    $whatsapp = preg_replace('/\D/', '', $cliente->whatsapp ?? '');
@endphp

<div
    id="cliente-site-vue"
    data-cor-principal="{{ $corPrincipal }}"
    data-cor-secundaria="{{ $corSecundaria }}"
>
    <header class="sticky top-0 z-50 border-b border-gray-100 bg-white shadow-sm">
        <div class="mx-auto flex min-h-20 max-w-7xl items-center justify-between gap-5 px-5 lg:px-8">

            <a href="/" class="flex min-w-0 items-center gap-3">
                @if(!empty($cliente->logo))
                    <img
                        src="{{ asset(ltrim($cliente->logo, '/')) }}"
                        alt="{{ $nomeSite }}"
                        class="max-h-14 max-w-44 object-contain"
                    >
                @else
                    <span
                        class="text-xl font-extrabold tracking-tight"
                        :style="{ color: corSecundaria }"
                    >
                        {{ $nomeSite }}
                    </span>
                @endif
            </a>

            <button
                type="button"
                class="flex h-11 w-11 shrink-0 items-center justify-center rounded-lg border border-gray-200 text-2xl lg:hidden"
                @click="menuAberto = !menuAberto"
                :aria-expanded="String(menuAberto)"
                aria-controls="menu-cliente"
                :aria-label="menuAberto ? 'Fechar menu' : 'Abrir menu'"
            >
                <span v-text="menuAberto ? '×' : '☰'"></span>
            </button>

            <nav
                id="menu-cliente"
                class="absolute top-full right-0 left-0 flex flex-col gap-1 border-b border-gray-100 bg-white p-5 shadow-lg lg:static lg:flex lg:flex-row lg:items-center lg:gap-6 lg:border-0 lg:p-0 lg:shadow-none"
                :class="menuAberto ? 'flex' : 'hidden lg:flex'"
                aria-label="Menu principal"
            >
                <a href="/" class="rounded-lg px-2 py-2 text-sm font-semibold text-gray-700 hover:text-gray-950">Início</a>
                <a href="/#produtos" class="rounded-lg px-2 py-2 text-sm font-semibold text-gray-700 hover:text-gray-950">Serviços</a>
                <a href="/#sobre" class="rounded-lg px-2 py-2 text-sm font-semibold text-gray-700 hover:text-gray-950">Sobre nós</a>
                <a href="/#trabalhos" class="rounded-lg px-2 py-2 text-sm font-semibold text-gray-700 hover:text-gray-950">Nossos Trabalhos</a>
                <a href="/#contato" class="rounded-lg px-2 py-2 text-sm font-semibold text-gray-700 hover:text-gray-950">Contato</a>

                <a
                    href="#cotacao"
                    class="mt-2 rounded-lg px-5 py-3 text-center text-sm font-bold text-white lg:mt-0"
                    :style="{ backgroundColor: corPrincipal }"
                    @click="menuAberto = false"
                >
                    Solicitar atendimento
                </a>
            </nav>
        </div>
    </header>

    <main>
        <!-- CAMINHO DE NAVEGAÇÃO -->
        <nav class="border-b border-gray-200 bg-white" aria-label="Caminho de navegação">
            <div class="mx-auto flex max-w-7xl flex-wrap items-center gap-3 px-5 py-4 text-sm text-gray-500 lg:px-8">
                <a href="/" class="hover:underline">Início</a>
                <span>/</span>
                <a href="/#produtos" class="hover:underline">Serviços de beleza</a>
                <span>/</span>
                <span class="font-bold text-gray-800" aria-current="page">{{ $produto->titulo }}</span>
            </div>
        </nav>

        <!-- BANNER DO PRODUTO -->
        <section
            class="overflow-hidden text-white"
            style="background:linear-gradient(115deg, {{ $corPrincipal }}, {{ $corSecundaria }})"
        >
            <div class="mx-auto grid max-w-7xl items-center gap-10 px-5 py-12 lg:grid-cols-2 lg:gap-16 lg:px-8 lg:py-20">

                <div class="min-w-0 text-center lg:text-left">
                    <span class="inline-flex items-center gap-2 rounded-full border border-white/30 bg-white/10 px-4 py-2 text-xs font-extrabold uppercase tracking-widest">
                        <span class="h-2 w-2 rounded-full bg-white"></span>
                        {{ $produto->categoria ?: 'Serviços de beleza' }}
                    </span>

                    <h1 class="mt-6 text-4xl font-black leading-tight tracking-tight md:text-5xl lg:text-6xl">
                        {{ $produto->titulo }}
                    </h1>

                    <p class="mx-auto mt-6 max-w-xl text-base leading-8 text-white/85 lg:mx-0 lg:text-lg">
                        {{ $produto->seo_descricao ?: 'Conheça esta área de atuação e entre em contato.' }}
                    </p>

                    <div class="mt-8 flex flex-wrap items-center justify-center gap-5 lg:justify-start">
                        <a
                            href="#cotacao"
                            class="inline-flex items-center gap-4 rounded-xl bg-white px-6 py-4 text-sm font-extrabold text-gray-900 shadow-lg transition hover:bg-gray-100"
                        >
                            Solicitar atendimento
                            <span aria-hidden="true">→</span>
                        </a>

                        <span class="text-xs font-semibold text-white/80">
                            Atendimento personalizado
                        </span>
                    </div>
                </div>

                @if($imagemProduto)
                    <figure class="relative min-w-0 rounded-3xl border border-white/30 bg-white/10 p-3 shadow-2xl">
                        <img
                            src="{{ $imagemProduto }}"
                            alt="{{ $produto->titulo }}"
                            class="aspect-[4/3] w-full rounded-2xl object-cover"
                            fetchpriority="high"
                        >
                        <figcaption class="absolute bottom-6 left-6 rounded-lg bg-slate-950/80 px-4 py-3 text-xs font-bold uppercase tracking-wider text-white">
                            Serviços de beleza
                        </figcaption>
                    </figure>
                @endif
            </div>
        </section>

        <!-- DESCRIÇÃO E ATENDIMENTO LATERAL -->
        <section class="bg-white py-16 lg:py-20">
            <div class="mx-auto grid max-w-7xl items-start gap-10 px-5 lg:grid-cols-[minmax(0,1fr)_330px] lg:gap-14 lg:px-8">

                <article class="min-w-0 text-base leading-8 text-slate-700">
                    @php
                        $descricaoSegura = \Illuminate\Support\Str::of((string) ($produto->descricao ?? ''))
                            ->replaceMatches('~<(script|style|iframe|object|embed|form|svg|math)\b[^>]*>.*?</\1\s*>~is', '')
                            ->replaceMatches('~<\s*(script|style|iframe|object|embed|form|svg|math)\b[^>]*\/?>~is', '')
                            ->replaceMatches('/\s+on[a-z]+\s*=\s*(?:"[^"]*"|\'[^\']*\'|[^\s>]+)/i', '')
                            ->replaceMatches('/\s+(href|src)\s*=\s*(["\'])\s*(?:javascript|data):.*?\2/i', '');
                    @endphp

                    <div class="space-y-5 [&_h2]:mb-4 [&_h2]:text-3xl [&_h2]:font-black [&_h3]:mb-3 [&_h3]:text-xl [&_h3]:font-bold [&_p]:mb-4 [&_ul]:mb-5 [&_ul]:list-disc [&_ul]:pl-6 [&_ol]:mb-5 [&_ol]:list-decimal [&_ol]:pl-6 [&_img]:max-w-full">
                        {!! $descricaoSegura !!}
                    </div>
                </article>

                <aside class="lg:sticky lg:top-28">
                    <div class="relative overflow-hidden rounded-3xl border border-gray-200 bg-gray-50 p-7 shadow-lg">
                        <div class="absolute right-0 top-0 h-1 w-20" style="background-color: {{ $corPrincipal }}"></div>

                        <div class="ml-auto flex h-12 w-12 items-center justify-center rounded-xl bg-gray-200 text-2xl font-bold text-gray-700">
                            ↗
                        </div>

                        <div class="mt-5 text-right">
                            <p class="text-xs font-extrabold uppercase tracking-widest text-gray-500">
                                Fale com o salão
                            </p>

                            <h2 class="mt-4 text-2xl font-black leading-tight text-gray-800">
                                Entre em contato com a barbearia.
                            </h2>

                            <p class="mt-4 text-sm leading-7 text-gray-600">
                                Entre em contato para informações sobre este serviço.
                            </p>

                            <a
                                href="#cotacao"
                                class="mt-6 flex items-center justify-between gap-3 rounded-xl px-5 py-4 text-sm font-bold text-white"
                                style="background-color: {{ $corPrincipal }}"
                            >
                                Solicitar atendimento
                                <span class="text-xl" aria-hidden="true">→</span>
                            </a>

                            <p class="mt-4 text-xs text-gray-500">
                                Atendimento profissional individualizado.
                            </p>
                        </div>
                    </div>
                </aside>
            </div>
        </section>

        <!-- FORMULÁRIO DEMONSTRATIVO -->
        <section id="cotacao" class="scroll-mt-24 bg-gray-50 py-16 lg:py-20">
            <div class="mx-auto max-w-6xl px-5 lg:px-8">
                <div
                    class="grid items-center gap-8 rounded-3xl p-6 shadow-xl md:p-8 lg:grid-cols-2"
                    style="background-color: {{ $corPrincipal }}"
                >
                    <div class="min-w-0 text-center text-slate-900 lg:text-left">
                        <p class="text-xs font-extrabold uppercase tracking-widest">
                            ◉ Atendimento personalizado
                        </p>

                        <h2 class="mt-5 text-3xl font-black leading-tight md:text-4xl">
                            O primeiro contato começa com uma conversa.
                        </h2>

                        <p class="mt-4 text-sm leading-relaxed">
                            Solicite informações sobre {{ $produto->titulo }}.
                        </p>

                        @if(!empty($cliente->banner_institucional_imagem))
                            <div class="mt-5 overflow-hidden rounded-2xl border-4 border-slate-900/70">
                                <img
                                    src="{{ asset(ltrim($cliente->banner_institucional_imagem, '/')) }}"
                                    alt="Ambiente de {{ $nomeSite }}"
                                    class="aspect-[16/7] w-full object-cover"
                                    loading="lazy"
                                >
                            </div>
                        @endif

                        <p class="mt-5 text-sm">
                            ✓ Atendimento profissional e individualizado
                        </p>
                    </div>

                    <div class="min-w-0 rounded-2xl bg-white p-6 text-slate-800 shadow-sm sm:p-7">
                        <p class="text-xs font-extrabold uppercase tracking-wider text-slate-600">
                            Vamos conversar?
                        </p>

                        <h3 class="mt-3 text-2xl font-black">
                            Solicite atendimento
                        </h3>

                        <p class="mt-1 text-sm text-slate-500">
                            Preencha seus dados para iniciar o atendimento pelo WhatsApp.
                        </p>

                        <form onsubmit="return false;" class="mt-5 space-y-3">
                            <div>
                                <label for="contato-nome" class="mb-1 block text-sm font-bold">Seu nome</label>
                                <input
                                    id="contato-nome"
                                    type="text"
                                    placeholder="Como podemos chamar você?"
                                    class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none"
                                >
                            </div>

                            <div>
                                <label for="contato-telefone" class="mb-1 block text-sm font-bold">WhatsApp</label>
                                <input
                                    id="contato-telefone"
                                    type="tel"
                                    data-mascara="telefone"
                                    placeholder="(00) 00000-0000"
                                    class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none"
                                >
                            </div>

                            <div>
                                <label for="contato-servico" class="mb-1 block text-sm font-bold">Área de interesse</label>
                                <input
                                    id="contato-servico"
                                    type="text"
                                    value="{{ $produto->titulo }}"
                                    class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none"
                                >
                            </div>

                            <div>
                                <label for="contato-mensagem" class="mb-1 block text-sm font-bold">Mensagem inicial (opcional)</label>
                                <textarea
                                    id="contato-mensagem"
                                    rows="3"
                                    placeholder="Descreva brevemente sua solicitação (opcional)"
                                    class="w-full resize-y rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none"
                                ></textarea>
                            </div>

                            <button
                                type="button"
                                onclick="alert('Este é um site demonstrativo, criado para apresentar as funcionalidades da plataforma. O envio de solicitações foi desativado intencionalmente nesta demonstração.')"
                                class="w-full rounded-xl px-6 py-4 text-sm font-extrabold text-white"
                                style="background-color: {{ $corSecundaria }}"
                            >
                                Solicitar atendimento via WhatsApp →
                            </button>

                            <p class="text-center text-xs leading-relaxed text-slate-500">
                                Esta é uma demonstração do formulário de atendimento.
                                Nenhuma informação será enviada.
                            </p>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer id="contato" class="py-12 text-slate-900" style="background-color: {{ $corPrincipal }}">
        <div class="mx-auto max-w-7xl px-5 text-center lg:px-8">
            <h2 class="text-2xl font-black">{{ $nomeSite }}</h2>

            <p class="mx-auto mt-4 max-w-2xl text-sm leading-7">
                {{ $cliente->descricao_site ?: 'Conheça nossos serviços e entre em contato para agendar seu atendimento.' }}
            </p>

            <div class="mt-6 flex flex-wrap justify-center gap-5 text-sm">
                @if($whatsapp)
                    <a href="https://wa.me/{{ $whatsapp }}" target="_blank" rel="noopener noreferrer" class="font-semibold hover:underline">
                        WhatsApp
                    </a>
                @endif

                @if(!empty($cliente->email_comercial))
                    <a href="mailto:{{ $cliente->email_comercial }}" class="font-semibold hover:underline">
                        E-mail
                    </a>
                @endif

                @if($enderecoCompleto)
                    <a
                        href="https://www.google.com/maps/search/?api=1&query={{ urlencode($enderecoCompleto) }}"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="font-semibold hover:underline"
                    >
                        Onde estamos
                    </a>
                @endif
            </div>

            <div class="mt-10 border-t border-slate-900/20 pt-6 text-xs">
                © {{ date('Y') }} {{ $nomeSite }}. Todos os direitos reservados.
                Site desenvolvido com <strong>BarberSite</strong>.
            </div>
        </div>
    </footer>
</div>

</body>
</html>