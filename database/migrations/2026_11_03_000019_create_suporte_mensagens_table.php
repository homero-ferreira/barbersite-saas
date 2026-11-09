<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        DB::unprepared(<<<'BARBERSITE_SQL_END'
CREATE TABLE `suporte_mensagens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `chamado_id` bigint(20) UNSIGNED NOT NULL,
  `autor_id` int(11) NOT NULL,
  `mensagem` text NOT NULL,
  `anexo` varchar(200) DEFAULT NULL,
  `criado_em` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
BARBERSITE_SQL_END
        );
        DB::unprepared(<<<'BARBERSITE_SQL_END'
ALTER TABLE `suporte_mensagens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_chamado` (`chamado_id`,`id`);
BARBERSITE_SQL_END
        );
        DB::unprepared(<<<'BARBERSITE_SQL_END'
ALTER TABLE `suporte_mensagens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
BARBERSITE_SQL_END
        );
    }

    public function down(): void
    {
        DB::statement('DROP TABLE IF EXISTS `suporte_mensagens`');
    }
};
