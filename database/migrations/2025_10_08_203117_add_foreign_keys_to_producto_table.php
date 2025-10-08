<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('producto', function (Blueprint $table) {
            $table->foreign(['idcategoria'])->references(['idcategoria'])->on('categoria')->onUpdate('restrict')->onDelete('cascade');
            $table->foreign(['idmarca'])->references(['idmarca'])->on('marca')->onUpdate('restrict')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('producto', function (Blueprint $table) {
            $table->dropForeign('producto_idcategoria_foreign');
            $table->dropForeign('producto_idmarca_foreign');
        });
    }
};
