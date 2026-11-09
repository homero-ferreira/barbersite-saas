<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        DB::unprepared(<<<'BARBERSITE_SQL_END'
CREATE TABLE `renovacoes_planos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `fatura_id` bigint(20) UNSIGNED NOT NULL,
  `usuario_id` bigint(20) UNSIGNED NOT NULL,
  `produto_plano_id` int(10) UNSIGNED NOT NULL,
  `dias_adicionados` int(10) UNSIGNED NOT NULL,
  `validade_anterior` date DEFAULT NULL,
  `validade_nova` date NOT NULL,
  `criado_em` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
BARBERSITE_SQL_END
        );
        DB::unprepared(<<<'BARBERSITE_SQL_END'
ALTER TABLE `renovacoes_planos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_renovacao_fatura` (`fatura_id`),
  ADD KEY `idx_renovacao_usuario` (`usuario_id`);
BARBERSITE_SQL_END
        );
        DB::unprepared(<<<'BARBERSITE_SQL_END'
ALTER TABLE `renovacoes_planos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
BARBERSITE_SQL_END
        );
    }

    public function down(): void
    {
        DB::statement('DROP TABLE IF EXISTS `renovacoes_planos`');
    }
};
