
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Novo usuário - BarberSite</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gray-100">

    <header class="bg-gray-900 px-6 py-4 text-white">
        <div class="mx-auto flex max-w-6xl items-center justify-between gap-4">
            <a href="{{ route('painel.dashboard') }}">
                <img
                    src="{{ asset('branding/logo-barbersite.png') }}"
                    alt="BarberSite"
                    class="h-10 w-auto max-w-44 object-contain"
                >
            </a>

            <a
                href="{{ route('painel.usuarios.index') }}"
                class="rounded-lg bg-gray-700 px-4 py-2 text-sm font-semibold hover:bg-gray-600"
            >
                Voltar aos usuários
            </a>
        </div>
    </header>

    <main class="mx-auto max-w-6xl p-6">

        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">
                Novo usuário
            </h1>
            <p class="mt-1 text-sm text-gray-500">
                Cadastro de cliente do BarberSite
            </p>
        </div>

        <form
            id="cadastro-usuario-form"
            data-demo-mode="{{ session('demo_mode') ? '1' : '0' }}"
            action="{{ route('painel.usuarios.store') }}"
            data-update-url="{{ route('painel.usuarios.update', ['id' => '__ID__']) }}"
            method="POST"
            class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm"
        >
            @csrf

            <div id="abas-edicao-usuario" class="mb-6 hidden">
                <div class="mb-5 flex flex-wrap gap-2 border-b border-gray-200 pb-3">
                    @foreach ([
                        'usuario' => 'Usuário',
                        'empresa' => 'Empresa',
                        'sobre' => 'Quem somos',
                        'redes' => 'Redes sociais',
                        'seo' => 'SEO',
                        'imagens' => 'Imagens e cores',
                        'produtos' => 'Produtos',
                        'banners' => 'Banners',
                    ] as $aba => $rotulo)
                        <button
                            type="button"
                            data-aba-usuario="{{ $aba }}"
                            class="rounded-lg border border-gray-200 px-3 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-100"
                        >
                            {{ $rotulo }}
                        </button>
                    @endforeach
                </div>
            </div>

            <div data-conteudo-aba="usuario" class="grid grid-cols-1 gap-5 md:grid-cols-2">

                <div>
                    <label for="nome" class="mb-1 block text-sm font-semibold text-gray-700">
                        Nome completo
                    </label>
                    <input
                        id="nome"
                        name="nome"
                        type="text"
                        maxlength="150"
                        autocomplete="name"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none"
                    >
                </div>

                <div>
                    <label for="email" class="mb-1 block text-sm font-semibold text-gray-700">
                        E-mail
                    </label>
                    <input
                        id="email"
                        name="email"
                        type="email"
                        maxlength="190"
                        autocomplete="email"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none"
                    >
                </div>

                <div>
                    <label for="whatsapp" class="mb-1 block text-sm font-semibold text-gray-700">
                        WhatsApp
                    </label>
                    <input
                        id="whatsapp"
                        name="whatsapp"
                        type="tel"
                        inputmode="numeric"
                        data-mascara="whatsapp"
                        maxlength="15"
                        placeholder="(43) 99999-9999"
                        autocomplete="tel"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none"
                    >
                </div>

                <div>
                    <label for="titulo_site" class="mb-1 block text-sm font-semibold text-gray-700">
                        Nome da barbearia
                    </label>
                    <input
                        id="titulo_site"
                        name="titulo_site"
                        type="text"
                        maxlength="150"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none"
                    >
                </div>

                <div>
                    <label for="dominio" class="mb-1 block text-sm font-semibold text-gray-700">
                        Domínio próprio
                    </label>
                    <input
                        id="dominio"
                        name="dominio"
                        type="text"
                        maxlength="253"
                        placeholder="barbearia.com.br"
                        autocomplete="off"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none"
                    >
                </div>

            </div>

            <div data-conteudo-aba="empresa" class="hidden grid-cols-1 gap-5 md:grid-cols-2">
                @foreach ([
                    'razao_social' => 'Razão social',
                    'documento' => 'CPF/CNPJ',
                    'email_comercial' => 'E-mail comercial',
                    'telefone' => 'Telefone',
                    'cep' => 'CEP',
                    'endereco' => 'Endereço',
                    'numero' => 'Número',
                    'complemento' => 'Complemento',
                    'bairro' => 'Bairro',
                    'cidade' => 'Cidade',
                    'uf' => 'UF',
                ] as $campo => $rotulo)
                    <div>
                        <label for="{{ $campo }}" class="mb-1 block text-sm font-semibold text-gray-700">
                            {{ $rotulo }}
                        </label>
                        <input
                            id="{{ $campo }}"
                            name="{{ $campo }}"
                            type="text"
                            maxlength="{{ $campo === 'uf' ? 2 : 255 }}"
                            @if($campo === 'documento') data-mascara="documento" @endif
                            @if($campo === 'telefone') data-mascara="telefone" @endif
                            @if($campo === 'cep') data-mascara="cep" @endif
                            class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none"
                        >
                    </div>
                @endforeach
            </div>

            <div data-conteudo-aba="sobre" class="hidden">
                <label for="sobre_nos" class="mb-1 block text-sm font-semibold text-gray-700">
                    Apresentação da barbearia
                </label>
                <textarea
                    id="sobre_nos"
                    name="sobre_nos"
                    maxlength="5000"
                    rows="9"
                    placeholder="Conte a história, os valores e os diferenciais da barbearia."
                    class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none"
                ></textarea>
            </div>

            <div data-conteudo-aba="redes" class="hidden grid-cols-1 gap-5 md:grid-cols-2">
                @foreach ([
                    'instagram' => 'Instagram',
                    'facebook' => 'Facebook',
                    'linkedin' => 'LinkedIn',
                    'youtube' => 'YouTube',
                    'tiktok' => 'TikTok',
                ] as $campo => $rotulo)
                    <div>
                        <label for="{{ $campo }}" class="mb-1 block text-sm font-semibold text-gray-700">
                            {{ $rotulo }}
                        </label>
                        <input
                            id="{{ $campo }}"
                            name="{{ $campo }}"
                            type="url"
                            maxlength="255"
                            placeholder="https://"
                            class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none"
                        >
                    </div>
                @endforeach
            </div>

            <div data-conteudo-aba="seo" class="hidden space-y-5">
                <div>
                    <label for="descricao_site" class="mb-1 block text-sm font-semibold text-gray-700">
                        Descrição do site
                    </label>
                    <textarea
                        id="descricao_site"
                        name="descricao_site"
                        maxlength="1000"
                        rows="5"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none"
                    ></textarea>
                </div>
                <p class="text-xs text-gray-500">
                    O título do site é o nome da barbearia informado na aba Usuário.
                </p>
            </div>

            <div data-conteudo-aba="imagens" class="hidden space-y-6">
                <div>
                    <h2 class="text-lg font-bold text-gray-900">Imagens e cores</h2>
                    <p class="mt-1 text-sm text-gray-500">
                        Personalize as imagens e as cores da barbearia.
                        Formatos aceitos: JPG, PNG e WebP. Máximo de 3 MB por imagem.
                    </p>
                </div>

                <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                    @foreach ([
                        'logo' => ['Logotipo', 'Identidade visual da barbearia.'],
                        'favicon' => ['Favicon', 'Ícone exibido na aba do navegador.'],
                        'imagem_compartilhamento' => ['Imagem de compartilhamento', 'Imagem exibida ao compartilhar o site no WhatsApp e nas redes sociais.'],
                        'banner_institucional_imagem' => ['Imagem do banner institucional', 'Imagem exibida no primeiro banner da página inicial.'],
                    ] as $campo => [$rotulo, $descricao])
                        <div class="rounded-xl border border-gray-200 p-4">
                            <label
                                for="{{ $campo }}"
                                class="mb-1 block text-sm font-semibold text-gray-800"
                            >
                                {{ $rotulo }}
                            </label>

                            <p class="mb-3 text-xs text-gray-500">{{ $descricao }}</p>

                            <div class="mb-3 flex h-40 items-center justify-center overflow-hidden rounded-lg bg-gray-100">
                                <img
                                    data-imagem-previa="{{ $campo }}"
                                    alt="Prévia: {{ $rotulo }}"
                                    class="hidden h-full max-w-full object-contain"
                                >

                                <span
                                    data-imagem-vazia="{{ $campo }}"
                                    class="text-xs text-gray-400"
                                >
                                    Nenhuma imagem selecionada
                                </span>
                            </div>

                            <input
                                id="{{ $campo }}"
                                name="{{ $campo }}"
                                type="file"
                                accept="image/jpeg,image/png,image/webp"
                                data-imagem-upload="{{ $campo }}"
                                class="block w-full text-sm text-gray-700 file:mr-3 file:rounded-lg file:border-0 file:bg-gray-900 file:px-4 file:py-2 file:font-semibold file:text-white hover:file:bg-gray-700"
                            >

                            <p class="mt-2 text-xs text-gray-500">
                                Deixe sem selecionar para manter a imagem atual.
                            </p>
                        </div>
                    @endforeach
                </div>

                <div class="border-t border-gray-200 pt-5">
                    <h3 class="mb-4 text-sm font-bold text-gray-900">
                        Cores da página
                    </h3>

                    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                        @foreach ([
                            'cor_principal' => ['Cor principal', '#4D6775'],
                            'cor_secundaria' => ['Cor secundária', '#4D6775'],
                        ] as $campo => [$rotulo, $cor])
                            <label class="flex items-center gap-3 text-sm font-semibold text-gray-700">
                                <input
                                    type="color"
                                    name="{{ $campo }}"
                                    value="{{ $cor }}"
                                    data-cor-site="{{ $campo }}"
                                    class="h-11 w-16 cursor-pointer rounded border border-gray-300"
                                >
                                {{ $rotulo }}
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>

            <div data-conteudo-aba="produtos" class="hidden space-y-4">
                <input type="hidden" name="produtos_configurados" value="1">

                <div>
                    <h2 class="text-lg font-bold text-gray-900">Produtos e serviços</h2>
                    <p class="mt-1 text-sm text-gray-500">
                        Todos os produtos ativos aparecem inicialmente. Desmarque os que não deseja oferecer e escolha os destaques.
                    </p>
                </div>

                <div class="flex flex-wrap gap-2">
                    <button type="button" data-produtos-acao="todos"
                        class="rounded-lg border border-gray-300 px-3 py-2 text-sm font-semibold hover:bg-gray-100">
                        Adicionar todos
                    </button>

                    <button type="button" data-produtos-acao="nenhum"
                        class="rounded-lg border border-gray-300 px-3 py-2 text-sm font-semibold hover:bg-gray-100">
                        Remover todos
                    </button>
                </div>

                <div class="max-h-96 overflow-y-auto rounded-lg border border-gray-200">
                    @forelse ($produtos as $produto)
                        <div class="flex flex-wrap items-center justify-between gap-3 border-b border-gray-100 px-4 py-3 last:border-b-0">
                            <label class="flex min-w-0 flex-1 items-center gap-3 text-sm text-gray-800">
                                <input
                                    type="checkbox"
                                    name="produtos_exibir[]"
                                    value="{{ $produto->id }}"
                                    data-produto-exibir
                                    checked
                                    class="h-4 w-4 rounded border-gray-300"
                                >
                                <span>{{ $produto->titulo }} (#{{ $produto->id }})</span>
                            </label>

                            <label class="flex items-center gap-2 text-sm text-gray-700">
                                <input
                                    type="checkbox"
                                    name="produtos_destaque[]"
                                    value="{{ $produto->id }}"
                                    data-produto-destaque
                                    class="h-4 w-4 rounded border-gray-300"
                                >
                                Destaque
                            </label>
                        </div>
                    @empty
                        <p class="p-4 text-sm text-gray-500">
                            Nenhum produto ativo encontrado.
                        </p>
                    @endforelse
                </div>

                <p class="text-xs text-gray-500">
                    Os destaques serão salvos somente para produtos selecionados.
                </p>
            </div>

            <div data-conteudo-aba="banners" class="hidden space-y-5">
                <input type="hidden" name="banners_configurados" value="1">

                <div>
                    <h2 class="text-lg font-bold text-gray-900">Banners personalizados</h2>
                    <p class="mt-1 text-sm text-gray-500">
                        Um banner institucional independente e até seis banners de produtos.
                        Personalize os textos, as cores e a ativação de cada posição.
                    </p>
                </div>

                <div class="flex flex-wrap gap-2">
                    <button
                        type="button"
                        data-banners-cores-padrao
                        class="rounded-lg border border-gray-300 px-3 py-2 text-sm font-semibold hover:bg-gray-100"
                    >
                        Aplicar cores padrão a todos
                    </button>
                </div>

                @for ($posicao = 0; $posicao <= 6; $posicao++)
                    @php
                        $institucional = $posicao === 0;
                        $tipo = $institucional ? 'boas_vindas' : 'produto';
                        $prefixo = 'swb_' . $tipo . '_' . $posicao;
                    @endphp

                    <div
                        data-banner-editor
                        data-banner-posicao="{{ $posicao }}"
                        class="overflow-hidden rounded-xl border border-gray-200 bg-white"
                    >
                        <div class="flex flex-wrap items-center justify-between gap-3 bg-gray-50 px-4 py-3">
                            <strong class="text-sm text-gray-900">
                                {{ $institucional ? 'Banner institucional' : 'Banner de produto ' . $posicao }}
                            </strong>

                            <label class="flex items-center gap-2 text-sm font-medium text-gray-700">
                                <input
                                    type="checkbox"
                                    name="{{ $prefixo }}[ativo]"
                                    value="1"
                                    data-banner-ativo
                                    @checked($institucional)
                                >
                                Ativo
                            </label>
                        </div>

                        <div class="grid gap-4 p-4 md:grid-cols-2">
                            @unless ($institucional)
                                <div class="md:col-span-2">
                                    <label class="mb-1 block text-sm font-semibold text-gray-700">
                                        Produto vinculado
                                    </label>
                                    <select
                                        name="{{ $prefixo }}[produto_id]"
                                        data-banner-produto
                                        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm"
                                    >
                                        <option value="">Selecione um produto</option>
                                        @foreach ($produtos as $produto)
                                            <option
                                                value="{{ $produto->id }}"
                                                data-texto-pequeno="{{ $produto->banner_texto_pequeno }}"
                                                data-titulo="{{ $produto->banner_titulo }}"
                                                data-descricao="{{ $produto->banner_descricao }}"
                                                data-texto-botao="{{ $produto->banner_texto_botao }}"
                                                data-imagem="{{ $produto->imagem_banner ?? '' }}"
                                            >
                                                {{ $produto->titulo }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            @endunless

                            <div>
                                <label class="mb-1 block text-sm font-semibold text-gray-700">
                                    Texto pequeno
                                </label>
                                <input
                                    type="text"
                                    name="{{ $prefixo }}[texto_pequeno]"
                                    data-banner-texto="texto_pequeno"
                                    maxlength="35"
                                    value="{{ $institucional ? 'Bem-vindo' : 'Conheça nossos serviços' }}"
                                    class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm"
                                >
                            </div>

                            <div>
                                <label class="mb-1 block text-sm font-semibold text-gray-700">
                                    Texto do botão
                                </label>
                                <input
                                    type="text"
                                    name="{{ $prefixo }}[texto_botao]"
                                    data-banner-texto="texto_botao"
                                    maxlength="25"
                                    value="{{ $institucional ? 'Solicitar atendimento' : 'Conhecer serviço' }}"
                                    class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm"
                                >
                            </div>

                            <div class="md:col-span-2">
                                <label class="mb-1 block text-sm font-semibold text-gray-700">
                                    Título principal
                                </label>
                                <input
                                    type="text"
                                    name="{{ $prefixo }}[titulo]"
                                    data-banner-texto="titulo"
                                    maxlength="75"
                                    value="{{ $institucional ? 'Bem-vindo à nossa barbearia' : 'Conheça nossos serviços' }}"
                                    class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm"
                                >
                            </div>

                            <div class="md:col-span-2">
                                <label class="mb-1 block text-sm font-semibold text-gray-700">
                                    Descrição
                                </label>
                                <textarea
                                    name="{{ $prefixo }}[descricao]"
                                    data-banner-texto="descricao"
                                    maxlength="130"
                                    rows="2"
                                    class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm"
                                >{{ $institucional ? 'Conheça nossa barbearia e nossos serviços.' : 'Conheça este serviço e entre em contato.' }}</textarea>
                            </div>

                            <div class="md:col-span-2">
                                <label class="flex items-center gap-2 text-sm font-semibold text-gray-700">
                                    <input
                                        type="checkbox"
                                        name="{{ $prefixo }}[usar_cores_site]"
                                        value="1"
                                        data-banner-usar-cores
                                        checked
                                    >
                                    Usar cores da minha página
                                </label>
                            </div>

                            <div class="flex flex-wrap gap-4 md:col-span-2">
                                @foreach ([1, 2, 3] as $numero)
                                    <label class="flex items-center gap-2 text-sm text-gray-700">
                                        Cor {{ $numero }}
                                        <input
                                            type="color"
                                            name="{{ $prefixo }}[cor_{{ $numero }}]"
                                            data-banner-cor="{{ $numero }}"
                                            value="{{ [1 => '#30343B', 2 => '#70777F', 3 => '#F2F3F4'][$numero] }}"
                                            class="h-9 w-12 cursor-pointer rounded border border-gray-300"
                                        >
                                    </label>
                                @endforeach
                            </div>

                            <div class="md:col-span-2">
                                <p class="mb-2 text-xs font-semibold text-gray-500">Combinações prontas</p>
                                <div class="flex flex-wrap gap-2">
                                    @foreach ([
                                        'Grafite e dourado' => '#30343B,#A88A52,#F4EBD8',
                                        'Azul e prata' => '#183B56,#5987A7,#E3ECF2',
                                        'Verde e areia' => '#234A3E,#7F9D83,#F1E8D7',
                                        'Vinho e rosé' => '#5D3044,#BB8090,#F5E8EA',
                                        'Preto e cobre' => '#181818,#A7643F,#E9CDB7',
                                        'Marinho e coral' => '#162B46,#E37B66,#F5E6D8',
                                        'Terracota' => '#603C31,#BC7053,#F2D6BD',
                                        'Esmeralda' => '#123D39,#299F80,#CBE9DC',
                                        'Ameixa e lavanda' => '#3B284B,#8865AA,#E8DDF1',
                                        'Café e creme' => '#403126,#97785C,#EFE2CF',
                                        'Petróleo e gelo' => '#163D47,#4D8791,#DCEBED',
                                        'Preto e cinza' => '#101114,#41454A,#A9ADB3',
                                        'Grafite e prata' => '#25292F,#777F89,#E1E4E8',
                                        'Chumbo e gelo' => '#34383D,#8B939C,#F1F3F5',
                                        'Preto e branco' => '#08090B,#55585D,#FFFFFF',
                                        'Cinza urbano' => '#4B5057,#9399A1,#D8DDE2',
                                        'Ardósia' => '#202D35,#627783,#CBD5DA',
                                        'Preto e platina' => '#17191C,#858B91,#E8E9EB',
                                        'Cinza minimalista' => '#393C41,#777B80,#C7C9CD',
                                        'Carvão e areia' => '#252523,#77736A,#E7E0D5',
                                        'Marinho e ouro' => '#111D31,#8D7445,#E8D9B8',
                                        'Oliva e creme' => '#293C30,#778467,#E9E8D9',
                                        'Bordô e pérola' => '#4A2430,#9B6875,#F1E8E8',
                                    ] as $nome => $cores)
                                        <button
                                            type="button"
                                            data-banner-paleta="{{ $cores }}"
                                            title="{{ $nome }}"
                                            class="rounded-lg border border-gray-200 p-1 hover:border-gray-500"
                                        >
                                            <span
                                                class="block h-6 w-12 rounded"
                                                style="background: linear-gradient(115deg, {{ str_replace(',', ', ', $cores) }})"
                                            ></span>
                                        </button>
                                    @endforeach
                                </div>
                            </div>

                            <div
                                data-banner-previa
                                class="min-h-48 overflow-hidden rounded-xl p-5 text-white md:col-span-2"
                            >
                                <div class="flex flex-col items-center gap-5 sm:flex-row sm:justify-between">
                                    <div class="min-w-0 flex-1">
                                        <p data-previa-texto-pequeno class="text-xs font-semibold uppercase tracking-wider"></p>
                                        <h3 data-previa-titulo class="mt-2 text-xl font-bold"></h3>
                                        <p data-previa-descricao class="mt-2 text-sm"></p>
                                        <span
                                            data-previa-botao
                                            class="mt-4 inline-block rounded-lg bg-white px-4 py-2 text-xs font-semibold text-gray-900"
                                        ></span>
                                    </div>

                                    <img
                                        data-previa-imagem
                                        alt="{{ $institucional ? 'Imagem do banner institucional' : 'Imagem do produto selecionado' }}"
                                        class="hidden h-40 w-40 shrink-0 rounded-lg object-contain sm:h-48 sm:w-48"
                                    >
                                </div>
                            </div>
                        </div>
                    </div>
                @endfor
            </div>

            <div id="cadastro-usuario-vue" class="mt-6"></div>

            <div class="mt-6 flex justify-end">
                <a
                    href="{{ route('painel.usuarios.index') }}"
                    class="rounded-lg border border-gray-300 px-5 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50"
                >
                    Voltar
                </a>
            </div>

        </form>

    </main>
</body>
</html>
