<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        DB::unprepared(<<<'BARBERSITE_SQL_END'
CREATE TABLE `planos_precos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `codigo` varchar(60) NOT NULL,
  `nome` varchar(150) NOT NULL,
  `valor_mensal` decimal(10,2) NOT NULL,
  `moeda` char(3) NOT NULL DEFAULT 'BRL',
  `ativo` tinyint(1) NOT NULL DEFAULT 1,
  `atualizado_em` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
BARBERSITE_SQL_END
        );
        DB::unprepared(<<<'BARBERSITE_SQL_END'
ALTER TABLE `planos_precos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_planos_codigo` (`codigo`);
BARBERSITE_SQL_END
        );
        DB::unprepared(<<<'BARBERSITE_SQL_END'
ALTER TABLE `planos_precos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
BARBERSITE_SQL_END
        );
    }

    public function down(): void
    {
        DB::statement('DROP TABLE IF EXISTS `planos_precos`');
    }
};
