<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('email_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('role', ['hr', 'lm', 'final']);
            $table->string('subject');
            $table->longText('content');
            $table->timestamps();
        });

        // Thêm dữ liệu mặc định
        $this->seedDefaultTemplates();
    }

    public function down(): void
    {
        Schema::dropIfExists('email_templates');
    }
    
    private function seedDefaultTemplates()
    {
        // Lấy nội dung từ template mặc định
        $defaultContent = file_get_contents(resource_path('views/emails/interview-notification.blade.php'));
        
        // Tạo 3 mẫu email cho 3 role
        DB::table('email_templates')->insert([
            [
                'name' => 'Mẫu thông báo HR',
                'role' => 'hr',
                'subject' => 'Thông báo lịch phỏng vấn HR cho ứng viên [candidate_name]',
                'content' => $defaultContent,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Mẫu thông báo LM',
                'role' => 'lm',
                'subject' => 'Thông báo lịch phỏng vấn Line Manager cho ứng viên [candidate_name]',
                'content' => $defaultContent,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Mẫu thông báo Final',
                'role' => 'final',
                'subject' => 'Thông báo lịch phỏng vấn Final cho ứng viên [candidate_name]',
                'content' => $defaultContent,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
};