<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        DB::unprepared(<<<'BARBERSITE_SQL_END'
CREATE TABLE `dominios` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `usuario_id` bigint(20) UNSIGNED NOT NULL,
  `dominio` varchar(253) NOT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT 1,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
BARBERSITE_SQL_END
        );
        DB::unprepared(<<<'BARBERSITE_SQL_END'
ALTER TABLE `dominios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_dominio` (`dominio`),
  ADD KEY `idx_dominio_usuario` (`usuario_id`);
BARBERSITE_SQL_END
        );
        DB::unprepared(<<<'BARBERSITE_SQL_END'
ALTER TABLE `dominios`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
BARBERSITE_SQL_END
        );
    }

    public function down(): void
    {
        DB::statement('DROP TABLE IF EXISTS `dominios`');
    }
};
