<!DOCTYPE html>
<html lang="pt-BR" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $cliente->titulo_site ?: $cliente->nome }}</title>
    <meta name="description" content="{{ $cliente->descricao_site ?? '' }}">

    @if(!empty($cliente->favicon))
        <link rel="icon" href="{{ asset(ltrim($cliente->favicon, '/')) }}">
    @endif

    @if(!empty($cliente->imagem_compartilhamento))
        <meta property="og:image" content="{{ asset(ltrim($cliente->imagem_compartilhamento, '/')) }}">
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

    $bannersSite = collect($banners ?? []);
    $galeriaSite = collect($galeria ?? []);
    $produtosSite = collect($produtos ?? []);

    $removidos = array_filter(
        array_map('intval', explode(',', $cliente->produtos_removidos ?? ''))
    );

    $produtosSite = $produtosSite->reject(
        fn ($produto) => in_array((int) $produto->id, $removidos, true)
    );

    $enderecoCompleto = implode(', ', array_filter([
        $cliente->endereco ?? '',
        $cliente->numero ?? '',
        $cliente->bairro ?? '',
        $cliente->cidade ?? '',
        $cliente->uf ?? '',
    ]));

    $whatsapp = preg_replace('/\D/', '', $cliente->whatsapp ?? '');
@endphp

<style>
    .icone-rodape {
        background-color: {{ $corSecundaria }};
        -webkit-mask-image: var(--icone);
        mask-image: var(--icone);
        -webkit-mask-repeat: no-repeat;
        mask-repeat: no-repeat;
        -webkit-mask-position: center;
        mask-position: center;
        -webkit-mask-size: contain;
        mask-size: contain;
    }
