<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        DB::unprepared(<<<'BARBERSITE_SQL_END'
CREATE TABLE `suporte_chamados` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `assunto` enum('cadastro_produto','problemas','outros') NOT NULL,
  `descricao` text NOT NULL,
  `status` enum('pendente','preparacao','concluido') NOT NULL DEFAULT 'pendente',
  `criado_em` datetime NOT NULL DEFAULT current_timestamp(),
  `atualizado_em` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
BARBERSITE_SQL_END
        );
        DB::unprepared(<<<'BARBERSITE_SQL_END'
ALTER TABLE `suporte_chamados`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_usuario` (`usuario_id`,`atualizado_em`),
  ADD KEY `idx_status` (`status`,`atualizado_em`);
BARBERSITE_SQL_END
        );
        DB::unprepared(<<<'BARBERSITE_SQL_END'
ALTER TABLE `suporte_chamados`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
BARBERSITE_SQL_END
        );
    }

    public function down(): void
    {
        DB::statement('DROP TABLE IF EXISTS `suporte_chamados`');
    }
};
