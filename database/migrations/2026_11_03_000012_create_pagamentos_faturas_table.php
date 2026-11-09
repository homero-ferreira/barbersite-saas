<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        DB::unprepared(<<<'BARBERSITE_SQL_END'
CREATE TABLE `pagamentos_faturas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `fatura_id` bigint(20) UNSIGNED NOT NULL,
  `provedor` enum('mercado_pago','pagseguro','manual') NOT NULL DEFAULT 'mercado_pago',
  `meio` enum('pix','cartao','boleto','outro') NOT NULL DEFAULT 'outro',
  `status` enum('criado','pendente','em_processamento','aprovado','recusado','cancelado','estornado') NOT NULL DEFAULT 'criado',
  `valor` decimal(10,2) NOT NULL,
  `valor_recebido` decimal(10,2) DEFAULT NULL,
  `referencia_externa` varchar(190) DEFAULT NULL,
  `provedor_pagamento_id` varchar(190) DEFAULT NULL,
  `provedor_preferencia_id` varchar(190) DEFAULT NULL,
  `idempotency_key` varchar(190) DEFAULT NULL,
  `detalhe_status` varchar(120) DEFAULT NULL,
  `checkout_url` text DEFAULT NULL,
  `pix_copia_cola` text DEFAULT NULL,
  `pix_expira_em` datetime DEFAULT NULL,
  `aprovado_em` datetime DEFAULT NULL,
  `atualizado_provedor_em` datetime DEFAULT NULL,
  `dados_retorno` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'Metadados não sensíveis; nunca salvar dados de cartão ou credenciais' CHECK (json_valid(`dados_retorno`)),
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp(),
  `atualizado_em` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
BARBERSITE_SQL_END
        );
        DB::unprepared(<<<'BARBERSITE_SQL_END'
ALTER TABLE `pagamentos_faturas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_pagamentos_idempotencia` (`idempotency_key`),
  ADD UNIQUE KEY `uq_sw_mp_order` (`provedor`,`provedor_pagamento_id`),
  ADD KEY `idx_pagamentos_fatura` (`fatura_id`),
  ADD KEY `idx_pagamentos_provedor_id` (`provedor`,`provedor_pagamento_id`);
BARBERSITE_SQL_END
        );
        DB::unprepared(<<<'BARBERSITE_SQL_END'
ALTER TABLE `pagamentos_faturas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
BARBERSITE_SQL_END
        );
    }

    public function down(): void
    {
        DB::statement('DROP TABLE IF EXISTS `pagamentos_faturas`');
    }
};
