<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->boolean('completed')->default(false)->after('quadrante');
            $table->integer('ordem')->nullable()->after('completed');

            $table->index(['user_id', 'quadrante', 'ordem']);
        });
    }

    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'quadrante', 'ordem']);
            $table->dropColumn(['completed', 'ordem']);
        });
    }
};
