<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        DB::unprepared(<<<'BARBERSITE_SQL_END'
CREATE TABLE `faturas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `usuario_id` bigint(20) UNSIGNED NOT NULL,
  `plano_preco_id` bigint(20) UNSIGNED DEFAULT NULL,
  `numero` varchar(40) NOT NULL,
  `competencia` char(7) DEFAULT NULL COMMENT 'AAAA-MM',
  `descricao` varchar(255) NOT NULL DEFAULT 'Mensalidade BarberSite',
  `valor_original` decimal(10,2) NOT NULL,
  `desconto` decimal(10,2) NOT NULL DEFAULT 0.00,
  `acrescimo` decimal(10,2) NOT NULL DEFAULT 0.00,
  `valor_total` decimal(10,2) NOT NULL,
  `vencimento` date NOT NULL,
  `status` enum('pendente','paga','cancelada') NOT NULL DEFAULT 'pendente',
  `emitida_em` datetime NOT NULL DEFAULT current_timestamp(),
  `paga_em` datetime DEFAULT NULL,
  `cancelada_em` datetime DEFAULT NULL,
  `observacoes` text DEFAULT NULL,
  `lote_geracao` varchar(64) DEFAULT NULL,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp(),
  `atualizado_em` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `produto_plano_id` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
BARBERSITE_SQL_END
        );
        DB::unprepared(<<<'BARBERSITE_SQL_END'
ALTER TABLE `faturas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_faturas_numero` (`numero`),
  ADD KEY `idx_faturas_usuario_status` (`usuario_id`,`status`),
  ADD KEY `idx_faturas_status_vencimento` (`status`,`vencimento`),
  ADD KEY `idx_faturas_lote` (`lote_geracao`),
  ADD KEY `fk_faturas_plano` (`plano_preco_id`);
BARBERSITE_SQL_END
        );
        DB::unprepared(<<<'BARBERSITE_SQL_END'
ALTER TABLE `faturas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
BARBERSITE_SQL_END
        );
    }

    public function down(): void
    {
        DB::statement('DROP TABLE IF EXISTS `faturas`');
    }
};
