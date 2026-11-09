<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        DB::unprepared(<<<'BARBERSITE_SQL_END'
CREATE TABLE `suporte_leituras` (
  `chamado_id` bigint(20) UNSIGNED NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `ultima_mensagem_id` bigint(20) UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
BARBERSITE_SQL_END
        );
        DB::unprepared(<<<'BARBERSITE_SQL_END'
ALTER TABLE `suporte_leituras`
  ADD PRIMARY KEY (`chamado_id`,`usuario_id`);
BARBERSITE_SQL_END
        );
    }

    public function down(): void
    {
        DB::statement('DROP TABLE IF EXISTS `suporte_leituras`');
    }
};
