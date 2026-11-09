<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        DB::unprepared(<<<'BARBERSITE_SQL_END'
CREATE TABLE `usuarios` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nome` varchar(150) NOT NULL,
  `email` varchar(190) NOT NULL,
  `whatsapp` varchar(30) NOT NULL,
  `senha_hash` varchar(255) NOT NULL,
  `senha_recuperavel` text DEFAULT NULL COMMENT 'Somente clientes: cifrado com libsodium; admin NULL',
  `razao_social` varchar(255) NOT NULL DEFAULT '',
  `documento` varchar(30) NOT NULL DEFAULT '',
  `telefone` varchar(30) NOT NULL DEFAULT '',
  `cep` varchar(20) NOT NULL DEFAULT '',
  `endereco` varchar(255) NOT NULL DEFAULT '',
  `numero` varchar(30) NOT NULL DEFAULT '',
  `complemento` varchar(255) NOT NULL DEFAULT '',
  `bairro` varchar(150) NOT NULL DEFAULT '',
  `cidade` varchar(150) NOT NULL DEFAULT '',
  `uf` char(2) NOT NULL DEFAULT '',
  `titulo_site` varchar(255) NOT NULL DEFAULT '',
  `descricao_site` text DEFAULT NULL,
  `sobre_nos` text DEFAULT NULL,
  `cor_principal` char(7) NOT NULL DEFAULT '#B87568',
  `cor_secundaria` char(7) NOT NULL DEFAULT '#222025',
  `ativo` tinyint(1) NOT NULL DEFAULT 1,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp(),
  `atualizado_em` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `registro_susep` varchar(80) NOT NULL DEFAULT '' COMMENT 'Campo legado preservado por compatibilidade; não utilizar no BarberSite',
  `email_comercial` varchar(190) NOT NULL DEFAULT '',
  `logo` varchar(255) NOT NULL DEFAULT '',
  `favicon` varchar(255) NOT NULL DEFAULT '',
  `imagem_compartilhamento` varchar(255) NOT NULL DEFAULT '',
  `instagram` varchar(255) NOT NULL DEFAULT '',
  `facebook` varchar(255) NOT NULL DEFAULT '',
  `linkedin` varchar(255) NOT NULL DEFAULT '',
  `youtube` varchar(255) NOT NULL DEFAULT '',
  `tiktok` varchar(255) NOT NULL DEFAULT '',
  `produtos_removidos` text DEFAULT NULL,
  `produtos_destaque` text DEFAULT NULL,
  `banners_selecionados` varchar(255) NOT NULL DEFAULT '',
  `site_pausado` tinyint(1) NOT NULL DEFAULT 0 COMMENT '1 = site público pausado; painel acessível',
  `modalidade_pagamento` enum('pagamento','assinatura') NOT NULL DEFAULT 'pagamento',
  `mp_assinatura_id` varchar(120) DEFAULT NULL,
  `mp_assinatura_status` varchar(40) DEFAULT NULL,
  `mp_assinatura_proxima_cobranca` datetime DEFAULT NULL,
  `site_pausa_motivo` enum('inadimplencia','manual') DEFAULT NULL,
  `plano_produto_id` int(10) UNSIGNED DEFAULT NULL,
  `data_expiracao` date DEFAULT NULL,
  `dias_tolerancia` tinyint(3) UNSIGNED NOT NULL DEFAULT 7,
  `banner_institucional_imagem` varchar(255) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
BARBERSITE_SQL_END
        );
        DB::unprepared(<<<'BARBERSITE_SQL_END'
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_usuario_email` (`email`),
  ADD UNIQUE KEY `uq_usuarios_mp_assinatura_id` (`mp_assinatura_id`);
BARBERSITE_SQL_END
        );
        DB::unprepared(<<<'BARBERSITE_SQL_END'
ALTER TABLE `usuarios`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
BARBERSITE_SQL_END
        );
    }

    public function down(): void
    {
        DB::statement('DROP TABLE IF EXISTS `usuarios`');
    }
};
