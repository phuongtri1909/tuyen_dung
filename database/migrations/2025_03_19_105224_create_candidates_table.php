<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCandidatesTable extends Migration
{
    public function up()
    {
        Schema::create('candidates', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('desired_position');
            $table->string('outlet_department');
            $table->enum('employment_type', ['full-time', 'casual-labor'])->nullable();

            $table->string('hr_name')->nullable();
            $table->date('hr_date')->nullable();
            $table->string('lm_name')->nullable();
            $table->date('lm_date')->nullable();
            $table->string('final_name')->nullable();
            $table->date('final_date')->nullable();
            

            // Các lựa chọn Yes/No
            $table->boolean('can_work_holidays')->nullable();
            $table->boolean('can_work_different_shifts')->nullable();
            $table->boolean('can_work_split_shifts')->nullable();
            $table->boolean('can_work_overtime')->nullable();
            $table->boolean('can_work_late_shift')->nullable();

            $table->string('notice_days')->nullable();
            $table->string('available_date')->nullable();
            $table->string('min_salary')->nullable();

            $table->string('reference_feedback')->nullable();

            $table->foreignId('department_id')->nullable()->constrained()->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('candidates');
    }
}
