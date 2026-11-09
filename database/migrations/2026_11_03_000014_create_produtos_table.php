<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        DB::unprepared(<<<'BARBERSITE_SQL_END'
CREATE TABLE `produtos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `titulo` varchar(200) NOT NULL,
  `descricao` mediumtext NOT NULL,
  `imagem_capa` varchar(255) NOT NULL,
  `imagem_detalhe` varchar(255) NOT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT 1,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp(),
  `atualizado_em` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `categoria_id` bigint(20) UNSIGNED DEFAULT NULL,
  `destaque_padrao` tinyint(1) NOT NULL DEFAULT 0,
  `seo_titulo` varchar(200) NOT NULL DEFAULT '',
  `seo_descricao` varchar(320) NOT NULL DEFAULT '',
  `seo_slug` varchar(200) NOT NULL DEFAULT '',
  `banner_texto_pequeno` varchar(35) NOT NULL DEFAULT '',
  `banner_titulo` varchar(75) NOT NULL DEFAULT '',
  `banner_descricao` varchar(130) NOT NULL DEFAULT '',
  `banner_texto_botao` varchar(25) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
BARBERSITE_SQL_END
        );
        DB::unprepared(<<<'BARBERSITE_SQL_END'
ALTER TABLE `produtos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_produtos_ativo` (`ativo`),
  ADD KEY `idx_produtos_categoria` (`categoria_id`);
BARBERSITE_SQL_END
        );
        DB::unprepared(<<<'BARBERSITE_SQL_END'
ALTER TABLE `produtos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=122;
BARBERSITE_SQL_END
        );
    }

    public function down(): void
    {
        DB::statement('DROP TABLE IF EXISTS `produtos`');
    }
};
