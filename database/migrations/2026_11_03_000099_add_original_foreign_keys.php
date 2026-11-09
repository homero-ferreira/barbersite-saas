<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        DB::unprepared(<<<'BARBERSITE_SQL_END'
ALTER TABLE `cliente_galeria`
  ADD CONSTRAINT `fk_galeria_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;
BARBERSITE_SQL_END
        );
        DB::unprepared(<<<'BARBERSITE_SQL_END'
ALTER TABLE `dominios`
  ADD CONSTRAINT `fk_dominio_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;
BARBERSITE_SQL_END
        );
        DB::unprepared(<<<'BARBERSITE_SQL_END'
ALTER TABLE `faturas`
  ADD CONSTRAINT `fk_faturas_plano` FOREIGN KEY (`plano_preco_id`) REFERENCES `planos_precos` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_faturas_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`);
BARBERSITE_SQL_END
        );
        DB::unprepared(<<<'BARBERSITE_SQL_END'
ALTER TABLE `pagamentos_faturas`
  ADD CONSTRAINT `fk_pagamentos_fatura` FOREIGN KEY (`fatura_id`) REFERENCES `faturas` (`id`);
BARBERSITE_SQL_END
        );
        DB::unprepared(<<<'BARBERSITE_SQL_END'
ALTER TABLE `suporte_leituras`
  ADD CONSTRAINT `fk_suporte_leituras_chamado` FOREIGN KEY (`chamado_id`) REFERENCES `suporte_chamados` (`id`) ON DELETE CASCADE;
BARBERSITE_SQL_END
        );
        DB::unprepared(<<<'BARBERSITE_SQL_END'
ALTER TABLE `suporte_mensagens`
  ADD CONSTRAINT `fk_suporte_mensagens_chamado` FOREIGN KEY (`chamado_id`) REFERENCES `suporte_chamados` (`id`) ON DELETE CASCADE;
BARBERSITE_SQL_END
        );
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE `cliente_galeria` DROP FOREIGN KEY `fk_galeria_usuario`');
        DB::statement('ALTER TABLE `dominios` DROP FOREIGN KEY `fk_dominio_usuario`');
        DB::statement('ALTER TABLE `faturas` DROP FOREIGN KEY `fk_faturas_plano`');
        DB::statement('ALTER TABLE `faturas` DROP FOREIGN KEY `fk_faturas_usuario`');
        DB::statement('ALTER TABLE `pagamentos_faturas` DROP FOREIGN KEY `fk_pagamentos_fatura`');
        DB::statement('ALTER TABLE `suporte_leituras` DROP FOREIGN KEY `fk_suporte_leituras_chamado`');
        DB::statement('ALTER TABLE `suporte_mensagens` DROP FOREIGN KEY `fk_suporte_mensagens_chamado`');
    }
};
