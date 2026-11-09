<?php

namespace App\Http\Controllers\Painel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UsuariosController extends Controller
{
    public function create(Request $request)
    {
        $usuario = $request->user();

        if (!$usuario || (int) $usuario->id !== 1 || !$usuario->ativo) {
            abort(403, 'Acesso não autorizado.');
        }

        $produtos = DB::table('produtos')
            ->where('ativo', 1)
            ->orderBy('titulo')
            ->get([
                'id',
                'titulo',
                'seo_slug',
                'imagem_capa',
                'banner_texto_pequeno',
                'banner_titulo',
                'banner_descricao',
                'banner_texto_botao',
            ]);

        foreach ($produtos as $produto) {
            $capa = trim((string) ($produto->imagem_capa ?? ''));
            $slug = trim((string) ($produto->seo_slug ?? ''));
            $candidatos = [];

            if ($capa !== '') {
                if (preg_match('~^https?://~i', $capa)) {
                    $produto->imagem_banner = $capa;
                    continue;
                }

                $caminho = ltrim($capa, '/');
                $candidatos[] = $caminho;

                if (!str_contains($caminho, '/')) {
                    $candidatos[] = 'img/produtos/' . $caminho;
                }

                $nome = basename($caminho);

                if (!str_contains($nome, '-thumb.')) {
                    $thumb = preg_replace(
                        '~\.(jpe?g|png|webp)$~i',
                        '-thumb.$1',
                        $nome
                    );

                    if ($thumb !== $nome) {
                        $candidatos[] = 'img/produtos/' . $thumb;
                    }
                }
            }

            if ($slug !== '') {
                foreach (['jpg', 'jpeg', 'webp', 'png'] as $extensao) {
                    $candidatos[] = 'img/produtos/' . $slug . '-thumb.' . $extensao;
                    $candidatos[] = 'img/produtos/' . $slug . '.' . $extensao;
                }
            }

            $produto->imagem_banner = '';

            foreach (array_unique($candidatos) as $arquivo) {
                $arquivo = ltrim($arquivo, '/');

                if (is_file(public_path($arquivo))) {
                    $produto->imagem_banner = asset($arquivo);
                    break;
                }
            }
        }

        return view('painel.usuarios.create', [
            'produtos' => $produtos,
        ]);
    }