</style>

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
                <a
                    v-for="item in links"
                    :key="item.nome"
                    :href="item.url"
                    class="rounded-lg px-2 py-2 text-sm font-semibold text-gray-700 transition hover:bg-gray-50 hover:text-gray-950 lg:px-0"
                    @click="menuAberto = false"
                    v-text="item.nome"
                ></a>

                <a
                    href="/#cotacao"
                    class="mt-2 rounded-lg px-5 py-3 text-center text-sm font-bold text-white transition hover:opacity-90 lg:mt-0"
                    :style="{ backgroundColor: corPrincipal }"
                    @click="menuAberto = false"
                >
                    Solicitar atendimento
                </a>
            </nav>
        </div>
    </header>

    <main>
        <!-- BANNERS -->
        <section id="inicio" class="relative overflow-hidden bg-gray-950 text-white" data-carrossel-banners>
            @forelse($bannersSite as $banner)
                @php
                    $imagemBanner = $banner->tipo === 'boas_vindas'
                        ? ($cliente->banner_institucional_imagem ?? '')
                        : (!empty($banner->imagem_capa)
                            ? 'img/produtos/' . basename($banner->imagem_capa)
                            : '');

                    $usarCores = (int) ($banner->usar_cores_site ?? 0) === 1;
                    $coresBanner = $usarCores
                        ? [$corPrincipal, $corSecundaria, $corSecundaria]
                        : [
                            $banner->cor_1 ?: $corPrincipal,
                            $banner->cor_2 ?: $corSecundaria,
                            $banner->cor_3 ?: $corSecundaria,
                        ];

                    $coresBanner = array_map(
                        fn ($cor) => preg_match('/^#[0-9a-fA-F]{6}$/', $cor ?? '')
                            ? $cor : $corSecundaria,
                        $coresBanner
                    );
                @endphp

                <div
                    data-banner-slide
                    aria-hidden="{{ $loop->first ? 'false' : 'true' }}"
                    class="relative isolate min-h-105 overflow-hidden {{ $loop->first ? '' : 'hidden' }}"
                    style="background:linear-gradient(115deg, {{ implode(', ', $coresBanner) }})"
                >
                    <div class="mx-auto grid min-h-105 max-w-7xl items-center gap-8 px-5 py-14 lg:grid-cols-2 lg:px-8">
                        <div class="relative z-10 text-center lg:text-left">
                            @if(!empty($banner->texto_pequeno))
                                <p class="mb-4 text-sm font-bold uppercase tracking-[0.2em] text-white/75">
                                    {{ $banner->texto_pequeno }}
                                </p>
                            @endif

                            <h1 class="mx-auto max-w-2xl text-4xl font-black leading-tight tracking-tight md:text-5xl lg:mx-0">
                                {{ $banner->titulo ?: ($cliente->titulo_site ?: $nomeSite) }}
                            </h1>

                            @if(!empty($banner->descricao))
                                <p class="mx-auto mt-5 max-w-xl text-lg leading-relaxed text-white/85 lg:mx-0">
                                    {{ $banner->descricao }}
                                </p>
                            @endif

                            <a
                                href="{{ $banner->tipo === 'boas_vindas' ? '#cotacao' : '#produtos' }}"
                                class="mt-8 inline-flex rounded-xl bg-white px-7 py-3.5 font-bold text-gray-900 shadow-lg transition hover:bg-gray-100"
                            >
                                {{ $banner->texto_botao ?: 'Conheça nossos serviços' }}
                            </a>
                        </div>

                        @if($imagemBanner)
                            <div class="relative flex items-center justify-center">
                                <div class="w-full max-w-xl rounded-3xl border border-white/30 bg-white/10 p-3 shadow-2xl ring-1 ring-white/10 backdrop-blur-md sm:p-4">
                                    <div class="overflow-hidden rounded-2xl bg-white/10">
                                        <img
                                            src="{{ asset(ltrim($imagemBanner, '/')) }}"
                                            alt="{{ $banner->titulo ?: $nomeSite }}"
                                            class="max-h-100 w-full rounded-2xl object-contain"
                                            loading="{{ $loop->first ? 'eager' : 'lazy' }}"
                                        >
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div
                    class="px-5 py-24 text-center"
                    style="background:linear-gradient(115deg, {{ $corPrincipal }}, {{ $corSecundaria }})"
                >
                    <p class="mb-4 font-semibold uppercase tracking-widest text-white/70">
                        Seja bem-vindo
                    </p>
                    <h1 class="mx-auto max-w-3xl text-4xl font-black md:text-5xl">
                        {{ $cliente->titulo_site ?: $nomeSite }}
                    </h1>
                    <p class="mx-auto mt-6 max-w-2xl text-lg text-white/85">
                        {{ $cliente->descricao_site }}
                    </p>
                    <a
                        href="#produtos"
                        class="mt-8 inline-flex rounded-xl bg-white px-7 py-3 font-bold text-gray-900"
                    >
                        Conheça nossos serviços
                    </a>
                </div>
            @endforelse

            @if($bannersSite->count() > 1)
                <button
                    type="button"
                    data-banner-anterior
                    aria-label="Banner anterior"
                    class="absolute left-3 top-1/2 z-20 flex h-11 w-11 -translate-y-1/2 items-center justify-center rounded-full border border-white/40 bg-black/40 text-white shadow-lg transition hover:bg-black/70 sm:left-6"
                >
                    <svg class="block h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="m15 18-6-6 6-6"></path>
                    </svg>
                </button>

                <button
                    type="button"
                    data-banner-proximo
                    aria-label="Próximo banner"
                    class="absolute right-3 top-1/2 z-20 flex h-11 w-11 -translate-y-1/2 items-center justify-center rounded-full border border-white/40 bg-black/40 text-white shadow-lg transition hover:bg-black/70 sm:right-6"
                >
                    <svg class="block h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="m9 18 6-6-6-6"></path>
                    </svg>
                </button>

                <div
                    class="absolute bottom-4 left-1/2 z-20 flex -translate-x-1/2 items-center gap-2 rounded-full bg-black/40 px-4 py-2"
                    aria-label="Selecionar banner"
                >
                    @foreach($bannersSite as $indice => $banner)
                        <button
                            type="button"
                            data-banner-indicador="{{ $indice }}"
                            aria-label="Mostrar banner {{ $indice + 1 }}"
                            aria-current="{{ $indice === 0 ? 'true' : 'false' }}"
                            class="h-2.5 w-2.5 rounded-full transition-all {{ $indice === 0 ? 'w-7 bg-white' : 'bg-white/50' }}"
                        ></button>
                    @endforeach
                </div>
            @endif
        </section>

        <!-- SERVIÇOS -->
        <section id="produtos" class="scroll-mt-24 bg-gray-50 py-20" data-catalogo>
            <div class="mx-auto max-w-7xl px-5 lg:px-8">
                <div class="mb-10 text-center">
                    <p class="text-sm font-bold uppercase tracking-widest" :style="{ color: corPrincipal }">
                        O que oferecemos
                    </p>
                    <h2 class="mt-3 text-3xl font-black tracking-tight md:text-4xl">
                        Nossos serviços
                    </h2>
                    <p class="mx-auto mt-4 max-w-2xl text-gray-600">
                        Conheça nossos serviços e encontre a opção ideal para você.
                    </p>
                </div>

                <div class="mb-8 rounded-3xl border border-gray-200 bg-gray-100 p-5 shadow-sm sm:p-8">

                    <div class="mx-auto max-w-2xl">
                        <label for="pesquisa-servicos" class="mb-4 block text-center text-sm font-extrabold text-gray-800">
                            Encontre um serviço
                        </label>

                        <div class="relative">
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                width="22"
                                height="22"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="{{ $corPrincipal }}"
                                stroke-width="1.8"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                class="pointer-events-none absolute left-5 top-1/2 -translate-y-1/2"
                                aria-hidden="true"
                            >
                                <circle cx="11" cy="11" r="8"></circle>
                                <path d="m21 21-4.35-4.35"></path>
                            </svg>

                            <input
                                id="pesquisa-servicos"
                                type="search"
                                placeholder="Pesquise por serviço..."
                                autocomplete="off"
                                class="w-full rounded-2xl border border-gray-200 bg-white py-4 pl-14 pr-5 text-sm shadow-sm outline-none focus:border-gray-400"
                                data-pesquisa-servicos
                            >
                        </div>
                    </div>

                    <div class="my-6 border-t border-gray-200"></div>

                    <div class="flex flex-wrap justify-center gap-2" data-filtros-servicos>
                        <button
                            type="button"
                            data-categoria=""
                            aria-pressed="true"
                            class="inline-flex items-center gap-2 rounded-full border px-5 py-3 text-sm font-bold"
                        >
                            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <rect x="3" y="3" width="7" height="7" rx="1"></rect>
                                <rect x="14" y="3" width="7" height="7" rx="1"></rect>
                                <rect x="3" y="14" width="7" height="7" rx="1"></rect>
                                <rect x="14" y="14" width="7" height="7" rx="1"></rect>
                            </svg>
                            Todos
                        </button>

                        @foreach($produtosSite->pluck('categoria')->filter()->unique()->sort()->values() as $categoria)
                            <button
                                type="button"
                                data-categoria="{{ $categoria }}"
                                aria-pressed="false"
                                class="inline-flex items-center gap-2 rounded-full border px-4 py-3 text-sm font-semibold"
                            >
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="{{ $corPrincipal }}" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <rect x="3" y="7" width="18" height="14" rx="2"></rect>
                                    <path d="M9 7V5a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v2"></path>
                                    <path d="M3 12a20 20 0 0 0 18 0"></path>
                                    <path d="M12 11v3"></path>
                                </svg>
                                {{ $categoria }}
                            </button>
                        @endforeach
                    </div>
                </div>

                <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3" data-lista-servicos>
                    @forelse($produtosSite as $produto)
                                                <article
                            data-servico
                            data-categoria="{{ $produto->categoria ?? '' }}"
                            data-pesquisa="{{ mb_strtolower($produto->titulo . ' ' . ($produto->categoria ?? '') . ' ' . strip_tags($produto->descricao ?? ''), 'UTF-8') }}"
                            class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-xl"
                        >
                            <a href="{{ route('cliente.produto', ['slug' => $produto->seo_slug]) }}" class="block">
                                @if(!empty($produto->imagem_capa))
                                    <img
                                        src="{{ asset('img/produtos/' . basename($produto->imagem_capa)) }}"
                                        alt="{{ $produto->titulo }}"
                                        class="aspect-[4/3] w-full object-contain"
                                        loading="lazy"
                                    >
                                @else
                                    <div class="flex h-56 items-center justify-center bg-gray-100 text-gray-400">
                                        Imagem do serviço
                                    </div>
                                @endif
                            </a>

                            <div class="p-6">
                                <h3 class="text-xl font-bold">
                                    <a href="{{ route('cliente.produto', ['slug' => $produto->seo_slug]) }}" class="hover:underline">
                                        {{ $produto->titulo }}
                                    </a>
                                </h3>
                                <p class="mt-3 line-clamp-3 text-sm leading-relaxed text-gray-600">
                                    {{ strip_tags($produto->descricao ?? '') }}
                                </p>

                                <a
                                    href="#cotacao"
                                    class="mt-6 inline-flex rounded-lg px-5 py-3 text-sm font-bold text-white transition hover:opacity-90"
                                    style="background-color: {{ $corPrincipal }}"
                                >
                                    Solicitar atendimento
                                </a>
                            </div>
                        </article>
                    @empty
                        <p class="col-span-full rounded-2xl bg-white p-10 text-center text-gray-500">
                            Os serviços aparecerão aqui quando estiverem cadastrados.
                        </p>
                    @endforelse
                </div>

                <p
                    class="mt-8 hidden rounded-xl bg-white p-6 text-center text-gray-500"
                    data-servicos-vazio
                >
                    Nenhum serviço encontrado.
                </p>

                <nav
                    class="mt-10 flex flex-wrap items-center justify-center gap-2"
                    aria-label="Paginação dos serviços"
                    data-paginacao-servicos
                ></nav>

                <p
                    class="mt-4 text-center text-sm text-gray-500"
                    aria-live="polite"
                    data-status-servicos
                ></p>
            </div>
        </section>

        <!-- SOBRE -->
        @php
            $textoSobre = trim($cliente->sobre_nos ?: ($cliente->descricao_site ?: 'Conheça mais sobre nossa empresa e nossos serviços.'));
            $resumoSobre = \Illuminate\Support\Str::limit($textoSobre, 310);
        @endphp

        <section id="sobre" class="scroll-mt-24 bg-gray-100 py-20 lg:py-24">
            <div class="mx-auto grid max-w-7xl items-center gap-12 px-5 lg:grid-cols-2 lg:gap-20 lg:px-8">

                <div>
                    <p
                        class="text-sm font-extrabold uppercase tracking-[0.15em]"
                        style="color: {{ $corPrincipal }}"
                    >
                        Sobre nossa empresa
                    </p>

                    <h2 class="mt-6 max-w-xl text-4xl font-black leading-tight tracking-tight text-slate-800 md:text-5xl">
                        Atendimento próximo.<br>
                        Experiências melhores.
                    </h2>

                    <div class="mt-7 whitespace-pre-line text-base leading-relaxed text-slate-600">
                        {{ $resumoSobre }}
                    </div>

                    <div class="mt-7 flex flex-wrap items-center gap-4">

                        <button
                            type="button"
                            onclick="document.getElementById('modal-historia-cliente').showModal()"
                            class="inline-flex items-center gap-3 border-b border-slate-800 pb-2 text-sm font-extrabold text-slate-800 transition hover:opacity-70"
                        >
                            Conheça nossa história
                            <span aria-hidden="true">→</span>
                        </button>

                        <a
                            href="#contato"
                            class="inline-flex items-center justify-center rounded-xl border border-slate-700 px-6 py-3.5 text-sm font-medium text-slate-800 transition hover:bg-white"
                        >
                            Fale com nossa equipe
                        </a>

                    </div>
                </div>

                <div class="grid gap-4">

                    <article class="rounded-2xl bg-white p-6 shadow-sm">
                        <span
                            class="text-sm font-extrabold"
                            style="color: {{ $corPrincipal }}"
                        >
                            01
                        </span>

                        <h3 class="mt-2 text-lg font-extrabold text-slate-800">
                            Entendemos sua necessidade
                        </h3>

                        <p class="mt-2 text-sm leading-relaxed text-slate-600">
                            Cada cliente tem prioridades diferentes. Começamos ouvindo você.
                        </p>
                    </article>

                    <article class="rounded-2xl bg-white p-6 shadow-sm">
                        <span
                            class="text-sm font-extrabold"
                            style="color: {{ $corPrincipal }}"
                        >
                            02
                        </span>

                        <h3 class="mt-2 text-lg font-extrabold text-slate-800">
                            Orientação na escolha
                        </h3>

                        <p class="mt-2 text-sm leading-relaxed text-slate-600">
                            Apresentamos nossos serviços para ajudar você a encontrar a opção ideal.
                        </p>
                    </article>

                    <article class="rounded-2xl bg-white p-6 shadow-sm">
                        <span
                            class="text-sm font-extrabold"
                            style="color: {{ $corPrincipal }}"
                        >
                            03
                        </span>

                        <h3 class="mt-2 text-lg font-extrabold text-slate-800">
                            Atendimento humanizado
                        </h3>

                        <p class="mt-2 text-sm leading-relaxed text-slate-600">
                            Você conta com um canal direto para conversar com nossa equipe.
                        </p>
                    </article>

                </div>
            </div>

            <!-- MODAL: HISTÓRIA COMPLETA -->
            <dialog
                id="modal-historia-cliente"
                onclick="if (event.target === this) this.close()"
                class="m-auto w-[calc(100%-2rem)] max-w-2xl rounded-2xl border-0 bg-white p-0 text-slate-800 shadow-2xl backdrop:bg-slate-950/70"
            >
                <div class="max-h-[85vh] overflow-y-auto p-6 sm:p-9">

                    <div class="flex items-start justify-between gap-5">
                        <div>
                            <p
                                class="text-xs font-bold uppercase tracking-widest"
                                style="color: {{ $corPrincipal }}"
                            >
                                Nossa história
                            </p>

                            <h2 class="mt-2 text-2xl font-black sm:text-3xl">
                                {{ $nomeSite }}
                            </h2>
                        </div>

                        <button
                            type="button"
                            onclick="document.getElementById('modal-historia-cliente').close()"
                            aria-label="Fechar"
                            class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-gray-100 text-xl font-bold text-gray-700 transition hover:bg-gray-200"
                        >
                            ×
                        </button>
                    </div>

                    <div class="mt-7 whitespace-pre-line text-base leading-8 text-slate-600">
                        {{ $textoSobre }}
                    </div>

                    <div class="mt-8 border-t border-gray-200 pt-6 text-right">
                        <button
                            type="button"
                            onclick="document.getElementById('modal-historia-cliente').close()"
                            class="rounded-xl px-6 py-3 text-sm font-bold text-white"
                            style="background-color: {{ $corPrincipal }}"
                        >
                            Fechar
                        </button>
                    </div>

                </div>
            </dialog>
        </section>

        <!-- GALERIA -->
        <section id="trabalhos" class="scroll-mt-24 bg-gray-50 py-20">
            <div class="mx-auto max-w-7xl px-5 lg:px-8">

                <div class="mb-9">
                    <p class="text-sm font-extrabold uppercase tracking-widest"
                       style="color: {{ $corPrincipal }}">
                        Galeria
                    </p>
                    <h2 class="mt-3 text-3xl font-black text-slate-800 md:text-4xl">
                        Nossos Trabalhos
                    </h2>
                    <p class="mt-3 text-slate-600">
                        Um pouco do cuidado e da dedicação em cada atendimento.
                    </p>
                </div>

                @if($galeriaSite->isNotEmpty())

                    <div
                        id="carrossel-trabalhos"
                        class="flex snap-x snap-mandatory gap-5 overflow-x-auto scroll-smooth pb-3"
                        style="scrollbar-width: none;"
                    >
                        @foreach($galeriaSite as $indice => $foto)
                            <button
                                type="button"
                                data-abrir-foto="{{ $indice }}"
                                class="group relative aspect-[4/3] w-[85%] shrink-0 snap-start overflow-hidden rounded-2xl bg-slate-200 text-left shadow-sm sm:w-[calc((100%-1.25rem)/2)] lg:w-[calc((100%-2.5rem)/3)]"
                                aria-label="Ampliar fotografia {{ $indice + 1 }}"
                            >
                                <div
                                    class="absolute inset-0 scale-110 bg-cover bg-center blur-xl"
                                    style="background-image: url('{{ asset(ltrim($foto->imagem, '/')) }}')"
                                ></div>

                                <img
                                    src="{{ asset(ltrim($foto->imagem, '/')) }}"
                                    alt="{{ $foto->descricao ?: 'Trabalho realizado' }}"
                                    class="relative h-full w-full object-contain transition duration-300 group-hover:scale-105"
                                    loading="lazy"
                                >

                                @if(!empty($foto->descricao))
                                    <span class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-black/85 to-transparent px-5 pb-4 pt-10 text-sm font-semibold text-white">
                                        {{ $foto->descricao }}
                                    </span>
                                @endif
                            </button>
                        @endforeach
                    </div>

                    <div class="mt-7 flex flex-wrap items-center gap-3">
                        <button
                            type="button"
                            data-trabalhos-anterior
                            aria-label="Fotografias anteriores"
                            class="flex h-11 w-11 items-center justify-center rounded-full border border-gray-300 bg-white text-slate-800 shadow-sm transition hover:border-gray-400 hover:bg-gray-100"
                        >
                            <svg class="block h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="m15 18-6-6 6-6"></path>
                            </svg>
                        </button>

                        <button
                            type="button"
                            data-trabalhos-proximo
                            aria-label="Próximas fotografias"
                            class="flex h-11 w-11 items-center justify-center rounded-full border border-gray-300 bg-white text-slate-800 shadow-sm transition hover:border-gray-400 hover:bg-gray-100"
                        >
                            <svg class="block h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="m9 18 6-6-6-6"></path>
                            </svg>
                        </button>

                        <button
                            type="button"
                            onclick="alert('Este é um site demonstrativo, criado para apresentar as funcionalidades da plataforma. Algumas funções interativas foram desativadas intencionalmente nesta demonstração.')"
                            class="ml-auto rounded-xl px-6 py-3 text-sm font-bold text-white transition hover:opacity-90"
                            style="background-color: {{ $corPrincipal }}"
                        >
                            Ver todas as fotos →
                        </button>
                    </div>

                    <!-- ÁLBUM COMPLETO -->
                    <dialog
                        id="album-trabalhos"
                        class="m-auto w-[calc(100%-2rem)] max-w-6xl rounded-2xl border-0 bg-white p-0 text-slate-800 shadow-2xl backdrop:bg-black/80"
                    >
                        <div class="max-h-[90vh] overflow-y-auto p-5 sm:p-8">

                            <div class="mb-6 flex items-center justify-between gap-4">
                                <div>
                                    <p class="text-xs font-bold uppercase tracking-widest"
                                       style="color: {{ $corPrincipal }}">
                                        Galeria
                                    </p>
                                    <h2 class="mt-2 text-2xl font-black">
                                        Nossos Trabalhos
                                    </h2>
                                </div>

                                <button
                                    type="button"
                                    data-fechar-album
                                    aria-label="Fechar álbum"
                                    class="flex h-11 w-11 items-center justify-center rounded-full bg-gray-100 text-2xl hover:bg-gray-200"
                                >
                                    ×
                                </button>
                            </div>

                            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                                @foreach($galeriaSite as $indice => $foto)
                                    <button
                                        type="button"
                                        data-abrir-foto="{{ $indice }}"
                                        class="group overflow-hidden rounded-xl bg-gray-100 text-left"
                                        aria-label="Ampliar fotografia {{ $indice + 1 }}"
                                    >
                                        <div class="aspect-[4/3]">
                                            <img
                                                src="{{ asset(ltrim($foto->imagem, '/')) }}"
                                                alt="{{ $foto->descricao ?: 'Trabalho realizado' }}"
                                                class="h-full w-full object-contain transition group-hover:scale-105"
                                                loading="lazy"
                                            >
                                        </div>

                                        @if(!empty($foto->descricao))
                                            <p class="p-3 text-sm text-slate-600">
                                                {{ $foto->descricao }}
                                            </p>
                                        @endif
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    </dialog>

                    <!-- FOTOGRAFIA AMPLIADA -->
                    <dialog
                        id="foto-trabalhos-ampliada"
                        class="m-auto w-[calc(100%-2rem)] max-w-5xl rounded-2xl border-0 bg-slate-950 p-4 text-white shadow-2xl backdrop:bg-black/90 sm:p-6"
                        aria-label="Fotografia ampliada"
                    >
                        <div class="mb-4 flex justify-end">
                            <button
                                type="button"
                                data-fechar-foto
                                aria-label="Fechar fotografia"
                                class="rounded-lg bg-white/15 px-4 py-2 font-bold hover:bg-white/25"
                            >
                                Fechar ×
                            </button>
                        </div>

                        <img
                            data-foto-ampliada
                            alt="Fotografia ampliada"
                            class="mx-auto max-h-[65vh] w-full object-contain"
                        >

                        <p data-legenda-ampliada class="mt-4 text-center text-sm text-white/80"></p>

                        <nav class="mt-5 flex justify-center gap-4" aria-label="Navegação entre fotografias">
                            <button type="button" data-foto-anterior class="rounded-lg bg-white/15 px-5 py-3 font-bold hover:bg-white/25">
                                ← Anterior
                            </button>
                            <button type="button" data-foto-proxima class="rounded-lg bg-white/15 px-5 py-3 font-bold hover:bg-white/25">
                                Próxima →
                            </button>
                        </nav>
                    </dialog>

                    @php
                        $dadosFotosTrabalhos = $galeriaSite->map(function ($foto) {
                            return [
                                'url' => asset(ltrim($foto->imagem, '/')),
                                'descricao' => $foto->descricao ?? '',
                            ];
                        })->values()->all();
                    @endphp

                    <script type="application/json" id="dados-fotos-trabalhos">
                        @json($dadosFotosTrabalhos)
                    </script>

                @else
                    <p class="rounded-2xl bg-white p-10 text-center text-slate-500">
                        Em breve, novos trabalhos por aqui.
                    </p>
                @endif

            </div>
        </section>

        <!-- FORMULÁRIO DEMONSTRATIVO -->
        <section id="cotacao" class="scroll-mt-24 bg-white py-16 lg:py-20">
            <div class="mx-auto max-w-6xl px-5 lg:px-8">
                <div
                    class="grid items-center gap-8 rounded-3xl p-6 shadow-xl md:p-8 lg:grid-cols-2"
                    style="background-color: {{ $corPrincipal }}"
                >
                    <!-- APRESENTAÇÃO -->
                    <div class="min-w-0 text-slate-900">
                        <p class="text-xs font-extrabold uppercase tracking-widest">
                            ◉ Atendimento personalizado
                        </p>

                        <h2 class="mt-5 text-3xl font-black leading-tight md:text-4xl">
                            O primeiro contato começa com uma conversa.
                        </h2>

                        <p class="mt-4 text-sm leading-relaxed">
                            Apresente sua dúvida inicial para entrar em contato com o salão.
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

                        <p class="mt-5 flex items-center gap-3 text-sm">
                            <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full border border-slate-900">
                                ✓
                            </span>
                            Atendimento profissional e individualizado
                        </p>
                    </div>

                    <!-- FORMULÁRIO -->
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
                                <label for="contato-nome" class="mb-1 block text-sm font-bold">
                                    Seu nome
                                </label>
                                <input
                                    id="contato-nome"
                                    type="text"
                                    placeholder="Como podemos chamar você?"
                                    class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none focus:border-slate-500"
                                >
                            </div>

                            <div>
                                <label for="contato-telefone" class="mb-1 block text-sm font-bold">
                                    WhatsApp
                                </label>
                                <input
                                    id="contato-telefone"
                                    type="tel"
                                    data-mascara="telefone"
                                    placeholder="(00) 00000-0000"
                                    class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none focus:border-slate-500"
                                >
                            </div>

                            <div>
                                <label for="contato-servico" class="mb-1 block text-sm font-bold">
                                    Área de interesse
                                </label>
                                <input
                                    id="contato-servico"
                                    type="text"
                                    placeholder="Ex.: Corte de cabelo, barba..."
                                    class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none focus:border-slate-500"
                                >
                            </div>

                            <div>
                                <label for="contato-mensagem" class="mb-1 block text-sm font-bold">
                                    Mensagem inicial (opcional)
                                </label>
                                <textarea
                                    id="contato-mensagem"
                                    rows="3"
                                    placeholder="Descreva brevemente sua solicitação (opcional)"
                                    class="w-full resize-y rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none focus:border-slate-500"
                                ></textarea>
                            </div>

                            <button
                                type="button"
                                onclick="alert('Este é um site demonstrativo, criado para apresentar as funcionalidades da plataforma. O envio de solicitações foi desativado intencionalmente nesta demonstração.')"
                                class="w-full rounded-xl px-6 py-4 text-sm font-extrabold text-white transition hover:opacity-90"
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

    <footer id="contato" class="scroll-mt-24 py-14 text-slate-900" style="background-color: {{ $corPrincipal }}">
        <div class="mx-auto max-w-7xl px-5 lg:px-8">

            <div class="grid gap-10 text-center md:grid-cols-3 md:gap-8 md:text-left">

                <!-- EMPRESA -->
                <div>
                    <p class="text-xs font-extrabold uppercase tracking-widest">
                        Beleza, cuidado e atendimento
                    </p>

                    <h2 class="mt-5 text-2xl font-black leading-tight">
                        {{ $nomeSite }}
                    </h2>

                    <p class="mt-5 text-sm leading-7">
                        {{ $cliente->descricao_site ?: 'Conheça nossos serviços e entre em contato para agendar seu atendimento.' }}
                    </p>

                    <a
                        href="#cotacao"
                        class="mt-6 inline-flex items-center gap-6 rounded-xl px-5 py-3.5 text-sm font-bold text-white transition hover:opacity-90"
                        style="background-color: {{ $corSecundaria }}"
                    >
                        Solicite atendimento
                        <span aria-hidden="true">→</span>
                    </a>
                </div>

                <!-- CONTATOS -->
                <div>
                    <h3 class="text-base font-extrabold">
                        Fale com a gente
                    </h3>

                    <div class="mt-6 space-y-4 text-sm [&_a]:justify-center md:[&_a]:justify-start">
                        @if(!empty($cliente->telefone))
                            <a href="tel:{{ preg_replace('/\D/', '', $cliente->telefone) }}" class="flex items-center gap-3 hover:underline">
                                <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl border border-slate-900/60">
    <span class="icone-rodape block h-6 w-6" style="--icone: url('{{ asset('img/telefone.png') }}')"></span>
