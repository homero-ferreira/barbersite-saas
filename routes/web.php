<?php

use App\Http\Controllers\InstaladorController;
use App\Http\Controllers\Painel\LoginController;
use App\Http\Controllers\Painel\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function (\Illuminate\Http\Request $request) {

    $dominio = strtolower(rtrim($request->getHost(), '.'));

    $cliente = \Illuminate\Support\Facades\DB::table('dominios as d')
        ->join('usuarios as u', 'u.id', '=', 'd.usuario_id')
        ->where('d.dominio', $dominio)
        ->where('d.ativo', 1)
        ->where('u.ativo', 1)
        ->select('u.*')
        ->first();

    if (!$cliente) {
        return view('welcome');
    }

    $banners = \Illuminate\Support\Facades\DB::table('cliente_banners as b')
        ->leftJoin('produtos as p', 'p.id', '=', 'b.produto_id')
        ->where('b.usuario_id', $cliente->id)
        ->where('b.ativo', 1)
        ->orderBy('b.posicao')
        ->select(
            'b.*',
            'p.titulo as produto_titulo',
            'p.imagem_capa',
            'p.seo_slug'
        )
        ->get();

    $galeria = \Illuminate\Support\Facades\DB::table('cliente_galeria')
        ->where('usuario_id', $cliente->id)
        ->orderBy('posicao')
        ->limit(20)
        ->get();

    $produtos = \Illuminate\Support\Facades\DB::table('produtos as p')
        ->leftJoin('categorias as c', 'c.id', '=', 'p.categoria_id')
        ->where('p.ativo', 1)
        ->select(
            'p.*',
            'c.nome as categoria'
        )
        ->orderBy('c.ordem')
        ->orderBy('p.titulo')
        ->get();

    return view('cliente.home', [
        'cliente' => $cliente,
        'banners' => $banners,
        'galeria' => $galeria,
        'produtos' => $produtos,
    ]);


});

Route::get('/produto/{slug}', function (\Illuminate\Http\Request $request, string $slug) {

    abort_unless(preg_match('/^[a-z0-9-]{1,200}$/D', $slug), 404);

    $dominio = strtolower(rtrim($request->getHost(), '.'));

    $cliente = \Illuminate\Support\Facades\DB::table('dominios as d')
        ->join('usuarios as u', 'u.id', '=', 'd.usuario_id')
        ->where('d.dominio', $dominio)
        ->where('d.ativo', 1)
        ->where('u.ativo', 1)
        ->select('u.*')
        ->first();

    abort_unless($cliente, 404);

    $produto = \Illuminate\Support\Facades\DB::table('produtos as p')
        ->leftJoin('categorias as c', 'c.id', '=', 'p.categoria_id')
        ->where('p.ativo', 1)
        ->where('p.seo_slug', $slug)
        ->select('p.*', 'c.nome as categoria')
        ->first();

    abort_unless($produto, 404);

    $removidos = array_filter(
        array_map('intval', explode(',', $cliente->produtos_removidos ?? ''))
    );

    abort_if(in_array((int) $produto->id, $removidos, true), 404);

    return view('cliente.produto', [
        'cliente' => $cliente,
        'produto' => $produto,
    ]);

})->name('cliente.produto');

Route::get('/instalar', [InstaladorController::class, 'index'])
    ->name('instalar');

Route::post('/instalar', [InstaladorController::class, 'instalar'])
    ->middleware('throttle:5,1')
    ->name('instalar.salvar');

Route::get('/painel/login', [LoginController::class, 'index'])
    ->name('painel.login');

Route::post('/painel/login', [LoginController::class, 'entrar'])
    ->middleware('throttle:5,1')
    ->name('painel.login.entrar');

Route::post('/painel/sair', [LoginController::class, 'sair'])
    ->middleware('auth')
    ->name('painel.sair');

Route::get('/painel', [DashboardController::class, 'index'])
    ->middleware('auth')
    ->name('painel.dashboard');

Route::get('/painel/usuarios', [\App\Http\Controllers\Painel\UsuariosController::class, 'index'])
    ->middleware('auth')
    ->name('painel.usuarios.index');

Route::get('/painel/usuarios/novo', [\App\Http\Controllers\Painel\UsuariosController::class, 'create'])
    ->middleware('auth')
    ->name('painel.usuarios.create');

Route::post('/painel/usuarios', [\App\Http\Controllers\Painel\UsuariosController::class, 'store'])
    ->middleware(['auth', 'throttle:10,1'])
    ->name('painel.usuarios.store');

Route::get('/painel/usuarios/{id}/editar', [\App\Http\Controllers\Painel\UsuariosController::class, 'edit'])
    ->whereNumber('id')
    ->middleware('auth')
    ->name('painel.usuarios.edit');

Route::get('/painel/usuarios/{id}', [\App\Http\Controllers\Painel\UsuariosController::class, 'edit'])
    ->whereNumber('id')
    ->middleware('auth');

Route::put('/painel/usuarios/{id}', [\App\Http\Controllers\Painel\UsuariosController::class, 'update'])
    ->whereNumber('id')
    ->middleware(['auth', 'throttle:30,1'])
    ->name('painel.usuarios.update');