    public function store(Request $request)
    {
        $usuario = $request->user();

        if (!$usuario || (int) $usuario->id !== 1 || !$usuario->ativo) {
            abort(403, 'Acesso não autorizado.');
        }

        $dados = $request->validate([
            'nome' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', 'max:190', Rule::unique('usuarios', 'email')],
            'whatsapp' => ['required', 'string', 'max:30', 'regex:/^\(\d{2}\) \d{4,5}-\d{4}$/'],
            'titulo_site' => ['required', 'string', 'max:150'],
            'dominio' => ['nullable', 'string', 'max:253'],
        ]);

        $dominio = mb_strtolower(trim((string) ($dados['dominio'] ?? '')));

        if ($dominio !== '') {
            if (!filter_var('http://' . $dominio, FILTER_VALIDATE_URL)
                || !preg_match('/^(?=.{1,253}$)[a-z0-9]+(?:[a-z0-9-]*[a-z0-9])?(?:\.[a-z0-9]+(?:[a-z0-9-]*[a-z0-9])?)+$/D', $dominio)) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'dominio' => 'Informe um domínio válido, sem http://, https:// ou caminhos.',
                ]);
            }

            if (DB::table('dominios')->where('dominio', $dominio)->exists()) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'dominio' => 'Este domínio já está cadastrado.',
                ]);
            }
        }

        $grupos = [
            'ACDEFHJKMNPRTUVWXY',
            'acdefhjkmnprtuvwxy',
            '34679',
            '@#$%&*?',
        ];

        $caracteres = [];

        foreach ($grupos as $grupo) {
            $caracteres[] = $grupo[random_int(0, strlen($grupo) - 1)];
        }

        $todos = implode('', $grupos);

        while (count($caracteres) < 8) {
            $caracteres[] = $todos[random_int(0, strlen($todos) - 1)];
        }

        for ($i = count($caracteres) - 1; $i > 0; $i--) {
            $j = random_int(0, $i);
            [$caracteres[$i], $caracteres[$j]] = [$caracteres[$j], $caracteres[$i]];
        }

        $senha = implode('', $caracteres);

        $id = DB::transaction(function () use ($dados, $senha, $dominio) {
            $id = DB::table('usuarios')->insertGetId([
                'nome' => trim($dados['nome']),
                'email' => mb_strtolower(trim($dados['email'])),
                'whatsapp' => $dados['whatsapp'],
                'titulo_site' => trim($dados['titulo_site']),
                'senha_hash' => Hash::make($senha),
                'senha_recuperavel' => Crypt::encryptString($senha),
                'ativo' => 1,
            ]);

            if ($dominio !== '') {
                DB::table('dominios')->insert([
                    'usuario_id' => $id,
                    'dominio' => $dominio,
                    'ativo' => 1,
                ]);
            }

            return $id;
        });

        return response()->json([
            'sucesso' => true,
            'mensagem' => 'Usuário cadastrado com sucesso.',
            'id' => $id,
        ], 201);
    }

    public function edit(Request $request, int $id)
    {
        $usuario = $request->user();

        if (!$usuario || (int) $usuario->id !== 1 || !$usuario->ativo) {
            abort(403, 'Acesso não autorizado.');
        }

        $cliente = DB::table('usuarios')
            ->where('id', $id)
            ->first([
                'id',
                'nome',
                'email',
                'whatsapp',
                'titulo_site',
                'razao_social',
                'documento',
                'email_comercial',
                'telefone',
                'cep',
                'endereco',
                'numero',
                'complemento',
                'bairro',
                'cidade',
                'uf',
                'sobre_nos',
                'descricao_site',
                'instagram',
                'facebook',
                'linkedin',
                'youtube',
                'tiktok',
                'logo',
                'favicon',
                'imagem_compartilhamento',
                'banner_institucional_imagem',
                'cor_principal',
                'cor_secundaria',
                'produtos_removidos',
                'produtos_destaque',
            ]);

        if (!$cliente) {
            abort(404, 'Usuário não encontrado.');
        }

        $cliente->dominio = DB::table('dominios')
            ->where('usuario_id', $id)
            ->where('ativo', 1)
            ->orderBy('id')
            ->value('dominio') ?? '';

        $banners = DB::table('cliente_banners')
            ->where('usuario_id', $id)
            ->orderBy('tipo')
            ->orderBy('posicao')
            ->get();

        return response()->json([
            'sucesso' => true,
            'usuario' => $cliente,
            'banners' => $banners,
        ]);
    }

    public function update(Request $request, int $id)
    {
        $usuario = $request->user();

        if (!$usuario || (int) $usuario->id !== 1 || !$usuario->ativo) {
            abort(403, 'Acesso não autorizado.');
        }

        $cliente = DB::table('usuarios')->where('id', $id)->first();

        if (!$cliente) {
            abort(404, 'Usuário não encontrado.');
        }

        $dados = $request->validate([
            'nome' => ['required', 'string', 'max:150'],
            'email' => [
                'required',
                'email',
                'max:190',
                Rule::unique('usuarios', 'email')->ignore($id),
            ],
            'whatsapp' => ['required', 'string', 'max:30', 'regex:/^\(\d{2}\) \d{4,5}-\d{4}$/'],
            'titulo_site' => ['required', 'string', 'max:150'],
            'dominio' => ['nullable', 'string', 'max:253'],

            'razao_social' => ['nullable', 'string', 'max:255'],
            'documento' => ['nullable', 'string', 'max:30'],
            'email_comercial' => ['nullable', 'email', 'max:190'],
            'telefone' => ['nullable', 'string', 'max:30'],
            'cep' => ['nullable', 'string', 'max:20'],
            'endereco' => ['nullable', 'string', 'max:255'],
            'numero' => ['nullable', 'string', 'max:30'],
            'complemento' => ['nullable', 'string', 'max:255'],
            'bairro' => ['nullable', 'string', 'max:150'],
            'cidade' => ['nullable', 'string', 'max:150'],
            'uf' => ['nullable', 'string', 'size:2'],
            'sobre_nos' => ['nullable', 'string', 'max:5000'],
            'descricao_site' => ['nullable', 'string', 'max:1000'],

            'instagram' => ['nullable', 'url', 'max:255'],
            'facebook' => ['nullable', 'url', 'max:255'],
            'linkedin' => ['nullable', 'url', 'max:255'],
            'youtube' => ['nullable', 'url', 'max:255'],
            'tiktok' => ['nullable', 'url', 'max:255'],

            'logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:3072', 'dimensions:max_width=4000,max_height=4000'],
            'favicon' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:3072', 'dimensions:max_width=4000,max_height=4000'],
            'imagem_compartilhamento' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:3072', 'dimensions:max_width=4000,max_height=4000'],
            'banner_institucional_imagem' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:3072', 'dimensions:max_width=4000,max_height=4000'],
            'cor_principal' => ['nullable', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'cor_secundaria' => ['nullable', 'regex:/^#[0-9a-fA-F]{6}$/'],

            'produtos_configurados' => ['sometimes', 'accepted'],
            'produtos_exibir' => ['sometimes', 'array'],
            'produtos_exibir.*' => ['integer', 'distinct'],
            'produtos_destaque' => ['sometimes', 'array'],
            'produtos_destaque.*' => ['integer', 'distinct'],

            'banners_configurados' => ['sometimes', 'accepted'],
            'swb_boas_vindas_0' => ['sometimes', 'array'],
            'swb_produto_1' => ['sometimes', 'array'],
            'swb_produto_2' => ['sometimes', 'array'],
            'swb_produto_3' => ['sometimes', 'array'],
            'swb_produto_4' => ['sometimes', 'array'],
            'swb_produto_5' => ['sometimes', 'array'],
            'swb_produto_6' => ['sometimes', 'array'],
        ]);

        $dominio = mb_strtolower(trim((string) ($dados['dominio'] ?? '')));

        if ($request->exists('dominio') && $dominio !== '') {
            if (!filter_var('http://' . $dominio, FILTER_VALIDATE_URL)
                || !preg_match('/^(?=.{1,253}$)[a-z0-9]+(?:[a-z0-9-]*[a-z0-9])?(?:\.[a-z0-9]+(?:[a-z0-9-]*[a-z0-9])?)+$/D', $dominio)) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'dominio' => 'Informe um domínio válido, sem http://, https:// ou caminhos.',
                ]);
            }

            if (DB::table('dominios')
                ->where('dominio', $dominio)
                ->where('usuario_id', '<>', $id)
                ->exists()) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'dominio' => 'Este domínio já está cadastrado para outro usuário.',
                ]);
            }
        }

        $alteracoes = [
            'nome' => trim($dados['nome']),
            'email' => mb_strtolower(trim($dados['email'])),
            'whatsapp' => $dados['whatsapp'],
            'titulo_site' => trim($dados['titulo_site']),
        ];

        foreach ([
            'razao_social', 'documento', 'email_comercial', 'telefone',
            'cep', 'endereco', 'numero', 'complemento', 'bairro',
            'cidade', 'uf', 'sobre_nos', 'descricao_site',
            'instagram', 'facebook', 'linkedin', 'youtube', 'tiktok',
        ] as $campo) {
            if ($request->exists($campo)) {
                $valor = $dados[$campo] ?? null;

                $alteracoes[$campo] = is_string($valor)
                    ? trim($valor)
                    : null;

                if ($alteracoes[$campo] === null && !in_array($campo, ['sobre_nos', 'descricao_site'], true)) {
                    $alteracoes[$campo] = '';
                }
            }
        }

        foreach (['cor_principal', 'cor_secundaria'] as $campo) {
            if ($request->exists($campo) && !empty($dados[$campo])) {
                $alteracoes[$campo] = strtoupper($dados[$campo]);
            }
        }

        if ($request->boolean('produtos_configurados')) {
            $ativos = DB::table('produtos')
                ->where('ativo', 1)
                ->pluck('id')
                ->map(fn ($id) => (int) $id)
                ->all();

            $exibir = array_values(array_intersect(
                $ativos,
                array_map('intval', $dados['produtos_exibir'] ?? [])
            ));

            $destaques = array_values(array_intersect(
                $exibir,
                array_map('intval', $dados['produtos_destaque'] ?? [])
            ));

            $removidos = array_values(array_diff($ativos, $exibir));

            $anteriores = array_map(
                'intval',
                array_filter(explode(',', (string) ($cliente->produtos_removidos ?? '')), 'strlen')
            );

            $removidos = array_values(array_unique(array_merge(
                $removidos,
                array_diff($anteriores, $ativos)
            )));

            $alteracoes['produtos_removidos'] = implode(',', $removidos);
            $alteracoes['produtos_destaque'] = implode(',', $destaques);
        }

        $bannersParaSalvar = [];

        if ($request->boolean('banners_configurados')) {
            $produtosDisponiveis = DB::table('produtos')
                ->where('ativo', 1)
                ->pluck('id')
                ->map(fn ($produtoId) => (int) $produtoId)
                ->all();

            $removidosAtuais = array_map(
                'intval',
                array_filter(
                    explode(',', (string) ($alteracoes['produtos_removidos'] ?? $cliente->produtos_removidos ?? '')),
                    'strlen'
                )
            );

            $produtosPermitidos = array_values(array_diff(
                $produtosDisponiveis,
                $removidosAtuais
            ));

            for ($posicao = 0; $posicao <= 6; $posicao++) {
                $tipo = $posicao === 0 ? 'boas_vindas' : 'produto';
                $chave = 'swb_' . $tipo . '_' . $posicao;
                $banner = $request->input($chave, []);

                if (!is_array($banner)) {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        $chave => 'Dados do banner inválidos.',
                    ]);
                }

                $ativo = !empty($banner['ativo']) ? 1 : 0;
                $usarCoresSite = !empty($banner['usar_cores_site']) ? 1 : 0;

                $produtoId = $tipo === 'produto'
                    ? (int) ($banner['produto_id'] ?? 0)
                    : 0;

                if ($tipo === 'produto' && $produtoId > 0
                    && !in_array($produtoId, $produtosPermitidos, true)) {
                    $produtoId = 0;
                    $ativo = 0;
                }

                if ($tipo === 'produto' && $ativo && !$produtoId) {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        $chave => 'Selecione um produto para o banner ' . $posicao . ' ou desative-o.',
                    ]);
                }

                $textos = [];

                foreach ([
                    'texto_pequeno' => 35,
                    'titulo' => 75,
                    'descricao' => 130,
                    'texto_botao' => 25,
                ] as $campo => $limite) {
                    $valor = trim((string) ($banner[$campo] ?? ''));

                    if (mb_strlen($valor) > $limite) {
                        throw \Illuminate\Validation\ValidationException::withMessages([
                            $chave => 'Texto longo demais no banner ' . $posicao . '.',
                        ]);
                    }

                    $textos[$campo] = $valor;
                }

                $coresPadrao = [
                    '#30343B',
                    strtoupper((string) ($alteracoes['cor_principal'] ?? $cliente->cor_principal ?: '#4D6775')),
                    strtoupper((string) ($alteracoes['cor_secundaria'] ?? $cliente->cor_secundaria ?: '#4D6775')),
                ];

                $cores = [];

                for ($numero = 1; $numero <= 3; $numero++) {
                    $cor = $usarCoresSite
                        ? $coresPadrao[$numero - 1]
                        : strtoupper(trim((string) ($banner['cor_' . $numero] ?? '')));

                    if (!preg_match('/^#[A-F0-9]{6}$/D', $cor)) {
                        throw \Illuminate\Validation\ValidationException::withMessages([
                            $chave => 'Cor inválida no banner ' . $posicao . '.',
                        ]);
                    }

                    $cores['cor_' . $numero] = $cor;
                }

                $bannersParaSalvar[] = [
                    'usuario_id' => $id,
                    'tipo' => $tipo,
                    'posicao' => $posicao,
                    'ativo' => $ativo,
                    'produto_id' => $produtoId ?: null,
                    'texto_pequeno' => $textos['texto_pequeno'],
                    'titulo' => $textos['titulo'],
                    'descricao' => $textos['descricao'],
                    'texto_botao' => $textos['texto_botao'],
                    'usar_cores_site' => $usarCoresSite,
                    ...$cores,
                ];
            }
        }

        $arquivosCriados = [];

        try {
            foreach ([
                'logo',
                'favicon',
                'imagem_compartilhamento',
                'banner_institucional_imagem',
            ] as $campo) {
                if (!$request->hasFile($campo)) {
                    continue;
                }

                $arquivo = $request->file($campo);
                $extensao = $arquivo->extension();

                $diretorioRelativo = 'img/escritorios/' . $id;
                $diretorioAbsoluto = public_path($diretorioRelativo);

                \Illuminate\Support\Facades\File::ensureDirectoryExists(
                    $diretorioAbsoluto
                );

                $nomeArquivo = \Illuminate\Support\Str::random(32) . '.' . $extensao;

                $arquivo->move($diretorioAbsoluto, $nomeArquivo);

                $caminhoRelativo = $diretorioRelativo . '/' . $nomeArquivo;

                $arquivosCriados[] = public_path($caminhoRelativo);
                $alteracoes[$campo] = $caminhoRelativo;
            }

            DB::transaction(function () use ($id, $alteracoes, $bannersParaSalvar, $request, $dominio) {
                DB::table('usuarios')
                    ->where('id', $id)
                    ->update($alteracoes);

                if ($request->exists('dominio')) {
                    $dominioAtual = DB::table('dominios')
                        ->where('usuario_id', $id)
                        ->where('ativo', 1)
                        ->orderBy('id')
                        ->first();

                    if ($dominio === '') {
                        if ($dominioAtual) {
                            DB::table('dominios')
                                ->where('id', $dominioAtual->id)
                                ->update(['ativo' => 0]);
                        }
                    } elseif ($dominioAtual) {
                        DB::table('dominios')
                            ->where('id', $dominioAtual->id)
                            ->update(['dominio' => $dominio]);
                    } else {
                        $dominioExistente = DB::table('dominios')
                            ->where('dominio', $dominio)
                            ->first();

                        if ($dominioExistente) {
                            DB::table('dominios')
                                ->where('id', $dominioExistente->id)
                                ->update([
                                    'usuario_id' => $id,
                                    'ativo' => 1,
                                ]);
                        } else {
                            DB::table('dominios')->insert([
                                'usuario_id' => $id,
                                'dominio' => $dominio,
                                'ativo' => 1,
                            ]);
                        }
                    }
                }

                if ($request->boolean('banners_configurados')) {
                    DB::table('cliente_banners')
                        ->where('usuario_id', $id)
                        ->delete();

                    DB::table('cliente_banners')->insert($bannersParaSalvar);
                }
            });
        } catch (\Throwable $erro) {
            foreach ($arquivosCriados as $arquivoCriado) {
                if (is_file($arquivoCriado)) {
                    @unlink($arquivoCriado);
                }
            }

            throw $erro;
        }

        return response()->json([
            'sucesso' => true,
            'mensagem' => 'Alterações salvas com sucesso.',
            'id' => $id,
        ]);
    }

    public function index(Request $request)
    {
        $usuario = $request->user();

        if (!$usuario || (int) $usuario->id !== 1 || !$usuario->ativo) {
            abort(403, 'Acesso não autorizado.');
        }

        $limite = (int) $request->query('limite', 10);

        if (!in_array($limite, [10, 30, 50], true)) {
            $limite = 10;
        }

        $busca = trim((string) $request->query('busca', ''));
        $busca = mb_substr($busca, 0, 120);

        $filtro = (string) $request->query('filtro', 'todos');

        if (!in_array($filtro, [
            'todos',
            'inadimplentes',
            'pausados',
            'pagamento',
            'assinatura',
        ], true)) {
            $filtro = 'todos';
        }

        $consulta = DB::table('usuarios as u')
            ->select([
                'u.id',
                'u.nome',
                'u.email',
                'u.whatsapp',
                'u.titulo_site',
                'u.ativo',
                'u.site_pausado',
                'u.modalidade_pagamento',
                'u.plano_produto_id',
                'u.data_expiracao',
                'u.dias_tolerancia',
            ])
            ->selectSub(function ($query) {
                $query->from('dominios as d')
                    ->select('d.dominio')
                    ->whereColumn('d.usuario_id', 'u.id')
                    ->where('d.ativo', 1)
                    ->orderBy('d.id')
                    ->limit(1);
            }, 'dominio')
            ->selectSub(function ($query) {
                $query->from('faturas as f')
                    ->selectRaw('COUNT(*)')
                    ->whereColumn('f.usuario_id', 'u.id')
                    ->where('f.status', 'pendente')
                    ->whereDate('f.vencimento', '<', now()->toDateString());
            }, 'vencidas')
            ->selectSub(function ($query) {
                $query->from('faturas as f')
                    ->selectRaw('COALESCE(SUM(f.valor_total), 0)')
                    ->whereColumn('f.usuario_id', 'u.id')
                    ->where('f.status', 'pendente')
                    ->whereDate('f.vencimento', '<', now()->toDateString());
            }, 'total_vencido');

        if ($busca !== '') {
            $consulta->where(function ($query) use ($busca) {
                $query->where('u.nome', 'like', '%' . $busca . '%')
                    ->orWhere('u.email', 'like', '%' . $busca . '%')
                    ->orWhereRaw('CAST(u.id AS CHAR) LIKE ?', ['%' . $busca . '%']);
            });
        }

        if ($filtro === 'inadimplentes') {
            $consulta->whereExists(function ($query) {
                $query->selectRaw('1')
                    ->from('faturas as fi')
                    ->whereColumn('fi.usuario_id', 'u.id')
                    ->where('fi.status', 'pendente')
                    ->whereDate('fi.vencimento', '<', now()->toDateString());
            });
        }

        if ($filtro === 'pausados') {
            $consulta->where('u.site_pausado', 1);
        }

        if (in_array($filtro, ['pagamento', 'assinatura'], true)) {
            $consulta->where('u.modalidade_pagamento', $filtro);
        }

        $usuarios = $consulta
            ->orderByDesc('u.id')
            ->paginate($limite)
            ->appends([
                'busca' => $busca,
                'filtro' => $filtro,
                'limite' => $limite,
            ]);

        $planosDisponiveis = DB::table('produtos_planos')
            ->select('id', 'descricao', 'quantidade_dias', 'preco_promocional')
            ->where('ativo', 1)
            ->orderBy('quantidade_dias')
            ->orderBy('id')
            ->get();

        return view('painel.usuarios.index', [
            'usuarios' => $usuarios,
            'planosDisponiveis' => $planosDisponiveis,
            'busca' => $busca,
            'filtro' => $filtro,
            'limite' => $limite,
        ]);
    }
}