</span>
                                <span>Telefone: {{ $cliente->telefone }}</span>
                            </a>
                        @endif

                        @if($whatsapp)
                            <a href="https://wa.me/{{ $whatsapp }}" target="_blank" rel="noopener noreferrer" class="flex items-center gap-3 hover:underline">
                                <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl border border-slate-900/60">
    <span class="icone-rodape block h-6 w-6" style="--icone: url('{{ asset('img/whatsapp.png') }}')"></span>
</span>
                                <span>WhatsApp: {{ $cliente->whatsapp }}</span>
                            </a>
                        @endif

                        @if(!empty($cliente->email_comercial))
                            <a href="mailto:{{ $cliente->email_comercial }}" class="flex items-center gap-3 break-all hover:underline">
                                <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl border border-slate-900/60">
    <span class="icone-rodape block h-6 w-6" style="--icone: url('{{ asset('img/email.png') }}')"></span>
</span>
                                <span>{{ $cliente->email_comercial }}</span>
                            </a>
                        @endif
                    </div>
                </div>

                <!-- REDES SOCIAIS -->
                <div>
                    <h3 class="text-base font-extrabold">
                        Conecte-se conosco
                    </h3>

                    <p class="mt-6 text-sm">
                        Acompanhe nossos trabalhos e novidades.
                    </p>

                    <div class="mt-5 grid grid-cols-2 gap-2 [&_a]:justify-center md:[&_a]:justify-start">
                        @foreach([
                            ['nome' => 'Instagram', 'url' => $cliente->instagram ?? '', 'icone' => 'instagram.png'],
                            ['nome' => 'Facebook', 'url' => $cliente->facebook ?? '', 'icone' => 'facebook.png'],
                            ['nome' => 'LinkedIn', 'url' => $cliente->linkedin ?? '', 'icone' => 'linkedin.png'],
                            ['nome' => 'YouTube', 'url' => $cliente->youtube ?? '', 'icone' => 'youtube.png'],
                            ['nome' => 'TikTok', 'url' => $cliente->tiktok ?? '', 'icone' => 'tiktok.png'],
                        ] as $rede)
                            @if(!empty($rede['url']))
                                <a
                                    href="{{ $rede['url'] }}"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="flex min-h-12 items-center gap-3 rounded-xl border border-slate-900/70 px-3 py-2 text-sm font-bold transition hover:bg-white/20"
                                >
                                    <span class="flex h-7 w-7 shrink-0 items-center justify-center">
    <span
        class="icone-rodape block h-6 w-6"
        style="--icone: url('{{ asset('img/' . $rede['icone']) }}')"
        aria-hidden="true"
    ></span>
