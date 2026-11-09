<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        DB::unprepared(<<<'BARBERSITE_SQL_END'
CREATE TABLE `cliente_banners` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `tipo` enum('boas_vindas','produto') NOT NULL,
  `posicao` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
  `ativo` tinyint(1) NOT NULL DEFAULT 1,
  `produto_id` int(11) DEFAULT NULL,
  `texto_pequeno` varchar(100) NOT NULL DEFAULT '',
  `titulo` varchar(180) NOT NULL DEFAULT '',
  `descricao` varchar(500) NOT NULL DEFAULT '',
  `texto_botao` varchar(60) NOT NULL DEFAULT 'Saiba mais',
  `usar_cores_site` tinyint(1) NOT NULL DEFAULT 1,
  `cor_1` char(7) NOT NULL DEFAULT '#30343B',
  `cor_2` char(7) NOT NULL DEFAULT '#70777F',
  `cor_3` char(7) NOT NULL DEFAULT '#F2F3F4'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
BARBERSITE_SQL_END
        );
        DB::unprepared(<<<'BARBERSITE_SQL_END'
ALTER TABLE `cliente_banners`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_cliente_posicao` (`usuario_id`,`tipo`,`posicao`),
  ADD KEY `idx_cliente_ativo` (`usuario_id`,`ativo`),
  ADD KEY `idx_produto` (`produto_id`);
BARBERSITE_SQL_END
        );
        DB::unprepared(<<<'BARBERSITE_SQL_END'
ALTER TABLE `cliente_banners`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=470;
BARBERSITE_SQL_END
        );
    }

    public function down(): void
    {
        DB::statement('DROP TABLE IF EXISTS `cliente_banners`');
    }
};
