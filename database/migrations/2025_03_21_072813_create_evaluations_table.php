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
        Schema::create('evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('candidate_id')->references('id')->on('candidates')->onDelete('cascade');
            $table->string('role');
            $table->enum('criteria', [
                'appearance',
                'english',
                'chinese',
                'japanese',
                'computer',
                'behavior',
                'characteristics',
                'communication',
                'motivation',
                'experience',
                'customer',
                'flexibility',
                'teamwork'
            ]);
            $table->integer('rating');

            $table->unique(['candidate_id', 'role', 'criteria']);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluations');
    }
};