</span>
                                    <span>{{ $rede['nome'] }}</span>
                                </a>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- LOCALIZAÇÃO NO RODAPÉ -->
            @if($enderecoCompleto)
                <div class="mt-10 flex flex-col items-center gap-5 border-t border-slate-900/20 pt-6 text-center md:flex-row md:justify-between md:text-left">
                    <div class="flex flex-col items-center gap-4 sm:flex-row">
                        <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl text-2xl text-white"
                              style="background-color: {{ $corSecundaria }}">
                            ⌖
                        </span>

                        <div>
                            <p class="text-xs font-extrabold uppercase tracking-widest">
                                Onde estamos
                            </p>
                            <p class="mt-1 text-sm">
                                {{ $enderecoCompleto }}
                            </p>
                        </div>
                    </div>

                    <a
                        href="https://www.google.com/maps/search/?api=1&query={{ urlencode($enderecoCompleto) }}"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="inline-flex items-center justify-center gap-3 rounded-xl border border-slate-900/60 px-5 py-3 text-sm font-bold transition hover:bg-white/20"
                    >
                        Abrir no Google Maps
                        <span aria-hidden="true">↗</span>
                    </a>
                </div>
            @endif

            <!-- DIREITOS AUTORAIS -->
            <div class="mt-10 flex flex-col items-center gap-3 border-t border-slate-900/20 pt-6 text-center text-xs sm:flex-row sm:justify-between sm:text-left">
                <p>
                    © {{ date('Y') }} {{ $nomeSite }}. Todos os direitos reservados.
                </p>

                <p>
                    Site desenvolvido com <strong><underline>BarberSite</underline></strong>.
                </p>
            </div>

        </div>
    </footer>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const carrossel = document.getElementById('carrossel-trabalhos');
    const dados = document.getElementById('dados-fotos-trabalhos');

    if (!carrossel || !dados) return;

    const fotos = JSON.parse(dados.textContent);
    if (!fotos.length) return;

    const album = document.getElementById('album-trabalhos');
    const ampliacao = document.getElementById('foto-trabalhos-ampliada');
    const imagem = document.querySelector('[data-foto-ampliada]');
    const legenda = document.querySelector('[data-legenda-ampliada]');

    let fotoAtual = 0;

    function deslocarCarrossel(direcao) {
        const primeiroItem = carrossel.querySelector('[data-abrir-foto]');
        if (!primeiroItem) return;

        const largura = primeiroItem.getBoundingClientRect().width + 20;

        carrossel.scrollBy({
            left: largura * direcao,
            behavior: 'smooth'
        });
    }

    function mostrarFoto(indice) {
        fotoAtual = (indice + fotos.length) % fotos.length;

        imagem.src = fotos[fotoAtual].url;
        imagem.alt = fotos[fotoAtual].descricao || 'Trabalho realizado';
        legenda.textContent = fotos[fotoAtual].descricao || '';

        if (!ampliacao.open) {
            ampliacao.showModal();
        }
    }

    document.querySelector('[data-trabalhos-anterior]')
        ?.addEventListener('click', () => deslocarCarrossel(-1));

    document.querySelector('[data-trabalhos-proximo]')
        ?.addEventListener('click', () => deslocarCarrossel(1));

    document.querySelector('[data-ver-album]')
        ?.addEventListener('click', () => album.showModal());

    document.querySelector('[data-fechar-album]')
        ?.addEventListener('click', () => album.close());

    document.querySelector('[data-fechar-foto]')
        ?.addEventListener('click', () => ampliacao.close());

    document.querySelector('[data-foto-anterior]')
        ?.addEventListener('click', () => mostrarFoto(fotoAtual - 1));

    document.querySelector('[data-foto-proxima]')
        ?.addEventListener('click', () => mostrarFoto(fotoAtual + 1));

    document.querySelectorAll('[data-abrir-foto]').forEach(botao => {
        botao.addEventListener('click', () => {
            mostrarFoto(Number(botao.dataset.abrirFoto));
        });
    });

    album.addEventListener('click', event => {
        if (event.target === album) album.close();
    });

    ampliacao.addEventListener('click', event => {
        if (event.target === ampliacao) ampliacao.close();
    });

    ampliacao.addEventListener('keydown', event => {
        if (event.key === 'ArrowLeft') {
            event.preventDefault();
            mostrarFoto(fotoAtual - 1);
        }

        if (event.key === 'ArrowRight') {
            event.preventDefault();
            mostrarFoto(fotoAtual + 1);
        }
    });
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const carrossel = document.querySelector('[data-carrossel-banners]');
    if (!carrossel) return;

    const slides = Array.from(carrossel.querySelectorAll('[data-banner-slide]'));
    const indicadores = Array.from(carrossel.querySelectorAll('[data-banner-indicador]'));

    if (slides.length <= 1) return;

    let atual = 0;
    let temporizador = null;

    function mostrarBanner(indice) {
        atual = (indice + slides.length) % slides.length;

        slides.forEach((slide, posicao) => {
            const ativo = posicao === atual;

            slide.classList.toggle('hidden', !ativo);
            slide.setAttribute('aria-hidden', String(!ativo));
        });

        indicadores.forEach((indicador, posicao) => {
            const ativo = posicao === atual;

            indicador.classList.toggle('w-7', ativo);
            indicador.classList.toggle('w-2.5', !ativo);
            indicador.classList.toggle('bg-white', ativo);
            indicador.classList.toggle('bg-white/50', !ativo);
            indicador.setAttribute('aria-current', String(ativo));
        });
    }

    function pararAutomatico() {
        if (temporizador !== null) {
            clearInterval(temporizador);
            temporizador = null;
        }
    }

    function iniciarAutomatico() {
        pararAutomatico();

        if (document.hidden) return;

        temporizador = setInterval(() => {
            mostrarBanner(atual + 1);
        }, 5000);
    }

    function navegar(indice) {
        mostrarBanner(indice);
        iniciarAutomatico();
    }

    carrossel.querySelector('[data-banner-anterior]')
        ?.addEventListener('click', () => navegar(atual - 1));

    carrossel.querySelector('[data-banner-proximo]')
        ?.addEventListener('click', () => navegar(atual + 1));

    indicadores.forEach((indicador, indice) => {
        indicador.addEventListener('click', () => navegar(indice));
    });

    carrossel.addEventListener('mouseenter', pararAutomatico);
    carrossel.addEventListener('mouseleave', iniciarAutomatico);

    carrossel.addEventListener('focusin', pararAutomatico);

    carrossel.addEventListener('focusout', evento => {
        if (!carrossel.contains(evento.relatedTarget)) {
            iniciarAutomatico();
        }
    });

    document.addEventListener('visibilitychange', () => {
        if (document.hidden) {
            pararAutomatico();
        } else {
            iniciarAutomatico();
        }
    });

    mostrarBanner(0);
    iniciarAutomatico();
});
</script>

</body>
</html>