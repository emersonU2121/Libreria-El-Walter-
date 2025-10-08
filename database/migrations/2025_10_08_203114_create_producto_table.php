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
        Schema::create('producto', function (Blueprint $table) {
            $table->collation = 'utf8mb4_unicode_ci';
            $table->charset = 'utf8mb4';

            $table->bigIncrements('idproducto');
            $table->string('nombre');
            $table->decimal('precio');
            $table->integer('stock');
            $table->enum('estado', ['disponible', 'agotado']);
            $table->unsignedBigInteger('idmarca')->index('producto_idmarca_foreign');
            $table->unsignedBigInteger('idcategoria')->index('producto_idcategoria_foreign');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('producto');
    }
};
