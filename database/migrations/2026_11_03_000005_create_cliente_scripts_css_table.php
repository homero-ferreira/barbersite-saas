<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        DB::unprepared(<<<'BARBERSITE_SQL_END'
CREATE TABLE `cliente_scripts_css` (
  `usuario_id` int(11) NOT NULL,
  `codigo_head` mediumtext DEFAULT NULL,
  `codigo_body_inicio` mediumtext DEFAULT NULL,
  `codigo_body_fim` mediumtext DEFAULT NULL,
  `css_personalizado` mediumtext DEFAULT NULL,
  `atualizado_em` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
BARBERSITE_SQL_END
        );
        DB::unprepared(<<<'BARBERSITE_SQL_END'
ALTER TABLE `cliente_scripts_css`
  ADD PRIMARY KEY (`usuario_id`);
BARBERSITE_SQL_END
        );
    }

    public function down(): void
    {
        DB::statement('DROP TABLE IF EXISTS `cliente_scripts_css`');
    }
};
