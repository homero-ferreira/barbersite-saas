<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class InstaladorController extends Controller
{
    public function index()
    {
        abort_if(User::query()->exists(), 404);

        return view('instalador');
    }

    public function instalar(Request $request)
    {
        abort_if(User::query()->exists(), 404);

        $dados = $request->validate([
            'nome' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', 'max:190', 'unique:usuarios,email'],
            'whatsapp' => ['required', 'string', 'max:30'],
            'senha' => ['required', 'confirmed', Password::min(8)],
        ]);

        DB::transaction(function () use ($dados) {
            // Impede duas instalações simultâneas.
            DB::table('usuarios')->lockForUpdate()->count();

            if (User::query()->exists()) {
                abort(404);
            }

            User::create([
                'id' => 1,
                'nome' => $dados['nome'],
                'email' => $dados['email'],
                'whatsapp' => $dados['whatsapp'],
                'senha_hash' => Hash::make($dados['senha']),
                'ativo' => 1,
            ]);
        });

        return redirect('/painel/login')
            ->with('sucesso', 'Administrador criado com sucesso. Faça seu login.');
    }
}