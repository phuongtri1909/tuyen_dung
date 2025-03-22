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
        Schema::create('recommended_actions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('candidate_id')->references('id')->on('candidates')->onDelete('cascade');
            $table->string('role');
            $table->string('action');
            $table->enum('propose_next_step', ['highly_recommend', 'recommend', 'do_not_recommend','hold_consider','other_position']);
            $table->string('other_position_detail')->nullable();
            $table->unique(['candidate_id', 'role', 'action']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recommended_actions');
    }
};
