<?php

namespace App\Http\Controllers\Painel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $usuario = $request->user();

        if (!$usuario) {
            return redirect()->route('painel.login');
        }

        if ((int) $usuario->id !== 1 || !$usuario->ativo) {
            abort(403, 'Acesso não autorizado.');
        }

        $hoje = now()->toDateString();
        $limiteSeteDias = now()->addDays(7)->toDateString();

        $indicadores = [
            'clientes_cadastrados' => DB::table('usuarios')
                ->where('id', '<>', 1)
                ->count(),

            'planos_ativos' => DB::table('usuarios')
                ->where('id', '<>', 1)
                ->whereNotNull('data_expiracao')
                ->whereDate('data_expiracao', '>=', $hoje)
                ->count(),

            'vencem_sete_dias' => DB::table('usuarios')
                ->where('id', '<>', 1)
                ->whereBetween('data_expiracao', [$hoje, $limiteSeteDias])
                ->count(),

            'planos_expirados' => DB::table('usuarios')
                ->where('id', '<>', 1)
                ->whereNotNull('data_expiracao')
                ->whereDate('data_expiracao', '<', $hoje)
                ->count(),

            'sites_pausados' => DB::table('usuarios')
                ->where('id', '<>', 1)
                ->where('site_pausado', 1)
                ->count(),

            'faturas_pendentes' => DB::table('faturas')
                ->where('status', 'pendente')
                ->count(),

            'recebido_mes' => DB::table('pagamentos_faturas')
                ->where('status', 'aprovado')
                ->whereYear('aprovado_em', now()->year)
                ->whereMonth('aprovado_em', now()->month)
                ->whereRaw('COALESCE(valor_recebido, valor) > 0')
                ->selectRaw('COALESCE(SUM(COALESCE(valor_recebido, valor)), 0) AS total')
                ->value('total'),

            'recebido_ano' => DB::table('pagamentos_faturas')
                ->where('status', 'aprovado')
                ->whereYear('aprovado_em', now()->year)
                ->whereRaw('COALESCE(valor_recebido, valor) > 0')
                ->selectRaw('COALESCE(SUM(COALESCE(valor_recebido, valor)), 0) AS total')
                ->value('total'),
        ];

        $proximosVencimentos = DB::table('usuarios')
            ->select('id', 'nome', 'data_expiracao')
            ->selectRaw('DATEDIFF(data_expiracao, CURDATE()) AS dias')
            ->where('id', '<>', 1)
            ->whereNotNull('data_expiracao')
            ->whereDate('data_expiracao', '<=', now()->addDays(15)->toDateString())
            ->orderBy('data_expiracao')
            ->limit(12)
            ->get();

        $ultimosPagamentos = DB::table('pagamentos_faturas as p')
            ->join('faturas as f', 'f.id', '=', 'p.fatura_id')
            ->join('usuarios as u', 'u.id', '=', 'f.usuario_id')
            ->select('p.aprovado_em', 'u.nome')
            ->selectRaw('COALESCE(p.valor_recebido, p.valor) AS recebido')
            ->where('p.status', 'aprovado')
            ->whereRaw('COALESCE(p.valor_recebido, p.valor) > 0')
            ->orderByDesc('p.aprovado_em')
            ->limit(8)
            ->get();

        $inicioPeriodo = now()->startOfMonth()->subMonths(11);

        $recebimentosMensais = DB::table('pagamentos_faturas')
            ->selectRaw("DATE_FORMAT(aprovado_em, '%Y-%m') AS mes")
            ->selectRaw('SUM(COALESCE(valor_recebido, valor)) AS total')
            ->where('status', 'aprovado')
            ->whereRaw('COALESCE(valor_recebido, valor) > 0')
            ->where('aprovado_em', '>=', $inicioPeriodo->toDateTimeString())
            ->where('aprovado_em', '<', now()->startOfMonth()->addMonth()->toDateTimeString())
            ->groupByRaw("DATE_FORMAT(aprovado_em, '%Y-%m')")
            ->orderBy('mes')
            ->pluck('total', 'mes');

        $graficoRecebimentos = [];

        for ($i = 0; $i < 12; $i++) {
            $mes = $inicioPeriodo->copy()->addMonths($i);

            $graficoRecebimentos[] = [
                'mes' => $mes->format('m/Y'),
                'total' => (float) ($recebimentosMensais[$mes->format('Y-m')] ?? 0),
            ];
        }

        $indicadoresChamados = [
            'pendentes' => DB::table('suporte_chamados')
                ->where('status', 'pendente')
                ->count(),

            'em_preparacao' => DB::table('suporte_chamados')
                ->where('status', 'preparacao')
                ->count(),

            'concluidos' => DB::table('suporte_chamados')
                ->where('status', 'concluido')
                ->count(),
        ];

        $resumoOperacional = [
            'faturas_pagas' => DB::table('faturas')
                ->where('status', 'paga')
                ->count(),

            'planos_disponiveis' => DB::table('produtos_planos')
                ->where('ativo', 1)
                ->count(),

            'produtos_cadastrados' => DB::table('produtos')
                ->count(),
        ];

        return view('painel.dashboard', [
            'usuario' => $usuario,
            'indicadores' => $indicadores,
            'proximosVencimentos' => $proximosVencimentos,
            'ultimosPagamentos' => $ultimosPagamentos,
            'graficoRecebimentos' => $graficoRecebimentos,
            'indicadoresChamados' => $indicadoresChamados,
            'resumoOperacional' => $resumoOperacional,
        ]);
    }
}