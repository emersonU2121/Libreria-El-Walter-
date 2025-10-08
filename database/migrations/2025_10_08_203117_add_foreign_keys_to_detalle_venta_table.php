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
        Schema::table('detalle_venta', function (Blueprint $table) {
            $table->foreign(['idventa'], 'detalle_venta_ibfk_1')->references(['idventa'])->on('venta')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['idproducto'], 'detalle_venta_ibfk_2')->references(['idproducto'])->on('producto')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('detalle_venta', function (Blueprint $table) {
            $table->dropForeign('detalle_venta_ibfk_1');
            $table->dropForeign('detalle_venta_ibfk_2');
        });
    }
};
