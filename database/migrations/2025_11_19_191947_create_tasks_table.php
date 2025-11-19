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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();

            // Relacionamento com usuários
            $table->foreignId('user_id')
                  ->constrained()
                  ->onDelete('cascade');

            // Campos da tarefa
            $table->string('titulo');
            $table->unsignedTinyInteger('quadrante'); // 1 = urgente/Importante ... etc.

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
