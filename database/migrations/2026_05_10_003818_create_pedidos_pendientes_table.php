<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('pedidos_pendientes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_cliente');
            $table->string('codigo')->unique();
            $table->decimal('total', 10, 2);
            $table->string('nombre_titular');
            $table->string('banco');
            $table->json('carrito');
            $table->enum('estado', ['esperando', 'aceptado', 'rechazado'])
                  ->default('esperando');
            $table->timestampTz('confirmado_en')->nullable();
            $table->timestampTz('revisado_en')->nullable();
            $table->timestamps();

            $table->foreign('id_cliente')
                  ->references('id_cliente')
                  ->on('clientes');
        });
    }

    public function down(): void {
        Schema::dropIfExists('pedidos_pendientes');
    }
};