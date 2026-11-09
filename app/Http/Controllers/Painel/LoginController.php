<?php
namespace App\Http\Controllers\Painel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function index()
    {
        if (Auth::check() && Auth::user()->ativo) {
            return redirect('/painel');
        }

        return view('painel.login');
    }

    public function entrar(Request $request)
    {
        $dados = $request->validate([
            'email' => ['required', 'string'],
            'senha' => ['required', 'string'],
        ]);

        // Acesso de demonstração
        if ($dados['email'] === 'demo' && $dados['senha'] === 'demo') {

            $usuario = DB::table('usuarios')
                ->where('id', 1)
                ->where('ativo', 1)
                ->first();

            if (!$usuario) {
                throw ValidationException::withMessages([
                    'email' => 'Demonstração indisponível.',
                ]);
            }

            Auth::loginUsingId(1);

            $request->session()->regenerate();
            $request->session()->put('demo_mode', true);

            return redirect('/painel');
        }

        // Login administrativo original
        $autenticado = Auth::attempt([
            'email' => $dados['email'],
            'password' => $dados['senha'],
            'ativo' => 1,
            'id' => 1,
        ]);

        if (!$autenticado) {
            throw ValidationException::withMessages([
                'email' => 'E-mail ou senha incorretos.',
            ]);
        }

        $request->session()->regenerate();
        $request->session()->forget('demo_mode');

        return redirect()->intended('/painel');
    }

    public function sair(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/painel/login');
    }
}
