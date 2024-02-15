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
        Schema::create('customers', function (Blueprint $table) {
            $table->string('dni', 45)->comment('Documento de Identidad');
            $table->integer('id_reg')->comment('');
            $table->integer('id_com')->comment('');
            $table->string('email', 120)->comment('Correo Electrónico');
            $table->string('name', 45)->comment('Nombre');
            $table->string('last_name', 45)->comment('Apellido');
            $table->string('address', 255)->nullable()->comment('Dirección');
            $table->dateTime('date_reg')->comment('Fecha y hora del registro');
            $table->enum('status', ['A', 'I', 'trash'])->default('A')->comment('Estado del registro: A - Activo, I - Desactivo, trash - Registro eliminado');
            $table->primary(['dni', 'id_reg', 'id_com']);
            $table->index(['id_com', 'id_reg']);
            $table->unique('email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
