<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        DB::unprepared(<<<'BARBERSITE_SQL_END'
CREATE TABLE `mercadopago_credenciais` (
  `id` tinyint(3) UNSIGNED NOT NULL,
  `ambiente` enum('teste','producao') NOT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT 0,
  `public_key` varchar(255) NOT NULL DEFAULT '',
  `access_token` text DEFAULT NULL,
  `webhook_secret` varchar(255) NOT NULL DEFAULT '',
  `atualizado_em` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
BARBERSITE_SQL_END
        );
        DB::unprepared(<<<'BARBERSITE_SQL_END'
ALTER TABLE `mercadopago_credenciais`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ambiente` (`ambiente`);
BARBERSITE_SQL_END
        );
        DB::unprepared(<<<'BARBERSITE_SQL_END'
ALTER TABLE `mercadopago_credenciais`
  MODIFY `id` tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
BARBERSITE_SQL_END
        );
    }

    public function down(): void
    {
        DB::statement('DROP TABLE IF EXISTS `mercadopago_credenciais`');
    }
};
