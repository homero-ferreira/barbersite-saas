
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Novo usuário - BarberSite</title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
</head>
<body class="min-h-screen bg-gray-100">

    <header class="bg-gray-900 px-6 py-4 text-white">
        <div class="mx-auto flex max-w-6xl items-center justify-between gap-4">
            <a href="<?php echo e(route('painel.dashboard')); ?>">
                <img
                    src="<?php echo e(asset('branding/logo-barbersite.png')); ?>"
                    alt="BarberSite"
                    class="h-10 w-auto max-w-44 object-contain"
                >
            </a>

            <a
                href="<?php echo e(route('painel.usuarios.index')); ?>"
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
            data-demo-mode="<?php echo e(session('demo_mode') ? '1' : '0'); ?>"
            action="<?php echo e(route('painel.usuarios.store')); ?>"
            data-update-url="<?php echo e(route('painel.usuarios.update', ['id' => '__ID__'])); ?>"
            method="POST"
            class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm"
        >
            <?php echo csrf_field(); ?>

            <div id="abas-edicao-usuario" class="mb-6 hidden">
                <div class="mb-5 flex flex-wrap gap-2 border-b border-gray-200 pb-3">
                    <?php $__currentLoopData = [
                        'usuario' => 'Usuário',
                        'empresa' => 'Empresa',
                        'sobre' => 'Quem somos',
                        'redes' => 'Redes sociais',
                        'seo' => 'SEO',
                        'imagens' => 'Imagens e cores',
                        'produtos' => 'Produtos',
                        'banners' => 'Banners',
                    ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $aba => $rotulo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <button
                            type="button"
                            data-aba-usuario="<?php echo e($aba); ?>"
                            class="rounded-lg border border-gray-200 px-3 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-100"
                        >
                            <?php echo e($rotulo); ?>

                        </button>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
                <?php $__currentLoopData = [
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
                ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $campo => $rotulo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div>
                        <label for="<?php echo e($campo); ?>" class="mb-1 block text-sm font-semibold text-gray-700">
                            <?php echo e($rotulo); ?>

                        </label>
                        <input
                            id="<?php echo e($campo); ?>"
                            name="<?php echo e($campo); ?>"
                            type="text"
                            maxlength="<?php echo e($campo === 'uf' ? 2 : 255); ?>"
                            <?php if($campo === 'documento'): ?> data-mascara="documento" <?php endif; ?>
                            <?php if($campo === 'telefone'): ?> data-mascara="telefone" <?php endif; ?>
                            <?php if($campo === 'cep'): ?> data-mascara="cep" <?php endif; ?>
                            class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none"
                        >
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
                <?php $__currentLoopData = [
                    'instagram' => 'Instagram',
                    'facebook' => 'Facebook',
                    'linkedin' => 'LinkedIn',
                    'youtube' => 'YouTube',
                    'tiktok' => 'TikTok',
                ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $campo => $rotulo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div>
                        <label for="<?php echo e($campo); ?>" class="mb-1 block text-sm font-semibold text-gray-700">
                            <?php echo e($rotulo); ?>

                        </label>
                        <input
                            id="<?php echo e($campo); ?>"
                            name="<?php echo e($campo); ?>"
                            type="url"
                            maxlength="255"
                            placeholder="https://"
                            class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none"
                        >
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
                    <?php $__currentLoopData = [
                        'logo' => ['Logotipo', 'Identidade visual da barbearia.'],
                        'favicon' => ['Favicon', 'Ícone exibido na aba do navegador.'],
                        'imagem_compartilhamento' => ['Imagem de compartilhamento', 'Imagem exibida ao compartilhar o site no WhatsApp e nas redes sociais.'],
                        'banner_institucional_imagem' => ['Imagem do banner institucional', 'Imagem exibida no primeiro banner da página inicial.'],
                    ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $campo => [$rotulo, $descricao]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="rounded-xl border border-gray-200 p-4">
                            <label
                                for="<?php echo e($campo); ?>"
                                class="mb-1 block text-sm font-semibold text-gray-800"
                            >
                                <?php echo e($rotulo); ?>

                            </label>

                            <p class="mb-3 text-xs text-gray-500"><?php echo e($descricao); ?></p>

                            <div class="mb-3 flex h-40 items-center justify-center overflow-hidden rounded-lg bg-gray-100">
                                <img
                                    data-imagem-previa="<?php echo e($campo); ?>"
                                    alt="Prévia: <?php echo e($rotulo); ?>"
                                    class="hidden h-full max-w-full object-contain"
                                >

                                <span
                                    data-imagem-vazia="<?php echo e($campo); ?>"
                                    class="text-xs text-gray-400"
                                >
                                    Nenhuma imagem selecionada
                                </span>
                            </div>

                            <input
                                id="<?php echo e($campo); ?>"
                                name="<?php echo e($campo); ?>"
                                type="file"
                                accept="image/jpeg,image/png,image/webp"
                                data-imagem-upload="<?php echo e($campo); ?>"
                                class="block w-full text-sm text-gray-700 file:mr-3 file:rounded-lg file:border-0 file:bg-gray-900 file:px-4 file:py-2 file:font-semibold file:text-white hover:file:bg-gray-700"
                            >

                            <p class="mt-2 text-xs text-gray-500">
                                Deixe sem selecionar para manter a imagem atual.
                            </p>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>

                <div class="border-t border-gray-200 pt-5">
                    <h3 class="mb-4 text-sm font-bold text-gray-900">
                        Cores da página
                    </h3>

                    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                        <?php $__currentLoopData = [
                            'cor_principal' => ['Cor principal', '#4D6775'],
                            'cor_secundaria' => ['Cor secundária', '#4D6775'],
                        ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $campo => [$rotulo, $cor]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <label class="flex items-center gap-3 text-sm font-semibold text-gray-700">
                                <input
                                    type="color"
                                    name="<?php echo e($campo); ?>"
                                    value="<?php echo e($cor); ?>"
                                    data-cor-site="<?php echo e($campo); ?>"
                                    class="h-11 w-16 cursor-pointer rounded border border-gray-300"
                                >
                                <?php echo e($rotulo); ?>

                            </label>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
                    <?php $__empty_1 = true; $__currentLoopData = $produtos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $produto): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="flex flex-wrap items-center justify-between gap-3 border-b border-gray-100 px-4 py-3 last:border-b-0">
                            <label class="flex min-w-0 flex-1 items-center gap-3 text-sm text-gray-800">
                                <input
                                    type="checkbox"
                                    name="produtos_exibir[]"
                                    value="<?php echo e($produto->id); ?>"
                                    data-produto-exibir
                                    checked
                                    class="h-4 w-4 rounded border-gray-300"
                                >
                                <span><?php echo e($produto->titulo); ?> (#<?php echo e($produto->id); ?>)</span>
                            </label>

                            <label class="flex items-center gap-2 text-sm text-gray-700">
                                <input
                                    type="checkbox"
                                    name="produtos_destaque[]"
                                    value="<?php echo e($produto->id); ?>"
                                    data-produto-destaque
                                    class="h-4 w-4 rounded border-gray-300"
                                >
                                Destaque
                            </label>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <p class="p-4 text-sm text-gray-500">
                            Nenhum produto ativo encontrado.
                        </p>
                    <?php endif; ?>
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

                <?php for($posicao = 0; $posicao <= 6; $posicao++): ?>
                    <?php
                        $institucional = $posicao === 0;
                        $tipo = $institucional ? 'boas_vindas' : 'produto';
                        $prefixo = 'swb_' . $tipo . '_' . $posicao;
                    ?>

                    <div
                        data-banner-editor
                        data-banner-posicao="<?php echo e($posicao); ?>"
                        class="overflow-hidden rounded-xl border border-gray-200 bg-white"
                    >
                        <div class="flex flex-wrap items-center justify-between gap-3 bg-gray-50 px-4 py-3">
                            <strong class="text-sm text-gray-900">
                                <?php echo e($institucional ? 'Banner institucional' : 'Banner de produto ' . $posicao); ?>

                            </strong>

                            <label class="flex items-center gap-2 text-sm font-medium text-gray-700">
                                <input
                                    type="checkbox"
                                    name="<?php echo e($prefixo); ?>[ativo]"
                                    value="1"
                                    data-banner-ativo
                                    <?php if($institucional): echo 'checked'; endif; ?>
                                >
                                Ativo
                            </label>
                        </div>

                        <div class="grid gap-4 p-4 md:grid-cols-2">
                            <?php if (! ($institucional)): ?>
                                <div class="md:col-span-2">
                                    <label class="mb-1 block text-sm font-semibold text-gray-700">
                                        Produto vinculado
                                    </label>
                                    <select
                                        name="<?php echo e($prefixo); ?>[produto_id]"
                                        data-banner-produto
                                        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm"
                                    >
                                        <option value="">Selecione um produto</option>
                                        <?php $__currentLoopData = $produtos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $produto): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option
                                                value="<?php echo e($produto->id); ?>"
                                                data-texto-pequeno="<?php echo e($produto->banner_texto_pequeno); ?>"
                                                data-titulo="<?php echo e($produto->banner_titulo); ?>"
                                                data-descricao="<?php echo e($produto->banner_descricao); ?>"
                                                data-texto-botao="<?php echo e($produto->banner_texto_botao); ?>"
                                                data-imagem="<?php echo e($produto->imagem_banner ?? ''); ?>"
                                            >
                                                <?php echo e($produto->titulo); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            <?php endif; ?>

                            <div>
                                <label class="mb-1 block text-sm font-semibold text-gray-700">
                                    Texto pequeno
                                </label>
                                <input
                                    type="text"
                                    name="<?php echo e($prefixo); ?>[texto_pequeno]"
                                    data-banner-texto="texto_pequeno"
                                    maxlength="35"
                                    value="<?php echo e($institucional ? 'Bem-vindo' : 'Conheça nossos serviços'); ?>"
                                    class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm"
                                >
                            </div>

                            <div>
                                <label class="mb-1 block text-sm font-semibold text-gray-700">
                                    Texto do botão
                                </label>
                                <input
                                    type="text"
                                    name="<?php echo e($prefixo); ?>[texto_botao]"
                                    data-banner-texto="texto_botao"
                                    maxlength="25"
                                    value="<?php echo e($institucional ? 'Solicitar atendimento' : 'Conhecer serviço'); ?>"
                                    class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm"
                                >
                            </div>

                            <div class="md:col-span-2">
                                <label class="mb-1 block text-sm font-semibold text-gray-700">
                                    Título principal
                                </label>
                                <input
                                    type="text"
                                    name="<?php echo e($prefixo); ?>[titulo]"
                                    data-banner-texto="titulo"
                                    maxlength="75"
                                    value="<?php echo e($institucional ? 'Bem-vindo à nossa barbearia' : 'Conheça nossos serviços'); ?>"
                                    class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm"
                                >
                            </div>

                            <div class="md:col-span-2">
                                <label class="mb-1 block text-sm font-semibold text-gray-700">
                                    Descrição
                                </label>
                                <textarea
                                    name="<?php echo e($prefixo); ?>[descricao]"
                                    data-banner-texto="descricao"
                                    maxlength="130"
                                    rows="2"
                                    class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm"
                                ><?php echo e($institucional ? 'Conheça nossa barbearia e nossos serviços.' : 'Conheça este serviço e entre em contato.'); ?></textarea>
                            </div>

                            <div class="md:col-span-2">
                                <label class="flex items-center gap-2 text-sm font-semibold text-gray-700">
                                    <input
                                        type="checkbox"
                                        name="<?php echo e($prefixo); ?>[usar_cores_site]"
                                        value="1"
                                        data-banner-usar-cores
                                        checked
                                    >
                                    Usar cores da minha página
                                </label>
                            </div>

                            <div class="flex flex-wrap gap-4 md:col-span-2">
                                <?php $__currentLoopData = [1, 2, 3]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $numero): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <label class="flex items-center gap-2 text-sm text-gray-700">
                                        Cor <?php echo e($numero); ?>

                                        <input
                                            type="color"
                                            name="<?php echo e($prefixo); ?>[cor_<?php echo e($numero); ?>]"
                                            data-banner-cor="<?php echo e($numero); ?>"
                                            value="<?php echo e([1 => '#30343B', 2 => '#70777F', 3 => '#F2F3F4'][$numero]); ?>"
                                            class="h-9 w-12 cursor-pointer rounded border border-gray-300"
                                        >
                                    </label>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>

                            <div class="md:col-span-2">
                                <p class="mb-2 text-xs font-semibold text-gray-500">Combinações prontas</p>
                                <div class="flex flex-wrap gap-2">
                                    <?php $__currentLoopData = [
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
                                    ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $nome => $cores): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <button
                                            type="button"
                                            data-banner-paleta="<?php echo e($cores); ?>"
                                            title="<?php echo e($nome); ?>"
                                            class="rounded-lg border border-gray-200 p-1 hover:border-gray-500"
                                        >
                                            <span
                                                class="block h-6 w-12 rounded"
                                                style="background: linear-gradient(115deg, <?php echo e(str_replace(',', ', ', $cores)); ?>)"
                                            ></span>
                                        </button>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
                                        alt="<?php echo e($institucional ? 'Imagem do banner institucional' : 'Imagem do produto selecionado'); ?>"
                                        class="hidden h-40 w-40 shrink-0 rounded-lg object-contain sm:h-48 sm:w-48"
                                    >
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endfor; ?>
            </div>

            <div id="cadastro-usuario-vue" class="mt-6"></div>

            <div class="mt-6 flex justify-end">
                <a
                    href="<?php echo e(route('painel.usuarios.index')); ?>"
                    class="rounded-lg border border-gray-300 px-5 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50"
                >
                    Voltar
                </a>
            </div>

        </form>

    </main>
</body>
</html>
<?php /**PATH C:\laragon\www\barbersite-laravel\resources\views/painel/usuarios/create.blade.php ENDPATH**/ ?>