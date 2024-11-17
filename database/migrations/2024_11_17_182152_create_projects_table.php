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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // Creador del proyecto
            $table->string('title');
            $table->text('description');
            $table->string('category');
            $table->decimal('goal_amount', 10, 2); // Meta financiera
            $table->decimal('collected_amount', 10, 2)->default(0); // Dinero recaudado
            $table->date('deadline');
            $table->string('status')->default('pending'); // 'pending', 'active', 'completed'
            $table->timestamps();
        
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
